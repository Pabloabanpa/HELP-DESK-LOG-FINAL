<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class UserSearch extends Component
{
    public $search = '';

    public function render()
    {
        $query = User::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
        }

        $users = $query->orderBy('id', 'desc')->paginate(10);

        // Mantener los parámetros en la paginación
        $users->appends(['search' => $this->search]);

        return view('livewire.user-search', [
            'users' => $users,
        ]);
    }
}
