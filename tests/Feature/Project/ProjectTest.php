<?php

namespace Tests\Feature\Project;

use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use Illuminate\Http\UploadedFile;
use App\Http\Livewire\Project\Project;
use App\Models\Project as ProjectModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function project_component_can_be_rendered(): void
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSeeLivewire('project.project');
    }

    /**
     * @test
     * @return void
     */
    public function component_can_load_projects(): void
    {
        $projects = ProjectModel::factory(2)->create();

        Livewire::test(Project::class)
            ->assertSee($projects->first()->name)
            ->assertSee($projects->first()->image)
            ->assertSee($projects->last()->name)
            ->assertSee($projects->last()->image);
    }

    /**
     * @test
     * @return void
     */
    public function user_can_see_all_project_info(): void
    {
        $project = ProjectModel::factory()->create([
            'name' => 'Project Name',
            'description' => 'Project Description',
            'image' => 'project-image.jpg',
            'video_link' => 'https://www.youtube.com/watch?v=video-link',
            'url' => 'https://project-url.com',
            'repo_url' => 'https://project-repo-url.com',
        ]);

        Livewire::test(Project::class)
            ->call('loadProject', $project->id)
            ->assertSee($project->name)
            ->assertSee($project->description)
            ->assertSee($project->image)
            ->assertSee($project->video_code) //En la vista vemos el accesor
            ->assertSee($project->url)
            ->assertSee($project->repo_url);
    }

    /**
     * @test
     * @return void
     */
    public function only_admin_can_see_projects_actions(): void
    {
        $user = User::factory()->create();

        //Crear proyectos
        ProjectModel::factory(3)->create();

        Livewire::actingAs($user)
            ->test(Project::class)
            ->assertStatus(200)
            ->assertSee(__('New Project'))
            ->assertSee(__('Edit'))
            ->assertSee(__('Delete'));

    }

    /**
     * @test
     * @return void
     */
    public function guest_can_not_see_projects_actions(): void
    {
        //$this->markTestSkipped('This test has not been implemented yet.');

        //Crear proyectos
        ProjectModel::factory(3)->create();

        Livewire::test(Project::class)
            ->assertStatus(200)
            ->assertDontSee(__('New Project'))
            ->assertDontSee(__('Edit'))
            ->assertDontSee(__('Delete'));

        //Validar que somos guests
        $this->assertGuest();

    }

    /**
     * @test
     * @return void
     */
    public function admin_can_add_a_project(): void
    {
        $user = User::factory()->create();

        $image = UploadedFile::fake()->image('myimg.jpg');

        Storage::fake('projects');

        Livewire::actingAs($user)
            ->test(Project::class)
            ->set('currentProject.name', 'Project Name')
            ->set('currentProject.description', 'Project Description')
            ->set('imageFile', $image)
            ->set('currentProject.video_link', 'https://www.youtube.com/watch?v=K4TOrB7at0Y')
            ->set('currentProject.url', 'https://www.cafedelprogramador.com/')
            ->set('currentProject.repo_url', 'https://github.com/gamg/workshop-portfolio')
            ->call('save');

        $newProject = ProjectModel::first();

        $this->assertDatabaseHas('projects',[
            'id' => $newProject->id,
            'name' => 'Project Name',
            'description' => 'Project Description',
            'image' => $newProject->image,
            'video_link' => $newProject->video_link,
            'url' => $newProject->url,
            'repo_url' => $newProject->repo_url,
        ]);

        //Verificar guardado de imagen
        Storage::disk('projects')->assertExists($newProject->image);

    }

    /** @test */
    public function admin_can_edit_a_project()
    {
        $user = User::factory()->create();
        $project = ProjectModel::factory()->create();
        $img = UploadedFile::fake()->image('mysuperimg.jpg');
        Storage::fake('projects');

        Livewire::actingAs($user)->test(Project::class)
            ->call('loadProject', $project->id)
            ->set('currentProject.name', 'My super project updated')
            ->set('currentProject.description', 'Software Developed with Laravel PHP and a lot of love')
            ->set('imageFile', $img)
            ->set('currentProject.video_link', 'https://www.youtube.com/watch?v=K4TOrB7at0Y')
            ->set('currentProject.url', 'https://www.cafedelprogramador.com/')
            ->set('currentProject.repo_url', 'https://github.com/gamg/workshop-portfolio')
            ->call('save');

        $project->refresh();

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'My super project updated',
            'description' => 'Software Developed with Laravel PHP and a lot of love',
            'image' => $project->image,
            'video_link' => $project->video_link,
            'url' => $project->url,
            'repo_url' => 'https://github.com/gamg/workshop-portfolio',
        ]);

        Storage::disk('projects')->assertExists($project->image);
    }

    /** @test */
    public function admin_can_delete_a_project()
    {
        $user = User::factory()->create();
        $project = ProjectModel::factory()->create();
        $img = UploadedFile::fake()->image('mysuperimg.jpg');
        Storage::fake('projects');

        //actualizar imagen, para quitar la que viene por default con el factory
        Livewire::actingAs($user)->test(Project::class)
            ->call('loadProject', $project->id)
            ->set('imageFile', $img)
            ->call('save');

        $project->refresh();

        Livewire::actingAs($user)->test(Project::class)
            ->call('deleteProject', $project->id);

        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
        ]);

        //Verificar que la imagen ya no existe en el disco
        Storage::disk('projects')->assertMissing($project->image);
    }

    /** @test */
    public function name_is_required()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(Project::class)
            ->set('currentProject.name', '')
            ->call('save')
            ->assertHasErrors(['currentProject.name' => 'required']);
    }

        /** @test */
    public function name_must_have_a_maximum_of_one_hundred_characters()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(Project::class)
            ->set('currentProject.name', 'abdcefghijklmnopqrstuabdcefghijklmnopqrstabdcefghijklmnopqrstuabdcefghijklmnopqrst1234567891234567891')
            ->call('save')
            ->assertHasErrors(['currentProject.name' => 'max']);
    }

    /** @test */
    public function description_is_required()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(Project::class)
            ->set('currentProject.description', '')
            ->call('save')
            ->assertHasErrors(['currentProject.description' => 'required']);
    }

    /** @test */
    public function description_must_have_a_maximum_of_four_hundred_fifty_characters()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(Project::class)
            ->set('currentProject.description', 'abdcefghabdcefghijklmnopqrstuabdcefghijklmnopqrstabdcefghijklmnopqrstuabdcefghijklmnopqrst123456789123456789abdcefghijklmnopqrstuabdcefghijklmnopqrstabdcefghijklmnopqrstuabdcefghijklmnopqrst123456789123456789abdcefghijklmnopqrstuabdcefghijklmnopqrstabdcefghijijklmnopqrstuabdcefghijklmnopqrstabdcefghijklmnopqrstuabdcefghijklmnopqrst123456789123456789abdcefghijklmnopqrstuabdcefghijklmnopqrstabdcefghijklmnopqrstuabdcefghijklmnopqrst123456789123456789abdcefghijklmnopqrstuabdcefghijklmnopqrstabdcefghij')
            ->call('save')
            ->assertHasErrors(['currentProject.description' => 'max']);
    }

    /** @test */
    public function image_file_must_be_a_image()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(Project::class)
            ->set('imageFile', UploadedFile::fake()->create('myfile.pdf'))
            ->call('save')
            ->assertHasErrors(['imageFile' => 'image']);
    }

    /** @test */
    public function image_file_must_be_max_one_megabyte()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(Project::class)
            ->set('imageFile', UploadedFile::fake()->image('myimage.jpg')->size(1025))
            ->call('save')
            ->assertHasErrors(['imageFile' => 'max']);
    }

    /** @test */
    public function video_link_must_be_a_valid_url()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(Project::class)
            ->set('currentProject.video_link', 'http:/www.google.com')
            ->call('save')
            ->assertHasErrors(['currentProject.video_link' => 'url']);
    }

    /** @test */
    public function video_link_must_match_with_regex()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(Project::class)
            ->set('currentProject.video_link', 'https://www.youtube.com/')
            ->call('save')
            ->assertHasErrors(['currentProject.video_link' => 'regex']);
    }

    /** @test */
    public function url_must_be_a_valid_url()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(Project::class)
            ->set('currentProject.url', 'http:/www.google.com')
            ->call('save')
            ->assertHasErrors(['currentProject.url' => 'url']);
    }

    /** @test */
    public function repo_url_must_be_a_valid_url()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(Project::class)
            ->set('currentProject.repo_url', 'http:/www.google.com')
            ->call('save')
            ->assertHasErrors(['currentProject.repo_url' => 'url']);
    }

    /** @test */
    public function repo_url_must_match_with_regex()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(Project::class)
            ->set('currentProject.repo_url', 'https://nogithub.com/gamg/workshop-portfolio')
            ->call('save')
            ->assertHasErrors(['currentProject.repo_url' => 'regex']);
    }






}
