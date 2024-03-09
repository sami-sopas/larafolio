<?php

namespace Tests\Feature\Project;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
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
}
