@extends('layouts.app')

@section('content')
<div class="container">
    <h1>เพิ่มโกดังสินค้า</h1>
    <form action="{{ route('warehouses.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">ชื่อโกดัง:</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="location">ที่ตั้ง:</label>
            <input type="text" id="location" name="location" class="form-control" required>
            <input type="hidden" id="latitude" name="latitude" required>
            <input type="hidden" id="longitude" name="longitude" required>
        </div>


        <button type="submit" class="btn btn-primary">บันทึก</button>
    </form>
</div>

<script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;
        document.getElementById('location').value = `${latitude},${longitude}`;
        document.getElementById('latitude').value = latitude;
        document.getElementById('longitude').value = longitude;
    }

    // Call the getLocation function when the page loads
    window.onload = getLocation;
</script>
@endsection
