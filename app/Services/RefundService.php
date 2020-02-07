<?php

namespace App\Services;

use App\Http\Requests\RefundStore;
use App\Http\Requests\RefundUpdate;
use App\Models\Refund;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class RefundService
{
    private $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

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

    public function create(array $data)
    {
        $employeeIdentification = $data['identification'];
        $employee = $this->employeeService->getByIdentification($employeeIdentification);
        if (empty($employee)) {
            $employee = $this->employeeService->create($data);
        }

        $refundsToInsert = $this->prepareRefundsWithEmployee($data['refunds'], $employee);

        $insertedRefunds = $refundsToInsert->map(function ($refund) {
            $refundCreated = Refund::create($refund);
            return $refundCreated;
        });

        dd($insertedRefunds);

        //if (!$refund) {
        //    throw new Exception('Ocorreu um erro ao criar o recurso');
        // }
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

    private function prepareRefundsWithEmployee(array $refunds, $employee)
    {
        $refundsCollection = collect($refunds);
        $refundsCollection->transform(function ($refund) use ($employee) {
            $refund['date'] = Carbon::parse($refund['date']);
            $refund['employee_id'] = $employee->id;
            return $refund;
        });
        return $refundsCollection;
    }
}
