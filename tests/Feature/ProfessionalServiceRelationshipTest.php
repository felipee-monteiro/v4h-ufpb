<?php

use App\Models\Professional;
use App\Models\Service;

it('associates professionals with multiple services and services with multiple professionals', function () {
    $professionalA = new Professional();
    $professionalA->name = 'Dr. Silva';
    $professionalA->email = 'dr.silva@example.com';
    $professionalA->password = bcrypt('password');
    $professionalA->save();

    $professionalB = new Professional();
    $professionalB->name = 'Dra. Maria';
    $professionalB->email = 'dra.maria@example.com';
    $professionalB->password = bcrypt('password');
    $professionalB->save();

    $servicePsychology = new Service();
    $servicePsychology->title = 'Psicologia';
    $servicePsychology->description = 'Atendimento psicológico';
    $servicePsychology->save();

    $serviceDentistry = new Service();
    $serviceDentistry->title = 'Odontologia';
    $serviceDentistry->description = 'Atendimento odontológico';
    $serviceDentistry->save();

    $professionalA->services()->attach([
        $servicePsychology->uuid,
        $serviceDentistry->uuid,
    ]);

    $professionalB->services()->attach($servicePsychology->uuid);

    expect($professionalA->services()->count())->toBe(2);
    expect($professionalB->services()->count())->toBe(1);
    expect($servicePsychology->professionals()->count())->toBe(2);
    expect($serviceDentistry->professionals()->count())->toBe(1);
});
