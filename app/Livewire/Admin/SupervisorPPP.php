<?php

namespace App\Livewire\Admin;

use App\Events\UserCreated;
use App\Livewire\Forms\UserForm;
use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Usernotnull\Toast\Concerns\WireToast;

class SupervisorPPP extends Component
{
    use WithPagination;
    use WireToast;

    public $search;
    public $dates;
    public $isOpen = false,
        $isOpenAssign = false,
        $showUser = false,
        $isOpenDelete = false;
    public $itemId, $userState, $userRol;
    public UserForm $form;
    public ?User $user;
    public $companyUser;
    public $supervisores,
        $supervisor = [];

    public function refreshUsers()
    {
        $this->render();
    }

    public function render()
    {
        $this->supervisores = User::role('Supervisor')->get();
        $users = DB::table('company_users')
            ->join('users', 'company_users.user_id', '=', 'users.id')
            ->join('companies', 'company_users.company_id', '=', 'companies.id')
            ->join('company_users as cu', function ($join) {
                $join->on('company_users.company_id', '=', 'cu.company_id')
                    ->on('company_users.user_id', '=', 'cu.user_id');
            })
            ->select('users.*', 'companies.razon_social as razon_social', 'companies.id as company_id', 'company_users.supervisor_id') // Selecciona los campos necesarios
            ->where(function ($query) {
                $query
                    ->where('users.dni', 'like', '%' . $this->search . '%')
                    ->orWhere('users.name', 'like', '%' . $this->search . '%')
                    ->orWhere('users.surnames', 'like', '%' . $this->search . '%')
                    ->orWhere('users.email', 'like', '%' . $this->search . '%');
            })
            ->when($this->userState !== null, function ($query) {
                $query->where('users.status', $this->userState);
            })
            ->when($this->userRol, function ($query) {
                $query->whereExists(function ($subQuery) {
                    $subQuery
                        ->select(DB::raw(1))
                        ->from('role_user')
                        ->whereRaw('role_user.user_id = users.id')
                        ->where('role_user.role_id', $this->userRol);
                });
            })
            ->latest('users.id')
            ->paginate(10);

        return view('livewire.admin.supervisorPPP', compact('users'));
    }

    public function create()
    {
        $this->resetForm();
        $this->isOpen = true;
    }

    public function edit(User $user)
    {
        $this->resetForm();
        $this->isOpen = true;
        $this->itemId = $user->id;
        $this->user = $user;
        $this->form->fill($user);
    }

    public function store()
    {
        $this->validate();
        $userData = $this->form->toArray();
        if (!isset($this->user->id)) {
            $userData['password'] = bcrypt($this->form->password);
            User::create($userData);
            toast()->success('Usuario creado correctamente', 'Mensaje de éxito')->push();
        } else {
            if (!empty($this->form->password)) {
                $userData['password'] = bcrypt($this->form->password);
            } else {
                unset($userData['password']);
            }
            $this->user->update($userData);
            toast()->success('Usuario actualizado correctamente', 'Mensaje de éxito')->push();
        }
        $this->closeModals();
    }

    public function deleteItem($id)
    {
        $this->itemId = $id;
        $this->isOpenDelete = true;
    }

    public function delete()
    {
        $user = User::find($this->itemId)->delete();
        toast()->success('Usuario eliminado correctamente', 'Mensaje de éxito')->push();
        $this->reset('isOpenDelete', 'itemId');
    }

    public function mostrarSupervisores(User $user, $company)
    {
        $this->isOpenAssign = true;
        $this->user = $user;
        $this->companyUser = DB::table('company_users')
            ->where('user_id', $user->id)
            ->where('company_id', $company)
            ->first();
        $this->supervisor = $this->companyUser->supervisor_id ? [$this->companyUser->supervisor_id] : [];
    }

    public function showUserDetail(User $user)
    {
        $this->showUser = true;
        $this->edit($user);
    }

    public function asignarSupervisor(User $user)
    {
        $isNewAssignment = count((array)$this->companyUser->supervisor_id);
        if ($isNewAssignment === 0 && $this->supervisor === []) {
            toast()->danger('No se puede asignar supervisor vacío', 'Mensaje de error')->push();
        } else {

            DB::table('company_users')
                ->where('user_id', $this->user->id)
                ->where('company_id', $this->companyUser->company_id)
                ->update(['supervisor_id' => $this->supervisor[0]]);

            $evaluaciones = ['Primera', 'Segunda', 'Tercera'];

            foreach ($evaluaciones as $key => $value) {
                Evaluation::updateOrCreate([
                    'nombre' => $value . ' evaluación',
                    'estudiante_id' => $this->companyUser->id
                ]);
            }


            if ($isNewAssignment && $this->supervisor) {
                toast()->success('Se asignaron correctamente los roles', 'Mensaje de éxito')->push();
            } elseif (!$isNewAssignment) {
                toast()->success('Se actualizaron correctamente los roles', 'Mensaje de éxito')->push();
            }
        }
        $this->reset(['isOpenAssign']);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function closeModals()
    {
        $this->isOpen = false;
        $this->isOpenAssign = false;
        $this->showUser = false;
        $this->isOpenDelete = false;
    }

    public function select($type, $value)
    {
        if ($type === 'userState') {
            $this->userState = $value;
        } elseif ($type === 'userRol') {
            $this->userRol = $value;
        }
    }

    private function resetForm()
    {
        $this->form->reset();
        $this->reset(['user', 'itemId']);
        $this->resetValidation();
    }
}
