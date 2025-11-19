{{-- resources/views/admin/auction-details.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'تفاصيل المزاد')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- رأس الصفحة -->
    <div class="mb-6">
        <a href="{{ route('admin.auctions') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            <i class="bi bi-arrow-right"></i> العودة إلى قائمة المزادات
        </a>
        <h1 class="text-3xl font-bold text-gray-800">🔨 تفاصيل المزاد</h1>
    </div>

    <!-- معلومات المنتج -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">معلومات المنتج</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">اسم المنتج</p>
                <p class="text-lg font-semibold">{{ $auction->product->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">الفئة</p>
                <p class="text-lg font-semibold">{{ $auction->product->category }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">البائع</p>
                <p class="text-lg font-semibold">{{ $auction->product->seller->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">السعر الابتدائي</p>
                <p class="text-lg font-semibold text-green-600">{{ number_format($auction->product->starting_price, 2) }} ر.س</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">السعر الأقصى</p>
                <p class="text-lg font-semibold text-red-600">{{ number_format($auction->product->max_price, 2) }} ر.س</p>
            </div>
        </div>
    </div>

    <!-- معلومات المزاد -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">معلومات المزاد</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">الحالة</p>
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    {{ $auction->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $auction->status === 'active' ? 'نشط' : 'منتهي' }}
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-600">المزايدة الحالية</p>
                <p class="text-lg font-semibold text-blue-600">{{ number_format($auction->current_bid, 2) }} ر.س</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">وقت البدء</p>
                <p class="text-lg font-semibold">{{ $auction->start_time->format('Y-m-d H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">وقت الانتهاء</p>
                <p class="text-lg font-semibold">{{ $auction->end_time->format('Y-m-d H:i') }}</p>
            </div>
            @if($auction->winner)
            <div>
                <p class="text-sm text-gray-600">الفائز</p>
                <p class="text-lg font-semibold text-green-600">{{ $auction->winner->name }}</p>
            </div>
            @endif
            <div>
                <p class="text-sm text-gray-600">عدد المزايدات</p>
                <p class="text-lg font-semibold">{{ $auction->bids->count() }}</p>
            </div>
        </div>
    </div>

    <!-- تاريخ المزايدات -->
    @if($auction->bids->count() > 0)
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">تاريخ المزايدات ({{ $auction->bids->count() }})</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المزايد</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المبلغ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($auction->bids->sortByDesc('bid_amount') as $index => $bid)
                    <tr class="{{ $bid->user_id === $auction->winner_id ? 'bg-green-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $bid->user->name }}
                            @if($bid->user_id === $auction->winner_id)
                                <span class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">الفائز</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold">{{ number_format($bid->bid_amount, 2) }} ر.س</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $bid->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <p class="text-yellow-800">لا توجد مزايدات على هذا المزاد حتى الآن.</p>
    </div>
    @endif
</div>
@endsection

