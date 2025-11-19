<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'name',
        'description',
        'category',
        'starting_price',
        'max_price',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
        'starting_price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // العلاقات
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function auction()
    {
        return $this->hasOne(Auction::class);
    }

    // الدوال المساعدة
    public function getImageUrlsAttribute()
    {
        if (!$this->images) {
            return [];
        }

        return array_map(function($image) {
            return asset('storage/products/' . $image);
        }, $this->images);
    }

    /**
     * الحصول على رابط الصورة
     */
    public function getImageUrl($image)
    {
        if (!$image) {
            return 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2U1ZTdlYiIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTgiIGZpbGw9IiM5Y2EzYWYiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj7Yp9mE2YXYp9mE2YXYp9mE2YU8L3RleHQ+PC9zdmc+';
        }
        return asset('storage/products/' . $image);
    }

    /**
     * الحصول على رابط الصورة الأولى
     */
    public function getFirstImageUrl()
    {
        if (!$this->images || count($this->images) === 0) {
            return 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2U1ZTdlYiIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTgiIGZpbGw9IiM5Y2EzYWYiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj7Yp9mE2YXYp9mE2YXYp9mE2YU8L3RleHQ+PC9zdmc+';
        }
        return asset('storage/products/' . $this->images[0]);
    }

    public function isActive()
    {
        return $this->auction && $this->auction->status === 'active';
    }
}