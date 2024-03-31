<?php

namespace Tests\Feature\Contact;

use Tests\TestCase;
use Livewire\Livewire;
use App\Models\PersonalInformation;
use App\Http\Livewire\Contact\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function contact_component_can_be_rendered(): void
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSeeLivewire(Contact::class);
    }

    /** @test */
    public function component_can_load_contact_email(): void
    {
        $info = PersonalInformation::factory()->create();

        Livewire::test(Contact::class)
            ->assertSee($info->email);
    }

    /** @test */
    public function only_admin_can_see_contact_action(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(Contact::class)
            ->assertStatus(200)
            ->assertSee(__('Edit'));
    }


    /** @test */
    public function guest_cannot_see_contact_action(): void
    {
        //$this->markTestSkipped('Uncomment later');

        Livewire::test(Contact::class)
            ->assertStatus(200)
            ->assertDontSee(__('Edit'));

        $this->assertGuest();
    }

        /** @test */
    public function admin_can_edit_contact_email(): void
    {
        $user = User::factory()->create();

        $contact = PersonalInformation::factory()->create();

        Livewire::actingAs($user)
            ->test(Contact::class)
            ->set('contact.email', 'email@gmail.com')
            ->call('edit');

        $this->assertDatabaseHas('personal_information',[
            'id' => $contact->id,
            'email' => 'email@gmail.com'
        ]);

    }

    /** @test */
    public function contact_email_is_required(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Contact::class)
            ->set('contact.email', '')
            ->call('edit')
            ->assertHasErrors(['contact.email' => 'required']);

    }


    /** @test */
    public function contact_email_must_be_a_valid_email(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Contact::class)
            ->set('contact.email', 'email incorrecto')
            ->call('edit')
            ->assertHasErrors(['contact.email' => 'email']);

    }
}
