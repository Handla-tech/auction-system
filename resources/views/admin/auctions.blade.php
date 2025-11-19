{{-- resources/views/admin/auctions.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'إدارة المزادات')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- رأس الصفحة -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">
            🔥 إدارة المزادات
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            إدارة وعرض جميع مزادات الموقع
        </p>
    </div>

    <!-- الإحصائيات الرئيسية -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- إجمالي المزادات -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">📊 إجمالي المزادات</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $auctions->total() }}</p>
                    <div class="mt-2 text-xs">
                        <span class="text-green-600 mr-2">
                            {{ $auctions->where('status', 'active')->count() }} نشط
                        </span>
                        <span class="text-gray-500">{{ $auctions->where('status', 'ended')->count() }} منتهي</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-clock text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- المزادات النشطة -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">⚡ المزادات النشطة</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ $auctions->where('status', 'active')->count() }}
                    </p>
                    <div class="mt-2 text-xs">
                        <span class="text-yellow-600 mr-2">{{ $auctions->where('status', 'active')->where('end_time', '<', now()->addHours(24))->count() }} تنتهي قريباً</span>
                        <span class="text-gray-500">{{ $auctions->where('status', 'active')->where('current_bid', '>', 0)->count() }} مع مزايدات</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-lightning text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- إجمالي المزايدات -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">💰 إجمالي المزايدات</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ $totalBids }}
                    </p>
                    <div class="mt-2 text-xs">
                        <span class="text-blue-600 mr-2">{{ $todayBids }} اليوم</span>
                        <span class="text-gray-500">{{ number_format($averageBidsPerAuction, 1) }} متوسط لكل مزاد</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-hammer text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- القيمة الإجمالية -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">💎 القيمة الإجمالية</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ number_format($totalValue, 0) }} ر.س
                    </p>
                    <div class="mt-2 text-xs">
                        <span class="text-green-600 mr-2">أعلى: {{ number_format($highestBid, 0) }} ر.س</span>
                        <span class="text-gray-500">متوسط: {{ number_format($averageBid, 0) }} ر.س</span>
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
        <form action="{{ route('admin.auctions') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">البحث</label>
                <input type="text" id="search" name="search" 
                       value="{{ request('search') }}" 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                       placeholder="اسم المنتج، الوصف...">
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الحالة</label>
                <select id="status" name="status"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">جميع الحالات</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>منتهي</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>مجدول</option>
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
                <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الترتيب</label>
                <select id="sort" name="sort"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>الأقدم</option>
                    <option value="ending_soon" {{ request('sort') == 'ending_soon' ? 'selected' : '' }}>ينتهي قريباً</option>
                    <option value="most_bids" {{ request('sort') == 'most_bids' ? 'selected' : '' }}>أكثر مزايدات</option>
                    <option value="highest_bid" {{ request('sort') == 'highest_bid' ? 'selected' : '' }}>أعلى مزايدة</option>
                </select>
            </div>
            
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors">
                    <i class="bi bi-search mr-1"></i> بحث
                </button>
                <a href="{{ route('admin.auctions') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg transition-colors text-center">
                    <i class="bi bi-arrow-clockwise mr-1"></i> إعادة
                </a>
            </div>
        </form>
    </div>

    <!-- جدول المزادات -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">قائمة المزادات</h3>
            <div class="flex items-center gap-4">
                <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-medium">
                    إجمالي: {{ $auctions->total() }}
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
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">التوقيت</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">المزايدات</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">السعر الحالي</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($auctions as $auction)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-3 flex-shrink-0 w-12 h-12">
                                        @if($auction->product->images && count($auction->product->images) > 0)
                                            <img src="{{ $auction->product->getFirstImageUrl() }}" 
                                                 alt="{{ $auction->product->name }}"
                                                 class="w-12 h-12 rounded-lg object-cover">
                                        @else
                                            <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                                <i class="bi bi-image text-gray-400 dark:text-gray-500"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mr-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ Str::limit($auction->product->name, 25) }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $auction->product->category }}
                                        </div>
                                        <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                            ID: {{ $auction->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-2 flex-shrink-0 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($auction->product->seller->name, 0, 1)) }}
                                    </div>
                                    <div class="mr-2">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $auction->product->seller->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $auction->product->starting_price }} ر.س ابتدائي
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    <div class="text-gray-900 dark:text-white">
                                        {{ $auction->start_time->format('Y-m-d') }}
                                    </div>
                                    <div class="text-gray-500 dark:text-gray-400 text-xs">
                                        البداية
                                    </div>
                                    @if($auction->status == 'active')
                                        <div class="text-yellow-600 dark:text-yellow-400 text-xs font-bold mt-1">
                                            <i class="bi bi-clock mr-1"></i>
                                            {{ $auction->timeRemaining() }}
                                        </div>
                                    @else
                                        <div class="text-gray-500 dark:text-gray-400 text-xs">
                                            {{ $auction->end_time->format('Y-m-d') }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-center">
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                        {{ $auction->bids_count }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        مزايدة
                                    </div>
                                    @if($auction->bids_count > 0)
                                        <div class="text-xs text-green-600 dark:text-green-400 mt-1">
                                            آخر: {{ $auction->last_bid_time?->diffForHumans() }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    @if($auction->current_bid > 0)
                                        <div class="text-green-600 dark:text-green-400 font-bold text-lg">
                                            {{ number_format($auction->current_bid, 0) }} ر.س
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            من {{ number_format($auction->product->starting_price, 0) }} ر.س
                                        </div>
                                    @else
                                        <div class="text-gray-600 dark:text-gray-400 font-bold">
                                            {{ number_format($auction->product->starting_price, 0) }} ر.س
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            بدون مزايدات
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($auction->status == 'active')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        <i class="bi bi-check-circle ml-1"></i> نشط
                                    </span>
                                    @if($auction->end_time < now()->addHours(24))
                                        <div class="text-red-600 dark:text-red-400 text-xs mt-1 font-bold">
                                            <i class="bi bi-exclamation-triangle ml-1"></i>
                                            ينتهي قريباً
                                        </div>
                                    @endif
                                @elseif($auction->status == 'ended')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200">
                                        <i class="bi bi-clock-history ml-1"></i> منتهي
                                    </span>
                                    @if($auction->winner)
                                        <div class="text-green-600 dark:text-green-400 text-xs mt-1">
                                            فائز: {{ $auction->winner->name }}
                                        </div>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        <i class="bi bi-calendar ml-1"></i> مجدول
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <a href="{{ route('auctions.show', $auction) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition-colors" 
                                       title="عرض المزاد" target="_blank">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('admin.auction-details', $auction) }}" 
                                       class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-lg transition-colors" 
                                       title="تفاصيل المزاد">
                                        <i class="bi bi-info-circle"></i>
                                    </a>

                                    @if($auction->status == 'active')
                                    <form action="{{ route('admin.auctions.end', $auction) }}" method="POST" class="inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" 
                                                class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg transition-colors"
                                                onclick="return confirm('هل أنت متأكد من إنهاء المزاد \\'{{ $auction->product->name }}\\'؟')"
                                                title="إنهاء المزاد">
                                            <i class="bi bi-stop-circle"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <i class="bi bi-clock text-4xl mb-3 block"></i>
                                    لا توجد مزادات
                                </div>
                                @if(request()->anyFilled(['search', 'status', 'seller', 'category']))
                                    <a href="{{ route('admin.auctions') }}" class="inline-block mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                        عرض جميع المزادات
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- التصفح -->
            @if($auctions->hasPages())
            <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                {{ $auctions->links() }}
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
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">📊 تصدير تقرير المزادات</h3>
                <button onclick="closeExportModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            <form action="{{ route('admin.export-report') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="auctions">
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
                        <label for="export_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الحالة (اختياري)</label>
                        <select id="export_status" name="status"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">جميع الحالات</option>
                            <option value="active">نشط</option>
                            <option value="ended">منتهي</option>
                            <option value="scheduled">مجدول</option>
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
@endsection