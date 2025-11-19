<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Product;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuctionController extends Controller
{
    /**
     * عرض المزادات النشطة
     */
    public function activeAuctions()
    {
        $auctions = Auction::active()
            ->with(['product', 'winner'])
            ->withCount('bids')
            ->orderBy('end_time', 'asc')
            ->paginate(12);

        return view('auctions.active', compact('auctions'));
    }

    /**
     * عرض المزادات المنتهية
     */
    public function endedAuctions()
    {
        $auctions = Auction::ended()
            ->with(['product', 'winner'])
            ->withCount('bids')
            ->orderBy('end_time', 'desc')
            ->paginate(12);

        return view('auctions.ended', compact('auctions'));
    }

    /**
     * عرض المزادات التي على وشك الانتهاء
     */
    public function endingSoon()
    {
        $auctions = Auction::endingSoon()
            ->with(['product', 'winner'])
            ->withCount('bids')
            ->orderBy('end_time', 'asc')
            ->paginate(12);

        return view('auctions.ending-soon', compact('auctions'));
    }

    /**
     * عرض المزادات الجديدة
     */
    public function newAuctions()
    {
        $auctions = Auction::newAuctions()
            ->with(['product', 'winner'])
            ->withCount('bids')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('auctions.new', compact('auctions'));
    }

    /**
     * عرض المزادات بدون مزايدات
     */
    public function withoutBids()
    {
        $auctions = Auction::active()
            ->withoutBids()
            ->with(['product', 'winner'])
            ->withCount('bids')
            ->orderBy('end_time', 'asc')
            ->paginate(12);

        return view('auctions.without-bids', compact('auctions'));
    }

    /**
     * عرض المزادات ذات القيمة العالية
     */
    public function highValue()
    {
        $auctions = Auction::active()
            ->highValue()
            ->with(['product', 'winner'])
            ->withCount('bids')
            ->orderBy('current_bid', 'desc')
            ->paginate(12);

        return view('auctions.high-value', compact('auctions'));
    }

    /**
     * عرض تفاصيل مزاد معين
     */
    public function show($id)
    {
        $auction = Auction::with([
            'product.seller',
            'bids.user',
            'winner'
        ])->findOrFail($id);

        $auctionStats = $auction->getAuctionStats();
        $bidsHistory = $auction->getBidsHistory();

        return view('auctions.show', compact('auction', 'auctionStats', 'bidsHistory'));
    }

    /**
     * البحث في المزادات
     */
    public function search(Request $request)
    {
        $query = Auction::active()
            ->with(['product', 'winner'])
            ->withCount('bids');

        // البحث بالكلمات المفتاحية
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // التصفية حسب الفئة
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        // التصفية حسب السعر
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('current_bid', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('current_bid', '<=', $request->max_price);
        }

        // التصفية حسب الوقت المتبقي
        if ($request->has('time_filter')) {
            switch ($request->time_filter) {
                case 'ending_soon':
                    $query->where('end_time', '<=', now()->addHours(24));
                    break;
                case 'new':
                    $query->where('start_time', '>=', now()->subHours(24));
                    break;
                case 'week':
                    $query->where('end_time', '<=', now()->addWeek());
                    break;
            }
        }

        // الترتيب
        $sort = $request->get('sort', 'ending_soon');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('current_bid', 'asc');
                break;
            case 'price_high':
                $query->orderBy('current_bid', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'most_bids':
                $query->orderBy('bids_count', 'desc');
                break;
            case 'ending_soon':
            default:
                $query->orderBy('end_time', 'asc');
                break;
        }

        $auctions = $query->paginate(12);
        $categories = Product::distinct()->pluck('category');

        return view('auctions.search', compact('auctions', 'categories'));
    }

    /**
     * إحصائيات المزادات (للمسؤول)
     */
    public function stats()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $stats = [
            'total_auctions' => Auction::count(),
            'active_auctions' => Auction::active()->count(),
            'ended_auctions' => Auction::ended()->count(),
            'total_bids' => Bid::count(),
            'total_revenue' => Auction::ended()->sum('current_bid'),
            'average_bid_amount' => Bid::avg('bid_amount') ?? 0,
            'auctions_ending_today' => Auction::active()
                ->whereDate('end_time', today())
                ->count(),
            'new_auctions_today' => Auction::whereDate('created_at', today())->count(),
        ];

        // المزادات الأكثر نشاطاً
        $mostActiveAuctions = Auction::withCount('bids')
            ->orderBy('bids_count', 'desc')
            ->limit(5)
            ->get();

        // المزادات الأعلى سعراً
        $highestAuctions = Auction::ended()
            ->orderBy('current_bid', 'desc')
            ->limit(5)
            ->get();

        return view('admin.auctions.stats', compact('stats', 'mostActiveAuctions', 'highestAuctions'));
    }

    /**
     * إنهاء مزاد يدوياً (للمسؤول)
     */
    public function endAuction($id)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $auction = Auction::findOrFail($id);
        $auction->endAuction();

        return redirect()->back()->with('success', 'تم إنهاء المزاد بنجاح.');
    }

    /**
     * إعادة تعيين مزاد (للمسؤول)
     */
    public function resetAuction($id)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $auction = Auction::findOrFail($id);
        $auction->resetAuction();

        return redirect()->back()->with('success', 'تم إعادة تعيين المزاد بنجاح.');
    }

    /**
     * تحديث حالة المزادات تلقائياً (لـ Cron Job)
     */
    public function updateStatuses()
    {
        // إنهاء المزادات التي انتهى وقتها
        $endedAuctions = Auction::active()
            ->where('end_time', '<=', now())
            ->get();

        foreach ($endedAuctions as $auction) {
            $auction->endAuction();
        }

        // بدء المزادات التي حان وقتها
        $startingAuctions = Auction::where('status', 'pending')
            ->where('start_time', '<=', now())
            ->get();

        foreach ($startingAuctions as $auction) {
            $auction->update(['status' => 'active']);
        }

        return response()->json([
            'ended' => $endedAuctions->count(),
            'started' => $startingAuctions->count()
        ]);
    }

    /**
     * الحصول على بيانات المزاد لـ API
     */
    public function getAuctionData($id)
    {
        $auction = Auction::with([
            'product',
            'bids.user',
            'winner'
        ])->findOrFail($id);

        return response()->json([
            'auction' => $auction,
            'stats' => $auction->getAuctionStats(),
            'bids_history' => $auction->getBidsHistory(),
            'time_remaining' => $auction->timeRemainingDetailed()
        ]);
    }

    /**
     * تصدير تقرير المزادات (للمسؤول)
     */
    public function exportReport(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $query = Auction::with(['product', 'winner']);

        if ($request->has('type')) {
            switch ($request->type) {
                case 'active':
                    $query->active();
                    break;
                case 'ended':
                    $query->ended();
                    break;
                case 'month':
                    $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                    break;
            }
        }

        $auctions = $query->get();

        // هنا يمكن إضافة منطق التصدير لـ PDF أو Excel
        return response()->json([
            'auctions' => $auctions,
            'total' => $auctions->count(),
            'total_value' => $auctions->sum('current_bid')
        ]);
    }
}