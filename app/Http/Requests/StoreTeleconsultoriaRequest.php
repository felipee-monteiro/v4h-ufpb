<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreTeleconsultoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'patient_name' => ['required', 'string', 'max:255'],
            'patient_initials' => ['required', 'string', 'max:8'],
            'patient_birthday' => ['required', 'date', 'before_or_equal:today'],
            'service_uuid' => ['required', 'string', 'exists:services,uuid'],
            'diagnostic_hypothesis' => ['required', 'string', 'max:1000'],
            'clinical_history' => ['required', 'string', 'max:3000'],
        ];
    }
}
