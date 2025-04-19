<?php

namespace App\Http\Controllers;
use App\Models\InventoryLoad;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class InventoryLoadController extends Controller
{
    public function index()
    {    $user = Auth::user();

        // ดึงประวัติการโหลดสินค้ากลับคลังทั้งหมด
        $inventoryLoads = InventoryLoad::with(['product', 'user', 'workRecord'])->get();

        return view('inventory_loads.index', compact('inventoryLoads','user'));
    }
    public function destroy($id)
{
    $inventoryLoad = InventoryLoad::findOrFail($id);
    $inventoryLoad->delete();

    return redirect()->back()->with('success', 'ลบประวัติการโหลดสินค้ากลับคลังเรียบร้อยแล้ว');
}

}
