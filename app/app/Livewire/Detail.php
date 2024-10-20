<?php

namespace App\Livewire;

use App\Models\AdmissionSetting;
use Error;
use Exception;
use Livewire\Component;

class Detail extends Component
{
    public $slug;
    public $admission;

    public function mount()
    {
        $this->admission = AdmissionSetting::where('slug', $this->slug)->first();
        if(!$this->admission) {
            abort(404);
        }
    }
    public function render()
    {
        return view('livewire.detail');
    }
}