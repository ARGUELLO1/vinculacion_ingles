<?php

namespace App\Livewire\Layout\Alumno;

use App\Livewire\Actions\Logout;
use Livewire\Component;

class Navigation extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.layout.alumno.navigation');
    }
}