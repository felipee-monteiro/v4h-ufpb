<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class TeleconsultoriasResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'patient' => $this->patient_name,
            'patient_initials'=> $this->patient_initials,
            'specialty'=> $this->service->title,
            'date'=> $this->created_at->toDateString(),
            'status'=> $this->status->value,
            'diagnostic_hypothesis'=> $this->diagnostic_hypothesis,
            'clinical_history'=> $this->clinical_history,
            'professional'=> $this->service->professional->name,
            'professional_uuid'=> $this->service->professional->getKey(),
            'professional_opinion'=> $this->professional_opinion,  
        ];
    }

    public function toId(Request $request): string
    {
        return $this->getKey();
    }
}
