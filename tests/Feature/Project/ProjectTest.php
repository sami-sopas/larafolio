<?php

namespace Tests\Feature\Project;

use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Http\Livewire\Project\Project;
use App\Models\Project as ProjectModel;
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

}
