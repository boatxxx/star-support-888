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
}
