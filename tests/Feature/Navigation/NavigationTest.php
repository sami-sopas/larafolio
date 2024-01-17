<?php

namespace Tests\Feature\Navigation;

use Tests\TestCase;
use Livewire\Livewire;
use App\Models\Navitem;
use App\Http\Livewire\Navigation\Navigation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NavigationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function navigation_component_can_be_rendered()
    {
        $this->get('/')
            ->assertStatus(200) //Verificar que se carga la pagina
            ->assertSeeLivewire('navigation.navigation'); //Verificar que se renderiza el componente
    }

    /**
     * @test
     * @return void
     */
    public function component_can_load_items_navigation()
    {
        $items = Navitem::factory(3)->create();

        Livewire::test(Navigation::class)
            //Ver que las propiedades de almenos el primer elemento se muestran en la vista del componente
            ->assertSee($items->first()->label)
            ->assertSee($items->first()->link);


    }

}
