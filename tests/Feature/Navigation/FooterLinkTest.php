<?php

namespace Tests\Feature\Navigation;

use Tests\TestCase;
use Livewire\Livewire;
use App\Models\Navitem;
use App\Http\Livewire\Navigation\FooterLink;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FooterLinkTest extends TestCase
{
    /** @test */
    public function footer_link_component_can_be_rendered(): void
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSeeLivewire('navigation.footer-link');
    }

    /** @test */
    public function component_can_load_items_navigation(): void
    {
        $items = Navitem::factory()->count(3)->create();

        Livewire::test(FooterLink::class)
            ->assertSee($items->first()->label)
            ->assertSee($items->last()->label);
    }
}
