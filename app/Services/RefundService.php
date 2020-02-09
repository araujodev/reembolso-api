<?php

namespace App\Services;

use App\Exports\RefundsReportExport;
use App\Models\Refund;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;

class RefundService
{
    /**
     * Employee private variable
     *
     * @var EmployeeService $employeeService
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
     * @param int $employee_id
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
     * @param int $refund_id
     * @param int $employee_id
     * @return Refund
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
     * @param int $employee_id
     * @return Collection
     */
    public function create(array $data, $employee_id)
    {
        $employee = $this->employeeService->get($employee_id);
        $refundsCollection = collect($data['refunds']);
        $refundsToInsert = $this->prepareRefundsWithEmployee($refundsCollection, $employee);

        $insertedRefunds = $refundsToInsert->map(function ($refund) {

            if (!empty($refund['receipt'])) {
                $fullPathReceipt = $this->uploadReceipt($refund['receipt']);
                $refund['receipt'] = $fullPathReceipt;
            }

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
     * @return Refund
     */
    public function update(array $request, $refund_id, $employee_id)
    {
        $refund = $this->get($refund_id, $employee_id);

        if (!empty($refund)) {

            if (
                ($refund->status == Refund::STATUS_APPROVED) ||
                ($refund->status == Refund::STATUS_CANCELED)
            ) {
                throw new Exception('Este reembolso nao pode ser atualizado pois ja esta aprovado ou fechado.');
            }

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
     * Aprova um Reembolso
     *
     * @param array $status
     * @param int $refund_id
     * @param int $employee_id
     * @return Refund
     */
    public function approve(array $request, $refund_id, $employee_id)
    {
        $refund = $this->get($refund_id, $employee_id);

        if ($request['status'] != Refund::STATUS_APPROVED) {
            throw new Exception('Alteracao de status do reembolso nao permitida.');
        }

        if (!empty($refund)) {
            $update = $refund->update($request);
            if (!$update) {
                throw new Exception('Ocorreu um erro ao aprovar o reembolso');
            }
        }
        return $refund;
    }

    /**
     * Gera um relatorio dos reembolsos de um Funcionario
     *
     * @param array $request
     * @param int $employee_id
     * @return mixed
     */
    public function reportByEmployee(array $request, $employee_id)
    {
        $month = $request['month'];
        $year = $request['year'];

        $dataset = Refund::where('employee_id', $employee_id)
            ->whereMonth('date', '=', $month)
            ->whereYear('date', '=', $year)
            ->get();

        if ($dataset->count() == 0) {
            throw new Exception('Nenhum resultado encontrado para o periodo selecionado');
        }

        $report = $this->makeReportByEmployee($dataset, $month, $year);
        return $report;
    }

    /**
     * Gera um relatorio em formato CSV dos reembolsos de um funcionario
     *
     * @param array $request
     * @param int $employee_id
     * @return Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function reportByEmployeeCSV(array $request, $employee_id)
    {
        $month = $request['month'];
        $year = $request['year'];

        return Excel::download(
            new RefundsReportExport($month, $year, $employee_id),
            'report.csv'
        );
    }

    /**
     * Atribui um comprovante a um reembolso
     *
     * @param array $request
     * @param int $refund_id
     * @param int $employee_id
     * @return mixed
     */
    public function receipt(array $request, $refund_id, $employee_id)
    {
        $refund = $this->get($refund_id, $employee_id);

        if (
            ($refund->status == Refund::STATUS_APPROVED) ||
            ($refund->status == Refund::STATUS_CANCELED)
        ) {
            throw new Exception('Alteracao de dados do reembolso nao permitida');
        }

        if (!empty($refund->receipt)) {
            throw new Exception('Ja existe um comprovante para este reembolso');
        }

        $fullPathReceipt = $this->uploadReceipt($request['receipt']);

        if (!empty($refund)) {
            $update = $refund->update(['receipt' => $fullPathReceipt]);
            if (!$update) {
                throw new Exception('Ocorreu um erro ao fazer upload do comprovante');
            }
        }
        return $refund;
    }

    /**
     * Altera o status de um reembolso (Painel web)
     *
     * @param int $refund_id
     * @param int $status
     * @return boolean
     */
    public function change($refund_id, $status)
    {
        $refund = $this->get($refund_id);

        $allowedStatus = [
            Refund::STATUS_APPROVED,
            Refund::STATUS_OPENED,
            Refund::STATUS_CANCELED
        ];

        if (!in_array($status, $allowedStatus)) {
            throw new Exception('Alteracao de status do reembolso nao permitida.');
        }

        if (!empty($refund)) {
            $update = $refund->update(['status' => $status]);
            if (!$update) {
                throw new Exception('Ocorreu um erro ao alterar status do reembolso');
            }
            return $update;
        }
        return false;
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
            $refund['status'] = Refund::STATUS_OPENED;
            return $refund;
        });
        return $refundsCollection;
    }

    /**
     * Monta o relatorio de Reeembolsos por Funcionario
     *
     * @param Collection $refunds
     * @param int $month
     * @param int $year
     * @return stdClass
     */
    private function makeReportByEmployee($refunds, $month, $year)
    {
        $refundsCount = $refunds->count();
        $sumRefunds = 0;
        $totalRefunds = $refunds->sum('value');

        $report = new stdClass();
        $report->month = $month;
        $report->year = $year;
        $report->totalRefunds = number_format($totalRefunds, 2, ',', '.');
        $report->refunds = $refundsCount;

        return $report;
    }

    /**
     * Realiza o upload PNG do Recibo do Reembolso (em Base64)
     *
     * @param string $receipt
     * @return string
     */
    private function uploadReceipt($receipt)
    {
        $image = $receipt;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = str_random(10) . '.' . 'png';
        \File::put(storage_path() . '/app/public/' . $imageName, base64_decode($image));
        $fullBase = url('/storage/' . $imageName);
        return $fullBase;
    }
}
