<?php

namespace App\Livewire\Customer;

use App\Enums\ReservationStatus;
use App\Models\BookingFormField;
use App\Models\Building;
use App\Models\FacilityType;
use App\Models\Reservation;
use App\Services\ReservationService;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

#[Title('Booking Gedung — SI-RESERVASI PNBP')]
class BookingWizard extends Component
{
    // --- Step State ---
    public int $current_step = 1;

    // --- Step 1: Schedule ---
    public string $facility_type_id = '';
    public string $building_id = '';
    public string $start_date  = '';
    public string $end_date    = '';

    // --- Step 2: Dynamic Form ---
    public array $customer_data = [];

    // --- Step 3: Confirmation ---
    public bool   $booking_success = false;
    public string $reservation_id  = '';
    public string $conflict_error  = '';

    public function mount(): void
    {
        $this->start_date = now()->addDay()->format('Y-m-d');
        $this->end_date   = now()->addDays(2)->format('Y-m-d');
    }

    public function updatedFacilityTypeId()
    {
        $this->building_id = '';
    }

    public function updatedStartDate()
    {
        // Re-check availability when dates change
        $this->building_id = '';
    }

    public function updatedEndDate()
    {
        $this->building_id = '';
    }

    public function nextStep(): void
    {
        if ($this->current_step === 1) {
            $this->validateStep1();
            $this->current_step = 2;
        }
    }

    public function previousStep(): void
    {
        if ($this->current_step > 1) {
            $this->current_step--;
        }
    }

    private function validateStep1(): void
    {
        $this->validate([
            'facility_type_id' => ['required', 'exists:facility_types,id'],
            'building_id' => ['required', 'exists:buildings,id'],
            'start_date'  => ['required', 'date', 'after_or_equal:today'],
            'end_date'    => ['required', 'date', 'after_or_equal:start_date'],
        ], [
            'facility_type_id.required' => 'Pilih kategori fasilitas terlebih dahulu.',
            'building_id.required'   => 'Pilih unit ruangan yang tersedia.',
            'building_id.exists'     => 'Unit tidak valid.',
            'start_date.required'    => 'Tanggal mulai wajib diisi.',
            'start_date.after_or_equal' => 'Tanggal mulai tidak boleh di masa lalu.',
            'end_date.required'      => 'Tanggal selesai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
        ]);

        // Double-check availability
        if (Reservation::hasConflict($this->building_id, $this->start_date, $this->end_date)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'building_id' => 'Unit ini sudah dipesan untuk tanggal tersebut. Pilih unit lain.'
            ]);
        }
    }

    private function validateStep2(): void
    {
        $fields = BookingFormField::ordered()->get();
        $rules  = [];
        $messages = [];

        foreach ($fields as $field) {
            $key = "customer_data.{$field->field_name}";

            $fieldRules = [];
            if ($field->is_required) {
                $fieldRules[] = 'required';
                $messages["{$key}.required"] = "{$field->field_label} wajib diisi.";
            } else {
                $fieldRules[] = 'nullable';
            }

            match ($field->field_type) {
                'number' => $fieldRules[] = 'numeric',
                'date'   => $fieldRules[] = 'date',
                'email'  => $fieldRules[] = 'email',
                default  => null,
            };

            $rules[$key] = $fieldRules;
        }

        if (!empty($rules)) {
            $this->validate($rules, $messages);
        }

        // WhatsApp Number Validation
        $fonnte = app(\App\Services\FonnteNotificationService::class);
        foreach ($fields as $field) {
            if (in_array(strtolower($field->field_name), ['whatsapp', 'phone', 'no_telp', 'telepon', 'nohp', 'no_hp'])) {
                $key = "customer_data.{$field->field_name}";
                $number = $this->customer_data[$field->field_name] ?? null;
                
                if ($number) {
                    // 1. Validasi Regex Dasar (Format Indonesia: 08xxx / 628xxx / +628xxx)
                    if (!preg_match('/^(\+62|62|0)8[1-9][0-9]{6,11}$/', $number)) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            $key => 'Format nomor WhatsApp tidak valid. Harus diawali 08, 628, atau +628 (contoh: 081234567890).'
                        ]);
                    }

                    // 2. Validasi via Fonnte (Apakah terdaftar di WA)
                    if (!$fonnte->validateNumber($number)) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            $key => 'Nomor ini tidak terdaftar di WhatsApp. Pastikan nomor aktif agar notifikasi dapat diterima.'
                        ]);
                    }
                }
            }
        }
    }

    public function confirmBooking(ReservationService $service): void
    {
        $this->conflict_error = '';
        
        $this->validateStep2();

        try {
            $reservation = $service->lockAndBook(
                buildingId:   $this->building_id,
                userId:       auth()->id(),
                startDate:    $this->start_date,
                endDate:      $this->end_date,
                customerData: $this->customer_data,
            );

            $this->reservation_id  = $reservation->id;
            $this->booking_success = true;

        } catch (ConflictHttpException $e) {
            $this->conflict_error = $e->getMessage();
        }
    }

    /**
     * Check if a building has a conflicting reservation for the selected dates.
     */
    private function getBuildingConflict(string $buildingId): ?Reservation
    {
        if (!$this->start_date || !$this->end_date) return null;

        return Reservation::where('building_id', $buildingId)
            ->whereNotIn('status', [
                ReservationStatus::REJECTED->value,
                ReservationStatus::EXPIRED->value,
            ])
            ->where(function ($q) {
                $q->whereBetween('start_date', [$this->start_date, $this->end_date])
                  ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                  ->orWhere(function ($q2) {
                      $q2->where('start_date', '<=', $this->start_date)
                         ->where('end_date', '>=', $this->end_date);
                  });
            })
            ->first();
    }

    public function render()
    {
        $fields = BookingFormField::ordered()->get();

        $conflictingBuildingIds = [];
        if ($this->start_date && $this->end_date) {
            $conflictingBuildingIds = Reservation::whereNotIn('status', [
                    ReservationStatus::REJECTED->value,
                    ReservationStatus::EXPIRED->value,
                ])
                ->where(function ($q) {
                    $q->whereBetween('start_date', [$this->start_date, $this->end_date])
                      ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                      ->orWhere(function ($q2) {
                          $q2->where('start_date', '<=', $this->start_date)
                             ->where('end_date', '>=', $this->end_date);
                      });
                })
                ->pluck('building_id')
                ->toArray();
        }

        // Only show facility types that have at least 1 building (active or inactive)
        $facilityTypes = FacilityType::with(['images', 'buildings' => function($q) {
                $q->where('is_active', true);
            }])
            ->has('buildings')
            ->orderBy('name')
            ->get();

        foreach ($facilityTypes as $type) {
            $available = 0;
            foreach ($type->buildings as $building) {
                if (!in_array($building->id, $conflictingBuildingIds)) {
                    $available++;
                }
            }
            $type->dynamic_available_count = $available;
        }

        // Get buildings for selected type with availability info
        $buildings = collect();
        if ($this->facility_type_id) {
            $rawBuildings = Building::where('facility_type_id', $this->facility_type_id)
                ->orderBy('name')
                ->get();

            $buildings = $rawBuildings->map(function ($building) {
                if (!$building->is_active) {
                    $building->is_booked = true;
                    $building->status_badge = '🚫 Non-Aktif';
                    $building->status_message = 'Unit sedang dinonaktifkan (Maintenance)';
                    return $building;
                }

                $conflict = $this->getBuildingConflict($building->id);
                $building->is_booked = $conflict !== null;
                if ($conflict) {
                    $start = $conflict->start_date?->isoFormat('D MMM');
                    $end = $conflict->end_date?->isoFormat('D MMM YYYY');
                    $building->status_badge = '🔒 Penuh';
                    $building->status_message = "Sudah dipesan: $start – $end";
                }
                
                return $building;
            });
        }

        $selectedType = $this->facility_type_id
            ? FacilityType::find($this->facility_type_id)
            : null;

        $durationDays = 1;
        if ($this->start_date && $this->end_date) {
            try {
                $durationDays = max(1, \Carbon\Carbon::parse($this->start_date)
                    ->diffInDays(\Carbon\Carbon::parse($this->end_date)) + 1);
            } catch (\Exception) {}
        }

        $estimatedTotal = $selectedType
            ? $selectedType->daily_rate * $durationDays
            : 0;

        return view('livewire.customer.booking-wizard', [
            'facilityTypes'  => $facilityTypes,
            'buildings'      => $buildings,
            'fields'         => $fields,
            'selectedType'   => $selectedType,
            'durationDays'   => $durationDays,
            'estimatedTotal' => $estimatedTotal,
        ])->layout('layouts.guest');
    }
}
