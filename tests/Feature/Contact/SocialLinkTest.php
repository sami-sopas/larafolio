<?php

namespace Tests\Feature\Contact;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Contact\SocialLink;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\SocialLink as SocialLinkModel;

class SocialLinkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function social_link_component_can_be_render(): void
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSeeLivewire('contact.social-link');
    }

    /** @test */
    public function component_can_load_social_links(): void
    {
        $links = SocialLinkModel::factory(3)->create();

        Livewire::test(SocialLink::class)
            ->assertSee($links->first()->url)
            ->assertSee($links->first()->icon)
            ->assertSee($links->last()->url)
            ->assertSee($links->last()->icon);
    }
}
