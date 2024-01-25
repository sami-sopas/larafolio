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
}
