<?php

namespace App\Http\Livewire\Hero;

use App\Models\PersonalInformation;
use Livewire\Component;

class Info extends Component
{

    //Propiedades que se veran en la vista
    public PersonalInformation $info;

    //Propiedad para gestionar el archivo
    public $cvFile = null;

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
