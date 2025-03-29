@extends('layouts.app')

@section('content')
<head>
    <title>แสดงประวัติการทำงาน</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" crossorigin=""/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js" crossorigin=""></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #map {
            height: 500px;
            width: 100%;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center mb-4">ประวัติการทำงานของพนักงาน</h2>

        <!-- ฟอร์มเลือกพนักงานและวันที่ -->
        <form id="salesRepForm">
            <div class="form-group">
                <label for="sales_rep_select">เลือกพนักงาน</label>
                <select id="sales_rep_select" name="sales_rep_id" class="form-control mb-3">
                    <option value="">เลือกพนักงาน</option>
                    @foreach ($salesReps as $rep)
                        <option value="{{ $rep->user_id }}" {{ $selectedSalesRepId == $rep->user_id ? 'selected' : '' }}>
                            {{ $rep->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="date">เลือกวันที่</label>
                <input type="date" id="date" name="date" class="form-control mb-3" value="{{ $selectedDate }}">
            </div>
            <button type="button" class="btn btn-secondary" id="resetButton">รีเซ็ต</button>
            <button type="submit" class="btn btn-primary">ดูประวัติ</button>
        </form>

        <!-- ตารางแสดงประวัติการทำงาน -->
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>พนักงาน ID</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>เวลา</th>
                </tr>
            </thead>
            <tbody id="work-history-body">
                @if ($workHistories->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center">ไม่พบข้อมูลสำหรับพนักงานที่เลือก</td>
                    </tr>
                @else
                    @foreach ($workHistories as $history)
                        <tr>
                            <td>{{ $history->sales_rep_id }}</td>
                            <td>{{ $history->latitude_date }}</td>
                            <td>{{ $history->longitude_date }}</td>
                            <td>{{ $history->created_at }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <!-- แผนที่แสดงตำแหน่ง -->
        <div id="map"></div>
    </div>

    <script>
        var map = L.map('map').setView([13.7563, 100.5018], 10); // ตั้งค่าตำแหน่งเริ่มต้นที่กรุงเทพฯ
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        var markers = []; // เก็บ Marker เพื่อลบทีหลัง

        // ฟังก์ชันเพิ่ม markers ลงในแผนที่
        function addMarkers(workHistories) {
            clearMarkers(); // เคลียร์ Marker เก่า
            workHistories.forEach(function(history) {
                if (history.latitude_date && history.longitude_date) {
                    var marker = L.marker([history.latitude_date, history.longitude_date])
                        .addTo(map)
                        .bindPopup('พนักงาน ID: ' + history.sales_rep_id + '<br>เวลา: ' + history.created_at);
                    markers.push(marker);
                }
            });
        }

        // ฟังก์ชันลบ markers เก่าออกจากแผนที่
        function clearMarkers() {
            markers.forEach(function(marker) {
                map.removeLayer(marker);
            });
            markers = [];
        }

        // ฟังก์ชันเมื่อกรอกฟอร์มและคลิกดูประวัติ
        $('#salesRepForm').submit(function(e) {
            e.preventDefault();
            var salesRepId = $('#sales_rep_select').val();
            var date = $('#date').val();

            $.ajax({
                url: '{{ route('trips.showMap') }}',
                method: 'GET',
                data: { sales_rep_id: salesRepId, date: date },
                success: function(response) {
                    $('#work-history-body').empty();
                    if (response.workHistories.length > 0) {
                        response.workHistories.forEach(function(history) {
                            $('#work-history-body').append('<tr>' +
                                '<td>' + history.sales_rep_id + '</td>' +
                                '<td>' + history.latitude_date + '</td>' +
                                '<td>' + history.longitude_date + '</td>' +
                                '<td>' + history.created_at + '</td>' +
                                '</tr>');
                        });
                        // เพิ่ม Marker เฉพาะข้อมูลที่กรองแล้ว
                        addMarkers(response.workHistories);
                        var lastHistory = response.workHistories[response.workHistories.length - 1];
                        if (lastHistory.latitude_date && lastHistory.longitude_date) {
                            map.setView([lastHistory.latitude_date, lastHistory.longitude_date], 13);
                        }
                    } else {
                        $('#work-history-body').append('<tr><td colspan="4" class="text-center">ไม่พบข้อมูลสำหรับพนักงานที่เลือก</td></tr>');
                        map.setView([13.7563, 100.5018], 10);
                        clearMarkers();
                    }
                }
            });
        });

        // ฟังก์ชันเมื่อเริ่มโหลดแผนที่และมีข้อมูลประวัติการทำงาน
        @if (!$workHistories->isEmpty())
            addMarkers(@json($workHistories));
            var lastHistory = @json($workHistories->last());
            if (lastHistory.latitude_date && lastHistory.longitude_date) {
                map.setView([lastHistory.latitude_date, lastHistory.longitude_date], 13);
            }
        @endif

        // ฟังก์ชันรีเซ็ตฟอร์ม
        document.getElementById('resetButton').addEventListener('click', function() {
            location.reload();
        });
    </script>

</body>
@endsection
