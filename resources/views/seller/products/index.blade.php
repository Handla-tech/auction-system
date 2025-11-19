{{-- resources/views/seller/products/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'إدارة المنتجات')

@section('content')
<div class="space-y-6">
    <!-- رأس الصفحة -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center space-x-2 space-x-reverse">
                <i class="bi bi-box-seam text-blue-500"></i>
                <span>منتجاتي</span>
            </h1>
            <p class="text-gray-600 mt-2">إدارة جميع منتجاتك وعرض حالتها</p>
        </div>
        <a href="{{ route('seller.products.create') }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center space-x-2 space-x-reverse">
            <i class="bi bi-plus-circle"></i>
            <span>إضافة منتج جديد</span>
        </a>
    </div>

    <!-- رسائل النجاح -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 space-x-reverse">
                    <i class="bi bi-check-circle-fill text-green-500 text-lg"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-green-500 hover:text-green-700">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- شبكة المنتجات -->
    @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
            <div class="bg-white rounded-lg shadow-custom border border-gray-200 hover:shadow-lg transition-all duration-200 hover-scale">
                <div class="p-6">
                    <!-- رأس البطاقة -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1 line-clamp-1">{{ $product->name }}</h3>
                            <div class="flex items-center space-x-2 space-x-reverse mb-2">
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                    {{ $product->category }}
                                </span>
                                <!-- حالة المزاد -->
                                @if($product->auction)
                                    @if($product->auction->status === 'active')
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full flex items-center space-x-1 space-x-reverse">
                                            <i class="bi bi-circle-fill text-green-500 text-xs"></i>
                                            <span>نشط</span>
                                        </span>
                                    @elseif($product->auction->status === 'ended')
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full flex items-center space-x-1 space-x-reverse">
                                            <i class="bi bi-circle-fill text-red-500 text-xs"></i>
                                            <span>منتهي</span>
                                        </span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full flex items-center space-x-1 space-x-reverse">
                                            <i class="bi bi-circle-fill text-yellow-500 text-xs"></i>
                                            <span>معلق</span>
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </div>
                        
                        <!-- صورة المنتج -->
                        <div class="ml-4 flex-shrink-0">
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ $product->getFirstImageUrl() }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-16 h-16 rounded-lg object-cover border border-gray-200"
                                     onerror="this.style.display='none'; document.getElementById('fallback-{{ $product->id }}').style.display='flex'; console.log('Failed to load image: {{ $product->images[0] }}')">
                                <div id="fallback-{{ $product->id }}" class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center border border-red-200 hidden">
                                    <i class="bi bi-exclamation-triangle text-red-500 text-sm"></i>
                                </div>
                            @else
                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                    <i class="bi bi-image text-gray-400 text-xl"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- معلومات الأسعار -->
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">السعر الابتدائي:</span>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($product->starting_price) }} ر.س</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">السعر الأقصى:</span>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($product->max_price) }} ر.س</span>
                        </div>
                        @if($product->auction && $product->auction->current_bid > $product->starting_price)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">أعلى مزايدة:</span>
                            <span class="text-sm font-semibold text-green-600">{{ number_format($product->auction->current_bid) }} ر.س</span>
                        </div>
                        @endif
                    </div>

                    <!-- معلومات إضافية -->
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                        <div class="flex items-center space-x-1 space-x-reverse">
                            <i class="bi bi-clock"></i>
                            <span>{{ $product->created_at->diffForHumans() }}</span>
                        </div>
                        @if($product->auction)
                        <div class="flex items-center space-x-1 space-x-reverse">
                            <i class="bi bi-gem"></i>
                            <span>{{ $product->auction->bids->count() }} مزايدة</span>
                        </div>
                        @endif
                    </div>

                    <!-- أزرار الإجراءات -->
                    <div class="flex items-center space-x-2 space-x-reverse pt-4 border-t border-gray-200">
                        <a href="{{ route('seller.products.edit', $product) }}" 
                           class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2 space-x-reverse text-sm font-medium">
                            <i class="bi bi-pencil text-sm"></i>
                            <span>تعديل</span>
                        </a>
                        <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('هل أنت متأكد من حذف المنتج \"{{ $product->name }}\"؟')"
                                    class="w-full bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2 space-x-reverse text-sm font-medium">
                                <i class="bi bi-trash text-sm"></i>
                                <span>حذف</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <!-- حالة عدم وجود منتجات -->
        <div class="bg-white rounded-lg shadow-custom border border-gray-200">
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-box text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد منتجات</h3>
                <p class="text-gray-500 mb-6">ابدأ بإضافة منتجك الأول للمزاد</p>
                <a href="{{ route('seller.products.create') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition-colors duration-200 inline-flex items-center space-x-2 space-x-reverse font-medium">
                    <i class="bi bi-plus-circle"></i>
                    <span>إضافة منتج جديد</span>
                </a>
            </div>
        </div>
    @endif

    <!-- إحصائيات بسيطة -->
    <div class="bg-white rounded-lg shadow-custom border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2 space-x-reverse">
            <i class="bi bi-graph-up text-blue-500"></i>
            <span>إحصائيات المنتجات</span>
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $products->count() }}</div>
                <div class="text-sm text-gray-600">إجمالي المنتجات</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">
                    {{ $products->where('auction.status', 'active')->count() }}
                </div>
                <div class="text-sm text-gray-600">مزادات نشطة</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-red-600">
                    {{ $products->where('auction.status', 'ended')->count() }}
                </div>
                <div class="text-sm text-gray-600">مزادات منتهية</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-yellow-600">
                    {{ $products->where('auction.status', 'pending')->count() }}
                </div>
                <div class="text-sm text-gray-600">مزادات معلقة</div>
            </div>
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
    
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    // تأكيد الحذف مع اسم المنتج
    function confirmDelete(productName, form) {
        if (confirm(`هل أنت متأكد من حذف المنتج "${productName}"؟`)) {
            form.submit();
        }
    }

    // فتح console لرؤية أخطاء تحميل الصور
    document.addEventListener('DOMContentLoaded', function() {
        console.log('=== Product Images Debug ===');
        @foreach($products as $product)
            @if($product->images && count($product->images) > 0)
                console.log('Product: {{ $product->name }}');
                console.log('First Image: {{ $product->images[0] }}');
                console.log('Full URL: {{ $product->getFirstImageUrl() }}');
            @endif
        @endforeach
    });
</script>
@endsection