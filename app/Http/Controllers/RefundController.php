<?php

namespace App\Http\Controllers;

use App\Http\Requests\RefundStore;
use App\Http\Requests\RefundUpdate;
use App\Http\Resources\RefundCollection;
use App\Http\Resources\RefundResource;
use App\Services\RefundService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    /**
     * @var RefundService $refundService
     */
    private $refundService;

    /**
     * Construtor
     */
    public function __construct(RefundService $refundService)
    {
        $this->middleware('auth:api');
        $this->refundService = $refundService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all(Request $request)
    {
        try {
            $list = $this->refundService->list($request);
            return new RefundCollection($list);
        } catch (Exception $ex) {
            return response()->json(['mensagem' => 'Ocorreu um erro ao recuperar a lista'], 400);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $employee_id)
    {
        try {
            $list = $this->refundService->list($request, $employee_id);
            return new RefundCollection($list);
        } catch (Exception $ex) {
            return response()->json(['mensagem' => 'Ocorreu um erro ao recuperar a lista'], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RefundStore $request, $employee_id)
    {
        try {
            $refundList = $this->refundService->create($request->all(), $employee_id);
            return new RefundCollection($refundList);
        } catch (Exception $ex) {
            return response()->json(['mensagem' => $ex->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($employee_id, $refund_id)
    {
        try {
            $refund = $this->refundService->get($refund_id, $employee_id);
            return new RefundResource($refund);
        } catch (ModelNotFoundException $ex) {
            return response()->json(['mensagem' => $ex->getMessage()], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $refund_id
     * @param  int  $employee_id
     * @return \Illuminate\Http\Response
     */
    public function update(RefundUpdate $request, $employee_id, $refund_id)
    {
        try {
            $refundUpdated = $this->refundService->update($request->only('value'), $refund_id, $employee_id);
            return new RefundResource($refundUpdated);
        } catch (Exception $ex) {
            return response()->json(['mensagem' => $ex->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource, but put in softdelete.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($employee_id, $refund_id)
    {
        try {
            $deleted = $this->refundService->delete($refund_id, $employee_id);
            return response()->json(['mensagem' => $deleted], 200);
        } catch (Exception $ex) {
            return response()->json(['mensagem' => $ex->getMessage()], 400);
        }
    }

    /**
     * Approve an refund
     *
     * @param RefundUpdate $request
     * @param int $employee_id
     * @param int $refund_id
     * @return
     */
    public function approve(RefundUpdate $request, $employee_id, $refund_id)
    {
        try {
            $refundApproved = $this->refundService->approve($request->only('status'), $refund_id, $employee_id);
            return new RefundResource($refundApproved);
        } catch (Exception $ex) {
            return response()->json(['mensagem' => $ex->getMessage()], 400);
        }
    }

    /**
     * Make a report to employee
     */
    public function report(Request $request, $employee_id)
    {
        try {
            $report = $this->refundService->reportByEmployee($request->only(['month', 'year']), $employee_id);
            return response()->json(['report' => $report]);
        } catch (Exception $ex) {
            return response()->json(['mensagem' => $ex->getMessage()], 200);
        }
    }

    /**
     * Make a csv report to employee
     *
     * @param Request $request
     * @param int $employee_id
     * @return Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function report_csv(Request $request, $employee_id)
    {
        try {
            $report = $this->refundService->reportByEmployeeCSV($request->only(['month', 'year']), $employee_id);
            return $report;
        } catch (Exception $ex) {
            return response()->json(['mensagem' => $ex->getMessage()], 200);
        }
    }

    /**
     * Approve an refund
     *
     * @param RefundUpdate $request
     * @param int $employee_id
     * @param int $refund_id
     * @return
     */
    public function receipt(RefundUpdate $request, $employee_id, $refund_id)
    {
        try {
            $refundApproved = $this->refundService->receipt($request->only('receipt'), $refund_id, $employee_id);
            return new RefundResource($refundApproved);
        } catch (Exception $ex) {
            return response()->json(['mensagem' => $ex->getMessage()], 400);
        }
    }
}
