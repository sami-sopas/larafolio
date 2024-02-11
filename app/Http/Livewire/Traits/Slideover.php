<?php

namespace App\Http\Livewire\Traits;

trait Slideover
{
    //Para controlar el modal de slideOver
    public $openSlideover = false;

    //Para controlar si se va a editar o agregar un nuevo item
    public $addNewItem = false;

    //Si se envia algo a addNewItem se da por entendido que sera accion de Add
    public function openSlide($addNewItem = false)
    {
        $this->addNewItem = $addNewItem;
        $this->openSlideover = true;
    }

}
