<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopVisit extends Model
{
    use HasFactory;
    protected $fillable = [
        'shop_id',
        'visit_date',
        'employee_id',
        'notes',
    ];
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id'); // shop_id เป็น foreign key ที่เชื่อมโยงกับตาราง Shop
    }

    // ความสัมพันธ์กับ User (พนักงาน)

     // ความสัมพันธ์กับ Shop


     // ความสัมพันธ์กับ User (พนักงาน)
     public function employee()
     {
        return $this->belongsTo(User::class, 'employee_id', 'user_id');
    }

}
