@extends('layouts.app1')

@section('content')
<div class="container">
    <h2>ค้นหาข้อมูลร้านค้า</h2>

    <form action="{{ route('shop.search') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>เลือกอำเภอ:</label>
            <select id="district" name="district" class="form-control">
                <option value="">-- เลือกอำเภอ --</option>
                @foreach ($subdistricts as $district => $subdis)
                    <option value="{{ $district }}">{{ $district }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>เลือกตำบล:</label>
            <select id="subdistrict" name="subdistrict" class="form-control" disabled>
                <option value="">-- เลือกตำบล --</option>
            </select>
        </div>

        <button id="showData" class="btn btn-primary mt-2" type="submit" disabled>ค้นหาร้านค้า</button>
        @if(isset($shops) && $shops->count() > 0)
    @php
        $waypoints = [];
        foreach ($shops as $shop) {
            if ($shop->latitude && $shop->longitude) {
                $waypoints[] = $shop->latitude . ',' . $shop->longitude;
            }
        }

        // เริ่มต้นที่จุดแรก → ปลายทางจุดสุดท้าย
        $origin = $waypoints[0] ?? '';
        $destination = $waypoints[count($waypoints) - 1] ?? '';
        $middlePoints = array_slice($waypoints, 1, count($waypoints) - 2);
        $waypointParam = implode('|', $middlePoints);

        $multiRouteUrl = 'https://www.google.com/maps/dir/?api=1'
                        . '&origin=' . urlencode($origin)
                        . '&destination=' . urlencode($destination)
                        . '&waypoints=' . urlencode($waypointParam);
    @endphp

    @if(count($waypoints) >= 2)
        <a href="{{ $multiRouteUrl }}" target="_blank" class="btn btn-danger mt-3 mb-3">
            🗺️ นำทางไปร้านค้าทั้งหมด ({{ count($waypoints) }} จุด)
        </a>
    @else
        <div class="alert alert-warning mt-3">ต้องมีร้านอย่างน้อย 2 ร้านที่มีพิกัดเพื่อวางแผนเส้นทาง</div>
    @endif
@endif

    </form>
    <div id="map" style="height: 500px;"></div> <!-- แผนที่ที่จะแสดง -->

    <div id="shopList" class="mt-4">
        @if(isset($shops) && $shops->count() > 0)
            <ul>
                @foreach($shops as $shop)
                    <li>{{ $shop->name }}</li>
                    <li>ละติจูด: {{ $shop->latitude }}</li>
                    <li>ลองจิจูด: {{ $shop->longitude }}</li>
                @endforeach
            </ul>
        @else
            <p>ไม่มีข้อมูลร้านค้าครับ</p>
        @endif
    </div>

    <div id="map" style="height: 500px;"></div> <!-- แผนที่ที่จะแสดง -->

</div>

<script>
    var subdistricts = @json($subdistricts);
    var shops = @json($shops ?? []);

    document.getElementById('district').addEventListener('change', function() {
        var district = this.value;
        var subdistrictSelect = document.getElementById('subdistrict');

        subdistrictSelect.innerHTML = '<option value="">-- เลือกตำบล --</option>';
        subdistrictSelect.disabled = !district;

        if (district) {
            subdistricts[district].forEach(function(sub) {
                var option = document.createElement('option');
                option.value = sub;
                option.textContent = sub;
                subdistrictSelect.appendChild(option);
            });
        }
    });

    document.getElementById('subdistrict').addEventListener('change', function() {
        document.getElementById('showData').disabled = this.value === "";
    });

    function initializeMap(lat, lon) {
        var map = L.map('map').setView([lat, lon], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([lat, lon]).addTo(map)
            .bindPopup('ตำแหน่งของคุณ')
            .openPopup();

        shops.forEach(function(shop) {
            if (shop.latitude && shop.longitude) {
                L.marker([shop.latitude, shop.longitude]).addTo(map)
                    .bindPopup(shop.name);
            }
        });
    }

    window.onload = function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    initializeMap(position.coords.latitude, position.coords.longitude);
                },
                function(error) {
                    console.log("ไม่สามารถดึงตำแหน่งจาก GPS ได้, กำลังใช้ IP Location");
                    fetch("https://ip-api.com/json")
                        .then(response => response.json())
                        .then(data => {
                            initializeMap(data.lat, data.lon);
                        })
                        .catch(err => {
                            console.log("ไม่สามารถดึงตำแหน่งจาก IP ได้", err);
                            return { lat: shops[0].latitude, lon: shops[0].longitude };

                        });
                }
            );
        } else {
            console.log("เบราว์เซอร์ไม่รองรับการดึงตำแหน่ง");
            return { lat: shops[0].latitude, lon: shops[0].longitude };

        }
    };
</script>

@endsection
