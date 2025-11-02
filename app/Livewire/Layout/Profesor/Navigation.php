<?php

namespace App\Livewire\Layout\Profesor;

use Livewire\Component;
use App\Livewire\Actions\Logout;

class Navigation extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.layout.profesor.navigation');
    }
}
