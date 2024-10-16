<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\ShopVisit;
use App\Models\CustomerVisit;


class ShopVisitController extends Controller
{

    public function shopVisits333(Request $request)
    {        $user = Auth::User();
        $query = ShopVisit::with(['shop', 'employee']);

        // ตรวจสอบว่ามีคำค้นหามาใน request หรือไม่
        if ($request->has('search')) {
            $query->whereHas('shop', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // ดึงข้อมูลทั้งหมดจากฐานข้อมูล
        $shopVisits = $query->get();

        return view('sales.shopVisits', compact('shopVisits','user'));
    }

    public function index()
    {        $user = Auth::User();
        $visits = ShopVisit::with('shop', 'employee')->get();
        return view('shop_visits.index', compact('visits','user'));
    }
    public function index1(Request $request)
    {
        $employees = User::where('status', 'active')->get(); // Assuming status 'active' indicates employees
        $shopVisits = collect();

        if ($request->has(['visit_day', 'employee_id'])) {
            $shopVisits = ShopVisit::where('visit_date', $request->visit_day)
                ->where('employee_id', $request->employee_id)
                ->get();
        }

        return view('shop_visits.createuser', compact('employees', 'shopVisits'));
    }
    // แสดงฟอร์มเพิ่มการเยี่ยมร้านค้า
    public function show($id)
{
    $shopVisit = ShopVisit::findOrFail($id);
    return view('shop_visits.show', compact('shopVisit'));
}

    public function create()
    {
        $user = Auth::user();
        $shops = Shop::all();
        $employees = User::all();

        return view('shop_visits.create', compact('shops', 'employees', 'user'));
    }
    public function createuser()
    {
        $user = Auth::user();
        $shops = Shop::all();
        $employees = User::all();

        return view('shop_visits.create', compact('shops', 'employees', 'user'));
    }
    // บันทึกการเยี่ยมร้านค้าใหม่
    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required',
            'visit_date' => 'required',
            'employee_id' => 'required',
            'notes' => 'nullable|string',
        ]);

        ShopVisit::create($request->all());

        return redirect()->route('shop_visits.index')->with('success', 'บันทึกการเยี่ยมร้านค้าเรียบร้อยแล้ว');
    }

    // แสดงฟอร์มแก้ไขการเยี่ยมร้านค้า
    public function edit(ShopVisit $shopVisit)
    {        $user = Auth::User();
        $shops = Shop::all();
        $employees = User::all();
        return view('shop_visits.edit', compact('shopVisit', 'shops', 'employees','user'));
    }

    // อัปเดตการเยี่ยมร้านค้า
    public function update(Request $request, ShopVisit $shopVisit)
    {
        $request->validate([
            'shop_id' => 'required|integer',
            'visit_date' => 'required',
            'employee_id' => 'required|integer',
            'notes' => 'nullable|string',
        ]);

        $shopVisit->update($request->all());

        return redirect()->route('shop_visits.index')->with('success', 'อัปเดตการเยี่ยมร้านค้าเรียบร้อยแล้ว');
    }

    // ลบการเยี่ยมร้านค้า
    public function destroy(ShopVisit $shopVisit)
    {
        $shopVisit->delete();
        return redirect()->route('shop_visits.index')->with('success', 'ลบการเยี่ยมร้านค้าเรียบร้อยแล้ว');
    }

    public function index2()
    {        $user = Auth::User();
        $today = \Carbon\Carbon::now()->format('l'); // รับวันในสัปดาห์ในรูปแบบภาษาอังกฤษ (Monday, etc.)
    $userId = auth()->user()->user_id; // รับรหัสผู้ใช้งานที่เข้าสู่ระบบ

    $shopVisits = ShopVisit::where('visit_date', $today)
        ->where('employee_id', $userId)
        ->with(['shop', 'employee'])
        ->get();

    return view('shop_visits.customer_visits', compact('shopVisits','user'));
}
public function create1(Request $request)
{        $user = Auth::User();
    // รับค่า shop_id จากหน้าที่แล้ว
    $shopId = $request->get('shop_id');

    // นำค่า shopId ไปใช้ใน view เพื่อกรอกในฟอร์ม
    return view('shop_visits.create1', compact('shopId','user'));
}

// Store the newly created customer visit in the database
public function store1(Request $request)
{        $user = Auth::User();
    // Validate the form data
    $validated = $request->validate([
        'customer_name' => 'required',
        'shop_id' => 'required|exists:shops,shop_id',
        'visit_date' => 'required|date',
        'employee_id' => 'required|exists:users,user_id',
        'notes' => 'nullable|string',
    ]);

    // Create a new CustomerVisit record with the validated data
    CustomerVisit::create($validated);

    // Redirect to the shop visits index page with a success message
    return redirect()->route('shop_visits.index')->with('success', 'บันทึกการเยี่ยมลูกค้าเรียบร้อยแล้ว');
}
}
