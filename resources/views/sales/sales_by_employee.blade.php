@extends('layouts.app')

@section('content')
<div class="container">
    <h1>ยอดขายตามพนักงาน</h1>
    <canvas id="salesChart" width="400" height="200"></canvas>

    <form method="GET" action="{{ route('sales.by_employee') }}">
        <div class="form-group">
            <label for="start_date">วันที่เริ่มต้น</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="end_date">วันที่สิ้นสุด</label>
            <input type="date" name="end_date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">ค้นหา</button>
    </form>

    <h2>สถิติ</h2>
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-icon">
                    <div class="icon-big text-center icon-info bubble-shadow-small">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                    <div class="numbers">
                        <p class="card-category">พนักงานขาย</p>
                        <h4 class="card-title">10</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h2>ยอดขายทั้งหมด</h2>
    <table class="table">
        <thead>
            <tr>
                <th>พนักงานขาย</th>
                <th>ราคารวม</th>
                <th>วันที่ขายล่าสุด</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
                <tr>
                    <td>{{ $sale->user->name ?? 'ไม่พบข้อมูล' }}</td>
                    <td>{{ number_format($sale->total_price, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- กราฟที่นี่ -->
    <canvas id="salesChart" width="400" height="200"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const salesData = @json($sales);

    // สร้างอาเรย์สำหรับ labels และ data โดยรวมยอดขายของพนักงานที่ซ้ำกัน
    const labels = [...new Set(salesData.map(sale => sale.user.name ?? 'ไม่พบข้อมูล'))];
    const data = labels.map(label => {
        return salesData
            .filter(sale => sale.user.name === label)
            .reduce((total, sale) => total + parseFloat(sale.total_price), 0);
    });

    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar', // เปลี่ยนประเภทกราฟได้ตามต้องการ
        data: {
            labels: labels, // ใช้ labels ที่ดึงจากข้อมูล
            datasets: [{
                label: 'ยอดขาย',
                data: data, // ใช้ data ที่ดึงจากข้อมูล
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    // เพิ่มสีสำหรับพนักงานเพิ่มเติมตามต้องการ
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    // เพิ่มสีสำหรับพนักงานเพิ่มเติมตามต้องการ
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
