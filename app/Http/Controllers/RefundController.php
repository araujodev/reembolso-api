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
    public function index(Request $request)
    {
        try {
            $list = $this->refundService->list($request);
            return new RefundCollection($list);
        } catch (Exception $ex) {
            return response()->json(['mensagem' => 'Ocorreu um erro ao recuperar a lista'], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RefundStore $request)
    {
        try {
            $employeeCreated = $this->refundService->create($request);
            return new RefundResource($employeeCreated);
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
    public function show($id)
    {
        try {
            $employee = $this->refundService->get($id);
            return new RefundResource($employee);
        } catch (ModelNotFoundException $ex) {
            return response()->json(['mensagem' => $ex->getMessage()], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RefundUpdate $request, $id)
    {
        try {
            $employeeUpdated = $this->refundService->update($request, $id);
            return new RefundResource($employeeUpdated);
        } catch (Exception $ex) {
            return response()->json(['mensagem' => $ex->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $deleted = $this->refundService->delete($id);
            return response()->json(['mensagem' => $deleted], 200);
        } catch (Exception $ex) {
            return response()->json(['mensagem' => $ex->getMessage()], 400);
        }
    }
}
