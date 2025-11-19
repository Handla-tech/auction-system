{{-- resources/views/admin/products.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'إدارة المنتجات')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- رأس الصفحة -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">
            🛍️ إدارة المنتجات
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            إدارة وعرض جميع منتجات الموقع
        </p>
    </div>

    <!-- الإحصائيات الرئيسية -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- إجمالي المنتجات -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">📦 إجمالي المنتجات</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $products->total() }}</p>
                    <div class="mt-2 text-xs">
                        <span class="text-green-600 mr-2">
                            {{ $products->where('auction.status', 'active')->count() }} نشط
                        </span>
                        <span class="text-gray-500">{{ $products->where('auction', null)->count() }} بدون مزاد</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-box text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- مع مزادات نشطة -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">🔥 مع مزادات نشطة</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ $products->where('auction.status', 'active')->count() }}
                    </p>
                    <div class="mt-2 text-xs">
                        <span class="text-yellow-600 mr-2">{{ $products->where('auction.status', 'ended')->count() }} منتهي</span>
                        <span class="text-gray-500">{{ $products->where('auction', '!=', null)->count() }} مزاد</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-lightning text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- بدون مزادات -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">⏰ بدون مزادات</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ $products->where('auction', null)->count() }}
                    </p>
                    <div class="mt-2 text-xs">
                        <span class="text-blue-600 mr-2">{{ $products->count() - $products->where('auction', null)->count() }} مع مزاد</span>
                        <span class="text-gray-500">من إجمالي {{ $products->total() }}</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-clock text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- متوسط السعر -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">💰 متوسط السعر الابتدائي</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ number_format($products->avg('starting_price') ?? 0, 0) }} ر.س
                    </p>
                    <div class="mt-2 text-xs">
                        <span class="text-green-600 mr-2">أعلى: {{ number_format($products->max('starting_price') ?? 0, 0) }} ر.س</span>
                        <span class="text-gray-500">أدنى: {{ number_format($products->min('starting_price') ?? 0, 0) }} ر.س</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-currency-dollar text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- شريط البحث والفلترة -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">🔍 البحث والفلترة</h3>
        <form action="{{ route('admin.products') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">البحث</label>
                <input type="text" id="search" name="search" 
                       value="{{ request('search') }}" 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                       placeholder="اسم المنتج، الوصف...">
            </div>
            
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الفئة</label>
                <select id="category" name="category"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">جميع الفئات</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="seller" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">البائع</label>
                <select id="seller" name="seller"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">جميع البائعين</option>
                    @foreach($sellers as $seller)
                        <option value="{{ $seller->id }}" {{ request('seller') == $seller->id ? 'selected' : '' }}>
                            {{ $seller->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الحالة</label>
                <select id="status" name="status"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">جميع الحالات</option>
                    <option value="with_auction" {{ request('status') == 'with_auction' ? 'selected' : '' }}>مع مزاد</option>
                    <option value="without_auction" {{ request('status') == 'without_auction' ? 'selected' : '' }}>بدون مزاد</option>
                </select>
            </div>
            
            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الترتيب</label>
                <select id="sort" name="sort"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>الأقدم</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>السعر: منخفض</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>السعر: عالي</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم</option>
                </select>
            </div>
            
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors">
                    <i class="bi bi-search mr-1"></i> بحث
                </button>
                <a href="{{ route('admin.products') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg transition-colors text-center">
                    <i class="bi bi-arrow-clockwise mr-1"></i> إعادة
                </a>
            </div>
        </form>
    </div>

    <!-- جدول المنتجات -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">قائمة المنتجات</h3>
            <div class="flex items-center gap-4">
                <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-medium">
                    إجمالي: {{ $products->total() }}
                </span>
                <button onclick="openExportModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="bi bi-download mr-1"></i> تصدير تقرير
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">المنتج</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">البائع</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الفئة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الأسعار</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">المزاد</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الإضافة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-3 flex-shrink-0 w-12 h-12">
                                        @if($product->images && count($product->images) > 0)
                                            <img src="{{ $product->getFirstImageUrl() }}" 
                                                 alt="{{ $product->name }}"
                                                 class="w-12 h-12 rounded-lg object-cover">
                                        @else
                                            <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                                <i class="bi bi-image text-gray-400 dark:text-gray-500"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mr-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ Str::limit($product->name, 25) }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $product->id }}</div>
                                        @if($product->description)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ Str::limit($product->description, 30) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-2 flex-shrink-0 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($product->seller->name, 0, 1)) }}
                                    </div>
                                    <div class="mr-2">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->seller->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $product->seller->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200">
                                    {{ $product->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    <div class="text-green-600 dark:text-green-400 font-bold">
                                        {{ number_format($product->starting_price, 0) }} ر.س
                                        <div class="text-xs text-gray-500 dark:text-gray-400">ابتدائي</div>
                                    </div>
                                    <div class="text-red-600 dark:text-red-400 font-bold mt-1">
                                        {{ number_format($product->max_price, 0) }} ر.س
                                        <div class="text-xs text-gray-500 dark:text-gray-400">أقصى</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->auction)
                                    <div class="text-sm">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $product->auction->status == 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200' }}">
                                            {{ $product->auction->status == 'active' ? 'نشط' : 'منتهي' }}
                                        </span>
                                        @if($product->auction->status == 'active')
                                            <div class="text-yellow-600 dark:text-yellow-400 text-xs mt-1">
                                                <i class="bi bi-clock mr-1"></i>
                                                {{ $product->auction->timeRemaining() }}
                                            </div>
                                        @else
                                            <div class="text-gray-500 dark:text-gray-400 text-xs mt-1">
                                                {{ $product->auction->end_time->format('Y-m-d') }}
                                            </div>
                                        @endif
                                        @if($product->auction->bids_count > 0)
                                            <div class="text-blue-600 dark:text-blue-400 text-xs mt-1">
                                                {{ $product->auction->bids_count }} مزايدة
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500 dark:bg-gray-600 dark:text-gray-400">
                                        بدون مزاد
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->auction && $product->auction->status == 'active')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        <i class="bi bi-check-circle ml-1"></i> نشط
                                    </span>
                                @elseif($product->auction && $product->auction->status == 'ended')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200">
                                        <i class="bi bi-clock-history ml-1"></i> منتهي
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 dark:bg-gray-600 dark:text-gray-400">
                                        <i class="bi bi-dash-circle ml-1"></i> بدون مزاد
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <div>{{ $product->created_at->format('Y-m-d') }}</div>
                                <div class="text-xs">{{ $product->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.product-details', $product) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition-colors" 
                                       title="عرض التفاصيل">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('products.show', $product) }}" 
                                       class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-lg transition-colors" 
                                       title="عرض في الموقع" target="_blank">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>

                                    <form action="{{ route('admin.products.delete', $product) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg transition-colors"
                                                onclick="return confirm('هل أنت متأكد من حذف المنتج \\'{{ $product->name }}\\'؟')"
                                                title="حذف المنتج">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <i class="bi bi-box text-4xl mb-3 block"></i>
                                    لا توجد منتجات
                                </div>
                                @if(request()->anyFilled(['search', 'category', 'seller', 'status']))
                                    <a href="{{ route('admin.products') }}" class="inline-block mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                        عرض جميع المنتجات
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- التصفح -->
            @if($products->hasPages())
            <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- نافذة تصدير التقرير -->
<div id="exportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">📊 تصدير تقرير المنتجات</h3>
                <button onclick="closeExportModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            <form action="{{ route('admin.export-report') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="products">
                <div class="space-y-4">
                    <div>
                        <label for="format" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">صيغة التقرير</label>
                        <select id="format" name="format" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">من تاريخ</label>
                            <input type="date" id="start_date" name="start_date"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">إلى تاريخ</label>
                            <input type="date" id="end_date" name="end_date"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label for="export_category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الفئة (اختياري)</label>
                        <select id="export_category" name="category"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">جميع الفئات</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeExportModal()" 
                            class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg transition-colors">
                        إلغاء
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors">
                        تصدير التقرير
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openExportModal() {
    document.getElementById('exportModal').classList.remove('hidden');
}

function closeExportModal() {
    document.getElementById('exportModal').classList.add('hidden');
}

// إغلاق النافذة عند النقر خارجها
document.getElementById('exportModal').addEventListener('click', function(e) {
    if (e.target === this) closeExportModal();
});
</script>

<style>
/* تخصيص التصميم للجدول */
table {
    border-collapse: separate;
    border-spacing: 0;
}

th, td {
    border-bottom: 1px solid #e5e7eb;
}

th {
    background-color: #f9fafb;
}

@media (prefers-color-scheme: dark) {
    th {
        background-color: #374151;
    }
}
</style>
@endsection