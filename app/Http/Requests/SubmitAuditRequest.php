<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitAuditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'verified_billing_code' => [
                'required',
                'digits:15',
            ],
            'verified_amount'       => [
                'required',
                'numeric',
                'min:1',
            ],
            'audit_decision'        => [
                'required',
                'in:APPROVE,REJECT',
            ],
            'rejection_reason'      => [
                'required_if:audit_decision,REJECT',
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'verified_billing_code.required' => 'Kode billing wajib diisi.',
            'verified_billing_code.digits'   => 'Kode billing harus tepat 15 digit angka.',
            'verified_amount.required'       => 'Nominal pembayaran wajib diisi.',
            'verified_amount.numeric'        => 'Nominal harus berupa angka.',
            'verified_amount.min'            => 'Nominal harus lebih dari 0.',
            'audit_decision.required'        => 'Keputusan audit wajib dipilih.',
            'audit_decision.in'              => 'Keputusan harus APPROVE atau REJECT.',
            'rejection_reason.required_if'   => 'Alasan penolakan wajib diisi jika keputusan adalah REJECT.',
        ];
    }
}
