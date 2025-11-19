{{-- resources/views/admin/users.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'إدارة المستخدمين')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- رأس الصفحة -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4 sm:mb-0">👥 إدارة المستخدمين</h1>
        <div>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors" 
                    onclick="document.getElementById('exportModal').classList.remove('hidden')">
                <i class="bi bi-download"></i> تصدير تقرير
            </button>
        </div>
    </div>

    <!-- بطاقات الإحصائيات -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- إجمالي المستخدمين -->
        <div class="bg-white rounded-lg shadow border-r-4 border-blue-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">إجمالي المستخدمين</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $users->total() }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-full">
                    <i class="bi bi-people text-blue-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- البائعون -->
        <div class="bg-white rounded-lg shadow border-r-4 border-green-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">البائعون</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $users->where('role', 'seller')->count() }}
                    </p>
                </div>
                <div class="p-3 bg-green-50 rounded-full">
                    <i class="bi bi-shop text-green-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- المشترون -->
        <div class="bg-white rounded-lg shadow border-r-4 border-cyan-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">المشترون</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $users->where('role', 'buyer')->count() }}
                    </p>
                </div>
                <div class="p-3 bg-cyan-50 rounded-full">
                    <i class="bi bi-cart text-cyan-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- المستخدمون النشطون -->
        <div class="bg-white rounded-lg shadow border-r-4 border-yellow-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">المستخدمون النشطون</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $users->where('is_active', true)->count() }}
                    </p>
                </div>
                <div class="p-3 bg-yellow-50 rounded-full">
                    <i class="bi bi-check-circle text-yellow-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- شريط البحث والفلترة -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="border-b border-gray-200 px-6 py-4">
            <h6 class="font-bold text-gray-900">🔍 البحث والفلترة</h6>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.users') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- البحث -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">البحث</label>
                    <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           id="search" name="search" value="{{ request('search') }}" 
                           placeholder="ابحث بالاسم، البريد، أو الهاتف...">
                </div>

                <!-- الدور -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">الدور</label>
                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                            id="role" name="role">
                        <option value="">جميع الأدوار</option>
                        <option value="seller" {{ request('role') == 'seller' ? 'selected' : '' }}>بائع</option>
                        <option value="buyer" {{ request('role') == 'buyer' ? 'selected' : '' }}>مشتري</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>مسؤول</option>
                    </select>
                </div>

                <!-- الحالة -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                            id="status" name="status">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>معطل</option>
                    </select>
                </div>

                <!-- الترتيب -->
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">الترتيب</label>
                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                            id="sort" name="sort">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>الأقدم</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم</option>
                        <option value="most_products" {{ request('sort') == 'most_products' ? 'selected' : '' }}>أكثر منتجات</option>
                        <option value="most_bids" {{ request('sort') == 'most_bids' ? 'selected' : '' }}>أكثر مزايدات</option>
                    </select>
                </div>

                <!-- الأزرار -->
                <div class="md:col-span-4 flex gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <i class="bi bi-search"></i> بحث
                    </button>
                    <a href="{{ route('admin.users') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <i class="bi bi-arrow-clockwise"></i> إعادة تعيين
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- جدول المستخدمين -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h6 class="font-bold text-gray-900">قائمة المستخدمين</h6>
            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                إجمالي: {{ $users->total() }}
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدم</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">معلومات الاتصال</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الدور</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإحصائيات</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التسجيل</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <!-- المستخدم -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="rounded-full bg-blue-500 text-white flex items-center justify-center ml-3" 
                                     style="width: 40px; height: 40px; font-size: 16px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">{{ $user->name }}</div>
                                    <div class="text-gray-500 text-sm">ID: {{ $user->id }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- معلومات الاتصال -->
                        <td class="px-6 py-4">
                            <div class="text-gray-900">{{ $user->email }}</div>
                            @if($user->phone)
                                <div class="text-gray-500 text-sm">{{ $user->phone }}</div>
                            @endif
                            @if($user->address)
                                <div class="text-gray-500 text-sm">{{ Str::limit($user->address, 30) }}</div>
                            @endif
                        </td>

                        <!-- الدور -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->role == 'seller')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="bi bi-shop ml-1"></i> بائع
                                </span>
                            @elseif($user->role == 'buyer')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-cyan-100 text-cyan-800">
                                    <i class="bi bi-cart ml-1"></i> مشتري
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="bi bi-shield-check ml-1"></i> مسؤول
                                </span>
                            @endif
                        </td>

                        <!-- الإحصائيات -->
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                @if($user->role == 'seller')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        منتجات: {{ $user->products_count }}
                                    </span>
                                @endif
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    مزايدات: {{ $user->bids_count }}
                                </span>
                                @if($user->won_auctions_count > 0)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                        فوزات: {{ $user->won_auctions_count }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- الحالة -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="bi bi-check-circle ml-1"></i> نشط
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="bi bi-x-circle ml-1"></i> معطل
                                </span>
                            @endif
                        </td>

                        <!-- التسجيل -->
                        <td class="px-6 py-4">
                            <div class="text-gray-900 text-sm">
                                {{ $user->created_at->format('Y-m-d') }}
                            </div>
                            <div class="text-gray-500 text-xs">
                                {{ $user->created_at->diffForHumans() }}
                            </div>
                        </td>

                        <!-- الإجراءات -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.user-details', $user) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors" 
                                   title="عرض التفاصيل">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" 
                                            class="{{ $user->is_active ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} text-white p-2 rounded-lg transition-colors" 
                                            title="{{ $user->is_active ? 'تعطيل' : 'تفعيل' }}">
                                        <i class="bi bi-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                                    </button>
                                </form>

                                @if($user->role != 'admin')
                                <button class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors" 
                                        onclick="confirmDelete('{{ $user->name }}', '{{ route('admin.users.delete', $user) }}')"
                                        title="حذف المستخدم">
                                    <i class="bi bi-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center">
                            <div class="text-gray-500">
                                <i class="bi bi-people text-4xl block mb-2"></i>
                                لا توجد مستخدمين
                            </div>
                            @if(request()->anyFilled(['search', 'role', 'status']))
                                <a href="{{ route('admin.users') }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                    عرض جميع المستخدمين
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- التصفح -->
        @if($users->hasPages())
        <div class="border-t border-gray-200 px-6 py-4">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

<!-- نافذة تأكيد الحذف -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">تأكيد الحذف</h3>
                <button onclick="document.getElementById('deleteModal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-gray-600">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="mb-6">
                <p>هل أنت متأكد من أنك تريد حذف المستخدم "<span id="userName" class="font-bold"></span>"؟</p>
                <p class="text-red-600 text-sm mt-2">هذا الإجراء لا يمكن التراجع عنه.</p>
            </div>
            <div class="flex justify-end gap-2">
                <button onclick="document.getElementById('deleteModal').classList.add('hidden')" 
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    إلغاء
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                        حذف
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- نافذة تصدير التقرير -->
<div id="exportModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">📊 تصدير تقرير المستخدمين</h3>
                <button onclick="document.getElementById('exportModal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-gray-600">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form action="{{ route('admin.export-report') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="users">
                <div class="space-y-4 mb-6">
                    <div>
                        <label for="format" class="block text-sm font-medium text-gray-700 mb-1">صيغة التقرير</label>
                        <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                id="format" name="format" required>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">من تاريخ</label>
                            <input type="date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   id="start_date" name="start_date">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">إلى تاريخ</label>
                            <input type="date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   id="end_date" name="end_date">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('exportModal').classList.add('hidden')" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                        إلغاء
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        تصدير التقرير
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete(userName, deleteUrl) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('deleteForm').action = deleteUrl;
    document.getElementById('deleteModal').classList.remove('hidden');
}

// إغلاق النوافذ عند النقر خارجها
document.addEventListener('click', function(event) {
    const deleteModal = document.getElementById('deleteModal');
    const exportModal = document.getElementById('exportModal');
    
    if (event.target === deleteModal) {
        deleteModal.classList.add('hidden');
    }
    if (event.target === exportModal) {
        exportModal.classList.add('hidden');
    }
});
</script>
@endsection