<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{
    /**
     * عرض صفحة المزايدة على منتج
     */
    public function show($productId)
    {
        $product = Product::with(['auction', 'seller'])->findOrFail($productId);
        $auction = $product->auction;
        
        // التحقق من أن المزاد نشط
        if (!$auction || $auction->status !== 'active') {
            return redirect()->back()->with('error', 'هذا المزاد غير متاح حالياً.');
        }

        $currentBid = $auction->current_bid;
        $bids = Bid::where('auction_id', $auction->id)
                  ->with('user')
                  ->orderBy('bid_amount', 'desc')
                  ->get();

        return view('buyer.auction', compact('product', 'auction', 'currentBid', 'bids'));
    }

    /**
     * تقديم مزايدة جديدة
     */
    public function store(Request $request, $auctionId)
    {
        $auction = Auction::findOrFail($auctionId);
        
        // التحقق من أن المستخدم مشتري
        if (Auth::user()->role !== 'buyer') {
            return response()->json(['error' => 'فقط المشترون يمكنهم المزايدة.'], 403);
        }

        // التحقق من أن المزاد نشط
        if ($auction->status !== 'active') {
            return response()->json(['error' => 'المزاد غير نشط.'], 400);
        }

        // التحقق من أن المزاد لم ينته بعد
        if (now() > $auction->end_time) {
            return response()->json(['error' => 'انتهى وقت المزاد.'], 400);
        }

        $request->validate([
            'bid_amount' => 'required|numeric|min:' . ($auction->current_bid + 1)
        ]);

        // إنشاء المزايدة الجديدة
        $bid = Bid::create([
            'auction_id' => $auction->id,
            'user_id' => Auth::id(),
            'bid_amount' => $request->bid_amount,
            'bid_time' => now()
        ]);

        // تحديث أعلى مزايدة في المزاد
        $auction->update([
            'current_bid' => $request->bid_amount,
            'winner_id' => Auth::id()
        ]);

        // التحقق إذا وصلت المزايدة للسعر الأقصى
        $product = $auction->product;
        if ($request->bid_amount >= $product->max_price) {
            $auction->update(['status' => 'ended']);
            return response()->json([
                'success' => 'مبروك! لقد فزت بالمزاد.',
                'bid' => $bid,
                'auction_ended' => true
            ]);
        }

        return response()->json([
            'success' => 'تم تقديم المزايدة بنجاح.',
            'bid' => $bid,
            'new_current_bid' => $request->bid_amount
        ]);
    }

    /**
     * الحصول على تاريخ المزايدات
     */
    public function getBids($auctionId)
    {
        $bids = Bid::where('auction_id', $auctionId)
                  ->with('user')
                  ->orderBy('bid_amount', 'desc')
                  ->get()
                  ->map(function ($bid) {
                      return [
                          'user_name' => $bid->user->name,
                          'bid_amount' => number_format($bid->bid_amount, 2),
                          'bid_time' => $bid->bid_time->format('Y-m-d H:i:s'),
                          'is_own_bid' => $bid->user_id === Auth::id()
                      ];
                  });

        return response()->json($bids);
    }

    /**
     * عرض تاريخ مزايدات المستخدم
     */
    public function myBids()
    {
        $user = Auth::user();
        
        // 🎯 الحصول على المزايدات مع الفلترة
        $query = Bid::where('user_id', $user->id)
                  ->with(['auction.product', 'auction.winner']);

        // فلترة حسب الحالة
        $status = request('status');
        if ($status === 'active') {
            $query->whereHas('auction', function($q) {
                $q->where('status', 'active')->where('end_time', '>', now());
            });
        } elseif ($status === 'won') {
            $query->whereHas('auction', function($q) use ($user) {
                $q->where('winner_id', $user->id);
            });
        } elseif ($status === 'lost') {
            $query->whereHas('auction', function($q) use ($user) {
                $q->where('status', 'ended')
                  ->where('winner_id', '!=', $user->id);
            });
        }

        // الترتيب
        $sort = request('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'highest':
                $query->orderBy('bid_amount', 'desc');
                break;
            case 'lowest':
                $query->orderBy('bid_amount', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $bids = $query->paginate(10);

        // 🎯 إحصائيات للمزايدات
        $stats = [
            'total_bids' => $user->bids()->count(),
            'active_bids' => $user->activeBids()->count(),
            'won_auctions' => $user->wonAuctions()->count(),
            'total_bid_amount' => $user->bids()->sum('bid_amount'),
        ];

        return view('buyer.my-bids', compact('bids', 'stats'));
    }
}