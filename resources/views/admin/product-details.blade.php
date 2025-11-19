{{-- resources/views/admin/product-details.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'تفاصيل المنتج')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- رأس الصفحة -->
    <div class="mb-6">
        <a href="{{ route('admin.products') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            <i class="bi bi-arrow-right"></i> العودة إلى قائمة المنتجات
        </a>
        <h1 class="text-3xl font-bold text-gray-800">🛍️ تفاصيل المنتج</h1>
    </div>

    <!-- معلومات المنتج -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">معلومات المنتج</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">الاسم</p>
                <p class="text-lg font-semibold">{{ $product->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">الفئة</p>
                <p class="text-lg font-semibold">{{ $product->category }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">الوصف</p>
                <p class="text-lg">{{ $product->description }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">البائع</p>
                <p class="text-lg font-semibold">{{ $product->seller->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">السعر الابتدائي</p>
                <p class="text-lg font-semibold text-green-600">{{ number_format($product->starting_price, 2) }} ر.س</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">السعر الأقصى</p>
                <p class="text-lg font-semibold text-red-600">{{ number_format($product->max_price, 2) }} ر.س</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">تاريخ الإنشاء</p>
                <p class="text-lg font-semibold">{{ $product->created_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>

        <!-- الصور -->
        @if($product->images && count($product->images) > 0)
        <div class="mt-6">
            <p class="text-sm text-gray-600 mb-2">الصور</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($product->images as $image)
                <img src="{{ asset('storage/products/' . $image) }}" alt="{{ $product->name }}" class="w-full h-32 object-cover rounded-lg">
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- معلومات المزاد -->
    @if($product->auction)
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">معلومات المزاد</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">الحالة</p>
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    {{ $product->auction->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $product->auction->status === 'active' ? 'نشط' : 'منتهي' }}
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-600">المزايدة الحالية</p>
                <p class="text-lg font-semibold text-blue-600">{{ number_format($product->auction->current_bid, 2) }} ر.س</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">وقت البدء</p>
                <p class="text-lg font-semibold">{{ $product->auction->start_time->format('Y-m-d H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">وقت الانتهاء</p>
                <p class="text-lg font-semibold">{{ $product->auction->end_time->format('Y-m-d H:i') }}</p>
            </div>
            @if($product->auction->winner)
            <div>
                <p class="text-sm text-gray-600">الفائز</p>
                <p class="text-lg font-semibold">{{ $product->auction->winner->name }}</p>
            </div>
            @endif
            <div>
                <p class="text-sm text-gray-600">عدد المزايدات</p>
                <p class="text-lg font-semibold">{{ $product->auction->bids->count() }}</p>
            </div>
        </div>
    </div>

    <!-- تاريخ المزايدات -->
    @if($product->auction->bids->count() > 0)
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">تاريخ المزايدات ({{ $product->auction->bids->count() }})</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المزايد</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المبلغ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($product->auction->bids->sortByDesc('bid_amount') as $bid)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $bid->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold">{{ number_format($bid->bid_amount, 2) }} ر.س</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $bid->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    @else
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <p class="text-yellow-800">هذا المنتج لا يحتوي على مزاد نشط.</p>
    </div>
    @endif
</div>
@endsection

