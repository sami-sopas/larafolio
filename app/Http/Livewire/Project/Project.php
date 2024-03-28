<?php

namespace App\Http\Livewire\Project;

use App\Http\Livewire\Traits\Notification;
use App\Http\Livewire\Traits\Slideover;
use App\Http\Livewire\Traits\WithImageFile;
use App\Models\Project as ProjectModel;
use Livewire\Component;
use Livewire\WithFileUploads;

class Project extends Component
{
    use Slideover, WithImageFile, WithFileUploads, Notification;

    public ProjectModel $currentProject;
    public bool $openModal = false;

    //Al eliminar, sale un mensaje si se da click, se dispara un evento desde el front, y aqui lo escuchamis
    protected $listeners = ['deleteProject'];

    protected $rules = [
        'currentProject.name' => 'required|max:100',
        'currentProject.description' => 'required|max:450',
        'imageFile' => 'nullable|image|max:1024',
        'currentProject.video_link' => ['nullable', 'url', 'regex:/^(https|http):\/\/(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)[A-z0-9-]+/i'],
        'currentProject.url' => 'nullable|url',
        'currentProject.repo_url' => ['nullable', 'url', 'regex:/^(https|http):\/\/(www\.)?(github|gitlab)\.com\/[A-z0-9-\/?=&]+/i'],
    ];

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

        $this->openModal = $modal;

        //Abrir slide para editar
        if(!$modal){
            $this->openSlide();
        }
    }

    public function create()
    {
        //Verificar si el ID esta guardado (existe ya uno)
        if($this->currentProject->getKey()){
            //Se crea un modelo vacio
            $this->currentProject = new ProjectModel();
        }

        //Abrir slideOver
        $this->openSlide();
    }

    //Guardar en BD
    public function save()
    {
        $this->validate();

        //Viene una nueva imagen en el request?
        if($this->imageFile) {
            //Eliminar la imagen anterior
            $this->deleteFile('projects', $this->currentProject->image);
            //Guardar la nueva imagen
            $this->currentProject->image = $this->imageFile->store('/', 'projects');
        }

        $this->currentProject->save();

        $this->reset(['imageFile','openSlideover']);

        $this->notify(__('Project saved successfully'));

        //Nota: Ya no se necesita obtener de nuevo los projects, porque se ejecutara el metodo render

    }

    public function deleteProject(ProjectModel $project)
    {
        //Eliminar imagen de disco
        $this->deleteFile('projects', $project->image);

        //Eliminar de BD
        $project->delete();

        $this->notify(__('Project has been deleted'), 'deleteMessage');
    }

    public function render()
    {
        $projects = ProjectModel::get();

        return view('livewire.project.project', compact('projects'));
    }
}
