{{-- resources/views/auctions/active.blade.php --}}
@extends('layouts.app')

@section('title', 'المزادات النشطة')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- رأس الصفحة -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">
            🔥 المزادات النشطة
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            استعرض وشارك في المزادات النشطة حالياً
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
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-hammer text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">تنتهي اليوم</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ $auctions->where('end_time', '<=', now()->addDay())->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-alarm text-red-600 dark:text-red-400 text-xl"></i>
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
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-graph-up text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">متوسط السعر</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ number_format($auctions->avg('current_bid') ?? 0, 0) }} ر.س
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-currency-exchange text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- قائمة المزادات -->
    @if($auctions->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($auctions as $auction)
                @php
                    $product = $auction->product;
                    $isEndingSoon = $auction->end_time->diffInHours(now()) <= 24;
                @endphp

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 
                           overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <!-- الصورة -->
                    <div class="relative">
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ $product->getFirstImageUrl() }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <i class="bi bi-image text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                        
                        <!-- حالة المزاد -->
                        <div class="absolute top-3 left-3">
                            @if($isEndingSoon)
                                <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                                    ⏰ ينتهي قريباً
                                </span>
                            @else
                                <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                                    🔥 نشط
                                </span>
                            @endif
                        </div>

                        <!-- مؤشر الوقت المتبقي -->
                        <div class="absolute bottom-3 right-3 bg-black bg-opacity-70 text-white px-2 py-1 rounded-lg text-xs">
                            {{ $auction->timeRemaining() }}
                        </div>
                    </div>

                    <!-- معلومات المنتج -->
                    <div class="p-4">
                        <!-- الفئة -->
                        <div class="mb-2">
                            <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 
                                        px-2 py-1 rounded-full text-xs font-medium">
                                {{ $product->category }}
                            </span>
                        </div>

                        <!-- اسم المنتج -->
                        <h3 class="font-bold text-lg text-gray-800 dark:text-white mb-2 line-clamp-2">
                            {{ $product->name }}
                        </h3>

                        <!-- معلومات البائع -->
                        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-3">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center mr-2">
                                    <span class="text-xs font-bold">
                                        {{ Str::substr($product->seller->name, 0, 1) }}
                                    </span>
                                </div>
                                {{ Str::limit($product->seller->name, 15) }}
                            </div>
                        </div>

                        <!-- الأسعار -->
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">السعر الابتدائي:</span>
                                <span class="font-bold text-green-600 dark:text-green-400">
                                    {{ number_format($product->starting_price, 2) }} ر.س
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">السعر الحالي:</span>
                                <span class="font-bold text-blue-600 dark:text-blue-400">
                                    {{ number_format($auction->current_bid, 2) }} ر.س
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">الحد الأقصى:</span>
                                <span class="font-bold text-red-600 dark:text-red-400">
                                    {{ number_format($product->max_price, 2) }} ر.س
                                </span>
                            </div>
                        </div>

                        <!-- عدد المزايدات -->
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm text-gray-600 dark:text-gray-400">عدد المزايدات:</span>
                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 
                                      px-2 py-1 rounded-full text-xs font-bold">
                                {{ $auction->bids_count }}
                            </span>
                        </div>

                        <!-- أزرار الإجراء -->
                        <div class="flex space-x-2 space-x-reverse">
                            <a href="{{ route('buyer.auction.show', $product->id) }}" 
                               class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 
                                      text-white text-center py-2 px-4 rounded-lg font-medium transition-all duration-200 
                                      transform hover:scale-105 flex items-center justify-center">
                                <i class="bi bi-hammer ml-2"></i>
                                ابدأ المزايدة
                            </a>
                            <a href="{{ route('products.show', $product->id) }}" 
                               class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 
                                      text-gray-700 dark:text-gray-300 p-2 rounded-lg transition-colors duration-200
                                      flex items-center justify-center">
                                <i class="bi bi-eye"></i>
                            </a>
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
        <!-- حالة عدم وجود مزادات -->
        <div class="text-center py-16">
            <div class="text-6xl mb-4">🤷‍♂️</div>
            <h3 class="text-2xl font-bold text-gray-600 dark:text-gray-400 mb-4">لا توجد مزادات نشطة</h3>
            <p class="text-gray-500 dark:text-gray-500 mb-6">
                لا توجد مزادات نشطة حالياً. يرجى التحقق لاحقاً.
            </p>
            <a href="{{ route('buyer.products') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium 
                      transition-colors duration-200 inline-flex items-center">
                <i class="bi bi-arrow-right ml-2"></i>
                عرض المنتجات المتاحة
            </a>
        </div>
    @endif
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection