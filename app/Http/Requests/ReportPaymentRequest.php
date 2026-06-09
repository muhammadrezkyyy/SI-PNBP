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
            'ntpn'          => [
                'required',
                'string',
                'size:16',
                'alpha_num',
                'unique:payments,ntpn',
            ],
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
            'ntpn.required'         => 'NTPN wajib diisi.',
            'ntpn.size'             => 'NTPN harus tepat 16 karakter.',
            'ntpn.alpha_num'        => 'NTPN hanya boleh berisi kombinasi angka dan huruf.',
            'ntpn.unique'           => 'NTPN ini sudah pernah digunakan. Setiap NTPN hanya dapat dipakai sekali.',
            'receipt_image.required' => 'Bukti pembayaran wajib diupload.',
            'receipt_image.image'   => 'File harus berupa gambar.',
            'receipt_image.mimes'   => 'Format gambar harus JPEG, PNG, atau JPG.',
            'receipt_image.max'     => 'Ukuran file maksimal 5 MB.',
        ];
    }
}
