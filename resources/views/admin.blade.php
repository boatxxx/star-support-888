@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4 text-center">Dashboard</h1>
    <h2 class="text-center">ยินดีต้อนรับ, {{ $user->name }}!</h2>
    <h3 class="text-center mb-5">บริษัท สตาร์ซัพพอร์ต 999 (ประเทศไทย) จำกัด</h3>

    <!-- KPI Cards -->
    <div class="row text-center mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">พนักงานทั้งหมด</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $employeesCount }} คน</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-success text-white">ลูกค้า</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $customersCount }} ร้าน</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-warning text-white">ยอดขาย</div>
                <div class="card-body">
                    <h5 class="card-title">${{ number_format($totalSales, 2) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-danger text-white">ออร์เดอร์ล่าสุด</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $recentSales->count() }} รายการ</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Sales Table -->
    <h2 class="my-4">รายการขายล่าสุด</h2>
    <table class="table table-hover table-striped">
        <thead class="thead-dark">
            <tr>
                <th>รายการขาย</th>
                <th>วันที่ & เวลา</th>
                <th>ยอด</th>
                <th>สถานะ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($recentSales as $sale)
                <tr>
                    <td>Payment from #{{ $sale->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('M d, Y, h:i A') }}</td>
                    <td>${{ number_format($sale->total_price, 2) }}</td>
                    <td>Completed</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales data for charts
    const salesData = @json($sales);
    const salesByShopData = @json($salesByShop);
    const salesByEmployeeData = @json($salesByEmployee);

    // Daily Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: salesData.map(sale => sale.sale_date),
            datasets: [{
                label: 'ยอดขาย',
                data: salesData.map(sale => sale.total_price),
                borderColor: 'rgba(75, 192, 192, 1)',
                fill: false
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // Sales by Employee Chart
    const employeeCtx = document.getElementById('salesEmployeeChart').getContext('2d');
    new Chart(employeeCtx, {
        type: 'doughnut',
        data: {
            labels: salesByEmployeeData.map(emp => emp.user.name),
            datasets: [{
                label: 'ยอดขายตามพนักงาน',
                data: salesByEmployeeData.map(emp => emp.total_sales),
                backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // Sales by Shop Chart
    const shopCtx = document.getElementById('salesShopChart').getContext('2d');
    new Chart(shopCtx, {
        type: 'bar',
        data: {
            labels: salesByShopData.map(shop => shop.shop.name),
            datasets: [{
                label: 'ยอดขายตามร้านค้า',
                data: salesByShopData.map(shop => shop.total_sales),
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: { y: { beginAtZero: true }},
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endsection
@endsection
