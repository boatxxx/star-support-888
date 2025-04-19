@extends('layouts.app')

@section('content')
<div class="container">
    <h1>แก้ไขร้านค้า</h1>

    <form action="{{ route('shops.update', $shop->shop_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">ชื่อร้านค้า</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $shop->name) }}" required>
        </div>

        <div class="form-group">
            <label for="address">ที่อยู่</label>
            <textarea name="address" class="form-control" required>{{ old('address', $shop->address) }}</textarea>
        </div>
        <div class="form-group">
            <label for="phone">เบอร์โทร</label>
            <textarea name="phone" class="form-control" required>{{ old('address', $shop->phone) }}</textarea>
        </div>
        <div class="form-group">
            <label for="district">อำเภอ</label>
            <select id="district" name="district" class="form-control" required>
                <option value="">-- กรุณาเลือกอำเภอ --</option>
                <option value="เมืองระยอง" {{ old('district', $shop->district) == 'เมืองระยอง' ? 'selected' : '' }}>เมืองระยอง</option>
                <option value="บ้านฉาง" {{ old('district', $shop->district) == 'บ้านฉาง' ? 'selected' : '' }}>บ้านฉาง</option>
                <option value="แกลง" {{ old('district', $shop->district) == 'แกลง' ? 'selected' : '' }}>แกลง</option>
                <option value="วังจันทร์" {{ old('district', $shop->district) == 'วังจันทร์' ? 'selected' : '' }}>วังจันทร์</option>
                <option value="บ้านค่าย" {{ old('district', $shop->district) == 'บ้านค่าย' ? 'selected' : '' }}>บ้านค่าย</option>
                <option value="ปลวกแดง" {{ old('district', $shop->district) == 'ปลวกแดง' ? 'selected' : '' }}>ปลวกแดง</option>
                <option value="เขาชะเมา" {{ old('district', $shop->district) == 'เขาชะเมา' ? 'selected' : '' }}>เขาชะเมา</option>
                <option value="นิคมพัฒนา" {{ old('district', $shop->district) == 'นิคมพัฒนา' ? 'selected' : '' }}>นิคมพัฒนา</option>

<option value="สัตหีบ" {{ old('district', $shop->district) == 'สัตหีบ' ? 'selected' : '' }}>สัตหีบ</option>
            </select>
        </div>

        <!-- Dropdown สำหรับเลือกตำบล -->
        <div class="form-group">
            <label for="subdistrict">ตำบล</label>
            <select id="subdistrict" name="subdistrict" class="form-control" required>
                <option value="">-- กรุณาเลือกตำบล --</option>
                <!-- ตัวเลือกตำบลจะถูกโหลดตามการเลือกอำเภอ -->
            </select>
        </div>

        <div class="form-group">
            <label for="link_google">ลิ้งแผนที่ Google</label>
            <input type="text" name="link_google" class="form-control" value="{{ old('Link_google', $shop->link_google) }}">
        </div>

        <div class="form-group">
            <label for="latitude">ละติจูด</label>
            <input type="text" name="latitude" class="form-control" value="{{ old('Latitude', $shop->latitude) }}">
        </div>

        <div class="form-group">
            <label for="longitude">ลองจิจูด</label>
            <input type="text" name="longitude" class="form-control" value="{{ old('Longitude', $shop->longitude) }}">
        </div>

        <div class="form-group">
            <label for="sta">สถานะ</label>
            <select name="sta" class="form-control">
                <option value="1" {{ old('sta', $shop->sta) == 1 ? 'selected' : '' }}>ใช้ลิ้งค์ Google Maps</option>
                <option value="0" {{ old('sta', $shop->sta) == 0 ? 'selected' : '' }}>ใช้ตำแหน่งที่อยู่</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
    </form>
</div>

<script>
// JavaScript สำหรับโหลดตำบลตามอำเภอที่เลือก
document.getElementById('district').addEventListener('change', function() {
    var district = this.value;
    var subdistrictSelect = document.getElementById('subdistrict');

    // เคลียร์ตำบลก่อน
    subdistrictSelect.innerHTML = '<option value="">-- กรุณาเลือกตำบล --</option>';

    // กำหนดข้อมูลตำบลตามอำเภอ
    var subdistricts = {
        "เมืองระยอง": [    "ท่าประดู่",
    "เชิงเนิน",
    "ตะพง",
    "ปากน้ำ",
    "เพ",
    "แกลง",
    "บ้านแลง",
    "นาตาขวัญ",
    "เนินพระ",
    "กะเฉด",
    "ทับมา",
    "น้ำคอก",
    "ห้วยโป่ง",
    "มาบตาพุด",
    "สำนักทอง"
],
        "บ้านฉาง": ["สำนักท้อน",
    "พลา",
    "บ้านฉาง",],
        "แกลง": ["ทางเกวียน",
    "วังหว้า",
    "ชากโดน",
    "เนินฆ้อ",
    "กร่ำ",
    "ชากพง",
    "กระแสบน",
    "บ้านนา",
    "ทุ่งควายกิน",
    "กองดิน",
    "คลองปูน",
    "พังราด",
    "ปากน้ำกระแส",
    "ห้วยยาง",
    "สองสลึง"],
        "วังจันทร์": ["วังจันทร์", "ชุมแสง", "ป่ายุบใน", "พลงตาเอี่ยม"],
        "บ้านค่าย": ["บ้านค่าย",
    "หนองละลอก",
    "หนองตะพาน",
    "ตาขัน",
    "บางบุตร",
    "หนองบัว",
    "ชากบก"
],"สัตหีบ": ["สัตหีบ", "นาจอมเทียน", "พลูตาหลวง", "บางเสร่", "แสมสาร"],
        "ปลวกแดง": ["ปลวกแดง", "ตาสิทธิ์", "ละหาร", "แม่น้ำคู้", "มาบยางพร", "หนองไร่"],
        "เขาชะเมา": ["น้ำเป็น", "ห้วยทับมอญ", "ชำฆ้อ", "เขาน้อย"],
        "นิคมพัฒนา": ["นิคมพัฒนา", "มาบข่า", "พนานิคม", "มะขามคู่"]
    };

    // เพิ่มตำบลลงใน dropdown
    if (subdistricts[district]) {
        subdistricts[district].forEach(function(subdistrict) {
            var option = document.createElement('option');
            option.value = subdistrict;
            option.textContent = subdistrict;
            subdistrictSelect.appendChild(option);
        });
    }
});

// เมื่อโหลดหน้าแล้ว ให้เลือกตำบลที่ถูกเลือกก่อนหน้านี้
document.addEventListener('DOMContentLoaded', function() {
    var district = '{{ old('district', $shop->district) }}';
    if (district) {
        document.getElementById('district').value = district;
        // เรียกใช้ event change เพื่อโหลดตำบล
        document.getElementById('district').dispatchEvent(new Event('change'));
    }

    var subdistrict = '{{ old('subdistrict', $shop->subdistrict) }}';
    if (subdistrict) {
        document.getElementById('subdistrict').value = subdistrict;
    }
});
</script>
@endsection
