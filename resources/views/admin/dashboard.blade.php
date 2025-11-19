{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'لوحة تحكم المسؤول')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- رأس الصفحة -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">
            📊 لوحة تحكم المسؤول
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            نظرة عامة على إحصائيات وأداء النظام
        </p>
    </div>

    <!-- الإحصائيات الرئيسية -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- المستخدمين -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">👥 إجمالي المستخدمين</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_users'] }}</p>
                    <div class="mt-2 text-xs">
                        <span class="text-green-600 mr-2">
                            +{{ $stats['new_users_today'] }} اليوم
                        </span>
                        <span class="text-gray-500">{{ $stats['active_users'] }} نشط</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-people text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- المنتجات -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">🛍️ المنتجات</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_products'] }}</p>
                    <div class="mt-2 text-xs">
                        <span class="text-blue-600 mr-2">{{ $stats['sellers'] }} بائع</span>
                        <span class="text-gray-500">{{ $stats['products_without_bids'] }} بدون مزايدات</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-box text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- المزادات -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">🔥 المزادات</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['active_auctions'] }}</p>
                    <div class="mt-2 text-xs">
                        <span class="text-yellow-600 mr-2">{{ $stats['ended_auctions'] }} منتهي</span>
                        <span class="text-red-500">{{ $stats['auctions_ending_today'] }} ينتهي اليوم</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-clock text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- المبيعات -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">💰 إجمالي المبيعات</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($stats['total_revenue'], 0) }} ر.س</p>
                    <div class="mt-2 text-xs">
                        <span class="text-green-600 mr-2">{{ $stats['today_bids'] }} مزايدة اليوم</span>
                        <span class="text-gray-500">{{ number_format($stats['average_bid_amount'], 0) }} ر.س متوسط</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                    <i class="bi bi-currency-dollar text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- الإجراءات السريعة -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">🚀 الإجراءات السريعة</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <a href="{{ route('admin.users') }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl p-4 text-center transition-all duration-200 transform hover:scale-105">
                <i class="bi bi-people text-2xl mb-2 block"></i>
                <div class="font-medium">المستخدمين</div>
                <div class="text-blue-200 text-sm">{{ $stats['total_users'] }}</div>
            </a>
            
            <a href="{{ route('admin.products') }}" class="bg-green-600 hover:bg-green-700 text-white rounded-xl p-4 text-center transition-all duration-200 transform hover:scale-105">
                <i class="bi bi-box text-2xl mb-2 block"></i>
                <div class="font-medium">المنتجات</div>
                <div class="text-green-200 text-sm">{{ $stats['total_products'] }}</div>
            </a>
            
            <a href="{{ route('admin.auctions') }}" class="bg-purple-600 hover:bg-purple-700 text-white rounded-xl p-4 text-center transition-all duration-200 transform hover:scale-105">
                <i class="bi bi-clock text-2xl mb-2 block"></i>
                <div class="font-medium">المزادات</div>
                <div class="text-purple-200 text-sm">{{ $stats['active_auctions'] }}</div>
            </a>
            
            <a href="{{ route('admin.reports') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white rounded-xl p-4 text-center transition-all duration-200 transform hover:scale-105">
                <i class="bi bi-graph-up text-2xl mb-2 block"></i>
                <div class="font-medium">التقارير</div>
                <div class="text-yellow-200 text-sm">إحصائيات</div>
            </a>
            
            <button class="bg-red-600 hover:bg-red-700 text-white rounded-xl p-4 text-center transition-all duration-200 transform hover:scale-105" onclick="openNotificationModal()">
                <i class="bi bi-bell text-2xl mb-2 block"></i>
                <div class="font-medium">إشعارات</div>
                <div class="text-red-200 text-sm">جماعية</div>
            </button>
            
            <button class="bg-gray-600 hover:bg-gray-700 text-white rounded-xl p-4 text-center transition-all duration-200 transform hover:scale-105" onclick="openExportModal()">
                <i class="bi bi-download text-2xl mb-2 block"></i>
                <div class="font-medium">تصدير</div>
                <div class="text-gray-200 text-sm">تقارير</div>
            </button>
        </div>
    </div>

    <!-- المحتوى الرئيسي -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- المستخدمون الجدد -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">👥 المستخدمون الجدد</h3>
                <a href="{{ route('admin.users') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">عرض الكل</a>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentUsers as $user)
                    <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-800 dark:text-white">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-2 py-1 text-xs rounded-full 
                                {{ $user->role == 'seller' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                  ($user->role == 'buyer' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 
                                  'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200') }}">
                                {{ $user->role == 'seller' ? 'بائع' : ($user->role == 'buyer' ? 'مشتري' : 'مسؤول') }}
                            </span>
                            <div class="text-xs text-gray-500 mt-1">{{ $user->created_at->format('Y-m-d') }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="bi bi-people text-4xl mb-2 block"></i>
                        لا توجد مستخدمين حديثين
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- المزادات النشطة -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">🔥 المزادات الأكثر نشاطاً</h3>
                <a href="{{ route('admin.auctions') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">عرض الكل</a>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($mostActiveAuctions as $auction)
                    <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex-1">
                            <div class="font-medium text-gray-800 dark:text-white">{{ $auction->product->name }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $auction->product->seller->name }}</div>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 text-xs rounded-full">
                                {{ $auction->bids_count }} مزايدة
                            </span>
                            <div class="text-sm font-bold text-green-600 dark:text-green-400 mt-1">
                                {{ number_format($auction->current_bid, 0) }} ر.س
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="bi bi-clock text-4xl mb-2 block"></i>
                        لا توجد مزادات نشطة
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- الصف الثاني -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- المنتجات الجديدة -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">🛍️ المنتجات المضافة حديثاً</h3>
                <a href="{{ route('admin.products') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">عرض الكل</a>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentProducts as $product)
                    <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex-1">
                            <div class="font-medium text-gray-800 dark:text-white">{{ $product->name }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $product->seller->name }}</div>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-2 py-1 text-xs rounded-full 
                                {{ $product->auction && $product->auction->status == 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' }}">
                                {{ $product->auction && $product->auction->status == 'active' ? 'نشط' : 'غير نشط' }}
                            </span>
                            <div class="text-sm font-bold text-gray-600 dark:text-gray-400 mt-1">
                                {{ number_format($product->starting_price, 0) }} ر.س
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="bi bi-box text-4xl mb-2 block"></i>
                        لا توجد منتجات حديثة
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- المزايدات الأخيرة -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">💰 آخر المزايدات</h3>
                <a href="{{ route('admin.auctions') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">عرض الكل</a>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentBids as $bid)
                    <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex-1">
                            <div class="font-medium text-gray-800 dark:text-white">{{ $bid->user->name }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($bid->auction->product->name, 25) }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-bold text-green-600 dark:text-green-400">
                                {{ number_format($bid->bid_amount, 0) }} ر.س
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $bid->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="bi bi-hammer text-4xl mb-2 block"></i>
                        لا توجد مزايدات حديثة
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- نافذة الإشعارات الجماعية -->
<div id="notificationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">🔔 إرسال إشعار جماعي</h3>
                <button onclick="closeNotificationModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            <form action="{{ route('admin.send-bulk-notification') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">عنوان الإشعار</label>
                        <input type="text" id="title" name="title" required 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                               placeholder="أدخل عنوان الإشعار">
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نص الإشعار</label>
                        <textarea id="message" name="message" rows="4" required
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="أدخل نص الإشعار"></textarea>
                    </div>
                    <div>
                        <label for="target_users" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الفئة المستهدفة</label>
                        <select id="target_users" name="target_users" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="all">جميع المستخدمين</option>
                            <option value="sellers">البائعون فقط</option>
                            <option value="buyers">المشترون فقط</option>
                            <option value="specific">مستخدمين محددين</option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeNotificationModal()" 
                            class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg transition-colors">
                        إلغاء
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors">
                        إرسال الإشعارات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- نافذة تصدير التقارير -->
<div id="exportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">📊 تصدير تقرير</h3>
                <button onclick="closeExportModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            <form action="{{ route('admin.export-report') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نوع التقرير</label>
                        <select id="type" name="type" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="users">المستخدمين</option>
                            <option value="products">المنتجات</option>
                            <option value="auctions">المزادات</option>
                            <option value="sales">المبيعات</option>
                        </select>
                    </div>
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
function openNotificationModal() {
    document.getElementById('notificationModal').classList.remove('hidden');
}

function closeNotificationModal() {
    document.getElementById('notificationModal').classList.add('hidden');
}

function openExportModal() {
    document.getElementById('exportModal').classList.remove('hidden');
}

function closeExportModal() {
    document.getElementById('exportModal').classList.add('hidden');
}

// إغلاق النوافذ عند النقر خارجها
document.getElementById('notificationModal').addEventListener('click', function(e) {
    if (e.target === this) closeNotificationModal();
});

document.getElementById('exportModal').addEventListener('click', function(e) {
    if (e.target === this) closeExportModal();
});
</script>
@endsection