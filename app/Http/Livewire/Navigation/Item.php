<?php

namespace App\Http\Livewire\Navigation;

use App\Models\Navitem;
use Livewire\Component;
use App\Http\Livewire\Traits\Notification;

class Item extends Component
{
    use Notification;

    public Navitem $item;

    protected $rules = [
        'item.label' => 'required|max:20',
        'item.link' => 'required|max:40',
    ];

    public function mount()
    {
        //Inicializar objeto vacio, para en la vista acceder a item.label, item.link
        $this->item = new Navitem();
    }

    public function save()
    {
        $this->validate();

        $this->item->save();

        $this->notify(__('Item created successfully!'));
    }

    public function render()
    {
        return view('livewire.navigation.item');
    }
}
