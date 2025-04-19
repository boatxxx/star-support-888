<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\PaymentCheck;
use App\Models\Sale;
use Illuminate\Http\Request;

use App\Models\User;

class PaymentCheckController extends Controller
{
    public function index()
    {    $user = Auth::user();

        // ดึงประวัติยอดรับเงินทั้งหมด
        $paymentChecks = PaymentCheck::with('sale')->get();
        return view('payment_checks.index', compact('paymentChecks','user'));
    }


}
