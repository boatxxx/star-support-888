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
    public function showShops(Request $request)
{         $user = Auth::user();
    $subdistricts = [
        "เมืองระยอง" => ["ท่าประดู่", "เชิงเนิน", "ตะพง", "ปากน้ำ", "เพ", "แกลง", "บ้านแลง", "นาตาขวัญ", "เนินพระ", "กะเฉด", "ทับมา", "น้ำคอก", "ห้วยโป่ง", "มาบตาพุด", "สำนักทอง"],
        "บ้านฉาง" => ["สำนักท้อน", "พลา", "บ้านฉาง"],
        "แกลง" => ["ทางเกวียน", "วังหว้า", "ชากโดน", "เนินฆ้อ", "กร่ำ", "ชากพง", "กระแสบน", "บ้านนา", "ทุ่งควายกิน", "กองดิน", "คลองปูน", "พังราด", "ปากน้ำกระแส", "ห้วยยาง", "สองสลึง"],
        "วังจันทร์" => ["วังจันทร์", "ชุมแสง", "ป่ายุบใน", "พลงตาเอี่ยม"],
        "บ้านค่าย" => ["บ้านค่าย", "หนองละลอก", "หนองตะพาน", "ตาขัน", "บางบุตร", "หนองบัว", "ชากบก"],
        "สัตหีบ" => ["สัตหีบ", "นาจอมเทียน", "พลูตาหลวง", "บางเสร่", "แสมสาร"],
        "ปลวกแดง" => ["ปลวกแดง", "ตาสิทธิ์", "ละหาร", "แม่น้ำคู้", "มาบยางพร", "หนองไร่"],
        "เขาชะเมา" => ["น้ำเป็น", "ห้วยทับมอญ", "ชำฆ้อ", "เขาน้อย"],
        "นิคมพัฒนา" => ["นิคมพัฒนา", "มาบข่า", "พนานิคม", "มะขามคู่"]
    ];
    $district = $request->input('district');
    $subdistrict = $request->input('subdistrict');

    // สร้างคำสั่งค้นหา
    $district = $request->input('district'); // อำเภอ เช่น "บ้านค่าย"
    $subdistrict = $request->input('subdistrict'); // ตำบล เช่น "หนองละลอก"

    // ตรวจสอบว่ามีค่าอำเภอและตำบลหรือไม่
    if (!$district || !$subdistrict) {
        return response()->json(['error' => 'กรุณาเลือกอำเภอและตำบล'], 400);
    }

    // ใช้ LIKE เพื่อค้นหาอำเภอและตำบลที่ถูกต้อง
    $shops = Shop::where('district', 'LIKE', "{$district} - {$subdistrict}")
                 ->get();

    return view('shops.shop_map', compact('subdistricts', 'shops', 'user'));
}

    public function shopMap2()
    {
        $user = Auth::user();

        $subdistricts = [
            "เมืองระยอง" => ["ท่าประดู่", "เชิงเนิน", "ตะพง", "ปากน้ำ", "เพ", "แกลง", "บ้านแลง", "นาตาขวัญ", "เนินพระ", "กะเฉด", "ทับมา", "น้ำคอก", "ห้วยโป่ง", "มาบตาพุด", "สำนักทอง"],
            "บ้านฉาง" => ["สำนักท้อน", "พลา", "บ้านฉาง"],
            "แกลง" => ["ทางเกวียน", "วังหว้า", "ชากโดน", "เนินฆ้อ", "กร่ำ", "ชากพง", "กระแสบน", "บ้านนา", "ทุ่งควายกิน", "กองดิน", "คลองปูน", "พังราด", "ปากน้ำกระแส", "ห้วยยาง", "สองสลึง"],
            "วังจันทร์" => ["วังจันทร์", "ชุมแสง", "ป่ายุบใน", "พลงตาเอี่ยม"],
            "บ้านค่าย" => ["บ้านค่าย", "หนองละลอก", "หนองตะพาน", "ตาขัน", "บางบุตร", "หนองบัว", "ชากบก"],
            "สัตหีบ" => ["สัตหีบ", "นาจอมเทียน", "พลูตาหลวง", "บางเสร่", "แสมสาร"],
            "ปลวกแดง" => ["ปลวกแดง", "ตาสิทธิ์", "ละหาร", "แม่น้ำคู้", "มาบยางพร", "หนองไร่"],
            "เขาชะเมา" => ["น้ำเป็น", "ห้วยทับมอญ", "ชำฆ้อ", "เขาน้อย"],
            "นิคมพัฒนา" => ["นิคมพัฒนา", "มาบข่า", "พนานิคม", "มะขามคู่"]
        ];

        return view('shops.shop_map', compact('subdistricts', 'user'));
    }
    public function getShopsByDistrict(Request $request)
    {
        $district = $request->input('district');
        $subdistrict = $request->input('subdistrict');

        $shops = Shop::where('district', $district)
                     ->where('subdistrict', $subdistrict)
                     ->select('name', 'latitude', 'longitude')
                     ->get();

        return response()->json($shops);
    }

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

        // กรองตามอำเภอ
        if ($request->has('district') && !empty($request->district)) {
            $query->where('district', 'like', '%' . $request->district . '%');
        }

        if ($request->has('subdistrict') && !empty($request->subdistrict)) {
            $query->where('district', 'like', '%' . $request->subdistrict . '%');
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

        // ดึงรายชื่ออำเภอทั้งหมด
        $districts = ['เมืองระยอง', 'บ้านฉาง', 'แกลง', 'วังจันทร์', 'บ้านค่าย', 'ปลวกแดง', 'เขาชะเมา', 'นิคมพัฒนา', 'สัตหีบ'];

        return view('shops.index', compact('shops', 'user', 'employees', 'districts'));
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

        $messages = [
            'phone.unique' => 'เบอร์โทรศัพท์นี้มีอยู่ในระบบแล้ว กรุณาใช้เบอร์โทรศัพท์อื่น',
            // กำหนดข้อความแจ้งเตือนอื่น ๆ ที่ต้องการ
        ];

        $validatedData = $request->validate([
            'name' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:shops,phone',
            'district' => 'required|string|max:255',
            'subdistrict' => 'required|string|max:255',
            'Link_google' => 'nullable|string',
            'sta' => 'required|boolean',
            'Latitude' => 'nullable|string|max:255',
            'Longitude' => 'nullable|string|max:255',
        ], $messages);

        $shop = new Shop();
        $shop->name = $request->name;
        $shop->address = $request->address;
        $shop->phone = $request->phone;
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
            session()->flash('success', 'ร้านค้าถูกบันทึกเรียบร้อยแล้ว ');
        } else {
            session()->flash('error', 'ร้านค้าถูกบันทึกเรียบร้อยแล้ว');
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


    $messages = [
        'phone.unique' => 'เบอร์โทรศัพท์นี้มีอยู่ในระบบแล้ว กรุณาใช้เบอร์โทรศัพท์อื่น',
        // กำหนดข้อความแจ้งเตือนอื่น ๆ ที่ต้องการ
    ];

    $validatedData = $request->validate([
        'name' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'phone' => 'required|string|max:20|unique:shops,phone',
        'district' => 'required|string|max:255',
        'subdistrict' => 'required|string|max:255',
        'Link_google' => 'nullable|string',
        'sta' => 'required|boolean',
        'Latitude' => 'nullable|string|max:255',
        'Longitude' => 'nullable|string|max:255',
    ], $messages);

    $shop->name = $request->input('name');
    $shop->address = $request->input('address');
    $shop->phone = $request->input('phone');

    $shop->district = "{$request->district} - {$request->subdistrict}"; // รวมอำเภอและตำบลลงในฟิลด์เดียว
    $shop->link_google = $request->input('link_google'); // Make sure this matches the column name
    $shop->sta = $request->input('sta');
    $shop->latitude = $request->input('latitude'); // Ensure this matches
    $shop->longitude = $request->input('longitude'); // Ensure this matches
    $shop->save();
    $user = Auth::User();
    $userName = $user->name;
  
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
