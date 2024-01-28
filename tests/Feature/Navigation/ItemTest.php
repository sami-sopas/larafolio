<?php

namespace Tests\Feature\Navigation;

use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Http\Livewire\Navigation\Item;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function item_can_be_rendered()
    {
        //Crear usuario autenticao
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Item::class)
            ->assertStatus(200); //Se renderiza el componente
    }

    /**
     * @test
     * @return void
     */
    public function admin_can_add_an_item()
    {
        //Crear usuario
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Item::class)
            ->set('item.label','My label') //Llenando el form
            ->set('item.link','#my-link')
            ->call('save');

        $this->assertDatabaseHas('navitems', [
            'label' => 'My label',
            'link' => '#my-link'
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function label_is_required()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Item::class)
            ->set('item.label','') //Se envia un label vacio
            ->set('item.link','#my-link')
            ->call('save')
            ->assertHasErrors(['item.label' => 'required']); //Verificar que se muestra el error del label

    }

    /**
     * @test
     * @return void
     */
    public function label_must_have_a_maximum_of_twenty_characteres()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Item::class)
            ->set('item.label','My label with more than twenty characters') //Se envia un label con mas de 20 caracteres
            ->set('item.link','#my-link')
            ->call('save')
            ->assertHasErrors(['item.label' => 'max']); //Verificar que se muestra el error del label
    }

        /**
     * @test
     * @return void
     */
    public function link_is_required()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Item::class)
            ->set('item.label','Mi enlace')
            ->set('item.link','') //Se envia un label vacio
            ->call('save')
            ->assertHasErrors(['item.link' => 'required']); //Verificar que se muestra el error del link

    }

        /**
     * @test
     * @return void
     */
    public function link_must_have_a_maximum_of_forty_characteres()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Item::class)
            ->set('item.label','my label')
            ->set('item.link','#11234567891234567891234567891234567891234567') //link con mas de 40 caracteres
            ->call('save')
            ->assertHasErrors(['item.link' => 'max']); //Verificar que se muestra el error del link
    }

}
