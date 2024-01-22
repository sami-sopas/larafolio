<?php

namespace Tests\Feature\Navigation;

use Tests\TestCase;
use App\Models\User;
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

    /**
     * @test
     * @return void
     */
    public function only_admin_can_see_navigation_actions() : void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Navigation::class)
            ->assertStatus(200) //Se renderiza el componente
            ->assertSee('Editar') //Ver si se ven esos botones
            ->assertSee('Nuevo');
    }

    /**
     * @test
     * @return void
     */
    // public function guests_cannot_see_navigation_actions() : void
    // {
    //     Livewire::test(Navigation::class)
    //         ->assertStatus(200) //Se renderiza el componente
    //         ->assertDontSee('Editar') //Ver si se ven esos botones
    //         ->assertDontSee('Nuevo');

    //     //Verificar que el usuario que visita la pagina es un invitado
    //     $this->assertGuest();
    // }

    /**
     * @test
     * @return void
     */
    public function admin_can_edit_items()
    {
        //Crear usuario
        $user = User::factory()->create();

        //Crear items
        $items = Navitem::factory(2)->create();

        Livewire::actingAs($user)
            ->test(Navigation::class)
            //Seteando o agregando un valor a una propiedad especifica del componente
            ->set('items.0.label', 'My Projects') //Modificando primer elemento de la coleccion
            ->set('items.0.link', '#my-projects')
            ->set('items.1.label', 'Contact Me') //Modificando segundo elemento de la coleccion
            ->set('items.1.link', '#contact-me')
            ->call('edit'); //Metodo que ejecutara la accion de editar

        //Verificar que los cambios se guardaron en la base de datos
        $this->assertDatabaseHas('navitems', [
            'id' => $items->first()->id,
            'label' => 'My Projects',
            'link' => '#my-projects'
        ]);

        //Verificando el segundo registro (items.1)
        $this->assertDatabaseHas('navitems', [
            'id' => $items->last()->id,
            'label' => 'Contact Me',
            'link' => '#contact-me'
        ]);

    }

}
