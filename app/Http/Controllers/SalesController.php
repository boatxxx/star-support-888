<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Models\Shop;
use App\Models\User;
use App\Models\WorkRecord;
use App\Models\PaymentCheck;



class SalesController extends Controller
{

    public function confirmPayment(Request $request, $sale_id)
{
    $sale = Sale::find($sale_id);

    if (!$sale) {
        return redirect()->back()->with('error', 'ไม่พบยอดขายที่ต้องการเช็ครับเงิน');
    }

    $paymentCheck = new PaymentCheck();
    $paymentCheck->sale_id = $sale->id;
    $paymentCheck->received_by = Auth::user()->user_id; // ดึง ID ของผู้ล็อกอิน
    $paymentCheck->received_amount = $sale->total_price;
    $paymentCheck->received_date = now();
    $paymentCheck->save();
    $sale->paymentCheck = 1; // หรือ true
    $sale->save(); // บันทึกการเปลี่ยนแปลง
    return redirect()->route('sales.summary')->with('success', 'บันทึกการเช็ครับเงินเรียบร้อยแล้ว');
}
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'ลบประวัติการขายเรียบร้อยแล้ว');
    }
    public function salesByShop(Request $request)
    { $user = Auth::user();
        $salesQuery = Sale::with(['shop', 'user']);

        // ตรวจสอบวันที่ค้นหา
        if ($request->has('start_date') && $request->has('end_date')) {
            $salesQuery->whereBetween('sale_date', [$request->start_date, $request->end_date]);
        }

        // ดึงข้อมูลยอดขาย
        $sales = $salesQuery->get();

        return view('sales.sales_by_shop', compact('sales','user'));
    }

    // ฟังก์ชันสำหรับแสดงยอดขายของพนักงาน
    public function salesByEmployee(Request $request)
    { $user = Auth::user();
        $salesQuery = Sale::with(['user']);

        // ตรวจสอบวันที่ค้นหา
        if ($request->has('start_date') && $request->has('end_date')) {
            $salesQuery->whereBetween('sale_date', [$request->start_date, $request->end_date]);
        }

        // ดึงข้อมูลยอดขาย
        $sales = $salesQuery->get();

        return view('sales.sales_by_employee', compact('sales','user'));
    }
    public function index(Request $request)
{
    $user = Auth::user();
    // รับค่าค้นหาจากฟอร์ม
    $search = $request->input('search'); // ค้นหาจากชื่อสินค้า หรือ ID สินค้า

    // เรียกข้อมูลจากตาราง Sale พร้อมความสัมพันธ์กับ product และ shop
    $query = Sale::with(['product', 'shop', 'user'])
                ->when($search, function($q) use ($search) {
                    // ค้นหาจากชื่อสินค้าหรือรหัสสินค้า
                    $q->whereHas('product', function($q) use ($search) {
                        $q->where('name', 'LIKE', "%$search%")
                          ->orWhere('product_id', 'LIKE', "%$search%");
                    });
                })
                ->orderBy('sale_date', 'desc') // เรียงตามวันที่ขาย
                ->get();

    return view('sales.index', compact('query','user')); // ส่งข้อมูลไปยังวิว
}
    public function store(Request $request)
    {
        $workRecordId = $request->input('work_record_id'); // รับ ID ของ WorkRecord
        $workRecord = WorkRecord::findOrFail($workRecordId);

        // ถ้าไม่มีค่าใน work_record_id ให้แสดงข้อผิดพลาด
        if (!$workRecordId) {
            return redirect()->back()->withErrors(['error' => 'ไม่พบ ID ของ WorkRecord']);
        }
        $request->validate([
            'items' => 'required|json',
            'shop_id' => 'required|exists:shops,shop_id', // ตรวจสอบว่ามี shop_id ที่ถูกต้อง
        ]);
        // รับข้อมูลสินค้าที่ส่งมา
        $items = json_decode($request->input('items'), true); // แปลง JSON เป็น Array

        // เริ่มต้นยอดรวม
        $total = 0;

        // บันทึกข้อมูลการขาย
        foreach ($items as $item) {
            // คำนวณราคาสินค้า
            $itemTotal = $item['quantity'] * $item['price'];
            $total += $itemTotal;

            // สร้างข้อมูลการขายในฐานข้อมูล
            Sale::create([
                'shop_id' => $request->input('shop_id'), // คุณสามารถเปลี่ยนเป็น ID ของร้านค้าที่ต้องการ
                'quantity' => $item['quantity'], // เพิ่มจำนวนที่ขาย
                'product_id' => $item['product_id'],
                'total_price' => $itemTotal,
                'sale_date' => now(),  // วันที่ขาย
                'user_id' => Auth::id(), // ID ของผู้ใช้งาน
                'promotion_id' => 12,
            ]);
        }
        $workRecord->status = 'completed';
        $workRecord->save();
        // ส่งข้อมูลกลับไปยังหน้าแรก
        return redirect('user');
    }
    public function exportPdf(Request $request)
{
    $sales = Sale::whereBetween('sale_date', [$request->start_date, $request->end_date])
                  ->where('shop_id', $request->shop_id)
                  ->where('user_id', $request->employee_id)
                  ->get();

    $commission = $request->commission;

    $pdf = PDF::loadView('sales.pdf_summary', compact('sales', 'commission'));
    return $pdf->download('sales_summary.pdf');
        return $pdf->download('sales_summary.pdf');
}
public function summary(Request $request)
{
    $user = Auth::user();

    // รับข้อมูลจากฟอร์ม
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $shopId = $request->input('shop_id');
    $employeeId = $request->input('employee_id');
    $commission = $request->input('commission', 0);

    // ดึงข้อมูลร้านค้าและพนักงาน
    $shops = Shop::all();
    $employees = User::all();

    // คิวรีข้อมูลจากฐานข้อมูลตามเงื่อนไข
    $sales = Sale::when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                        return $query->whereBetween('sale_date', [$startDate, $endDate]);
                    })
                    ->when($shopId, function ($query, $shopId) {
                        return $query->where('shop_id', $shopId);
                    })
                    ->when($employeeId, function ($query, $employeeId) {
                        return $query->where('user_id', $employeeId);
                    })
                    ->get();

    // ตรวจสอบว่ามีการเช็ครับยอดเงินหรือไม่ (เพิ่มเงื่อนไขตามที่ต้องการ)
    $hasCheckedPayments = $sales->isNotEmpty(); // สมมุติว่าเช็ครับยอดเงินถ้ามียอดขาย

    // ส่งข้อมูลไปยังวิว
    return view('sales.summary', compact('sales', 'commission', 'shops', 'employees', 'user', 'hasCheckedPayments'));
}

}
