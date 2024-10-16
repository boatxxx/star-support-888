<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\ShopVisit;
use App\Models\CustomerVisit;


class shopVisit1 extends Controller
{
    public function createuser()
    {
        $user = Auth::user();
        $shops = Shop::all();
        $employees = User::all();

        return view('shop_visits.create', compact('shops', 'employees', 'user'));
    }
}
