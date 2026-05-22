<?php

namespace Database\Seeders;

use App\Models\Service;
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
        // (Cardiologia, Cirurgia Robótica, Odontologia, Doenças Raras, Oxigenoterapia)

        Service::firstOrCreate([
            'title' => 'Cardiologia'
        ]);

        Service::firstOrCreate([
            'title' => 'Cirurgia Robótica'
        ]);

        Service::firstOrCreate([
            'title' => 'Odontologia'
        ]);

        Service::firstOrCreate([
            'title' => 'Doenças Raras'
        ]);

        Service::firstOrCreate([
            'title' => 'Oxigenoterapia'
        ]);
    }
}
