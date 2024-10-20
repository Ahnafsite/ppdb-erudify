<?php

namespace App\Livewire;

use App\Models\AdmissionSetting;
use Carbon\Carbon;
use Livewire\Component;

class Home extends Component
{
    public $admissions;

    public function mount()
    {
        $now = Carbon::now();
        $this->admissions = AdmissionSetting::where('admission_period_start', '<=', $now)
        ->where('admission_period_end', '>=', $now)
        ->get();
    }
    public function render()
    {
        return view('livewire.home');
    }
}
