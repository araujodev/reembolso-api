<?php

namespace App\Services;

use App\Http\Requests\EmployeeStore;
use App\Http\Requests\EmployeeUpdate;
use App\Models\Employee;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class EmployeeService
{

    public function list(Request $request)
    {
        $employeesCollection = Employee::paginate(Employee::DEFAULT_PER_PAGE);
        return $employeesCollection;
    }

    public function get($id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            throw new ModelNotFoundException('Funcionario nao encontrado');
        }
        return $employee;
    }

    public function create(EmployeeStore $request)
    {
        $employee = Employee::create($request->all());
        if (!$employee) {
            throw new Exception('Ocorreu um erro ao criar o recurso');
        }
    }

    public function update(EmployeeUpdate $request, $id)
    {
        $employee = $this->get($id);

        if (!empty($employee)) {
            $update = $employee->update($request->all());
            if (!$update) {
                throw new Exception('Ocorreu um erro ao atualizar o recurso');
            }
        }
        return $employee;
    }

    public function delete($id)
    {
        $employee = $this->get($id);

        if (!empty($employee)) {
            $deleted = $employee->delete();
            if (!$deleted) {
                throw new Exception('Ocorreu um erro ao remover o recurso');
            }
        }
        return "Funcionario removido com sucesso.";
    }
}
