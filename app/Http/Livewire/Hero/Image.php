<?php

namespace App\Http\Livewire\Hero;

use Livewire\Component;
use App\Models\PersonalInformation;
use Illuminate\Support\Facades\Storage;

class Image extends Component
{
    private string $image = 'default-hero.jpg';

    protected $listeners = [
        'heroImageUpdated' => 'mount'
    ];

    public function mount()
    {
        $info = PersonalInformation::select('image')->first();

        //Si el objeto no es null y su campo imagen tampoco
        if(!is_null($info) && !is_null($info->image)){

            //Usamos la imagen que se encuentra en la base de datos
            $this->image = $info->image;
        }
    }

    //get property
    //Busca la url completa de la imagen
    public function getImageUrlProperty()
    {
        return Storage::disk('hero')->url($this->image);
    }

    public function render()
    {
        return view('livewire.hero.image');
    }
}
