<?php

namespace App\Http\Livewire\Project;

use App\Models\Project as ProjectModel;
use Livewire\Component;

class Project extends Component
{
    public function render()
    {
        $projects = ProjectModel::get();

        return view('livewire.project.project', compact('projects'));
    }
}
