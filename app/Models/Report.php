<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    // أنواع التقارير
    const TYPE_SALES = 'sales';
    const TYPE_USERS = 'users';
    const TYPE_AUCTIONS = 'auctions';
    const TYPE_BIDS = 'bids';

    // الدوال المساعدة
    public static function generateSalesReport()
    {
        $data = [
            'total_sales' => Auction::where('status', 'ended')->count(),
            'total_revenue' => Auction::where('status', 'ended')->sum('current_bid'),
            'average_sale_price' => Auction::where('status', 'ended')->avg('current_bid'),
            'top_products' => Product::withCount(['auction as bids_count'])
                ->orderBy('bids_count', 'desc')
                ->limit(10)
                ->get()
                ->toArray(),
        ];

        return self::create([
            'type' => self::TYPE_SALES,
            'data' => $data,
        ]);
    }

    public static function generateUsersReport()
    {
        $data = [
            'total_users' => User::count(),
            'sellers_count' => User::where('role', 'seller')->count(),
            'buyers_count' => User::where('role', 'buyer')->count(),
            'admins_count' => User::where('role', 'admin')->count(),
            'top_bidders' => User::withCount('bids')
                ->orderBy('bids_count', 'desc')
                ->limit(10)
                ->get()
                ->toArray(),
        ];

        return self::create([
            'type' => self::TYPE_USERS,
            'data' => $data,
        ]);
    }
}