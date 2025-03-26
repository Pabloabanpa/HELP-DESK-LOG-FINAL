<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class UserSearch extends Component
{
    use WithPagination;

    public $search = '';

    protected $updatesQueryString = ['search'];

    public function updatingSearch()
    {
        // Resetea la paginación cada vez que cambie la búsqueda
        $this->resetPage();
    }

    public function render()
    {
        $query = User::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        $users = $query->orderBy('id', 'desc')->paginate(10);

        return view('livewire.user-search', compact('users'));
    }
}
