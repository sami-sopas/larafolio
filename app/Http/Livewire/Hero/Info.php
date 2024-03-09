<?php

namespace App\Http\Livewire\Hero;

use RuntimeException;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PersonalInformation;
use App\Http\Livewire\Traits\Slideover;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Traits\Notification;
use App\Http\Livewire\Traits\WithImageFile;

class Info extends Component
{
    use Slideover, WithFileUploads, WithImageFile, Notification;

    //Propiedades que se utilizara en la vista
    public PersonalInformation $info;

    //Propiedad para gestionar el archivo
    public $cvFile = null;

    //Propiedad para la imagen
    public $imageFile = null;

    protected $rules = [
        'info.title' => 'required|max:20',
        'info.description' => 'required|max:255',
        'cvFile' => 'nullable|mimes:pdf|max:1024',
        'imageFile' => 'nullable|image|max:1024',
    ];

    /*
    * Hooks para validar de inmediao el archivo cuando se haya subido
      y asi no esperar hasta que se de click al boton actualizar
    */
    public function updatedCvFile()
    {
        $this->validate([
            'cvFile' => 'mimes:pdf|max:1024',
        ]);
    }

    public function updatedImageFile()
    {
        $this->verifyTemporaryUrl();

        $this->validate([
            'imageFile' => 'image|max:1024',
        ]);
    }

    public function mount()
    {
        //En caso de no tener un registro, se creara un objeto vacio
        $this->info = PersonalInformation::first() ?? new PersonalInformation();
    }

    public function download()
    {
        return Storage::disk('cv')->download($this->info->cv ?? 'my-cv.pdf');
    }

    public function edit()
    {
        $this->validate();

        $this->info->save();

        //Si se subio un archivo...
        if($this->cvFile){
            //se elimina el anterior
            $this->deleteFile(disk: 'cv', filename: $this->info->cv);

            //Se guarda el nuevo archivo (actualiza), el metodo store retorna el nombre del archivo que el asigna
            // y lo guarda en la carpeta cv
            $newName =  $this->cvFile->store('/', 'cv');

            $this->info->update(['cv' => $newName]);
        }

        //Si se subio una imagen...
        if($this->imageFile){
            //se elimina el anterior
            $this->deleteFile(disk: 'hero', filename: $this->info->image);

            //Se guarda el nuevo archivo (actualiza), el metodo store retorna el nombre del archivo que el asigna
            // y lo guarda en la carpeta cv
            $newName =  $this->imageFile->store('/', 'hero');

            $this->info->update(['image' => $newName]);
        }

        //Reiniciar propiedades (ojo tambien reinicia la de los traits)
        $this->resetExcept('info');

        $this->notify('Information saved successfully!');
    }

    public function render()
    {
        return view('livewire.hero.info');
    }
}
