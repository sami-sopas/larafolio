<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Navitem;
use App\Models\PersonalInformation;
use App\Models\Project;
use App\Models\SocialLink;
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

        Project::factory(3)->create();

        SocialLink::factory()->create([
            'name' => 'Facebook',
            'url' => 'https://www.facebook.com',
            'icon' => 'fa-brands fa-facebook',
        ]);

        SocialLink::factory()->create([
            'name' => 'Twitter',
            'url' => 'https://www.twitter.com',
            'icon' => 'fa-brands fa-twitter',
        ]);

        SocialLink::factory()->create([
            'name' => 'Youtube',
            'url' => 'https://www.youtube.com',
            'icon' => 'fa-brands fa-youtube',
        ]);
    }
}
