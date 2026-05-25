<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class ServiceSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cardiologist = User::create([
            'name'     => 'Cardiologista',
            'email'    => 'cardiologista@example.com',
            'password' => bcrypt('password'),
        ]);

        $cardiologist->assignRole(RoleName::ESPECIALISTA->value);

        Service::firstOrCreate([
            'title'             => 'Cardiologia',
            'professional_uuid' => $cardiologist->getKey(),
        ]);

        $cirurgiaRobotica = User::create([
            'name'     => 'Cirurgião Robótico',
            'email'    => 'cirurgiao@example.com',
            'password' => bcrypt('password'),
        ]);

        $cirurgiaRobotica->assignRole(RoleName::ESPECIALISTA->value);

        Service::firstOrCreate([
            'title'             => 'Cirurgia Robótica',
            'professional_uuid' => $cirurgiaRobotica->getKey(),
        ]);

        $odontologist = User::create([
            'name'     => 'Dentista',
            'email'    => 'dentista@example.com',
            'password' => bcrypt('password'),
        ]);

        $odontologist->assignRole(RoleName::ESPECIALISTA->value);

        Service::firstOrCreate([
            'title'             => 'Odontologia',
            'professional_uuid' => $odontologist->getKey(),
        ]);

        $doencasRaras = User::create([
            'name'     => 'Especialista em Doenças Raras',
            'email'    => 'doencasraras@example.com',
            'password' => bcrypt('password'),
        ]);

        $doencasRaras->assignRole(RoleName::ESPECIALISTA->value);

        Service::firstOrCreate([
            'title'             => 'Doenças Raras',
            'professional_uuid' => $doencasRaras->getKey(),
        ]);

        $oxigenoterapia = User::create([
            'name'     => 'Especialista em Oxigenoterapia',
            'email'    => 'oxigenoterapia@example.com',
            'password' => bcrypt('password'),
        ]);

        $oxigenoterapia->assignRole(RoleName::ESPECIALISTA->value);

        Service::firstOrCreate([
            'title'             => 'Oxigenoterapia',
            'professional_uuid' => $oxigenoterapia->getKey(),
        ]);
    }
}
