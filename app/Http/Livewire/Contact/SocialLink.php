<?php

namespace App\Http\Livewire\Contact;

use App\Http\Livewire\Traits\Notification;
use App\Http\Livewire\Traits\Slideover;
use Livewire\Component;
use App\Models\SocialLink as SocialLinkModel;

class SocialLink extends Component
{

    use Slideover, Notification;

    public SocialLinkModel $socialLink;

    protected $rules = [
        'socialLink.name' => 'required|max:20',
        'socialLink.url' => 'required|url',
        'socialLink.icon' => ['nullable', 'regex:/^(fa-brands|fa-solid)\sfa-[a-z-]+/i'],
    ];

    public function mount()
    {
        $this->socialLink = new SocialLinkModel();
    }

    public function save()
    {
        $this->validate();

        $this->socialLink->save();

        $this->reset('openSlideover');

        $this->notify(__('Social link saved successfully!'));
    }

    public function create()
    {
        //Si vamos a crear, y ya teniamos un modelo cargado, lo limpiamos
        if($this->socialLink->getKey()){
            $this->socialLink = new SocialLinkModel();
        }

        $this->openSlide(true);

    }

    public function render()
    {
        $socialLinks = SocialLinkModel::get();

        return view('livewire.contact.social-link',['socialLinks' => $socialLinks]);
    }
}
