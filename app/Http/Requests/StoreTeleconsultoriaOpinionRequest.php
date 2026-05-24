<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreTeleconsultoriaOpinionRequest extends FormRequest
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
            'professional_opinion' => ['required', 'string', 'max:3000'],
        ];
    }

    public function professionalOpinion(): string
    {
        return $this->input('professional_opinion');
    }
}
