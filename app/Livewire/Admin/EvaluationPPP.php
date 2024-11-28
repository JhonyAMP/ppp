<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\UserForm;
use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class EvaluationPPP extends Component
{
    use WithPagination;
    use WireToast;

    public $search;
    public $isOpenEvaluar = false,
        $isOpenAssign = false,
        $showUser = false,
        $isOpenDelete = false,
        $isOpenConfig = false;
    public $itemId, $userState, $userRol;
    public UserForm $form;
    public ?User $user;
    public $dates;
    public $companyUser;
    public $supervisores,
        $notas = [],
        $fechas = [];

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
            ->select('users.*', 'companies.razon_social as razon_social', 'companies.id as company_id', 'company_users.supervisor_id', 'company_users.id as estudiante_id') // Selecciona los campos necesarios
            ->where(function ($query) {
                $query
                    ->where('users.dni', 'like', '%' . $this->search . '%')
                    ->orWhere('users.name', 'like', '%' . $this->search . '%')
                    ->orWhere('users.surnames', 'like', '%' . $this->search . '%')
                    ->orWhere('users.email', 'like', '%' . $this->search . '%');
            })
            ->where('company_users.supervisor_id', Auth::user()->id)
            ->latest('users.id')
            ->paginate(10);


        return view('livewire.admin.supervisorPPP', compact('users'));
    }

    public function evaluar($id)
    {
        $this->dates = Evaluation::where('estudiante_id', $id)->get();
        $this->fechas = $this->dates ? $this->dates->pluck('fecha', 'id')->toArray() : [];
        $this->notas = $this->dates ? $this->dates->pluck('nota', 'id')->toArray() : [];

        if (count(array_filter($this->fechas)) === 3) {
            $this->isOpenEvaluar = true;
        } else {
            toast()->danger('Configure las fechas de evaluacion', 'Mensaje de error')->push();
        }

    }

    public function actualizarNotas()
    {

        foreach ($this->notas as $index => $nota) {
            // Validar que no esté vacío
            if ($nota) {
                // Buscar la evaluación correspondiente
                $evaluation = Evaluation::where('id', $index)
                    ->first();

                if ($evaluation) {
                    // Actualizar la fecha si la evaluación existe
                    $evaluation->update(['nota' => $nota]);
                }
            }
        }

        toast()->success('Notas configuradas correctamente', 'Mensaje de éxito')->push();

        $this->notas = [];
        $this->reset('dates');
        $this->closeModals();
    }

    public function actualizarFechas()
    {

        foreach ($this->fechas as $index => $fecha) {
            // Validar que no esté vacío
            if ($fecha) {
                // Buscar la evaluación correspondiente
                $evaluation = Evaluation::where('id', $index)
                    ->first();

                if ($evaluation) {
                    // Actualizar la fecha si la evaluación existe
                    $evaluation->update(['fecha' => $fecha]);
                }
            }
        }

        toast()->success('fechas configuradas correctamente', 'Mensaje de éxito')->push();

        $this->fechas = [];
        $this->reset('dates');
        $this->closeModals();
    }

    public function configDates($id)
    {
        $this->isOpenConfig = true;
        $this->dates = Evaluation::where('estudiante_id', $id)->get();
        $this->fechas = $this->dates ? $this->dates->pluck('fecha', 'id')->toArray() : [];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function closeModals()
    {
        $this->isOpenEvaluar = false;
        $this->isOpenAssign = false;
        $this->showUser = false;
        $this->isOpenDelete = false;
        $this->isOpenConfig = false;
    }

    private function resetForm()
    {
        $this->form->reset();
        $this->reset(['user', 'itemId']);
        $this->resetValidation();
    }
}
