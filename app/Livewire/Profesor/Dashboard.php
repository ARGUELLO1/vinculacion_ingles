<?php

namespace App\Livewire\Profesor;

use Illuminate\Support\Facades\Auth;
use App\Livewire\Actions\Logout;
use Livewire\Component;

class Dashboard extends Component
{

    public function render()
    {
        return view('livewire.profesor.dashboard');
    }
}
