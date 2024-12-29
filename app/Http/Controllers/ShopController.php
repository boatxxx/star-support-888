<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Models\ShopVisit;
use App\Models\User;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Shop::query();

       // กรองตามคำค้นหา
if ($request->has('search') && !empty($request->search)) {
    $query->where('name', 'like', '%' . $request->search . '%')
          ->orWhere('address', 'like', '%' . $request->search . '%')
          ->orWhere('district', 'like', '%' . $request->search . '%');
}



        // กรองตามสถานะ
        if ($request->has('status') && $request->status !== null) {
            $query->where('sta', $request->status);
        }

        // กรองตามผู้รับผิดชอบ
        if ($request->has('employee') && !empty($request->employee)) {
            $query->whereHas('shopVisits', function ($q) use ($request) {
                $q->where('employee_id', $request->employee);
            });
        }

        // การแบ่งหน้า (Pagination)
        $shops = $query->paginate(10);

        // ดึงข้อมูลผู้รับผิดชอบจาก ShopVisit
        foreach ($shops as $shop) {
            $shopVisit = ShopVisit::where('shop_id', $shop->shop_id)->first();
            if ($shopVisit) {
                $employee = User::find($shopVisit->employee_id);
                $shop->employee_name = $employee ? $employee->name : 'ไม่มีข้อมูล';
            } else {
                $shop->employee_name = 'ไม่มีผู้รับผิดชอบ';
            }
        }

        // ดึงรายชื่อผู้รับผิดชอบทั้งหมด
        $employees = User::all();

        return view('shops.index', compact('shops', 'user', 'employees'));
    }



    public function create()
    {
        $user = Auth::User();
        $shops = Shop::all();

        return view('shops.create', compact('shops','user'));
    }

    public function store(Request $request)
    {
        $user = Auth::User();
        $userName = $user->name; // ดึงชื่อของผู้ใช้จากฟิลด์ 'name' ในตาราง 'users'

        $request->validate([
            'name' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'district' => 'required|string|max:255', // เพิ่ม validation สำหรับอำเภอและตำบล
            'subdistrict' => 'required|string|max:255', // เพิ่ม validation สำหรับตำบล
            'Link_google' => 'nullable|string',
            'sta' => 'required|boolean',
            'Latitude' => 'nullable|string|max:255',
            'Longitude' => 'nullable|string|max:255',
        ]);

        $shop = new Shop();
        $shop->name = $request->name;
        $shop->address = $request->address;
        $shop->district = "{$request->district} - {$request->subdistrict}"; // รวมอำเภอและตำบลลงในฟิลด์เดียว
        $shop->Link_google = $request->Link_google;
        $shop->sta = $request->sta;
        $shop->Latitude = $request->Latitude;
        $shop->Longitude = $request->Longitude;
        $shop->save();

        $message = "ร้านค้าใหม่ถูกเพิ่มเข้ามา!\n" .
                   "ชื่อร้าน: {$shop->name}\n" .
                   "ที่อยู่: {$shop->address}\n" .
                   "อำเภอ/ตำบล: {$shop->district}\n" . // เพิ่มข้อมูลอำเภอ/ตำบลในข้อความแจ้งเตือน
                   ($shop->Link_google ? "Google Map: {$shop->Link_google}" : "ไม่มีลิงก์แผนที่ \n ผู้เพิ่มข้อมูลร้านค้า :$userName");

        $isSent = Notification::sendLineNotification($message);

        if ($isSent) {
            session()->flash('success', 'ร้านค้าถูกบันทึกเรียบร้อยแล้ว และแจ้งเตือนไปยัง LINE สำเร็จ');
        } else {
            session()->flash('error', 'ร้านค้าถูกบันทึกเรียบร้อยแล้ว แต่การแจ้งเตือน LINE ล้มเหลว');
        }

        return redirect()->route('shops.create');
    }


    public function show(Shop $shop)
    {
        return view('shops.show', compact('shop'));
    }

    public function edit(Shop $shop)
    {        $user = Auth::User();

        return view('shops.edit', compact('shop','user'));
    }

   // ตรวจสอบว่าใช้ชื่อคอลัมน์ที่ถูกต้อง
public function update(Request $request, Shop $shop)
{

    $request->validate([
        'name' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'Link_google' => 'nullable|string',
        'district' => 'required|string|max:255', // เพิ่ม validation สำหรับอำเภอและตำบล
        'subdistrict' => 'required|string|max:255',
        'sta' => 'required|boolean',
        'Latitude' => 'nullable|string|max:255',
        'Longitude' => 'nullable|string|max:255',
    ]);

    $shop->name = $request->input('name');
    $shop->address = $request->input('address');
    $shop->district = "{$request->district} - {$request->subdistrict}"; // รวมอำเภอและตำบลลงในฟิลด์เดียว
    $shop->link_google = $request->input('link_google'); // Make sure this matches the column name
    $shop->sta = $request->input('sta');
    $shop->latitude = $request->input('latitude'); // Ensure this matches
    $shop->longitude = $request->input('longitude'); // Ensure this matches
    $shop->save();
    $user = Auth::User();
    $userName = $user->name;
    $message = "ร้านค้าใหม่ถูกเพิ่มเข้ามา!\n" .
    "ชื่อร้าน: {$shop->name}\n" .
    "ที่อยู่: {$shop->address}\n" .
    ($shop->Link_google ? "Google Map: {$shop->Link_google}" : "ไม่มีลิงก์แผนที่ \n ร้านค้าถูกอัปเดตเรียบร้อยแล้ว ผู้อัพเดท :$userName");

$isSent = Notification::sendLineNotification($message);
    return redirect()->route('shops.index')->with('success', 'ร้านค้าถูกอัปเดตเรียบร้อยแล้ว');
}


    public function destroy(Shop $shop)
    { $user = Auth::User();
        $userName = $user->name;
        $message = "ร้านค้านี้โดนลบออกจากระบบ!\n" .
        "ชื่อร้าน: {$shop->name}\n" .
        "ที่อยู่: {$shop->address}\n" .
        " ผู้ลบ :$userName";

    $isSent = Notification::sendLineNotification($message);
        $shop->delete();
        return redirect()->route('shops.index')->with('success', 'ร้านค้าถูกลบเรียบร้อยแล้ว');
    }
}
