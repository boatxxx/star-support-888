@include('layouts.app1')


@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="container">
    <h1>บันทึกร้านค้า</h1>
    <form action="{{ route('shops.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">ชื่อร้านค้า</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="address">ที่อยู่ร้านค้า</label>
            <textarea name="address" class="form-control" rows="3" required></textarea>
        </div>

        <div class="form-group">
            <label for="district">อำเภอ</label>
            <select id="district" name="district" class="form-control" required>
                <option value="">-- กรุณาเลือกอำเภอ --</option>
                <option value="เมืองระยอง">เมืองระยอง</option>
                <option value="บ้านฉาง">บ้านฉาง</option>
                <option value="แกลง">แกลง</option>
                <option value="วังจันทร์">วังจันทร์</option>
                <option value="บ้านค่าย">บ้านค่าย</option>
                <option value="ปลวกแดง">ปลวกแดง</option>
                <option value="เขาชะเมา">เขาชะเมา</option>
                <option value="นิคมพัฒนา">นิคมพัฒนา</option>
            </select>
        </div>

        <!-- Dropdown สำหรับเลือกตำบล -->
        <div class="form-group">
            <label for="subdistrict">ตำบล</label>
            <select id="subdistrict" name="subdistrict" class="form-control" required>
                <option value="">-- กรุณาเลือกตำบล --</option>
            </select>
        </div>

        <div class="form-group">
            <label for="Link_google">ลิ้งค์ Google Maps</label>
            <input type="text" name="Link_google" class="form-control">
        </div>
        <div class="form-group">
            <label for="Latitude">ละติจูด</label>
            <input type="text"id="Latitude" name="Latitude" class="form-control">
        </div>
        <div class="form-group">
            <label for="Longitude">ลองจิจูด</label>
            <input type="text" id="Longitude"name="Longitude" class="form-control">
        </div>
        <button type="button" id="getLocation" class="btn btn-primary">ดึงตำแหน่งที่ยืนอยู่</button>

        <div class="form-group">
            <label for="sta">สถานะ</label>
            <select name="sta" class="form-control">
                <option value="1">ใช้ลิ้งค์ Google Maps</option>
                <option value="0">ใช้ตำแหน่งที่อยู่</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">บันทึกร้านค้า</button>
    </form>
</div>
<script>
    document.getElementById('getLocation').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('Latitude').value = position.coords.latitude;
                document.getElementById('Longitude').value = position.coords.longitude;
            }, function(error) {
                alert('Error occurred. Error code: ' + error.code);
            });
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    });
    const subdistricts = {
        "เมืองระยอง": ["ท่าประดู่", "เชิงเนิน", "ตะพง", "ปากน้ำ", "เพ", "แกลง", "บ้านแลง"],
        "บ้านฉาง": ["สำนักท้อน", "พลา", "บ้านฉาง"],
        "แกลง": ["ทางเกวียน", "วังหว้า", "ชากโดน", "เนินฆ้อ"],
        "วังจันทร์": ["วังจันทร์", "ชุมแสง", "ป่ายุบใน", "พลงตาเอี่ยม"],
        "บ้านค่าย": ["บ้านค่าย", "หนองละลอก", "หนองตะพาน"],
        "ปลวกแดง": ["ปลวกแดง", "ตาสิทธิ์", "ละหาร", "แม่น้ำคู้"],
        "เขาชะเมา": ["น้ำเป็น", "ห้วยทับมอญ", "ชำฆ้อ", "เขาน้อย"],
        "นิคมพัฒนา": ["นิคมพัฒนา", "มาบข่า", "พนานิคม", "มะขามคู่"]
    };

    // Event เมื่อเลือกอำเภอ
    document.getElementById("district").addEventListener("change", function() {
        const district = this.value;
        const subdistrictSelect = document.getElementById("subdistrict");

        // ลบตัวเลือกเดิม
        subdistrictSelect.innerHTML = '<option value="">-- กรุณาเลือกตำบล --</option>';

        // เพิ่มตำบลใหม่ตามอำเภอที่เลือก
        if (subdistricts[district]) {
            subdistricts[district].forEach(function(subdistrict) {
                const option = document.createElement("option");
                option.value = subdistrict;
                option.textContent = subdistrict;
                subdistrictSelect.appendChild(option);
            });
        }
    });
    </script>

