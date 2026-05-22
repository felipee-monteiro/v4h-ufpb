<?php

use App\Enums\RoleName;
use App\Models\Service;
use App\Models\Teleconsultoria;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\ServiceSeeder;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard.index'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    app(RoleSeeder::class)->run();

    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard.index'));
    $response->assertOk();
});

test('solicitante users can create a teleconsultoria from the dashboard flow', function () {
    app(RoleSeeder::class)->run();
    app(ServiceSeeder::class)->run();

    $user = User::factory()->create();
    $user->assignRole(RoleName::SOLICITANTE->value);

    $service = Service::where('title', 'Cardiologia')->firstOrFail();

    $this->actingAs($user)
        ->post(route('solicitante.teleconsultorias.store'), [
            'patient_name' => 'Mariana Costa',
            'patient_initials' => 'MC',
            'patient_birthday' => '1988-05-10',
            'service_uuid' => $service->uuid,
            'diagnostic_hypothesis' => 'Arritmia em investigação',
            'clinical_history' => 'Paciente relata palpitações recorrentes há duas semanas.',
        ])
        ->assertRedirect(route('dashboard.index'));

    expect(Teleconsultoria::query()->count())->toBe(1);

    $this->assertDatabaseHas('teleconsultorias', [
        'solicitante_uuid' => $user->uuid,
        'service_uuid' => $service->uuid,
        'patient_name' => 'Mariana Costa',
        'patient_initials' => 'MC',
        'status' => 'Pendente',
    ]);
});

test('especialista users can register an opinion for their assigned teleconsultoria', function () {
    app(RoleSeeder::class)->run();
    app(ServiceSeeder::class)->run();

    $solicitante = User::factory()->create();
    $solicitante->assignRole(RoleName::SOLICITANTE->value);

    $especialista = User::factory()->create();
    $especialista->assignRole(RoleName::ESPECIALISTA->value);

    $service = Service::where('title', 'Cardiologia')->firstOrFail();
    $service->professional_uuid = $especialista->uuid;
    $service->save();

    $teleconsultoria = Teleconsultoria::create([
        'solicitante_uuid' => $solicitante->uuid,
        'service_uuid' => $service->uuid,
        'patient_name' => 'Paciente Teste',
        'patient_initials' => 'PT',
        'patient_birthday' => '1990-01-01',
        'diagnostic_hypothesis' => 'Hipótese clínica',
        'clinical_history' => 'História clínica',
        'status' => 'Pendente',
    ]);

    $this->actingAs($especialista)
        ->post(route('especialista.teleconsultorias.registerOpinion', ['teleconsultoria' => $teleconsultoria->uuid]), [
            'professional_opinion' => 'Avaliação do especialista sobre o caso.',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('teleconsultorias', [
        'uuid' => $teleconsultoria->uuid,
        'professional_opinion' => 'Avaliação do especialista sobre o caso.',
    ]);
});
