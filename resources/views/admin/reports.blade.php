{{-- resources/views/admin/reports.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'التقارير والإحصائيات')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- رأس الصفحة -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">
            📊 التقارير والإحصائيات
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            نظرة شاملة على أداء النظام وإحصائيات المبيعات
        </p>
    </div>

    <!-- الإحصائيات الرئيسية -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- المبيعات اليومية -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">💰 المبيعات اليوم</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ number_format($salesStats['daily_sales'], 0) }} ر.س
                    </p>
                    <div class="mt-2 text-xs">
                        <span class="text-green-600 mr-2">
                            {{ $todayAuctions = \App\Models\Auction::ended()->whereDate('end_time', today())->count() }} مزاد
                        </span>
                        <span class="text-gray-500">{{ $todayBids = \App\Models\Bid::whereDate('created_at', today())->count() }} مزايدة</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-currency-dollar text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- المبيعات الأسبوعية -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">📈 المبيعات الأسبوعية</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ number_format($salesStats['weekly_sales'], 0) }} ر.س
                    </p>
                    <div class="mt-2 text-xs">
                        <span class="text-blue-600 mr-2">
                            {{ $weeklyAuctions = \App\Models\Auction::ended()->whereBetween('end_time', [now()->startOfWeek(), now()->endOfWeek()])->count() }} مزاد
                        </span>
                        <span class="text-gray-500">هذا الأسبوع</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-graph-up text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- المبيعات الشهرية -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">📅 المبيعات الشهرية</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ number_format($salesStats['monthly_sales'], 0) }} ر.س
                    </p>
                    <div class="mt-2 text-xs">
                        <span class="text-purple-600 mr-2">
                            {{ $monthlyAuctions = \App\Models\Auction::ended()->whereBetween('end_time', [now()->startOfMonth(), now()->endOfMonth()])->count() }} مزاد
                        </span>
                        <span class="text-gray-500">هذا الشهر</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-calendar-check text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- إجمالي المبيعات -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">💎 إجمالي المبيعات</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ number_format($salesStats['total_sales'], 0) }} ر.س
                    </p>
                    <div class="mt-2 text-xs">
                        <span class="text-yellow-600 mr-2">
                            {{ $totalAuctions = \App\Models\Auction::ended()->count() }} مزاد منتهي
                        </span>
                        <span class="text-gray-500">منذ البداية</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-trophy text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- الإجراءات السريعة -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">🚀 الإجراءات السريعة</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <button onclick="openExportModal('users')" class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl p-4 text-center transition-all duration-200 transform hover:scale-105">
                <i class="bi bi-people text-2xl mb-2 block"></i>
                <div class="font-medium">المستخدمين</div>
                <div class="text-blue-200 text-sm">تقرير</div>
            </button>
            
            <button onclick="openExportModal('products')" class="bg-green-600 hover:bg-green-700 text-white rounded-xl p-4 text-center transition-all duration-200 transform hover:scale-105">
                <i class="bi bi-box text-2xl mb-2 block"></i>
                <div class="font-medium">المنتجات</div>
                <div class="text-green-200 text-sm">تقرير</div>
            </button>
            
            <button onclick="openExportModal('auctions')" class="bg-purple-600 hover:bg-purple-700 text-white rounded-xl p-4 text-center transition-all duration-200 transform hover:scale-105">
                <i class="bi bi-clock text-2xl mb-2 block"></i>
                <div class="font-medium">المزادات</div>
                <div class="text-purple-200 text-sm">تقرير</div>
            </button>
            
            <button onclick="openExportModal('sales')" class="bg-yellow-600 hover:bg-yellow-700 text-white rounded-xl p-4 text-center transition-all duration-200 transform hover:scale-105">
                <i class="bi bi-currency-dollar text-2xl mb-2 block"></i>
                <div class="font-medium">المبيعات</div>
                <div class="text-yellow-200 text-sm">تقرير</div>
            </button>
            
            <button onclick="openCustomReportModal()" class="bg-red-600 hover:bg-red-700 text-white rounded-xl p-4 text-center transition-all duration-200 transform hover:scale-105">
                <i class="bi bi-graph-up text-2xl mb-2 block"></i>
                <div class="font-medium">تقرير مخصص</div>
                <div class="text-red-200 text-sm">متقدم</div>
            </button>
            
            <button onclick="openAnalyticsModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl p-4 text-center transition-all duration-200 transform hover:scale-105">
                <i class="bi bi-bar-chart text-2xl mb-2 block"></i>
                <div class="font-medium">التحليلات</div>
                <div class="text-indigo-200 text-sm">تفصيلية</div>
            </button>
        </div>
    </div>

    <!-- المحتوى الرئيسي -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- المزادات الأكثر ربحاً -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">💎 المزادات الأكثر ربحاً</h3>
                <span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-3 py-1 rounded-full text-sm font-medium">
                    أعلى 10
                </span>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($topAuctions as $auction)
                    <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <div class="w-10 h-10 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                                {{ $loop->iteration }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-800 dark:text-white">{{ Str::limit($auction->product->name, 20) }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $auction->product->seller->name }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-green-600 dark:text-green-400">
                                {{ number_format($auction->current_bid, 0) }} ر.س
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $auction->bids_count }} مزايدة
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="bi bi-trophy text-4xl mb-2 block"></i>
                        لا توجد مزادات منتهية
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- البائعون الأكثر نشاطاً -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">🏆 البائعون الأكثر نشاطاً</h3>
                <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-medium">
                    أعلى 10
                </span>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($topSellers as $seller)
                    <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr($seller->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-800 dark:text-white">{{ $seller->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $seller->email }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="flex gap-2">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    {{ $seller->products_count }} منتج
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $seller->auctions_count }} مزاد
                                </span>
                            </div>
                            <div class="text-sm font-bold text-gray-600 dark:text-gray-400 mt-1">
                                {{ number_format($seller->products_sum_starting_price ?? 0, 0) }} ر.س
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="bi bi-shop text-4xl mb-2 block"></i>
                        لا توجد بيانات للبائعين
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- الصف الثاني -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- المشترون الأكثر نشاطاً -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">🎯 المشترون الأكثر نشاطاً</h3>
                <span class="bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 px-3 py-1 rounded-full text-sm font-medium">
                    أعلى 10
                </span>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($topBuyers as $buyer)
                    <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr($buyer->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-800 dark:text-white">{{ $buyer->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $buyer->email }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="flex gap-2">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    {{ $buyer->bids_count }} مزايدة
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    {{ $buyer->won_auctions_count }} فوز
                                </span>
                            </div>
                            <div class="text-sm font-bold text-green-600 dark:text-green-400 mt-1">
                                {{ number_format($buyer->won_auctions_sum_current_bid ?? 0, 0) }} ر.س
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="bi bi-cart text-4xl mb-2 block"></i>
                        لا توجد بيانات للمشترين
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- إحصائيات الفئات -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">📦 توزيع الفئات</h3>
                <span class="bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $categoryStats->count() }} فئة
                </span>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($categoryStats as $category)
                    <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                                {{ $loop->iteration }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-800 dark:text-white">{{ $category->category }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $category->count }} منتج</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" 
                                     style="width: {{ ($category->count / max($categoryStats->max('count'), 1)) * 100 }}%">
                                </div>
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ number_format(($category->count / max($categoryStats->sum('count'), 1)) * 100, 1) }}%
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="bi bi-tags text-4xl mb-2 block"></i>
                        لا توجد فئات
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- نافذة تصدير التقرير -->
<div id="exportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white" id="exportModalTitle">📊 تصدير تقرير</h3>
                <button onclick="closeExportModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            <form action="{{ route('admin.export-report') }}" method="POST">
                @csrf
                <input type="hidden" name="type" id="exportType" value="">
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
                    <div id="customFields" class="hidden">
                        <!-- حقول إضافية للتقرير المخصص -->
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
function openExportModal(type) {
    const modal = document.getElementById('exportModal');
    const title = document.getElementById('exportModalTitle');
    const typeInput = document.getElementById('exportType');
    
    const titles = {
        'users': '📊 تصدير تقرير المستخدمين',
        'products': '📊 تصدير تقرير المنتجات',
        'auctions': '📊 تصدير تقرير المزادات',
        'sales': '📊 تصدير تقرير المبيعات'
    };
    
    title.textContent = titles[type] || '📊 تصدير تقرير';
    typeInput.value = type;
    modal.classList.remove('hidden');
}

function closeExportModal() {
    document.getElementById('exportModal').classList.add('hidden');
}

function openCustomReportModal() {
    // يمكن إضافة منطق للتقرير المخصص المتقدم
    alert('سيتم تطوير هذه الميزة قريباً!');
}

function openAnalyticsModal() {
    // يمكن إضافة منطق للتحليلات التفصيلية
    alert('سيتم تطوير هذه الميزة قريباً!');
}

// إغلاق النافذة عند النقر خارجها
document.getElementById('exportModal').addEventListener('click', function(e) {
    if (e.target === this) closeExportModal();
});
</script>
@endsection