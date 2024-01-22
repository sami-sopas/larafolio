<?php

namespace App\Http\Livewire\Navigation;

use App\Models\Navitem;
use Livewire\Component;

class Navigation extends Component
{
    //Links que tendra la navegacion
    public $items;

    //Para controlar el modal de slideOver
    public $openSlideover = false;

    //Para controlar si se va a editar o agregar un nuevo item
    public $addNewItem = false;

    //Reglas de validacion, el * es que se aplicara a todo dentro de la coleccion
    protected $rules = [
        'items.*.label' => 'required|string|max:20',
        'items.*.link' => 'required|string|max:40',
    ];

    public function mount()
    {
        $this->items = Navitem::all();
    }

    //Si se envia algo a addNewItem se da por entendido que sera accion de Add
    public function openSlide($addNewItem = false)
    {
        $this->addNewItem = $addNewItem;
        $this->openSlideover = true;
    }

    public function edit()
    {
        //Validar reglas
        $this->validate();

        //Actualizo cada item al recorrelos
        foreach($this->items as $item)
        {
            $item->save();
        }

        //Cerrar el slideOver
        $this->reset('openSlideover'); //Es lo mismo que $this->openSlideover = false;

        //Disparar evento al navegador
        $this->dispatchBrowserEvent('notify',['message' => __('Menu items updated successfully!')]);


    }

    public function render()
    {
        return view('livewire.navigation.navigation');
    }
}
