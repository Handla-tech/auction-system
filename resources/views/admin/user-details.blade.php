{{-- resources/views/admin/user-details.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'تفاصيل المستخدم')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- رأس الصفحة -->
    <div class="mb-6">
        <a href="{{ route('admin.users') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            <i class="bi bi-arrow-right"></i> العودة إلى قائمة المستخدمين
        </a>
        <h1 class="text-3xl font-bold text-gray-800">👤 تفاصيل المستخدم</h1>
    </div>

    <!-- معلومات المستخدم الأساسية -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">المعلومات الأساسية</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">الاسم</p>
                <p class="text-lg font-semibold">{{ $user->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">البريد الإلكتروني</p>
                <p class="text-lg font-semibold">{{ $user->email }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">الهاتف</p>
                <p class="text-lg font-semibold">{{ $user->phone ?? 'غير محدد' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">الدور</p>
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : ($user->role === 'seller' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                    {{ $user->role === 'admin' ? 'مسؤول' : ($user->role === 'seller' ? 'بائع' : 'مشتري') }}
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-600">العنوان</p>
                <p class="text-lg font-semibold">{{ $user->address ?? 'غير محدد' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">تاريخ التسجيل</p>
                <p class="text-lg font-semibold">{{ $user->created_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- إحصائيات المستخدم -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">المنتجات</p>
                    <p class="text-2xl font-bold">{{ $user->products->count() }}</p>
                </div>
                <i class="bi bi-box text-3xl text-blue-500"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">المزايدات</p>
                    <p class="text-2xl font-bold">{{ $user->bids->count() }}</p>
                </div>
                <i class="bi bi-hammer text-3xl text-green-500"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">المزادات الفائزة</p>
                    <p class="text-2xl font-bold">{{ $user->wonAuctions->count() }}</p>
                </div>
                <i class="bi bi-trophy text-3xl text-yellow-500"></i>
            </div>
        </div>
    </div>

    <!-- المنتجات -->
    @if($user->role === 'seller' && $user->products->count() > 0)
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">المنتجات ({{ $user->products->count() }})</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الاسم</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الفئة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">السعر الابتدائي</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($user->products->take(10) as $product)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->category }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($product->starting_price, 2) }} ر.س</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->auction)
                                <span class="px-2 py-1 text-xs rounded-full {{ $product->auction->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $product->auction->status === 'active' ? 'نشط' : 'منتهي' }}
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">بدون مزاد</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- المزايدات -->
    @if($user->role === 'buyer' && $user->bids->count() > 0)
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">المزايدات الأخيرة ({{ $user->bids->count() }})</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المنتج</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المبلغ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($user->bids->take(10) as $bid)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $bid->auction->product->name ?? 'غير محدد' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($bid->bid_amount, 2) }} ر.س</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $bid->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection

