<?php

namespace App\Http\Requests;

use App\Contracts\ProfessionalValidator;
use App\Enums\ProfessionalType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CreateProfessionalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'professional_type' => ['required', 'string', Rule::in(ProfessionalType::values())],
        ];
    }

    public function professionalType(): ProfessionalType
    {
        return ProfessionalType::from($this->input('professional_type'));
    }

    public function professionalValidator(): ProfessionalValidator
    {
        return $this->professionalType()->validator();
    }
}
