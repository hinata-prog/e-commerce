<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'shipping_returns',
        'measurement_unit',
        'measurement_value',
        'price',
        'compare_price',
        'category_id',
        'sub_category_id',
        'is_featured',
        'sku',
        'barcode',
        'track_qty',
        'qty',
        'status',
    ];
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


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id')->withDefault(); // Optional
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function wishlists()
    {
        return $this->hasMany(WishList::class, 'product_id');
    }

    public function productRatings(){
        return $this->hasMany(ProductRating::class,'product_id')->where('status',1);
    }
}
