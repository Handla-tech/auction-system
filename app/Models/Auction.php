<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'start_time',
        'end_time',
        'current_bid',
        'winner_id',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'current_bid' => 'decimal:2',
    ];

    // 🎯 Scopes الجديدة لنظام المزايدات
    
    /**
     * Scope للمزادات النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('end_time', '>', now());
    }

    /**
     * Scope للمزادات المنتهية
     */
    public function scopeEnded($query)
    {
        return $query->where(function($q) {
            $q->where('status', 'ended')
              ->orWhere('end_time', '<=', now());
        });
    }

    /**
     * Scope للمزادات التي على وشك الانتهاء (أقل من 24 ساعة)
     */
    public function scopeEndingSoon($query)
    {
        return $query->where('status', 'active')
                    ->where('end_time', '>', now())
                    ->where('end_time', '<=', now()->addHours(24));
    }

    /**
     * Scope للمزادات الجديدة (بدأت خلال 24 ساعة)
     */
    public function scopeNewAuctions($query)
    {
        return $query->where('status', 'active')
                    ->where('start_time', '>=', now()->subHours(24))
                    ->where('end_time', '>', now());
    }

    /**
     * Scope للمزادات بدون مزايدات
     */
    public function scopeWithoutBids($query)
    {
        return $query->where('current_bid', 0)
                    ->orWhereNull('current_bid');
    }

    /**
     * Scope للمزادات ذات المزايدات العالية (أعلى من 1000)
     */
    public function scopeHighValue($query)
    {
        return $query->where('current_bid', '>', 1000);
    }

    // العلاقات
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function seller()
    {
        return $this->hasOneThrough(User::class, Product::class, 'id', 'id', 'product_id', 'seller_id');
    }

    // 🎯 الدوال المساعدة المحسنة

    /**
     * التحقق من أن المزاد نشط
     */
    public function isActive()
    {
        return $this->status === 'active' && 
               now()->between($this->start_time, $this->end_time);
    }

    /**
     * التحقق من أن المزاد منتهي
     */
    public function isEnded()
    {
        return $this->status === 'ended' || now()->greaterThan($this->end_time);
    }

    /**
     * الوقت المتبقي للمزاد
     */
    public function timeRemaining()
    {
        if ($this->isEnded()) {
            return 'انتهى';
        }

        return $this->end_time->diffForHumans();
    }

    /**
     * الوقت المتبقي بالتنسيق الرقمي
     */
    public function timeRemainingDetailed()
    {
        if ($this->isEnded()) {
            return ['days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 0];
        }

        $diff = now()->diff($this->end_time);
        
        return [
            'days' => $diff->d,
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'seconds' => $diff->s,
            'total_seconds' => now()->diffInSeconds($this->end_time)
        ];
    }

    /**
     * أعلى مزايدة في المزاد
     */
    public function getHighestBid()
    {
        return $this->bids()->orderBy('bid_amount', 'desc')->first();
    }

    /**
     * عدد المزايدات في المزاد
     */
    public function getBidsCount()
    {
        return $this->bids()->count();
    }

    /**
     * تاريخ المزايدات مع معلومات المستخدمين
     */
    public function getBidsHistory()
    {
        return $this->bids()
            ->with('user')
            ->orderBy('bid_amount', 'desc')
            ->get()
            ->map(function($bid) {
                return [
                    'user_name' => $bid->user->name,
                    'bid_amount' => $bid->bid_amount,
                    'bid_time' => $bid->created_at->format('Y-m-d H:i:s'),
                    'formatted_amount' => number_format($bid->bid_amount, 2) . ' ر.س',
                    'formatted_time' => $bid->created_at->diffForHumans()
                ];
            });
    }

    /**
     * تقديم مزايدة جديدة
     */
    public function placeBid(User $user, $amount)
    {
        if (!$this->isActive()) {
            throw new \Exception('المزاد غير نشط');
        }

        if ($amount <= $this->current_bid) {
            throw new \Exception('المبلغ يجب أن يكون أعلى من المبلغ الحالي');
        }

        if ($user->id === $this->product->seller_id) {
            throw new \Exception('لا يمكنك المزايدة على منتجك الخاص');
        }

        // إنشاء المزايدة
        $bid = $this->bids()->create([
            'user_id' => $user->id,
            'bid_amount' => $amount,
            'bid_time' => now()
        ]);

        // تحديث السعر الحالي والفائز
        $this->update([
            'current_bid' => $amount,
            'winner_id' => $user->id
        ]);

        // التحقق إذا وصل المزاد للسعر الأقصى
        if ($amount >= $this->product->max_price) {
            $this->update(['status' => 'ended']);
        }

        return $bid;
    }

    /**
     * إنهاء المزاد تلقائياً
     */
    public function endAuction()
    {
        $this->update(['status' => 'ended']);
        
        // يمكن إضافة إشعارات هنا للمستخدمين
        return $this;
    }

    /**
     * الحصول على معلومات المزاد للإحصائيات
     */
    public function getAuctionStats()
    {
        $bidsCount = $this->getBidsCount();
        $highestBid = $this->getHighestBid();
        $startingPrice = $this->product->starting_price;
        $maxPrice = $this->product->max_price;
        
        $priceIncrease = $this->current_bid - $startingPrice;
        $priceIncreasePercentage = $startingPrice > 0 ? 
            round(($priceIncrease / $startingPrice) * 100, 2) : 0;

        return [
            'bids_count' => $bidsCount,
            'highest_bidder' => $highestBid ? $highestBid->user->name : 'لا يوجد',
            'highest_bid_amount' => $highestBid ? $highestBid->bid_amount : 0,
            'starting_price' => $startingPrice,
            'current_price' => $this->current_bid,
            'max_price' => $maxPrice,
            'price_increase' => $priceIncrease,
            'price_increase_percentage' => $priceIncreasePercentage,
            'time_remaining' => $this->timeRemaining(),
            'is_ending_soon' => $this->end_time->diffInHours(now()) <= 24
        ];
    }

    /**
     * التحقق إذا كان المزاد على وشك الانتهاء (أقل من ساعة)
     */
    public function isEndingSoon()
    {
        return $this->isActive() && $this->end_time->diffInMinutes(now()) <= 60;
    }

    /**
     * الحصول على الفائز النهائي (بعد انتهاء المزاد)
     */
    public function getFinalWinner()
    {
        if (!$this->isEnded()) {
            return null;
        }

        return $this->winner ?: $this->getHighestBid()?->user;
    }

    /**
     * إعادة تعيين المزاد (للاستخدام في حالة الأخطاء)
     */
    public function resetAuction()
    {
        // حذف جميع المزايدات
        $this->bids()->delete();
        
        // إعادة التعيين إلى الحالة الأولية
        $this->update([
            'current_bid' => $this->product->starting_price,
            'winner_id' => null,
            'status' => 'active'
        ]);

        return $this;
    }
}