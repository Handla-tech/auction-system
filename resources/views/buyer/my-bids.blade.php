{{-- resources/views/buyer/my-bids.blade.php --}}
@extends('layouts.app')

@section('title', 'مزايداتي')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- رأس الصفحة -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">
            💰 مزايداتي
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            تابع جميع مزايداتك ونتائج المزادات التي شاركت فيها
        </p>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- إجمالي المزايدات -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">إجمالي المزايدات</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_bids'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-hammer text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- المزايدات النشطة -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">مزايدات نشطة</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['active_bids'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-lightning text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- المزادات التي فزت بها -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">فوزاتي</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['won_auctions'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-trophy text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- إجمالي المبلغ -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">إجمالي المبالغ</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($stats['total_bid_amount'], 0) }} ر.س</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-currency-exchange text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- فلترة المزايدات -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('buyer.my-bids') }}" 
                   class="px-4 py-2 rounded-lg font-medium transition-colors duration-200 
                          {{ !request('status') ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    الكل ({{ $stats['total_bids'] }})
                </a>
                <a href="{{ route('buyer.my-bids', ['status' => 'active']) }}" 
                   class="px-4 py-2 rounded-lg font-medium transition-colors duration-200 
                          {{ request('status') == 'active' ? 'bg-green-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    🔥 نشطة ({{ $stats['active_bids'] }})
                </a>
                <a href="{{ route('buyer.my-bids', ['status' => 'won']) }}" 
                   class="px-4 py-2 rounded-lg font-medium transition-colors duration-200 
                          {{ request('status') == 'won' ? 'bg-purple-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    🏆 فوزات ({{ $stats['won_auctions'] }})
                </a>
                <a href="{{ route('buyer.my-bids', ['status' => 'lost']) }}" 
                   class="px-4 py-2 rounded-lg font-medium transition-colors duration-200 
                          {{ request('status') == 'lost' ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    ❌ خسائر
                </a>
            </div>

            <div class="flex items-center space-x-4 space-x-reverse">
                <select id="sortSelect" 
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                               dark:bg-gray-700 dark:text-white text-sm">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>الأقدم</option>
                    <option value="highest" {{ request('sort') == 'highest' ? 'selected' : '' }}>أعلى مبلغ</option>
                    <option value="lowest" {{ request('sort') == 'lowest' ? 'selected' : '' }}>أقل مبلغ</option>
                </select>
            </div>
        </div>
    </div>

    <!-- قائمة المزايدات -->
    @if($bids->count() > 0)
        <div class="space-y-4">
            @foreach($bids as $bid)
                @php
                    $auction = $bid->auction;
                    $product = $auction->product;
                    $isWinning = $bid->isWinningBid();
                    $isActive = $auction->isActive();
                    $isEnded = $auction->isEnded();
                    $isHighest = $bid->isHighestBid();
                @endphp

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 
                           overflow-hidden hover:shadow-xl transition-all duration-300 
                           {{ $isWinning ? 'border-l-4 border-l-green-500' : '' }}
                           {{ !$isActive && !$isWinning ? 'border-l-4 border-l-red-500' : '' }}">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center space-y-4 lg:space-y-0">
                            <!-- الصورة والمعلومات الأساسية -->
                            <div class="flex items-start space-x-4 space-x-reverse lg:w-2/3">
                                <!-- صورة المنتج -->
                                <div class="flex-shrink-0">
                                    @if($product->images && count($product->images) > 0)
                                        <img src="{{ $product->getFirstImageUrl() }}" 
                                             alt="{{ $product->name }}"
                                             class="w-20 h-20 lg:w-24 lg:h-24 object-cover rounded-xl">
                                    @else
                                        <div class="w-20 h-20 lg:w-24 lg:h-24 bg-gray-200 dark:bg-gray-700 rounded-xl flex items-center justify-center">
                                            <i class="bi bi-image text-gray-400 text-xl"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- معلومات المنتج والمزاد -->
                                <div class="flex-1">
                                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between mb-2">
                                        <div>
                                            <h3 class="font-bold text-xl text-gray-800 dark:text-white mb-1">
                                                {{ $product->name }}
                                            </h3>
                                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">
                                                {{ Str::limit($product->description, 100) }}
                                            </p>
                                        </div>
                                        
                                        <!-- حالة المزاد -->
                                        <div class="flex items-center space-x-2 space-x-reverse mb-2 lg:mb-0">
                                            @if($isActive)
                                                <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 
                                                            px-3 py-1 rounded-full text-xs font-bold">
                                                    🔥 نشط
                                                </span>
                                                @if($isHighest)
                                                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 
                                                                px-2 py-1 rounded-full text-xs font-bold">
                                                        👑 في الصدارة
                                                    </span>
                                                @endif
                                            @elseif($isWinning)
                                                <span class="bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 
                                                            px-3 py-1 rounded-full text-xs font-bold">
                                                    🏆 فائز
                                                </span>
                                            @else
                                                <span class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 
                                                            px-3 py-1 rounded-full text-xs font-bold">
                                                    ❌ خسر
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- معلومات إضافية -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        <div class="flex items-center">
                                            <i class="bi bi-person text-gray-400 ml-2"></i>
                                            <span class="text-gray-600 dark:text-gray-400">البائع:</span>
                                            <span class="font-medium text-gray-800 dark:text-white mr-2">{{ $product->seller->name }}</span>
                                        </div>
                                        
                                        <div class="flex items-center">
                                            <i class="bi bi-clock text-gray-400 ml-2"></i>
                                            <span class="text-gray-600 dark:text-gray-400">وقت المزايدة:</span>
                                            <span class="font-medium text-gray-800 dark:text-white mr-2">{{ $bid->created_at->format('Y-m-d H:i') }}</span>
                                        </div>

                                        @if($isActive)
                                            <div class="flex items-center">
                                                <i class="bi bi-alarm text-gray-400 ml-2"></i>
                                                <span class="text-gray-600 dark:text-gray-400">ينتهي:</span>
                                                <span class="font-medium text-orange-600 mr-2">{{ $auction->timeRemaining() }}</span>
                                            </div>
                                        @else
                                            <div class="flex items-center">
                                                <i class="bi bi-calendar-check text-gray-400 ml-2"></i>
                                                <span class="text-gray-600 dark:text-gray-400">انتهى:</span>
                                                <span class="font-medium text-gray-800 dark:text-white mr-2">{{ $auction->end_time->format('Y-m-d') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- المعلومات المالية والأزرار -->
                            <div class="lg:w-1/3 border-t lg:border-t-0 lg:border-r border-gray-200 dark:border-gray-700 
                                      pt-4 lg:pt-0 lg:pr-6 lg:pl-6">
                                <div class="space-y-3">
                                    <!-- مبلغ المزايدة -->
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">مبلغ مزايدتك</p>
                                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                            {{ number_format($bid->bid_amount, 2) }} ر.س
                                        </p>
                                    </div>

                                    <!-- السعر الحالي -->
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">السعر الحالي:</span>
                                        <span class="font-bold text-gray-800 dark:text-white">
                                            {{ number_format($auction->current_bid, 2) }} ر.س
                                        </span>
                                    </div>

                                    <!-- ترتيب المزايدة -->
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">ترتيبك:</span>
                                        <span class="font-bold {{ $bid->getBidRank() == 1 ? 'text-green-600' : 'text-yellow-600' }}">
                                            #{{ $bid->getBidRank() }}
                                        </span>
                                    </div>

                                    <!-- أزرار الإجراء -->
                                    <div class="flex space-x-2 space-x-reverse pt-2">
                                        @if($isActive)
                                            <a href="{{ route('buyer.auction.show', $product->id) }}" 
                                               class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 
                                                      text-white text-center py-2 px-3 rounded-lg font-medium transition-all duration-200 
                                                      transform hover:scale-105 text-sm flex items-center justify-center">
                                                <i class="bi bi-hammer ml-1"></i>
                                                تابع المزايدة
                                            </a>
                                        @else
                                            <a href="{{ route('products.show', $product->id) }}" 
                                               class="flex-1 bg-gray-500 hover:bg-gray-600 text-white text-center py-2 px-3 rounded-lg 
                                                      font-medium transition-colors duration-200 text-sm flex items-center justify-center">
                                                <i class="bi bi-eye ml-1"></i>
                                                عرض النتيجة
                                            </a>
                                        @endif
                                        
                                        <button class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 
                                                     text-gray-700 dark:text-gray-300 p-2 rounded-lg transition-colors duration-200"
                                                onclick="showBidDetails({{ $bid->id }})">
                                            <i class="bi bi-info-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- التصفح -->
        <div class="mt-8">
            {{ $bids->links() }}
        </div>
    @else
        <!-- حالة عدم وجود مزايدات -->
        <div class="text-center py-16">
            <div class="text-6xl mb-4">🤷‍♂️</div>
            <h3 class="text-2xl font-bold text-gray-600 dark:text-gray-400 mb-4">لا توجد مزايدات</h3>
            <p class="text-gray-500 dark:text-gray-500 mb-6">
                @if(request('status') == 'active')
                    ليس لديك مزايدات نشطة حالياً.
                @elseif(request('status') == 'won')
                    لم تفز بأي مزاد حتى الآن.
                @elseif(request('status') == 'lost')
                    ليس لديك مزايدات خاسرة.
                @else
                    لم تقدم على أي مزايدة حتى الآن.
                @endif
            </p>
            <a href="{{ route('buyer.products') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium 
                      transition-colors duration-200 inline-flex items-center">
                <i class="bi bi-hammer ml-2"></i>
                ابدأ المزايدة الآن
            </a>
        </div>
    @endif
</div>

<!-- نافذة تفاصيل المزايدة -->
<div id="bidDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full mx-4 max-h-90vh overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">تفاصيل المزايدة</h3>
                <button onclick="closeBidDetails()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            <div id="bidDetailsContent">
                <!-- سيتم ملؤه بالجافاسكريبت -->
            </div>
        </div>
    </div>
</div>

<script>
    // فلترة وترتيب المزايدات
    document.getElementById('sortSelect').addEventListener('change', function() {
        const sort = this.value;
        const url = new URL(window.location.href);
        url.searchParams.set('sort', sort);
        window.location.href = url.toString();
    });

    // عرض تفاصيل المزايدة
    function showBidDetails(bidId) {
        // هنا يمكن جلب بيانات المزايدة عبر AJAX
        const modal = document.getElementById('bidDetailsModal');
        const content = document.getElementById('bidDetailsContent');
        
        // بيانات وهمية للتوضيح
        content.innerHTML = `
            <div class="space-y-4">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="bi bi-hammer text-blue-600 dark:text-blue-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400">تفاصيل المزايدة #${bidId}</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                        <p class="text-gray-600 dark:text-gray-400">ترتيب المزايدة</p>
                        <p class="font-bold text-lg text-blue-600">#1</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                        <p class="text-gray-600 dark:text-gray-400">الفرق عن السابق</p>
                        <p class="font-bold text-lg text-green-600">+50 ر.س</p>
                    </div>
                </div>
                
                <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                    <p class="text-yellow-800 dark:text-yellow-200 text-sm">
                        <i class="bi bi-info-circle ml-1"></i>
                        هذه المزايدة تحت المراجعة وسيتم الإعلان عن النتيجة قريباً.
                    </p>
                </div>
            </div>
        `;
        
        modal.classList.remove('hidden');
    }

    function closeBidDetails() {
        document.getElementById('bidDetailsModal').classList.add('hidden');
    }

    // إغلاق النافذة عند النقر خارجها
    document.getElementById('bidDetailsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeBidDetails();
        }
    });
</script>

<style>
.max-h-90vh {
    max-height: 90vh;
}
</style>
@endsection