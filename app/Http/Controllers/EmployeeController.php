<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeStore;
use App\Http\Requests\EmployeeUpdate;
use App\Http\Resources\EmployeeCollection;
use App\Http\Resources\EmployeeResource;
use App\Services\EmployeeService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    private $employeeService;

    /**
     * Construtor
     */
    public function __construct(EmployeeService $employeeService)
    {
        $this->middleware('auth:api');
        $this->employeeService = $employeeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $list = $this->employeeService->list($request);
            return new EmployeeCollection($list);
        } catch (Exception $ex) {
            return response()->json(['mensagem' => 'Ocorreu um erro ao recuperar a lista'], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeStore $request)
    {
        try {
            $employeeCreated = $this->employeeService->create($request);
            return new EmployeeResource($employeeCreated);
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
            $employee = $this->employeeService->get($id);
            return new EmployeeResource($employee);
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
    public function update(EmployeeUpdate $request, $id)
    {
        try {
            $employeeUpdated = $this->employeeService->update($request, $id);
            return new EmployeeResource($employeeUpdated);
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
            $deleted = $this->employeeService->delete($id);
            return response()->json(['mensagem' => $deleted], 200);
        } catch (Exception $ex) {
            return response()->json(['mensagem' => $ex->getMessage()], 400);
        }
    }
}
