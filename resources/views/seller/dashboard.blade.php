{{-- resources/views/seller/dashboard.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'لوحة تحكم البائع')

@section('content')
<div class="space-y-6">
    <!-- الإحصائيات -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- بطاقة المنتجات -->
        <div class="bg-white rounded-lg shadow-custom border-r-4 border-blue-500 hover-scale transition-all duration-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">المنتجات</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['total_products'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-box text-blue-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-xs text-gray-500">إجمالي المنتجات المضافة</span>
                </div>
            </div>
        </div>

        <!-- بطاقة المزادات النشطة -->
        <div class="bg-white rounded-lg shadow-custom border-r-4 border-green-500 hover-scale transition-all duration-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">مزادات نشطة</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['active_auctions'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-clock text-green-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-xs text-gray-500">مزادات قيد التشغيل</span>
                </div>
            </div>
        </div>

        <!-- بطاقة المزادات المنتهية -->
        <div class="bg-white rounded-lg shadow-custom border-r-4 border-purple-500 hover-scale transition-all duration-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">مزادات منتهية</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['ended_auctions'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-check-circle text-purple-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-xs text-gray-500">مزادات تم الانتهاء منها</span>
                </div>
            </div>
        </div>

        <!-- بطاقة المزايدات -->
        <div class="bg-white rounded-lg shadow-custom border-r-4 border-yellow-500 hover-scale transition-all duration-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي المزايدات</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['total_bids'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-gem text-yellow-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-xs text-gray-500">عدد المزايدات الكلي</span>
                </div>
            </div>
        </div>
    </div>

    <!-- الإجراءات السريعة -->
    <div class="bg-white rounded-lg shadow-custom">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center space-x-2 space-x-reverse">
                <i class="bi bi-lightning text-blue-500"></i>
                <span>الإجراءات السريعة</span>
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- إدارة المنتجات -->
                <a href="{{ route('seller.products.index') }}" 
                   class="group bg-gradient-to-l from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4 hover:from-blue-100 hover:to-blue-200 transition-all duration-200 hover-scale">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center group-hover:bg-blue-600 transition-colors">
                            <i class="bi bi-box text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 group-hover:text-blue-700">إدارة المنتجات</h3>
                            <p class="text-sm text-gray-600 mt-1">عرض وتعديل منتجاتك</p>
                        </div>
                    </div>
                </a>

                <!-- إضافة منتج جديد -->
                <a href="{{ route('seller.products.create') }}" 
                   class="group bg-gradient-to-l from-green-50 to-green-100 border border-green-200 rounded-lg p-4 hover:from-green-100 hover:to-green-200 transition-all duration-200 hover-scale">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center group-hover:bg-green-600 transition-colors">
                            <i class="bi bi-plus-circle text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 group-hover:text-green-700">إضافة منتج جديد</h3>
                            <p class="text-sm text-gray-600 mt-1">بدء مزاد جديد</p>
                        </div>
                    </div>
                </a>

                <!-- مزاداتي -->
                <a href="#" 
                   class="group bg-gradient-to-l from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-4 hover:from-purple-100 hover:to-purple-200 transition-all duration-200 hover-scale">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center group-hover:bg-purple-600 transition-colors">
                            <i class="bi bi-clock text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 group-hover:text-purple-700">مزاداتي</h3>
                            <p class="text-sm text-gray-600 mt-1">عرض جميع مزاداتي</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- آخر المنتجات -->
    <div class="bg-white rounded-lg shadow-custom">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center space-x-2 space-x-reverse">
                <i class="bi bi-box-seam text-blue-500"></i>
                <span>آخر المنتجات</span>
            </h2>
            <a href="{{ route('seller.products.index') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center space-x-2 space-x-reverse text-sm">
                <span>عرض الكل</span>
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
        <div class="p-6">
            @if(isset($recent_products) && $recent_products->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم المنتج</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الفئة</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السعر الابتدائي</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recent_products as $product)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($product->images && count($product->images) > 0)
                                            <img src="{{ $product->getFirstImageUrl() }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="w-10 h-10 rounded-lg object-cover ml-3">
                                        @else
                                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center ml-3">
                                                <i class="bi bi-image text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600">{{ $product->category }}</span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ number_format($product->starting_price) }} ر.س</span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($product->auction && $product->auction->status === 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="bi bi-circle-fill text-green-500 ml-1 text-xs"></i>
                                            نشط
                                        </span>
                                    @elseif($product->auction && $product->auction->status === 'ended')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="bi bi-circle-fill text-red-500 ml-1 text-xs"></i>
                                            منتهي
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="bi bi-circle-fill text-yellow-500 ml-1 text-xs"></i>
                                            معلق
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('seller.products.edit', $product) }}" 
                                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg transition-colors duration-200 inline-flex items-center space-x-1 space-x-reverse text-xs">
                                        <i class="bi bi-pencil text-xs"></i>
                                        <span>تعديل</span>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-box text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد منتجات</h3>
                    <p class="text-gray-500 mb-6">ابدأ بإضافة منتجك الأول للمزاد</p>
                    <a href="{{ route('seller.products.create') }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition-colors duration-200 inline-flex items-center space-x-2 space-x-reverse">
                        <i class="bi bi-plus-circle"></i>
                        <span>إضافة منتج جديد</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .hover-scale:hover {
        transform: translateY(-2px);
        transition: all 0.2s ease-in-out;
    }
    
    .shadow-custom {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
</style>
@endsection 