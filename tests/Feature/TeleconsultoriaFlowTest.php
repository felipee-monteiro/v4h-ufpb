<?php

declare(strict_types=1);

use App\Enums\RoleName;
use App\Models\Service;
use App\Models\Teleconsultoria;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\ServiceSeeder;

beforeEach(static function (): void {
    app(RoleSeeder::class)->run();
    app(ServiceSeeder::class)->run();
});

test('the teleconsultoria critical flow works from creation through specialist opinion', function (): void {
    $solicitante = User::factory()->create();
    $solicitante->assignRole(RoleName::SOLICITANTE->value);

    $especialista = User::factory()->create();
    $especialista->assignRole(RoleName::ESPECIALISTA->value);

    $service                    = Service::where('title', 'Cardiologia')->firstOrFail();
    $service->professional_uuid = $especialista->uuid;
    $service->save();

    $this->actingAs($solicitante)
        ->post(route('solicitante.teleconsultorias.store'), [
            'patient_name'          => 'Mariana Costa',
            'patient_initials'      => 'MC',
            'patient_birthday'      => '1988-05-10',
            'service_uuid'          => $service->uuid,
            'diagnostic_hypothesis' => 'Arritmia em investigação',
            'clinical_history'      => 'Paciente relata palpitações recorrentes há duas semanas.',
        ])
        ->assertRedirect(route('dashboard.index'));

    expect(Teleconsultoria::query()->count())->toBe(1);

    $teleconsultoria = Teleconsultoria::first();

    $this->assertNotNull($teleconsultoria);
    $this->assertDatabaseHas('teleconsultorias', [
        'uuid'                 => $teleconsultoria->uuid,
        'solicitante_uuid'     => $solicitante->uuid,
        'service_uuid'         => $service->uuid,
        'patient_name'         => 'Mariana Costa',
        'patient_initials'     => 'MC',
        'status'               => 'Pendente',
        'professional_opinion' => null,
    ]);

    $this->actingAs($especialista)
        ->post(route('especialista.teleconsultorias.registerOpinion', ['teleconsultoria' => $teleconsultoria->uuid]), [
            'professional_opinion' => 'O caso requer acompanhamento cardiológico mensal.',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('teleconsultorias', [
        'uuid'                 => $teleconsultoria->uuid,
        'professional_opinion' => 'O caso requer acompanhamento cardiológico mensal.',
    ]);
});

test('teleconsultoria creation fails when service uuid is invalid', function (): void {
    $solicitante = User::factory()->create();
    $solicitante->assignRole(RoleName::SOLICITANTE->value);

    $this->actingAs($solicitante)
        ->post(route('solicitante.teleconsultorias.store'), [
            'patient_name'          => 'Mariana Costa',
            'patient_initials'      => 'MC',
            'patient_birthday'      => '1988-05-10',
            'service_uuid'          => 'invalid-uuid',
            'diagnostic_hypothesis' => 'Arritmia em investigação',
            'clinical_history'      => 'Paciente relata palpitações recorrentes há duas semanas.',
        ])
        ->assertSessionHasErrors(['service_uuid']);

    expect(Teleconsultoria::query()->count())->toBe(0);
});

test('role restrictions block a specialist from creating a teleconsultoria and a solicitante from registering an opinion', function (): void {
    $solicitante = User::factory()->create();
    $solicitante->assignRole(RoleName::SOLICITANTE->value);

    $especialista = User::factory()->create();
    $especialista->assignRole(RoleName::ESPECIALISTA->value);

    $service                    = Service::where('title', 'Cardiologia')->firstOrFail();
    $service->professional_uuid = $especialista->uuid;
    $service->save();

    $this->actingAs($especialista)
        ->post(route('solicitante.teleconsultorias.store'), [
            'patient_name'          => 'Caso Inválido',
            'patient_initials'      => 'CI',
            'patient_birthday'      => '1995-02-20',
            'service_uuid'          => $service->uuid,
            'diagnostic_hypothesis' => 'Teste de permissão',
            'clinical_history'      => 'História de permissão.',
        ])
        ->assertForbidden();

    $teleconsultoria = Teleconsultoria::create([
        'solicitante_uuid'      => $solicitante->uuid,
        'service_uuid'          => $service->uuid,
        'patient_name'          => 'Paciente Teste',
        'patient_initials'      => 'PT',
        'patient_birthday'      => '1990-01-01',
        'diagnostic_hypothesis' => 'Hipótese clínica',
        'clinical_history'      => 'História clínica',
        'status'                => 'Pendente',
    ]);

    $this->actingAs($solicitante)
        ->post(route('especialista.teleconsultorias.registerOpinion', ['teleconsultoria' => $teleconsultoria->uuid]), [
            'professional_opinion' => 'Tentativa indevida de registro.',
        ])
        ->assertForbidden();
});

test('registering a professional opinion without text returns validation errors', function (): void {
    $solicitante = User::factory()->create();
    $solicitante->assignRole(RoleName::SOLICITANTE->value);

    $especialista = User::factory()->create();
    $especialista->assignRole(RoleName::ESPECIALISTA->value);

    $service                    = Service::where('title', 'Cardiologia')->firstOrFail();
    $service->professional_uuid = $especialista->uuid;
    $service->save();

    $teleconsultoria = Teleconsultoria::create([
        'solicitante_uuid'      => $solicitante->uuid,
        'service_uuid'          => $service->uuid,
        'patient_name'          => 'Paciente Sem Parecer',
        'patient_initials'      => 'PSP',
        'patient_birthday'      => '1980-09-30',
        'diagnostic_hypothesis' => 'Hipótese de teste',
        'clinical_history'      => 'História de teste',
        'status'                => 'Pendente',
    ]);

    $this->actingAs($especialista)
        ->post(route('especialista.teleconsultorias.registerOpinion', ['teleconsultoria' => $teleconsultoria->uuid]), [
            'professional_opinion' => '',
        ])
        ->assertSessionHasErrors(['professional_opinion']);

    $this->assertDatabaseHas('teleconsultorias', [
        'uuid'                 => $teleconsultoria->uuid,
        'professional_opinion' => null,
    ]);
});
