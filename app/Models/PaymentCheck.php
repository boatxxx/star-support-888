<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCheck extends Model
{
    use HasFactory;
    protected $table = 'payment_checks';

    protected $primaryKey = 'id';

    protected $fillable = [
        'sale_id',
        'received_by',
        'received_amount',
        'received_date',
        'created_at',
        'updated_at',

    ];
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id'); // assuming Sale model exists
    }
    public function paymentChecks()
    {
        return $this->hasMany(PaymentCheck::class, 'received_by', 'user_id'); // received_by เป็น foreign key ที่อ้างอิงถึง user_id
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'received_by', 'user_id'); // received_by เป็น foreign key ที่อ้างอิงถึง user_id
    }
}
