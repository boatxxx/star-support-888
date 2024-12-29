<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerVisit extends Model
{
    use HasFactory;

    protected $table = 'customer_visits';

    protected $fillable = [
        'shop_id',
        'visit_date',
        'employee_id',
        'notes',
        'customer_name',  // เพิ่มฟิลด์ customer_name
    ];


    // ความสัมพันธ์กับร้านค้า
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    // ความสัมพันธ์กับพนักงาน
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
