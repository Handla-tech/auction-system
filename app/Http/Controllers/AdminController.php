<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * لوحة تحكم المسؤول الرئيسية
     */
    public function dashboard()
    {
        $stats = [
            // إحصائيات المستخدمين
            'total_users' => User::count(),
            'sellers' => User::where('role', 'seller')->count(),
            'buyers' => User::where('role', 'buyer')->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'active_users' => User::where('is_active', true)->count(),
            
            // إحصائيات المنتجات والمزادات
            'total_products' => Product::count(),
            'active_auctions' => Auction::where('status', 'active')->count(),
            'ended_auctions' => Auction::where('status', 'ended')->count(),
            'total_bids' => Bid::count(),
            
            // إحصائيات مالية
            'total_revenue' => Auction::ended()->sum('current_bid'),
            'average_bid_amount' => Bid::avg('bid_amount') ?? 0,
            'today_bids' => Bid::whereDate('created_at', today())->count(),
            
            // إحصائيات إضافية
            'products_without_bids' => Auction::active()->doesntHave('bids')->count(),
            'auctions_ending_today' => Auction::active()
                ->whereDate('end_time', today())
                ->count(),
        ];

        // المستخدمون النشطون حديثاً
        $recentUsers = User::withCount(['products', 'bids'])
            ->latest()
            ->take(5)
            ->get();

        // المزادات النشطة الأكثر نشاطاً
        $mostActiveAuctions = Auction::with(['product', 'winner'])
            ->withCount('bids')
            ->active()
            ->orderBy('bids_count', 'desc')
            ->take(5)
            ->get();

        // المنتجات الأكثر مشاهدة (يمكن إضافة نظام المشاهدات لاحقاً)
        $recentProducts = Product::with(['seller', 'auction'])
            ->latest()
            ->take(5)
            ->get();

        // المزايدات الأخيرة
        $recentBids = Bid::with(['user', 'auction.product'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 
            'recentUsers', 
            'mostActiveAuctions', 
            'recentProducts', 
            'recentBids'
        ));
    }

    /**
     * إدارة المستخدمين
     */
    public function users(Request $request)
    {
        $query = User::withCount(['products', 'bids', 'wonAuctions']);

        // البحث والفلترة
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active');
        }

        // الترتيب
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'most_products':
                $query->orderBy('products_count', 'desc');
                break;
            case 'most_bids':
                $query->orderBy('bids_count', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $users = $query->paginate(15);

        return view('admin.users', compact('users'));
    }

    /**
     * إدارة المنتجات
     */
    public function products(Request $request)
    {
        $query = Product::with(['seller', 'auction'])
            ->withCount(['auction']);

        // البحث والفلترة
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        if ($request->has('seller') && $request->seller != '') {
            $query->where('seller_id', $request->seller);
        }

        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'with_auction') {
                $query->whereHas('auction');
            } elseif ($request->status == 'without_auction') {
                $query->whereDoesntHave('auction');
            }
        }

        // الترتيب
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('starting_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('max_price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(15);
        $categories = Product::distinct()->pluck('category');
        $sellers = User::where('role', 'seller')->get();

        return view('admin.products', compact('products', 'categories', 'sellers'));
    }

    /**
     * إدارة المزادات
     */
    public function auctions(Request $request)
    {
        $query = Auction::with(['product.seller', 'winner'])
            ->withCount('bids')
            ->withMax('bids', 'bid_amount')
            ->withMin('bids', 'bid_amount')
            ->withAvg('bids', 'bid_amount');

        // البحث والفلترة
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'active') {
                $query->active();
            } elseif ($request->status == 'ended') {
                $query->ended();
            } elseif ($request->status == 'scheduled') {
                $query->where('status', 'scheduled');
            }
        }

        if ($request->has('seller') && $request->seller != '') {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('seller_id', $request->seller);
            });
        }

        if ($request->has('category') && $request->category != '') {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        // الترتيب
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'ending_soon':
                $query->orderBy('end_time', 'asc');
                break;
            case 'most_bids':
                $query->orderBy('bids_count', 'desc');
                break;
            case 'highest_bid':
                $query->orderBy('current_bid', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $auctions = $query->paginate(15);
        
        // إحصائيات المزادات
        $totalBids = Bid::count();
        $todayBids = Bid::whereDate('created_at', today())->count();
        $totalValue = Auction::where('status', 'ended')->sum('current_bid');
        $highestBid = Bid::max('bid_amount') ?? 0;
        $averageBid = Bid::avg('bid_amount') ?? 0;
        
        // حساب متوسط المزايدات لكل مزاد
        $totalAuctionsWithBids = Auction::has('bids')->count();
        $averageBidsPerAuction = $totalAuctionsWithBids > 0 ? $totalBids / $totalAuctionsWithBids : 0;

        $sellers = User::where('role', 'seller')->get();
        $categories = Product::distinct()->pluck('category');

        return view('admin.auctions', compact(
            'auctions', 
            'sellers',
            'categories',
            'totalBids',
            'todayBids',
            'totalValue',
            'highestBid',
            'averageBid',
            'averageBidsPerAuction'
        ));
    }

    /**
     * التقارير والإحصائيات المتقدمة
     */
    public function reports()
    {
        // إحصائيات المبيعات
        $salesStats = [
            'daily_sales' => Auction::ended()
                ->whereDate('end_time', today())
                ->sum('current_bid'),
            'weekly_sales' => Auction::ended()
                ->whereBetween('end_time', [now()->startOfWeek(), now()->endOfWeek()])
                ->sum('current_bid'),
            'monthly_sales' => Auction::ended()
                ->whereBetween('end_time', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('current_bid'),
            'total_sales' => Auction::ended()->sum('current_bid'),
        ];

        // المزادات الأكثر ربحاً
        $topAuctions = Auction::ended()
            ->with(['product', 'winner'])
            ->orderBy('current_bid', 'desc')
            ->take(10)
            ->get();

        // البائعون الأكثر نشاطاً
        $topSellers = User::where('role', 'seller')
            ->withCount(['products', 'auctions'])
            ->withSum('products', 'starting_price')
            ->orderBy('products_count', 'desc')
            ->take(10)
            ->get();

        // المشترون الأكثر نشاطاً
        $topBuyers = User::where('role', 'buyer')
            ->withCount(['bids', 'wonAuctions'])
            ->withSum('wonAuctions', 'current_bid')
            ->orderBy('bids_count', 'desc')
            ->take(10)
            ->get();

        // إحصائيات الفئات
        $categoryStats = Product::select('category', \Illuminate\Support\Facades\DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get();

        return view('admin.reports', compact(
            'salesStats',
            'topAuctions',
            'topSellers',
            'topBuyers',
            'categoryStats'
        ));
    }

    /**
     * تبديل حالة المستخدم
     */
    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'مفعل' : 'معطل';
        return back()->with('success', "تم {$status} حساب المستخدم {$user->name} بنجاح");
    }

    /**
     * حذف المنتج
     */
    public function deleteProduct(Product $product)
    {
        $productName = $product->name;
        $product->delete();

        return back()->with('success', "تم حذف المنتج '{$productName}' بنجاح");
    }

    /**
     * إنهاء مزاد يدوياً
     */
    public function endAuction(Auction $auction)
    {
        $auction->update(['status' => 'ended']);
        return back()->with('success', 'تم إنهاء المزاد يدوياً بنجاح');
    }

    /**
     * إعادة تعيين مزاد
     */
    public function resetAuction(Auction $auction)
    {
        $auction->resetAuction();
        return back()->with('success', 'تم إعادة تعيين المزاد بنجاح');
    }

    /**
     * تعطيل/تفعيل مزاد
     */
    public function toggleAuctionStatus(Auction $auction)
    {
        $newStatus = $auction->status === 'active' ? 'paused' : 'active';
        $auction->update(['status' => $newStatus]);
        
        $statusText = $newStatus === 'active' ? 'تفعيل' : 'تعطيل';
        return back()->with('success', "تم {$statusText} المزاد بنجاح");
    }

    /**
     * إرسال إشعار جماعي
     */
    public function sendBulkNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target_users' => 'required|in:all,sellers,buyers,specific',
            'specific_users' => 'required_if:target_users,specific|array',
        ]);

        // هنا يمكن إضافة منطق إرسال الإشعارات
        // يمكن استخدام Laravel Notifications أو أي نظام إشعارات

        return back()->with('success', 'تم إرسال الإشعارات بنجاح');
    }

    /**
     * تصدير تقرير
     */
    public function exportReport(Request $request)
    {
        $request->validate([
            'type' => 'required|in:users,products,auctions,sales',
            'format' => 'required|in:csv,excel,pdf',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // هنا يمكن إضافة منطق التصدير
        // يمكن استخدام packages مثل Laravel Excel

        return back()->with('success', 'سيبدأ تحميل التقرير shortly');
    }

    /**
     * عرض تفاصيل المستخدم
     */
    public function userDetails(User $user)
    {
        $user->load([
            'products.auction',
            'bids.auction.product',
            'wonAuctions.product'
        ]);

        return view('admin.user-details', compact('user'));
    }

    /**
     * عرض تفاصيل المزاد للمسؤول
     */
    public function auctionDetails(Auction $auction)
    {
        $auction->load([
            'product.seller',
            'bids.user',
            'winner'
        ]);

        return view('admin.auction-details', compact('auction'));
    }

    /**
     * عرض تفاصيل المنتج
     */
    public function productDetails(Product $product)
    {
        $product->load([
            'seller',
            'auction.bids.user',
            'auction.winner'
        ]);

        return view('admin.product-details', compact('product'));
    }

    /**
     * حذف مستخدم
     */
    public function deleteUser(User $user)
    {
        // منع حذف المستخدم المسؤول
        if ($user->isAdmin()) {
            return back()->with('error', 'لا يمكن حذف حساب المسؤول');
        }

        $userName = $user->name;
        $user->delete();

        return back()->with('success', "تم حذف المستخدم '{$userName}' بنجاح");
    }
}