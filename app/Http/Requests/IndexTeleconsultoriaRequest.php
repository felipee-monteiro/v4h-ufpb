<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class IndexTeleconsultoriaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search'    => ['nullable', 'string', 'max:255'],
            'status'    => ['nullable', 'array'],
            'status.*'  => ['string'],
            'date_from' => ['nullable', 'date'],
            'date_to'   => ['nullable', 'date'],
        ];
    }

    public function search(): ?string
    {
        return $this->input('search');
    }

    public function from()
    {
        return $this->input('date_from');
    }

    public function to()
    {
        return $this->input('date_to');
    }
}
