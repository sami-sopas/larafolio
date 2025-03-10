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

    public $socialLinkSelected = '';

    protected $rules = [
        'socialLink.name' => 'required|max:20',
        'socialLink.url' => 'required|url',
        'socialLink.icon' => ['nullable', 'regex:/^(fa-brands|fa-solid)\sfa-[a-z-]+/i'],
    ];

    protected $listeners = ['deleteSocialLink'];

    //Hook updated
    public function updatedSocialLinkSelected()
    {
        $data = SocialLinkModel::find($this->socialLinkSelected);

        if($data){
            $this->socialLink = $data;
        } else{
            $this->socialLinkSelected = '';
        }

    }

    public function mount()
    {
        $this->socialLink = new SocialLinkModel();
    }

    public function save()
    {
        $this->validate();

        $this->socialLink->save();

        $this->reset(['openSlideover','socialLinkSelected']);

        $this->notify(__('Social link saved successfully!'));
    }

    public function create()
    {
        //Si vamos a crear, y ya teniamos un modelo cargado, lo limpiamos
        if($this->socialLink->getKey()){
            $this->socialLink = new SocialLinkModel();
            $this->reset('socialLinkSelected');
        }

        $this->openSlide(true);

    }

    public function deleteSocialLink()
    {
        $this->socialLink->delete();
        $this->reset('socialLinkSelected');
        $this->notify(__('Social link has been deleted successfully!'),'deleteMessage');
    }

    public function render()
    {
        $socialLinks = SocialLinkModel::get();

        return view('livewire.contact.social-link',['socialLinks' => $socialLinks]);
    }
}
