<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DiscountCoupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'name',
        'description',
        'max_uses',
        'max_uses_user',
        'type',
        'discount_amount',
        'min_amount',
        'status',
        'starts_at',
        'expires_at',
    ];

    protected $dates = ['starts_at', 'expires_at'];

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Str::uuid();
        });
    }

}
