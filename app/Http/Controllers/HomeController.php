<?php

namespace App\Http\Controllers;

use App\Models\Refund;
use App\Services\RefundService;
use Illuminate\Http\Request;

class HomeController extends Controller
{

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
        return view('home', compact('refunds'));
    }

    public function change_status(Request $request)
    {
        dd($request->all());
    }
}
