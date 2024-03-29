<?php

namespace App\Http\Livewire\Traits;

use App\Models\Project;

trait ShowProjects
{
    //Proyectos a mostrar
    public int $counter = 3;

    //Cuantos proyectos hay en la BD
    public function getTotalProperty()
    {
        return Project::count();
    }

    public function showMore()
    {
        if($this->counter < $this->total){
            $this->counter += 3;
        }
    }

    //Dejar el contador de nuevo en 3
    public function showLess()
    {
        $this->reset('counter');
    }
}
