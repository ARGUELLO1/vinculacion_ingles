<?php

namespace App\Livewire\ModalGlobal\AdminCoordinador;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class DeleteModal extends Component
{
    public $isOpen = false;
    public $userToDelete;
    public $userName;
    public $userType; // 'coordinador', 'capturista', 'profesor', 'alumno'

    protected $listeners = ['openDeleteUserModal' => 'openModal'];

    public function openModal($userId, $userType = null)
    {
        // Si solo llega un array (el caso problemático), extraemos los valores
        if (is_array($userId) && isset($userId['userId'])) {
            $data = $userId;
            $userId = $data['userId'] ?? null;
            $userType = $data['userType'] ?? $userType;
        }

        if (!$userId) {
            Log::error('userId no recibido en el evento');
            return;
        }

        $user = User::find($userId);

        if ($user) {
            $this->userToDelete = $userId;
            $this->userType = $userType;
            $this->userName = $user->{$userType}?->nombre_completo ?? $user->name;
            $this->isOpen = true;
        } else {
            Log::error('Usuario no encontrado', ['user_id' => $userId]);
        }
    }

    public function deleteUser()
    {
        if (!$this->userToDelete) return;

        // Configuración de rutas y mensajes por tipo de usuario
        $config = [
            'coordinador' => [
                'success' => 'Coordinador eliminado correctamente.',
                'error' => 'Error al eliminar el coordinador',
                'route' => 'admin.coordinadores.index'
            ],
            'capturista' => [
                'success' => 'Capturista eliminado correctamente.',
                'error' => 'Error al eliminar el capturista',
                'route' => 'admin.capturistas.index'
            ],
            'profesor' => [
                'success' => 'Profesor eliminado correctamente.',
                'error' => 'Error al eliminar el profesor',
                'route' => 'admin.profesores.index'
            ],
            'alumno' => [
                'success' => 'Alumno eliminado correctamente.',
                'error' => 'Error al eliminar el alumno',
                'route' => 'admin.alumnos.index'
            ]
        ];

        $defaultConfig = [
            'success' => 'Usuario eliminado correctamente.',
            'error' => 'Error al eliminar el usuario',
            'route' => 'admin.dashboard'
        ];

        $currentConfig = $config[$this->userType] ?? $defaultConfig;

        try {
            DB::transaction(function () {
                $user = User::find($this->userToDelete);

                if (!$user) {
                    throw new \Exception('Usuario no encontrado');
                }

                // Eliminar primero el registro específico según el tipo
                switch ($this->userType) {
                    case 'coordinador':
                        if ($user->coordinador) {
                            $user->coordinador->delete();
                        }
                        break;

                    case 'capturista':
                        if ($user->capturista) {
                            $user->capturista->delete();
                        }
                        break;

                    case 'profesor':
                        if ($user->profesor) {
                            $user->profesor->delete();
                        }
                        break;

                    case 'alumno':
                        if ($user->alumno) {
                            $user->alumno->delete();
                        }
                        break;
                }

                // Remover roles antes de eliminar el usuario
                $user->roles()->detach();

                // Ahora eliminar el usuario
                $user->delete();
            });

            $this->closeModal();
            session()->flash('success', $currentConfig['success']);
            return $this->redirectRoute($currentConfig['route'], navigate: true);
        } catch (\Exception $e) {
            $this->closeModal();
            session()->flash('error', $currentConfig['error'] . ': ' . $e->getMessage());
            return $this->redirectRoute($currentConfig['route'], navigate: true);
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(['userToDelete', 'userName', 'userType']);
    }

    public function render()
    {
        return view('livewire.modal-global.admin-coordinador.delete-modal');
    }
}
