{{-- resources/views/buyer/dashboard.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'لوحة تحكم المشتري')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
        <i class="bi bi-cart3 ml-2 text-green-600"></i>
        لوحة تحكم المشتري
    </h1>
    <p class="text-gray-600 mt-2">مرحباً {{ auth()->user()->name }}! استكشف وشارك في المزادات المميزة.</p>
</div>

{{-- 🎯 إحصائيات سريعة - محدثة --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">المزادات النشطة</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ \App\Models\Auction::active()->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="bi bi-hammer text-blue-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('auctions.active') }}" class="text-sm text-green-600 font-medium hover:text-green-700">استعرض الجميع →</a>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">مزاداتي النشطة</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ auth()->user()->activeBids()->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="bi bi-bid text-green-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('buyer.my-bids') }}" class="text-sm text-blue-600 font-medium hover:text-blue-700">شاهد المزادات →</a>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">المكاسب</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ auth()->user()->wonAuctions()->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="bi bi-trophy text-purple-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-sm text-green-600 font-medium">+{{ auth()->user()->wonAuctionsThisMonth()->count() }} هذا الشهر</span>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">إجمالي المزايدات</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ auth()->user()->bids()->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="bi bi-clock-history text-yellow-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-sm text-gray-600 font-medium">هذا الشهر: {{ auth()->user()->bidsThisMonth()->count() }}</span>
        </div>
    </div>
</div>

{{-- 🎯 الإجراءات السريعة - محدثة --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- بطاقة المنتجات المتاحة -->
    <a href="{{ route('buyer.products') }}" 
       class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">🛍️ المنتجات المتاحة</h3>
                <p class="text-gray-600 text-sm">استعرض وشارك في المزايدات</p>
            </div>
            <div class="text-3xl text-blue-500">🎯</div>
        </div>
    </a>

    <!-- بطاقة مزايداتي -->
    <a href="{{ route('buyer.my-bids') }}" 
       class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">📊 مزايداتي</h3>
                <p class="text-gray-600 text-sm">شاهد تاريخ مزايداتك</p>
            </div>
            <div class="text-3xl text-green-500">💰</div>
        </div>
    </a>

    <!-- بطاقة المزادات النشطة -->
    <a href="{{ route('auctions.active') }}" 
       class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">🔥 المزادات النشطة</h3>
                <p class="text-gray-600 text-sm">مزادات على وشك الانتهاء</p>
            </div>
            <div class="text-3xl text-red-500">🔥</div>
        </div>
    </a>

    <!-- بطاقة المزادات المنتهية -->
    <a href="{{ route('auctions.ended') }}" 
       class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">⏰ المزادات المنتهية</h3>
                <p class="text-gray-600 text-sm">شاهد نتائج المزادات</p>
            </div>
            <div class="text-3xl text-gray-500">⏰</div>
        </div>
    </a>
</div>

{{-- المحتوى الرئيسي --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    {{-- مزاداتي النشطة --}}
    <div class="bg-white rounded-xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-900 flex items-center">
                <i class="bi bi-lightning text-yellow-500 ml-2"></i>
                مزاداتي النشطة
            </h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @php
                    $userActiveBids = auth()->user()->activeBids()->take(3)->get();
                @endphp
                
                @forelse($userActiveBids as $bid)
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:border-green-300 transition-colors">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            @if($bid->auction->product->images)
                                <img src="{{ $bid->auction->product->getFirstImageUrl() }}" 
                                     alt="{{ $bid->auction->product->name }}"
                                     class="w-12 h-12 rounded-lg object-cover">
                            @else
                                <i class="bi bi-image text-gray-600"></i>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $bid->auction->product->name }}</h3>
                            <p class="text-sm text-gray-500">مزايدتي: {{ number_format($bid->bid_amount, 2) }} ر.س</p>
                        </div>
                    </div>
                    <div class="text-left">
                        @if($bid->bid_amount == $bid->auction->current_bid)
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">في الصدارة</span>
                        @else
                            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">متفوق عليك</span>
                        @endif
                        <p class="text-xs text-gray-500 mt-1">ينتهي {{ $bid->auction->end_time->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-gray-500">
                    <i class="bi bi-inbox text-3xl mb-2"></i>
                    <p>لا توجد مزادات نشطة</p>
                    <a href="{{ route('buyer.products') }}" class="text-green-600 hover:text-green-700 text-sm">ابدأ المزايدة الآن</a>
                </div>
                @endforelse
            </div>
            @if($userActiveBids->count() > 0)
            <a href="{{ route('buyer.my-bids') }}" class="block text-center mt-4 text-green-600 hover:text-green-700 font-medium">
                عرض جميع مزاداتي <i class="bi bi-arrow-left"></i>
            </a>
            @endif
        </div>
    </div>

    {{-- المزادات الموصى بها --}}
    <div class="bg-white rounded-xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-900 flex items-center">
                <i class="bi bi-star text-blue-500 ml-2"></i>
                موصى بها لك
            </h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @php
                    $recommendedAuctions = \App\Models\Auction::active()
                        ->with('product')
                        ->where('end_time', '>', now())
                        ->inRandomOrder()
                        ->take(3)
                        ->get();
                @endphp
                
                @forelse($recommendedAuctions as $auction)
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors group">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition-colors overflow-hidden">
                            @if($auction->product->images)
                                <img src="{{ $auction->product->getFirstImageUrl() }}" 
                                     alt="{{ $auction->product->name }}"
                                     class="w-12 h-12 object-cover">
                            @else
                                <i class="bi bi-image text-blue-600"></i>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">{{ \Illuminate\Support\Str::limit($auction->product->name, 20) }}</h3>
                            <p class="text-sm text-gray-500">السعر: {{ number_format($auction->current_bid, 2) }} ر.س</p>
                        </div>
                    </div>
                    <a href="{{ route('buyer.auction.show', $auction->product->id) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-sm transition-colors">
                        مزايدة
                    </a>
                </div>
                @empty
                <div class="text-center py-4 text-gray-500">
                    <i class="bi bi-search text-3xl mb-2"></i>
                    <p>لا توجد مزادات متاحة حالياً</p>
                </div>
                @endforelse
            </div>
            <a href="{{ route('buyer.products') }}" class="block text-center mt-4 text-blue-600 hover:text-blue-700 font-medium">
                استكشاف المزيد <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>
</div>

{{-- 🎯 قسم المزادات المنتهية مؤخرًا --}}
<div class="mt-8 bg-white rounded-xl shadow-lg border border-gray-100">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-900 flex items-center">
            <i class="bi bi-clock-history text-purple-500 ml-2"></i>
            مزادات منتهية مؤخراً
        </h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @php
                $recentEndedAuctions = \App\Models\Auction::ended()
                    ->with(['product', 'winner'])
                    ->latest('end_time')
                    ->take(3)
                    ->get();
            @endphp
            
            @forelse($recentEndedAuctions as $auction)
            <div class="border border-gray-200 rounded-lg p-4 hover:border-purple-300 transition-colors">
                <div class="flex items-center space-x-3 space-x-reverse mb-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center overflow-hidden">
                        @if($auction->product->images)
                            <img src="{{ $auction->product->getFirstImageUrl() }}" 
                                 alt="{{ $auction->product->name }}"
                                 class="w-10 h-10 object-cover">
                        @else
                            <i class="bi bi-image text-purple-600"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900 text-sm">{{ \Illuminate\Support\Str::limit($auction->product->name, 25) }}</h4>
                        <p class="text-xs text-gray-500">باع بـ {{ number_format($auction->current_bid, 2) }} ر.س</p>
                    </div>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded">
                        {{ $auction->winner ? $auction->winner->name : 'لا يوجد فائز' }}
                    </span>
                    <span class="text-gray-500">{{ $auction->end_time->diffForHumans() }}</span>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-4 text-gray-500">
                <i class="bi bi-clock text-2xl mb-2"></i>
                <p>لا توجد مزادات منتهية بعد</p>
            </div>
            @endforelse
        </div>
        <a href="{{ route('auctions.ended') }}" class="block text-center mt-4 text-purple-600 hover:text-purple-700 font-medium">
            عرض جميع المزادات المنتهية <i class="bi bi-arrow-left"></i>
        </a>
    </div>
</div>
@endsection