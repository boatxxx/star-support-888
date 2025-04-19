<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionCondition extends Model
{
    use HasFactory;

    protected $table = 'promotion_conditions';

    protected $primaryKey = 'condition_id';

    protected $fillable = [
        'promotion_id',
        'condition_type',
        'condition_value',
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promotion_id', 'promotion_id');
    }

}
