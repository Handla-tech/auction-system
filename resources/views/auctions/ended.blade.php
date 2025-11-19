{{-- resources/views/auctions/ended.blade.php --}}
@extends('layouts.app')

@section('title', 'المزادات المنتهية')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- رأس الصفحة -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">
            ⏰ المزادات المنتهية
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            استعرض نتائج المزادات المنتهية والفائزين
        </p>
    </div>

    <!-- شريط الإحصائيات -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">إجمالي المزادات</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $auctions->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-clock-history text-gray-600 dark:text-gray-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">إجمالي المبيعات</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ number_format($auctions->sum('current_bid'), 0) }} ر.س
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-currency-exchange text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">متوسط سعر البيع</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ number_format($auctions->avg('current_bid') ?? 0, 0) }} ر.س
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-graph-up text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">معدل المزايدات</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ number_format($auctions->avg('bids_count') ?? 0, 1) }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-hammer text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- فلترة المزادات -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <div class="flex flex-wrap gap-4 items-center justify-between">
            <div class="flex flex-wrap gap-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">الفلاتر:</span>
                <a href="{{ route('auctions.ended') }}" 
                   class="px-4 py-2 rounded-lg font-medium transition-colors duration-200 
                          {{ !request('filter') ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    الكل
                </a>
                <a href="{{ route('auctions.ended', ['filter' => 'today']) }}" 
                   class="px-4 py-2 rounded-lg font-medium transition-colors duration-200 
                          {{ request('filter') == 'today' ? 'bg-green-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    انتهت اليوم
                </a>
                <a href="{{ route('auctions.ended', ['filter' => 'week']) }}" 
                   class="px-4 py-2 rounded-lg font-medium transition-colors duration-200 
                          {{ request('filter') == 'week' ? 'bg-purple-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    هذا الأسبوع
                </a>
                <a href="{{ route('auctions.ended', ['filter' => 'high_value']) }}" 
                   class="px-4 py-2 rounded-lg font-medium transition-colors duration-200 
                          {{ request('filter') == 'high_value' ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    أعلى الأسعار
                </a>
            </div>

            <div class="flex items-center space-x-4 space-x-reverse">
                <select id="sortSelect" 
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                               dark:bg-gray-700 dark:text-white text-sm">
                    <option value="newest">الأحدث</option>
                    <option value="oldest">الأقدم</option>
                    <option value="highest">أعلى سعر</option>
                    <option value="lowest">أقل سعر</option>
                    <option value="most_bids">أكثر مزايدات</option>
                </select>
            </div>
        </div>
    </div>

    <!-- قائمة المزادات المنتهية -->
    @if($auctions->count() > 0)
        <div class="space-y-6">
            @foreach($auctions as $auction)
                @php
                    $product = $auction->product;
                    $winner = $auction->winner;
                    $isHighValue = $auction->current_bid > 1000;
                    $endedRecently = $auction->end_time->diffInDays(now()) <= 7;
                @endphp

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 
                           overflow-hidden hover:shadow-xl transition-all duration-300
                           {{ $endedRecently ? 'border-l-4 border-l-green-500' : '' }}">
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
                                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 
                                                        px-3 py-1 rounded-full text-xs font-bold">
                                                ⏰ منتهي
                                            </span>
                                            @if($isHighValue)
                                                <span class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 
                                                            px-2 py-1 rounded-full text-xs font-bold">
                                                    💎 قيمة عالية
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
                                            <i class="bi bi-trophy text-gray-400 ml-2"></i>
                                            <span class="text-gray-600 dark:text-gray-400">الفائز:</span>
                                            <span class="font-medium text-green-600 mr-2">
                                                {{ $winner ? $winner->name : 'لا يوجد فائز' }}
                                            </span>
                                        </div>

                                        <div class="flex items-center">
                                            <i class="bi bi-calendar-check text-gray-400 ml-2"></i>
                                            <span class="text-gray-600 dark:text-gray-400">انتهى:</span>
                                            <span class="font-medium text-gray-800 dark:text-white mr-2">
                                                {{ $auction->end_time->format('Y-m-d H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- المعلومات المالية -->
                            <div class="lg:w-1/3 border-t lg:border-t-0 lg:border-r border-gray-200 dark:border-gray-700 
                                      pt-4 lg:pt-0 lg:pr-6 lg:pl-6">
                                <div class="space-y-3">
                                    <!-- سعر البيع النهائي -->
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">سعر البيع النهائي</p>
                                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                                            {{ number_format($auction->current_bid, 2) }} ر.س
                                        </p>
                                    </div>

                                    <!-- معلومات الأسعار -->
                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                        <div class="text-center bg-gray-50 dark:bg-gray-700 rounded-lg p-2">
                                            <p class="text-gray-600 dark:text-gray-400">الابتدائي</p>
                                            <p class="font-bold text-gray-800 dark:text-white">
                                                {{ number_format($product->starting_price, 0) }} ر.س
                                            </p>
                                        </div>
                                        <div class="text-center bg-gray-50 dark:bg-gray-700 rounded-lg p-2">
                                            <p class="text-gray-600 dark:text-gray-400">المزايدات</p>
                                            <p class="font-bold text-gray-800 dark:text-white">{{ $auction->bids_count }}</p>
                                        </div>
                                    </div>

                                    <!-- نسبة الزيادة -->
                                    @php
                                        $increase = $auction->current_bid - $product->starting_price;
                                        $increasePercentage = $product->starting_price > 0 ? 
                                            round(($increase / $product->starting_price) * 100, 2) : 0;
                                    @endphp
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">زيادة</p>
                                        <p class="font-bold text-blue-600">
                                            +{{ number_format($increase, 0) }} ر.س ({{ $increasePercentage }}%)
                                        </p>
                                    </div>

                                    <!-- أزرار الإجراء -->
                                    <div class="flex space-x-2 space-x-reverse pt-2">
                                        <a href="{{ route('products.show', $product->id) }}" 
                                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-3 rounded-lg 
                                                  font-medium transition-colors duration-200 text-sm flex items-center justify-center">
                                            <i class="bi bi-eye ml-1"></i>
                                            عرض التفاصيل
                                        </a>
                                        
                                        @if($winner && auth()->id() === $winner->id)
                                            <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 
                                                       px-3 py-2 rounded-lg text-sm font-bold flex items-center">
                                                🏆 فائز
                                            </span>
                                        @endif
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
            {{ $auctions->links() }}
        </div>
    @else
        <!-- حالة عدم وجود مزادات منتهية -->
        <div class="text-center py-16">
            <div class="text-6xl mb-4">📊</div>
            <h3 class="text-2xl font-bold text-gray-600 dark:text-gray-400 mb-4">لا توجد مزادات منتهية</h3>
            <p class="text-gray-500 dark:text-gray-500 mb-6">
                لم تنته أي مزادات بعد. يمكنك استعراض المزادات النشطة حالياً.
            </p>
            <a href="{{ route('auctions.active') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium 
                      transition-colors duration-200 inline-flex items-center">
                <i class="bi bi-lightning ml-2"></i>
                عرض المزادات النشطة
            </a>
        </div>
    @endif
</div>

<script>
    // فلترة وترتيب المزادات
    document.getElementById('sortSelect').addEventListener('change', function() {
        const sort = this.value;
        const url = new URL(window.location.href);
        url.searchParams.set('sort', sort);
        window.location.href = url.toString();
    });

    // تطبيق الفلترة الحالية على select
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const sort = urlParams.get('sort');
        if (sort) {
            document.getElementById('sortSelect').value = sort;
        }
    });
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection