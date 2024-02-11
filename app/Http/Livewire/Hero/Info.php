<?php

namespace App\Http\Livewire\Hero;

use App\Models\PersonalInformation;
use Livewire\Component;
use App\Http\Livewire\Traits\Slideover;

class Info extends Component
{
    use Slideover;

    //Propiedades que se veran en la vista
    public PersonalInformation $info;

    //Propiedad para gestionar el archivo
    public $cvFile = null;

    //Propiedad para la imagen
    public $imageFile = null;

    public function mount()
    {
        //En caso de no tener un registro, se creara un objeto vacio
        $this->info = PersonalInformation::first() ?? new PersonalInformation();
    }

    public function render()
    {
        return view('livewire.hero.info');
    }
}
