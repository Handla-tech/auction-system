<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function dashboard()
    {
        $user = auth()->user();
        
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'seller':
                return redirect()->route('seller.dashboard');
            case 'buyer':
                return redirect()->route('buyer.dashboard');
            default:
                return redirect('/');
        }
    }

    public function sellerDashboard()
    {
        $stats = [
            'total_products' => Product::where('seller_id', auth()->id())->count(),
            'active_auctions' => Auction::whereHas('product', function($query) {
                $query->where('seller_id', auth()->id());
            })->where('status', 'active')->count(),
            'ended_auctions' => Auction::whereHas('product', function($query) {
                $query->where('seller_id', auth()->id());
            })->where('status', 'ended')->count(),
            'total_bids' => Bid::whereHas('auction.product', function($query) {
                $query->where('seller_id', auth()->id());
            })->count(),
        ];

        $recent_products = Product::where('seller_id', auth()->id())
            ->with('auction')
            ->latest()
            ->take(5)
            ->get();

        return view('seller.dashboard', compact('stats', 'recent_products'));
    }

    public function buyerDashboard()
    {
        $user = auth()->user();
        
        // 🎯 إحصائيات المشتري
        $stats = [
            'total_bids' => $user->bids()->count(),
            'active_bids' => $user->activeBids()->count(),
            'won_auctions' => $user->wonAuctions()->count(),
            'won_this_month' => $user->wonAuctionsThisMonth()->count(),
            'bids_this_month' => $user->bidsThisMonth()->count(),
            'total_spent' => $user->wonAuctions()->sum('current_bid') ?? 0,
        ];

        // 🎯 المزايدات النشطة
        $activeBids = $user->activeBids()
            ->with(['auction.product'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 🎯 المزادات الموصى بها
        $recommendedAuctions = $user->recommendedAuctions(3);

        // 🎯 المزادات المنتهية مؤخراً
        $recentEndedAuctions = Auction::ended()
            ->with(['product', 'winner'])
            ->latest('end_time')
            ->take(3)
            ->get();

        return view('buyer.dashboard', compact(
            'stats', 
            'activeBids', 
            'recommendedAuctions', 
            'recentEndedAuctions'
        ));
    }
}