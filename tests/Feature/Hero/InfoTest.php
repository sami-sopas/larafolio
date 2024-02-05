<?php

namespace Tests\Feature\Hero;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Hero\Info;
use App\Models\PersonalInformation;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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

    /**
     * @test
     * @return void
     */
    public function admin_can_edit_hero_info()
    {
        $user = User::factory()->create();

        $info = PersonalInformation::factory()->create();

        //Simula que se sube al servidor una imagen
        $image = UploadedFile::fake()->image('heroimage.jpg');

        //Lo mismo pero con un archivo
        $cv = UploadedFile::fake()->create('cv.pdf');

        //Guardar los archivos en un disco ficticio
        Storage::fake('hero');
        Storage::fake('cv');

        //Editando esa informacion
        Livewire::actingAs($user)
            ->test(Info::class)
            ->set('info.title', 'Juanito Animaña')
            ->set('info.description', 'No chambea')
            ->set('cvFile', $cv)
            ->set('imageFile', $image)
            ->call('edit');

        //Refrescar la info (para que no se quede con la info anterior)
        $info->refresh();

        //Verificar que se haya guardado la informacion
        $this->assertDatabaseHas('personal_information',[
            'id' => $info->id,
            'title' => 'Juanito Animaña',
            'description' => 'No chambea',
            'cv' => $info->cv, //al guardar los archivos, sus nombres cambiaran siempre (se les aplica un hash)
            'image' => $info->image
        ]);

        //Verificar que se haya guardado el archivo en base a sus nombres
        Storage::disk('hero')->assertExists($info->image);
        Storage::disk('cv')->assertExists($info->cv);
    }
}
