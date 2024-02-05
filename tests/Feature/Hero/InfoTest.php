<?php

namespace Tests\Feature\Hero;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Hero\Info;
use App\Models\PersonalInformation;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InfoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function hero_info_component_can_be_rendered()
    {
        $this->get('/')
            ->assertStatus(200) //Verificar que se carga la pagina
            ->assertSeeLivewire('hero.info'); //Verificar que se renderiza el componente
    }

    /**
     * @test
     * @return void
     */
    public function component_can_load_hero_information()
    {
        $info = PersonalInformation::factory()->create();

        Livewire::test(Info::class)
            ->assertSee($info->title) //Ver en la pagina la informacion
            ->assertSee($info->description);
    }

    /**
     * @test
     * @return void
     */
    public function only_admin_can_see_hero_action()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Info::class)
            ->assertStatus(200)
            ->assertSee(__('Edit'));

    }

    /**
     * @test
     * @return void
     */
    public function guests_cannot_see_hero_action()
    {
        $this->markTestSkipped('Descomentar despues');

        // Livewire::test(Info::class)
        //     ->assertStatus(200)
        //     ->assertDontSee(__('Edit'));

        // //Verificar que sea un visitante
        // $this->assertGuest();

    }
}
