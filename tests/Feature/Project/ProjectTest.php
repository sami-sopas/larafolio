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
        $this->markTestSkipped('This test has not been implemented yet.');

        // //Crear proyectos
        // ProjectModel::factory(3)->create();

        // Livewire::test(Project::class)
        //     ->assertStatus(200)
        //     ->assertDontSee(__('New Project'))
        //     ->assertDontSee(__('Edit'))
        //     ->assertDontSee(__('Delete'));

        // //Validar que somos guests
        // $this->assertGuest();

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



}
