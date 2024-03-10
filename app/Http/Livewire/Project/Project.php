<?php

namespace App\Http\Livewire\Project;

use App\Models\Project as ProjectModel;
use Livewire\Component;

class Project extends Component
{
    public ProjectModel $currentProject;
    public bool $openModal = false;

    public function mount()
    {
        //Para evitar error cuando no existe un proyecto
        $this->currentProject = new ProjectModel();
    }

    public function loadProject(ProjectModel $project, $modal = true)
    {
        //Si recibimos un projecto diferente al actual, lo cargamos
        if($this->currentProject->isNot($project)) {
            $this->currentProject = $project;
        }

    $this->openModal = true;
    }

    public function render()
    {
        $projects = ProjectModel::get();

        return view('livewire.project.project', compact('projects'));
    }
}
