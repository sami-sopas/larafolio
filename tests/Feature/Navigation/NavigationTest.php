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

    /**
     * @test
     * @return void
     */
    public function admin_can_delete_an_item()
    {
        //Crear usuario
        $user = User::factory()->create();

        //Crear item
        $item = Navitem::factory()->create();

        Livewire::actingAs($user)
            ->test(Navigation::class)
            ->call('deleteItem', $item); //Metodo que ejecutara la accion de eliminar

        //Verificar que ese Item no exista en la BD
        $this->assertDatabaseMissing('navitems', [
            'id' => $item->id,
            'label' => $item->label,
            'link' => $item->link
        ]);

    }

    /**
     * @test
     * @return void
     */
    public function label_of_items_is_required()
    {
        //Crear usuario
        $user = User::factory()->create();

        //Crear item
        $items = Navitem::factory(2)->create();

        Livewire::actingAs($user)
            ->test(Navigation::class)
            ->set('items.0.label', '')
            ->set('items.1.label', '')
            ->call('edit')   //Se envia el campo con la regla que se va a validar
            ->assertHasErrors(['items.0.label' => 'required'])
            ->assertHasErrors(['items.1.label' => 'required']);

    }

        /**
     * @test
     * @return void
     */
    public function link_of_items_is_required()
    {
        //Crear usuario
        $user = User::factory()->create();

        //Crear item
        $items = Navitem::factory(2)->create();

        Livewire::actingAs($user)
            ->test(Navigation::class)
            ->set('items.0.link', '')
            ->set('items.1.link', '')
            ->call('edit')   //Se envia el campo con la regla que se va a validar
            ->assertHasErrors(['items.0.link' => 'required'])
            ->assertHasErrors(['items.1.link' => 'required']);

    }

        /**
     * @test
     * @return void
     */
    public function label_of_items_must_have_a_maximum_of_twenty_characters()
    {
        //Crear usuario
        $user = User::factory()->create();

        //Crear item
        $items = Navitem::factory(2)->create();

        Livewire::actingAs($user)
            ->test(Navigation::class)
            ->set('items.0.label', '123456789112345678911111')
            ->set('items.1.label', '12345678998765432198765432')
            ->call('edit')   //Se envia el campo con la regla que se va a validar
            ->assertHasErrors(['items.0.label' => 'max'])
            ->assertHasErrors(['items.1.label' => 'max']);

    }

            /**
     * @test
     * @return void
     */
    public function link_of_items_must_have_a_maximum_of_twenty_characters()
    {
        //Crear usuario
        $user = User::factory()->create();

        //Crear item
        $items = Navitem::factory(2)->create();

        Livewire::actingAs($user)
            ->test(Navigation::class)
            ->set('items.0.link', '123456789112345678911111123456789112345678911111')
            ->set('items.1.link', '12345678998765432198765432123456789112345678911111')
            ->call('edit')   //Se envia el campo con la regla que se va a validar
            ->assertHasErrors(['items.0.link' => 'max'])
            ->assertHasErrors(['items.1.link' => 'max']);

    }

}
