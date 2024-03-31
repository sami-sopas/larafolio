<?php

namespace Tests\Feature\Contact;

use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Http\Livewire\Contact\SocialLink;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\SocialLink as SocialLinkModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

    /** @test */
    public function only_admin_can_see_social_links_actions(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(SocialLink::class)
            ->assertStatus(200)
            ->assertSee(__('New'))
            ->assertSee(__('Edit'));

    }

    /** @test */
    public function guests_cannot_see_social_links_actions(): void
    {
        //$this->markTestSkipped('Descomentar despues');

        Livewire::test(SocialLink::class)
         ->assertStatus(200)
        ->assertDontSee(__('New'))
        ->assertDontSee(__('Edit'));

        $this->assertGuest();
    }


    /** @test */
    public function admin_can_add_a_social_link(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(SocialLink::class)
            ->set('socialLink.name','Facebook')
            ->set('socialLink.url','https://facebook.com')
            ->set('socialLink.icon','fa-brands fa-facebook')
            ->call('save');

        $this->assertDatabaseHas('social_links', [
            'name' => 'Facebook',
            'url' => 'https://facebook.com',
            'icon' => 'fa-brands fa-facebook'
        ]);
    }

    /** @test */
    public function admin_can_add_edit_a_social_link(): void
    {
        $user = User::factory()->create();

        $socialLink = SocialLinkModel::factory()->create();

        Livewire::actingAs($user)
            ->test(SocialLink::class)
            ->set('socialLinkSelected',$socialLink->id)
            ->set('socialLink.name','Facebook')
            ->set('socialLink.url','https://facebook.com')
            ->set('socialLink.icon','fa-brands fa-facebook')
            ->call('save');

        $socialLink->refresh();

        $this->assertDatabaseHas('social_links', [
            'id' => $socialLink->id,
            'name' => 'Facebook',
            'url' => 'https://facebook.com',
            'icon' => $socialLink->icon
        ]);
    }


    /** @test */
    public function admin_can_delete_a_social_link(): void
    {
        $user = User::factory()->create();

        $socialLink = SocialLinkModel::factory()->create();

        Livewire::actingAs($user)
            ->test(SocialLink::class)
            ->set('socialLinkSelected',$socialLink->id)
            ->call('deleteSocialLink');

        $this->assertDatabaseMissing('social_links', [
            'id' => $socialLink->id
        ]);

    }

        /** @test */
    public function name_is_required()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(SocialLink::class)
            ->set('socialLink.name', '')
            ->call('save')
            ->assertHasErrors(['socialLink.name' => 'required']);
    }

    /** @test */
    public function name_must_have_a_maximum_of_twenty_characters()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(SocialLink::class)
            ->set('socialLink.name', '123456789012345678901')
            ->call('save')
            ->assertHasErrors(['socialLink.name' => 'max']);
    }

    /** @test */
    public function url_is_required()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(SocialLink::class)
            ->set('socialLink.url', '')
            ->call('save')
            ->assertHasErrors(['socialLink.url' => 'required']);
    }

    /** @test */
    public function url_must_be_a_valid_url()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(SocialLink::class)
            ->set('socialLink.url', 'ertretretr')
            ->call('save')
            ->assertHasErrors(['socialLink.url' => 'url']);
    }

    /** @test */
    public function icon_must_match_with_regex()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(SocialLink::class)
            ->set('socialLink.icon', 'fa-fa fa-face-smile-wink')
            ->call('save')
            ->assertHasErrors(['socialLink.icon' => 'regex']);
    }
}
