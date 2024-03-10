<?php

namespace Tests\Feature\Project;

use App\Http\Livewire\Project\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use App\Models\Project as ProjectModel;

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
}
