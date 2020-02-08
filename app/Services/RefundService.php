<?php

namespace App\Services;

use App\Http\Requests\RefundUpdate;
use App\Models\Refund;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class RefundService
{
    /**
     * Employee private variable
     *
     * @var EmployeeService
     */
    private $employeeService;

    /**
     * @param EmployeeService $employeeService
     */
    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    /**
     * Recupera uma lista paginada dos reembolsos.
     *
     * @param Request $request
     * @return Collection
     */
    public function list(Request $request, $employee_id = null)
    {
        $refundsCollection = Refund::paginate(Refund::DEFAULT_PER_PAGE);

        if (!empty($employee_id)) {
            $refundsCollection = Refund::where('employee_id', $employee_id)->paginate(Refund::DEFAULT_PER_PAGE);
        }
        return $refundsCollection;
    }

    /**
     * Recupera um Reembolso
     *
     * @param $refund_id
     * @param $employee_id
     * @return void
     */
    public function get($refund_id, $employee_id = null)
    {
        $refund = Refund::where('id', $refund_id)
            ->where('employee_id', $employee_id)
            ->first();

        if (empty($employee_id)) {
            $refund = Refund::find($refund_id);
        }

        if (empty($refund)) {
            throw new ModelNotFoundException('Reembolso nao encontrado');
        }

        return $refund;
    }

    /**
     * Metodo responsavel por criar um ou mais refunds.
     *
     * @param array $data
     * @return Collection
     */
    public function create(array $data, $employee_id)
    {
        $employee = $this->employeeService->get($employee_id);
        $refundsCollection = collect($data['refunds']);
        $refundsToInsert = $this->prepareRefundsWithEmployee($refundsCollection, $employee);

        $insertedRefunds = $refundsToInsert->map(function ($refund) {
            $refundCreated = Refund::create($refund);
            return $refundCreated;
        });

        if ($refundsCollection->count() > 0 && $insertedRefunds->count() == 0) {
            throw new Exception('Ocorreu um erro ao criar o recurso');
        }

        return $insertedRefunds;
    }

    /**
     * Atualiza um Reembolso
     *
     * @param array $request
     * @param int $refund_id
     * @param int $employee_id
     * @return void
     */
    public function update(array $request, $refund_id, $employee_id)
    {
        $refund = $this->get($refund_id, $employee_id);

        if (!empty($refund)) {
            $update = $refund->update($request);
            if (!$update) {
                throw new Exception('Ocorreu um erro ao atualizar o recurso');
            }
        }
        return $refund;
    }

    /**
     * Remove um reembolso
     *
     * @param int $refund_id
     * @param int $employee_id
     * @return string
     */
    public function delete($refund_id, $employee_id)
    {
        $employee = $this->get($refund_id, $employee_id);

        if (!empty($employee)) {
            $deleted = $employee->delete();
            if (!$deleted) {
                throw new Exception('Ocorreu um erro ao remover o recurso');
            }
        }
        return "Reembolso removido com sucesso.";
    }

    /**
     * Metodo Responsavel por receber uma collection de Refunds,
     * modificar ela colocando o ID do Employee bem como a formatacao
     * do campo Date para ser inserido no banco de dados.
     *
     * @param array $refundsCollection
     * @param Employee $employee
     * @return Collection
     */
    private function prepareRefundsWithEmployee($refundsCollection, $employee)
    {
        $refundsCollection->transform(function ($refund) use ($employee) {
            $refund['date'] = Carbon::parse($refund['date']);
            $refund['employee_id'] = $employee->id;
            return $refund;
        });
        return $refundsCollection;
    }
}
