@extends('layouts.app')

@section('title', 'المنتجات المتاحة للمزايدة')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- رأس الصفحة -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">
            🛍️ المنتجات المتاحة للمزايدة
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            استعرض وشارك في المزايدات على المنتجات المميزة
        </p>
    </div>

    <!-- شريط البحث والتصفية -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <form action="{{ route('buyer.products') }}" method="GET" class="space-y-4 md:space-y-0 md:grid md:grid-cols-4 md:gap-4">
            <!-- حقل البحث -->
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    🔍 البحث في المنتجات
                </label>
                <input type="text" 
                       name="search" 
                       id="search"
                       value="{{ request('search') }}"
                       placeholder="ابحث باسم المنتج، الوصف، أو الفئة..."
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl 
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                              dark:bg-gray-700 dark:text-white">
            </div>

            <!-- التصفية حسب الفئة -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    📁 الفئة
                </label>
                <select name="category" 
                        id="category"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl 
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                               dark:bg-gray-700 dark:text-white">
                    <option value="">جميع الفئات</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- الترتيب -->
            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    🔄 الترتيب
                </label>
                <select name="sort" 
                        id="sort"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl 
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                               dark:bg-gray-700 dark:text-white">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                    <option value="ending_soon" {{ request('sort') == 'ending_soon' ? 'selected' : '' }}>ينتهي قريباً</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>السعر: منخفض إلى عالي</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>السعر: عالي إلى منخفض</option>
                </select>
            </div>

            <!-- أزرار -->
            <div class="md:col-span-4 flex space-x-4 space-x-reverse justify-end items-end">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl font-medium 
                               transition-colors duration-200 flex items-center">
                    <i class="bi bi-search ml-2"></i>
                    بحث
                </button>
                <a href="{{ route('buyer.products') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-xl font-medium 
                          transition-colors duration-200 flex items-center">
                    <i class="bi bi-arrow-clockwise ml-2"></i>
                    إعادة تعيين
                </a>
            </div>
        </form>
    </div>

    <!-- نتائج البحث -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <p class="text-gray-600 dark:text-gray-400">
                عثرنا على <span class="font-bold text-blue-600">{{ $products->total() }}</span> منتج
                @if(request('search'))
                    لـ "<span class="font-bold text-blue-600">{{ request('search') }}</span>"
                @endif
            </p>
        </div>
        
        <div class="flex items-center space-x-4 space-x-reverse">
            <!-- عرض الشبكة / القائمة -->
            <div class="flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                <button id="gridView" class="p-2 rounded-lg bg-white dark:bg-gray-600 shadow-sm">
                    <i class="bi bi-grid-3x3-gap text-blue-600"></i>
                </button>
                <button id="listView" class="p-2 rounded-lg text-gray-500 dark:text-gray-400">
                    <i class="bi bi-list-ul"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- قائمة المنتجات -->
    @if($products->count() > 0)
        <div id="productsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
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
                            @if($product->auction->isEndingSoon())
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
                            {{ $product->auction->timeRemaining() }}
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

                        <!-- الوصف المختصر -->
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                            {{ Str::limit($product->description, 80) }}
                        </p>

                        <!-- معلومات البائع -->
                        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center mr-2">
                                    <span class="text-xs font-bold">
                                        {{ Str::substr($product->seller->name, 0, 1) }}
                                    </span>
                                </div>
                                {{ Str::limit($product->seller->name, 15) }}
                            </div>
                            <span class="text-xs">
                                {{ $product->created_at->diffForHumans() }}
                            </span>
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
                                    {{ number_format($product->auction->current_bid, 2) }} ر.س
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
                                {{ $product->auction->bids_count ?? 0 }}
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
                            <button class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 
                                         text-gray-700 dark:text-gray-300 p-2 rounded-lg transition-colors duration-200"
                                    onclick="toggleWatchlist({{ $product->id }})">
                                <i class="bi bi-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- عرض القائمة (مخفي افتراضياً) -->
        <div id="productsList" class="hidden space-y-4">
            @foreach($products as $product)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 
                           p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0">
                        <!-- الصورة -->
                        <div class="md:w-1/6">
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ $product->getFirstImageUrl() }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-32 object-cover rounded-lg">
                            @else
                                <div class="w-full h-32 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-image text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- المعلومات -->
                        <div class="md:w-4/6 md:px-6">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-2">
                                <div>
                                    <h3 class="font-bold text-xl text-gray-800 dark:text-white mb-1">
                                        {{ $product->name }}
                                    </h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-2">
                                        {{ Str::limit($product->description, 120) }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-2 space-x-reverse mb-2 md:mb-0">
                                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 
                                                px-2 py-1 rounded-full text-xs font-medium">
                                        {{ $product->category }}
                                    </span>
                                    @if($product->auction->isEndingSoon())
                                        <span class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 
                                                    px-2 py-1 rounded-full text-xs font-bold">
                                            ⏰ ينتهي قريباً
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">البائع:</span>
                                    <span class="font-medium text-gray-800 dark:text-white">{{ $product->seller->name }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">الوقت المتبقي:</span>
                                    <span class="font-medium text-orange-600">{{ $product->auction->timeRemaining() }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">المزايدات:</span>
                                    <span class="font-medium text-gray-800 dark:text-white">{{ $product->auction->bids_count ?? 0 }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">السعر الحالي:</span>
                                    <span class="font-bold text-blue-600 dark:text-blue-400">
                                        {{ number_format($product->auction->current_bid, 2) }} ر.س
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- الأزرار -->
                        <div class="md:w-1/6 flex flex-col space-y-2">
                            <a href="{{ route('buyer.auction.show', $product->id) }}" 
                               class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 
                                      text-white text-center py-2 px-4 rounded-lg font-medium transition-all duration-200 
                                      transform hover:scale-105 flex items-center justify-center">
                                <i class="bi bi-hammer ml-2"></i>
                                مزايدة
                            </a>
                            <button class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 
                                         text-gray-700 dark:text-gray-300 py-2 px-4 rounded-lg transition-colors duration-200 
                                         flex items-center justify-center"
                                    onclick="toggleWatchlist({{ $product->id }})">
                                <i class="bi bi-heart ml-2"></i>
                                المفضلة
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- التصفح -->
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @else
        <!-- حالة عدم وجود منتجات -->
        <div class="text-center py-16">
            <div class="text-6xl mb-4">🤷‍♂️</div>
            <h3 class="text-2xl font-bold text-gray-600 dark:text-gray-400 mb-4">لا توجد منتجات</h3>
            <p class="text-gray-500 dark:text-gray-500 mb-6">
                @if(request()->anyFilled(['search', 'category', 'sort']))
                    لم نعثر على منتجات تطابق معايير البحث الخاصة بك.
                @else
                لا توجد منتجات متاحة للمزايدة حالياً.
                @endif
            </p>
            @if(request()->anyFilled(['search', 'category', 'sort']))
                <a href="{{ route('buyer.products') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium 
                          transition-colors duration-200 inline-flex items-center">
                    <i class="bi bi-arrow-clockwise ml-2"></i>
                    عرض جميع المنتجات
                </a>
            @endif
        </div>
    @endif
</div>

<script>
    // تبديل بين عرض الشبكة والقائمة
    document.addEventListener('DOMContentLoaded', function() {
        const gridView = document.getElementById('gridView');
        const listView = document.getElementById('listView');
        const productsGrid = document.getElementById('productsGrid');
        const productsList = document.getElementById('productsList');

        gridView.addEventListener('click', function() {
            productsGrid.classList.remove('hidden');
            productsList.classList.add('hidden');
            gridView.classList.add('bg-white', 'dark:bg-gray-600', 'shadow-sm');
            gridView.classList.remove('text-gray-500', 'dark:text-gray-400');
            listView.classList.remove('bg-white', 'dark:bg-gray-600', 'shadow-sm');
            listView.classList.add('text-gray-500', 'dark:text-gray-400');
        });

        listView.addEventListener('click', function() {
            productsGrid.classList.add('hidden');
            productsList.classList.remove('hidden');
            listView.classList.add('bg-white', 'dark:bg-gray-600', 'shadow-sm');
            listView.classList.remove('text-gray-500', 'dark:text-gray-400');
            gridView.classList.remove('bg-white', 'dark:bg-gray-600', 'shadow-sm');
            gridView.classList.add('text-gray-500', 'dark:text-gray-400');
        });

        // دالة المفضلة (وهمية للتوضيح)
        window.toggleWatchlist = function(productId) {
            // هنا يمكن إضافة منطق إضافة/إزالة من المفضلة
            alert('سيتم إضافة المنتج إلى المفضلة - هذه وظيفة تجريبية');
        };
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