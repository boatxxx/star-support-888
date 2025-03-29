@extends('layouts.app')

@section('content')
<div class="container">
    <h2>การเดินทางของพนักงาน</h2>

    <div id="map" style="height: 500px;"></div> <!-- แสดงแผนที่ที่เดียว -->


<!-- เพิ่ม script สำหรับการใช้งาน Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    // แผนที่เริ่มต้นที่กรุงเทพฯ
    var map = L.map('map').setView([13.7563, 100.5018], 10);

    // กำหนด Tile Layer ของแผนที่ (OpenStreetMap)
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

    // ดึงข้อมูล trips ทั้งหมดในรูปแบบ JSON
    var workHistories = @json($trips);

    // เพิ่ม Marker ทุกจุดในแผนที่
    addMarkers(workHistories);

    // ตั้งค่าการแสดงแผนที่ไปยังตำแหน่งสุดท้าย
    if (workHistories.length > 0) {
        var lastTrip = workHistories[workHistories.length - 1];
        if (lastTrip.latitude_date && lastTrip.longitude_date) {
            map.setView([lastTrip.latitude_date, lastTrip.longitude_date], 13);
        }
    }
</script>
@endsection
