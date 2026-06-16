<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Ensure the authenticated user owns this reservation
        $reservation = $this->route('reservation');
        return $reservation && $this->user()?->id === $reservation->user_id;
    }

    public function rules(): array
    {
        return [

            'receipt_image' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg',
                'max:5120', // 5 MB
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'receipt_image.required' => 'Bukti pembayaran wajib diupload.',
            'receipt_image.image'   => 'File harus berupa gambar.',
            'receipt_image.mimes'   => 'Format gambar harus JPEG, PNG, atau JPG.',
            'receipt_image.max'     => 'Ukuran file maksimal 5 MB.',
        ];
    }
}
