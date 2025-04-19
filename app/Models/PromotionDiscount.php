<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionDiscount extends Model
{
    use HasFactory;

    protected $table = 'promotion_discounts';

    protected $primaryKey = 'discount_id';

    protected $fillable = [
        'promotion_id',
        'discount_type',
        'discount_value',
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promotion_id', 'promotion_id');
    }
}
