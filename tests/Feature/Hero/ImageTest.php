<?php

namespace Tests\Feature\Hero;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Hero\Image;
use App\Models\PersonalInformation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImageTest extends TestCase
{
    /**
     *
     * @test
     * @return void
     */
    public function hero_image_component_can_be_render()
    {
        Livewire::test(Image::class)->assertStatus(200);
    }

    /**
     *
     * @test
     * @return void
     */
    public function component_can_load_hero_image()
    {
        //$info = PersonalInformation::factory()->create();

        /*La database seeder crea un registro de personalInformation
          pero con campo image null, por lo que se debe se ve la imagen por default*/
        Livewire::test(Image::class)
            ->assertSee('default-hero.jpg');
    }
}
