<?php

namespace App\Services;

use App\Http\Requests\RefundStore;
use App\Http\Requests\RefundUpdate;
use App\Models\Refund;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class RefundService
{

    public function list(Request $request)
    {
        $employeesCollection = Refund::paginate(Refund::DEFAULT_PER_PAGE);
        return $employeesCollection;
    }

    public function get($id)
    {
        $employee = Refund::find($id);
        if (!$employee) {
            throw new ModelNotFoundException('Reembolso nao encontrado');
        }
        return $employee;
    }

    public function create(RefundStore $request)
    {
        $employee = Refund::create($request->all());
        if (!$employee) {
            throw new Exception('Ocorreu um erro ao criar o recurso');
        }
    }

    public function update(RefundUpdate $request, $id)
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
        return "Reembolso removido com sucesso.";
    }
}
