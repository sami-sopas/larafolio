<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Navitem;
use App\Models\PersonalInformation;
use Illuminate\Database\Seeder;
use Database\Factories\NavitemFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Sami',
            'email' => 'prueba@gmail.com',
        ]);

        Navitem::factory()->create([
            'label' => 'Hola',
            'link' => '#hola',
        ]);

        Navitem::factory()->create([
            'label' => 'Proyectos',
            'link' => '#proyectos',
        ]);

        Navitem::factory()->create([
            'label' => 'Contacto',
            'link' => '#contacto',
        ]);

        PersonalInformation::factory()->create();
    }
}
