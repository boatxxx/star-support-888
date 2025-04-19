@extends($user->role_id == 1 ? 'layouts.app' : 'layouts.app1')

@section('content')
<div class="container">
    <h1>{{ isset($shopVisit) ? 'แก้ไข' : 'เพิ่ม' }} การเยี่ยมร้านค้า</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($shopVisit) ? route('shop_visits.update', $shopVisit->id) : route('shop_visits.store') }}" method="POST">
        @csrf
        @if (isset($shopVisit))
            @method('PUT')
        @endif
        <div class="form-group">
            <label for="district">อำเภอ</label>
            <select id="district" name="district" class="form-control" required>
                <option value="">-- กรุณาเลือกอำเภอ --</option>
                <option value="เมืองระยอง">เมืองระยอง</option>
                <option value="บ้านฉาง">บ้านฉาง</option>
                <option value="แกลง">แกลง</option>
                <option value="วังจันทร์">วังจันทร์</option>
                <option value="บ้านค่าย">บ้านค่าย</option>
                <option value="สัตหีบ">สัตหีบ</option>
                <option value="ปลวกแดง">ปลวกแดง</option>
                <option value="เขาชะเมา">เขาชะเมา</option>
                <option value="นิคมพัฒนา">นิคมพัฒนา</option>
            </select>
        </div>

        <div class="form-group">
            <label for="subdistrict">ตำบล</label>
            <select id="subdistrict" name="subdistrict" class="form-control" required>
                <option value="">-- กรุณาเลือกตำบล --</option>
            </select>
        </div>
        <button type="button" id="fetchShops" class="btn btn-primary">ดึงข้อมูลร้านค้า</button>

        <script>
            const subdistrictsByDistrict =  {
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


    document.getElementById('district').addEventListener('change', function () {
        const district = this.value;
        const subdistrictDropdown = document.getElementById('subdistrict');
        subdistrictDropdown.innerHTML = '<option value="">-- กรุณาเลือกตำบล --</option>';

        if (district && subdistrictsByDistrict[district]) {
            subdistrictsByDistrict[district].forEach(subdistrict => {
                const option = document.createElement('option');
                option.value = subdistrict;
                option.textContent = subdistrict;
                subdistrictDropdown.appendChild(option);
            });
        }
    });

    document.getElementById('fetchShops').addEventListener('click', function () {
        const district = document.getElementById('district').value;
        const subdistrict = document.getElementById('subdistrict').value;
        const shopsDropdown = document.getElementById('shop_id');

        shopsDropdown.innerHTML = '<option value="">-- กรุณาเลือกร้านค้า --</option>';

        if (district && subdistrict) {
            fetch(`/api/shops-by-district?district=${district}&subdistrict=${subdistrict}`)
            .then(response => response.json())
                .then(data => {
                    data.forEach(shop => {
                        const option = document.createElement('option');
                        option.value = shop.shop_id;
                        option.textContent = shop.name;
                        shopsDropdown.appendChild(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        } else {
            alert("กรุณาเลือกอำเภอและตำบลก่อน");
        }
    });
    
    
        </script>


        <div class="form-group">
            <label for="shop_id">ร้านค้า</label>
            <select class="form-control" id="shop_id" name="shop_id" required>
                <option value="">-- กรุณาเลือกร้านค้า --</option>
            </select>
        </div>
        <!-- เพิ่ม JavaScript สำหรับการอัปเดตร้านค้า -->


        <div class="form-group">
            <label for="visit_date">วันในสัปดาห์</label>
            <select class="form-control" id="visit_date" name="visit_date" required>
                <option value="" disabled selected>เลือกวันในสัปดาห์</option>
                <option value="Monday" {{ (isset($shopVisit) && $shopVisit->visit_date == 'Monday') ? 'selected' : '' }}>วันจันทร์</option>
                <option value="Tuesday" {{ (isset($shopVisit) && $shopVisit->visit_date == 'Tuesday') ? 'selected' : '' }}>วันอังคาร</option>
                <option value="Wednesday" {{ (isset($shopVisit) && $shopVisit->visit_date == 'Wednesday') ? 'selected' : '' }}>วันพุธ</option>
                <option value="Thursday" {{ (isset($shopVisit) && $shopVisit->visit_date == 'Thursday') ? 'selected' : '' }}>วันพฤหัสบดี</option>
                <option value="Friday" {{ (isset($shopVisit) && $shopVisit->visit_date == 'Friday') ? 'selected' : '' }}>วันศุกร์</option>
                <option value="Saturday" {{ (isset($shopVisit) && $shopVisit->visit_date == 'Saturday') ? 'selected' : '' }}>วันเสาร์</option>
                <option value="Sunday" {{ (isset($shopVisit) && $shopVisit->visit_date == 'Sunday') ? 'selected' : '' }}>วันอาทิตย์</option>            </select>
        </div>
        <div class="form-group">
            <label for="employee_id">พนักงาน</label>
            <select class="form-control" id="employee_id" name="employee_id" required>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->user_id }}" {{ (isset($shopVisit) && $shopVisit->employee_id == $employee->id) ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="notes">หมายเหตุ</label>
            <textarea class="form-control" id="notes" name="notes" rows="3">{{ isset($shopVisit) ? $shopVisit->notes : old('notes') }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">{{ isset($shopVisit) ? 'อัปเดต' : 'บันทึก' }}</button>
    </form>
</div>
@endsection
