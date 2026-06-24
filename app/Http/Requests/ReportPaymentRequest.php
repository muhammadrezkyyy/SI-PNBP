<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // The URL uses an unguessable UUID which acts as a secret token.
        // Therefore, we allow anyone with the link to upload the payment proof.
        return true;
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
