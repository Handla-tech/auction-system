<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_id',
        'user_id',
        'bid_amount',
        'bid_time'
    ];

    protected $casts = [
        'bid_amount' => 'decimal:2',
        'bid_time' => 'datetime',
    ];

    // 🎯 القيم الافتراضية
    protected $attributes = [
        'bid_time' => 'now',
    ];

    // 🎯 Scopes الجديدة
    public function scopeHighest($query)
    {
        return $query->orderBy('bid_amount', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('bid_time', 'desc');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('bid_time', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('bid_time', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('bid_time', [now()->startOfMonth(), now()->endOfMonth()]);
    }

    public function scopeForAuction($query, $auctionId)
    {
        return $query->where('auction_id', $auctionId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // العلاقات
    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🎯 علاقات إضافية عبر الـ auction
    public function product()
    {
        return $this->hasOneThrough(
            Product::class,
            Auction::class,
            'id', // Foreign key on auctions table
            'id', // Foreign key on products table
            'auction_id', // Local key on bids table
            'product_id' // Local key on auctions table
        );
    }

    public function seller()
    {
        return $this->hasOneThrough(
            User::class,
            Auction::class,
            'id', // Foreign key on auctions table
            'id', // Local key on users table
            'auction_id', // Local key on bids table
            'product_id' // Local key on auctions table
        )->join('products', 'auctions.product_id', '=', 'products.id')
         ->select('users.*')
         ->whereColumn('products.seller_id', 'users.id');
    }

    // 🎯 الدوال المساعدة المحسنة

    /**
     * التحقق إذا كانت المزايدة هي الفائزة
     */
    public function isWinningBid()
    {
        return $this->auction->winner_id === $this->user_id;
    }

    /**
     * التحقق إذا كانت المزايدة هي الأعلى في المزاد
     */
    public function isHighestBid()
    {
        $highestBid = $this->auction->bids()->highest()->first();
        return $highestBid && $highestBid->id === $this->id;
    }

    /**
     * الحصول على ترتيب المزايدة في المزاد
     */
    public function getBidRank()
    {
        return $this->auction->bids()
            ->where('bid_amount', '>', $this->bid_amount)
            ->count() + 1;
    }

    /**
     * الحصول على الفرق بين المزايدة والسعر الحالي
     */
    public function getBidDifference()
    {
        $previousBid = $this->auction->bids()
            ->where('id', '<', $this->id)
            ->highest()
            ->first();

        if (!$previousBid) {
            return $this->bid_amount - $this->auction->product->starting_price;
        }

        return $this->bid_amount - $previousBid->bid_amount;
    }

    /**
     * الحصول على معلومات المزايدة بشكل مفصل
     */
    public function getBidDetails()
    {
        return [
            'id' => $this->id,
            'user_name' => $this->user->name,
            'bid_amount' => $this->bid_amount,
            'formatted_amount' => number_format($this->bid_amount, 2) . ' ر.س',
            'bid_time' => $this->bid_time,
            'formatted_time' => $this->bid_time->format('Y-m-d H:i:s'),
            'relative_time' => $this->bid_time->diffForHumans(),
            'is_winning' => $this->isWinningBid(),
            'is_highest' => $this->isHighestBid(),
            'rank' => $this->getBidRank(),
            'difference' => $this->getBidDifference(),
            'product_name' => $this->product->name ?? 'غير معروف',
            'auction_status' => $this->auction->status,
            'time_remaining' => $this->auction->timeRemaining()
        ];
    }

    /**
     * التحقق إذا كانت المزايدة صالحة (لم تنته المدة)
     */
    public function isValid()
    {
        return $this->auction->isActive() && 
               $this->bid_time <= $this->auction->end_time;
    }

    /**
     * التحقق إذا كان يمكن إلغاء المزايدة
     */
    public function canBeCancelled()
    {
        // يمكن إلغاء المزايدة فقط إذا لم تكن الأعلى ولم ينته المزاد
        return !$this->isHighestBid() && 
               $this->auction->isActive() &&
               $this->bid_time->addMinutes(5) > now(); // خلال 5 دقائق فقط
    }

    /**
     * الحصول على المزايدة السابقة في نفس المزاد
     */
    public function getPreviousBid()
    {
        return $this->auction->bids()
            ->where('id', '<', $this->id)
            ->highest()
            ->first();
    }

    /**
     * الحصول على المزايدة التالية في نفس المزاد
     */
    public function getNextBid()
    {
        return $this->auction->bids()
            ->where('id', '>', $this->id)
            ->oldest('id')
            ->first();
    }

    /**
     * التحقق إذا كانت المزايدة تجاوزت السعر الأقصى للمنتج
     */
    public function exceededMaxPrice()
    {
        return $this->bid_amount >= $this->product->max_price;
    }

    /**
     * الحصول على نسبة الإكمال نحو السعر الأقصى
     */
    public function getProgressPercentage()
    {
        $startingPrice = $this->product->starting_price;
        $maxPrice = $this->product->max_price;
        
        if ($maxPrice <= $startingPrice) {
            return 100;
        }

        $progress = (($this->bid_amount - $startingPrice) / ($maxPrice - $startingPrice)) * 100;
        return min(round($progress, 2), 100);
    }

    /**
     * إحصائيات المزايدة
     */
    public function getBidStats()
    {
        $totalBidsInAuction = $this->auction->bids()->count();
        $userBidsInAuction = $this->auction->bids()->where('user_id', $this->user_id)->count();

        return [
            'total_bids_in_auction' => $totalBidsInAuction,
            'user_bids_in_auction' => $userBidsInAuction,
            'user_bid_percentage' => $totalBidsInAuction > 0 ? 
                round(($userBidsInAuction / $totalBidsInAuction) * 100, 2) : 0,
            'progress_percentage' => $this->getProgressPercentage(),
            'time_since_first_bid' => $this->auction->bids()->oldest()->first()?->bid_time->diffForHumans() ?? 'لا توجد مزايدات',
            'average_bid_amount' => $this->auction->bids()->avg('bid_amount') ?? 0
        ];
    }

    /**
     * نموذج للعرض في القوائم
     */
    public function toArray()
    {
        $array = parent::toArray();
        $array['formatted_amount'] = number_format($this->bid_amount, 2) . ' ر.س';
        $array['formatted_time'] = $this->bid_time->format('Y-m-d H:i:s');
        $array['relative_time'] = $this->bid_time->diffForHumans();
        $array['is_winning'] = $this->isWinningBid();
        
        return $array;
    }
}