<?php

use App\Enums\ProfessionalType;
use App\Services\Professionals\ValidateCRO;
use App\Services\Professionals\ValidateCRM;

test('professional type enum exposes the expected values and validator mapping', function () {
    expect(ProfessionalType::values())->toBe(['medico', 'psicologo']);
    expect(ProfessionalType::MEDICO->value)->toBe('medico');
    expect(ProfessionalType::MEDICO->validator())->toBe(ValidateCRM::class);
    expect(ProfessionalType::PSICOLOGO->validator())->toBe(ValidateCRO::class);
    expect(ProfessionalType::MEDICO->label())->toBe('Médico');
    expect(ProfessionalType::PSICOLOGO->label())->toBe('Psicólogo');
});
