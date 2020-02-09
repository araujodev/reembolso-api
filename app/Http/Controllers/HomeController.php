<?php

namespace App\Http\Controllers;

use App\Models\Refund;
use App\Services\RefundService;
use Exception;

class HomeController extends Controller
{

    /**
     *
     * @var RefundService
     */
    private $refundService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RefundService $refundService)
    {
        $this->middleware('auth');
        $this->refundService = $refundService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $refunds = Refund::all();
        $totalRefunds = $refunds->sum('value');
        return view('home', compact('refunds', 'totalRefunds'));
    }

    /**
     * Update status refund
     *
     * @param int $refund_id
     * @param int $status
     * @return Illuminate\Http\RedirectResponse
     */
    public function change_status($refund_id, $status)
    {
        try {
            $changeResult = $this->refundService->change($refund_id, $status);
            if ($changeResult) {
                return redirect()->back()->with('success', "Status do Reembolso alterado com sucesso.");
            }
            return redirect()->back()->with('errors', "Ocorreu um erro ao alterar o status do Reembolso.");
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage());
        }
    }

    /**
     * Destroy an Refund
     *
     * @param int $refund_id
     * @param int $status
     * @return Illuminate\Http\RedirectResponse
     */
    public function remove($refund_id)
    {
        try {
            $refund = $this->refundService->get($refund_id);
            $refundDeletedMessage = $this->refundService->delete($refund_id, $refund->employee_id);
            return redirect()->back()->with('success', $refundDeletedMessage);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage());
        }
    }
}
