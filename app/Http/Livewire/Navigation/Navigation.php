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

    public function render()
    {
        return view('livewire.navigation.navigation');
    }
}
