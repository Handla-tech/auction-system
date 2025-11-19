<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email', 
        'password',
        'phone',
        'address',
        'role',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // الأدوار المسموحة
    const ROLE_SELLER = 'seller';
    const ROLE_BUYER = 'buyer'; 
    const ROLE_ADMIN = 'admin';

    public static function getRoles()
    {
        return [
            self::ROLE_SELLER => 'بائع',
            self::ROLE_BUYER => 'مشتري',
            self::ROLE_ADMIN => 'مسؤول'
        ];
    }

    // دوال التحقق من الأدوار
    public function isSeller()
    {
        return $this->role === self::ROLE_SELLER;
    }

    public function isBuyer()
    {
        return $this->role === self::ROLE_BUYER;
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    // العلاقات الأساسية
    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function wonAuctions()
    {
        return $this->hasMany(Auction::class, 'winner_id');
    }

    // 🆕 العلاقات الجديدة المطلوبة للتقارير
    public function auctions()
    {
        return $this->hasManyThrough(Auction::class, Product::class, 'seller_id', 'product_id');
    }

    public function activeAuctions()
    {
        return $this->auctions()->where('status', 'active');
    }

    public function endedAuctions()
    {
        return $this->auctions()->where('status', 'ended');
    }

    // 🎯 العلاقات الجديدة لنظام المزايدات

    /**
     * المزايدات النشطة للمستخدم
     */
    public function activeBids()
    {
        return $this->hasMany(Bid::class)
            ->whereHas('auction', function($query) {
                $query->where('status', 'active')
                      ->where('end_time', '>', now());
            })
            ->with(['auction.product'])
            ->orderBy('created_at', 'desc');
    }

    /**
     * المزادات التي فاز بها المستخدم هذا الشهر
     */
    public function wonAuctionsThisMonth()
    {
        return $this->hasMany(Auction::class, 'winner_id')
            ->where('status', 'ended')
            ->where('end_time', '>=', now()->startOfMonth())
            ->where('end_time', '<=', now()->endOfMonth());
    }

    /**
     * المزايدات التي قدمها المستخدم هذا الشهر
     */
    public function bidsThisMonth()
    {
        return $this->hasMany(Bid::class)
            ->where('created_at', '>=', now()->startOfMonth())
            ->where('created_at', '<=', now()->endOfMonth());
    }

    /**
     * المزادات التي يشارك فيها المستخدم حالياً (حيث لديه مزايدات نشطة)
     */
    public function activeParticipations()
    {
        return $this->hasManyThrough(
            Auction::class,
            Bid::class,
            'user_id', // Foreign key on bids table
            'id', // Foreign key on auctions table
            'id', // Local key on users table
            'auction_id' // Local key on bids table
        )->where('auctions.status', 'active')
         ->where('auctions.end_time', '>', now())
         ->distinct();
    }

    /**
     * أعلى مزايدة للمستخدم في مزاد معين
     */
    public function getHighestBidInAuction($auctionId)
    {
        return $this->bids()
            ->where('auction_id', $auctionId)
            ->orderBy('bid_amount', 'desc')
            ->first();
    }

    /**
     * التحقق إذا كان المستخدم هو المزايد الأعلى في مزاد معين
     */
    public function isHighestBidder($auctionId)
    {
        $auction = Auction::find($auctionId);
        if (!$auction) return false;

        return $auction->winner_id === $this->id;
    }

    /**
     * إحصائيات سريعة عن أداء المستخدم في المزايدات
     */
    public function getBiddingStats()
    {
        $totalBids = $this->bids()->count();
        $activeBids = $this->activeBids()->count();
        $wonAuctions = $this->wonAuctions()->count();
        $winRate = $totalBids > 0 ? round(($wonAuctions / $totalBids) * 100, 2) : 0;

        return [
            'total_bids' => $totalBids,
            'active_bids' => $activeBids,
            'won_auctions' => $wonAuctions,
            'win_rate' => $winRate,
            'total_spent' => $this->wonAuctions()->sum('current_bid'),
        ];
    }

    /**
     * المزادات الموصى بها للمستخدم بناءً على تاريخ مزايداته
     */
    public function recommendedAuctions($limit = 6)
    {
        // الحصول على فئات المنتجات التي يزايد عليها المستخدم
        $userCategories = $this->bids()
            ->join('auctions', 'bids.auction_id', '=', 'auctions.id')
            ->join('products', 'auctions.product_id', '=', 'products.id')
            ->pluck('products.category')
            ->unique()
            ->toArray();

        if (empty($userCategories)) {
            // إذا لم يكن لديه تاريخ مزايدات، عرض مزادات عشوائية
            return Auction::active()
                ->with('product')
                ->where('end_time', '>', now())
                ->inRandomOrder()
                ->limit($limit)
                ->get();
        }

        // عرض مزادات من الفئات المفضلة للمستخدم
        return Auction::active()
            ->with('product')
            ->whereHas('product', function($query) use ($userCategories) {
                $query->whereIn('category', $userCategories);
            })
            ->where('end_time', '>', now())
            ->where('winner_id', '!=', $this->id) // استبعاد المزادات التي فاز بها
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * التحقق إذا كان المستخدم يمكنه المزايدة على منتج معين
     */
    public function canBidOnProduct($productId)
    {
        $product = Product::find($productId);
        if (!$product) return false;

        // لا يمكن للمستخدم المزايدة على منتجاته
        if ($product->seller_id === $this->id) {
            return false;
        }

        $auction = $product->auction;
        if (!$auction || $auction->status !== 'active') {
            return false;
        }

        // التحقق من أن المزاد لم ينته بعد
        return $auction->end_time > now();
    }
}
?>