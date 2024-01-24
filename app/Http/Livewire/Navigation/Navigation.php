<?php

namespace App\Http\Livewire\Navigation;

use App\Models\Navitem;
use Livewire\Component;
use App\Http\Livewire\Traits\Notification;

class Navigation extends Component
{
    use Notification;

    //Links que tendra la navegacion
    public $items;

    //Para controlar el modal de slideOver
    public $openSlideover = false;

    //Para controlar si se va a editar o agregar un nuevo item
    public $addNewItem = false;

    protected $listeners = [
        //Nombre del evento que se va a escuchar => 'Nombre del metodo que se va a ejecutar'
        'deleteItem'
    ];

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

        //Disparar evento al navegador mediante un trait nuestro
        $this->notify(__('Menu item updated successfully!'));
        //$this->dispatchBrowserEvent('notify',['message' => __('Menu item updated successfully!')]);


    }

    public function deleteItem(Navitem $item)
    {
        //Eliminar el item
        $item->delete();

        //Actualizar items
        $this->mount();

        //Enviar notificacion (disparar evento al navegador)
        //$this->dispatchBrowserEvent('deleteMessage',['message' => __('Menu item has been deleted')]);
        $this->notify(__('Menu item has been deleted'),'deleteMessage');
    }

    public function render()
    {
        return view('livewire.navigation.navigation');
    }
}
