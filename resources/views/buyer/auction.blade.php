@extends('layouts.app')

@section('title', 'المزايدة على المنتج')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- رأس الصفحة -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">المزايدة على المنتج</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">شارك في المزايدة واربح المنتج بأفضل سعر</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- العمود الأيسر: معلومات المنتج -->
        <div class="lg:col-span-2">
            <!-- بطاقة المنتج -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- معرض الصور -->
                <div class="relative">
                    @if($product->images && count($product->images) > 0)
                    <div class="swiper product-gallery h-96">
                        <div class="swiper-wrapper">
                            @foreach($product->images as $image)
                            <div class="swiper-slide">
                                <img src="{{ $product->getImageUrl($image) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-96 object-cover">
                            </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                
                    @else
                        <div class="h-96 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <span class="text-gray-500 dark:text-gray-400">لا توجد صور</span>
                        </div>
                    @endif
                    
                    <!-- حالة المزاد -->
                    <div class="absolute top-4 left-4">
                        @if($auction->status === 'active')
                            <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                🔥 المزاد نشط
                            </span>
                        @else
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                ❌ المزاد منتهي
                            </span>
                        @endif
                    </div>
                </div>

                <!-- معلومات المنتج -->
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">
                        {{ $product->name }}
                    </h2>
                    
                    <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                        {{ $product->description }}
                    </p>

                    <!-- معلومات البائع -->
                    <div class="flex items-center justify-between py-4 border-t border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-sm">
                                    {{ substr($product->seller->name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">البائع</p>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $product->seller->name }}</p>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <p class="text-sm text-gray-600 dark:text-gray-400">التصنيف</p>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $product->category }}</p>
                        </div>
                    </div>

                    <!-- إحصائيات المزاد -->
                    <div class="grid grid-cols-3 gap-4 mt-6">
                        <div class="text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">السعر الابتدائي</p>
                            <p class="text-xl font-bold text-green-600 dark:text-green-400">
                                {{ number_format($product->starting_price, 2) }} ر.س
                            </p>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">السعر الحالي</p>
                            <p class="text-xl font-bold text-blue-600 dark:text-blue-400">
                                {{ number_format($currentBid, 2) }} ر.س
                            </p>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">السعر الأقصى</p>
                            <p class="text-xl font-bold text-red-600 dark:text-red-400">
                                {{ number_format($product->max_price, 2) }} ر.س
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- مؤقت المزاد -->
            <div class="mt-6 bg-gradient-to-r from-purple-500 to-blue-600 rounded-2xl p-6 text-white">
                <h3 class="text-lg font-bold mb-4 text-center">⏰ وقت المزاد المتبقي</h3>
                <div class="grid grid-cols-4 gap-4 text-center" id="auctionTimer">
                    <div>
                        <div class="text-3xl font-bold" id="days">00</div>
                        <div class="text-sm">أيام</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold" id="hours">00</div>
                        <div class="text-sm">ساعات</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold" id="minutes">00</div>
                        <div class="text-sm">دقائق</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold" id="seconds">00</div>
                        <div class="text-sm">ثواني</div>
                    </div>
                </div>
                @if($auction->status === 'active')
                    <p class="text-center mt-4 text-yellow-200" id="timerStatus">المزاد نشط - شارك الآن!</p>
                @else
                    <p class="text-center mt-4 text-red-200">انتهى المزاد</p>
                @endif
            </div>
        </div>

        <!-- العمود الأيمن: منطقة المزايدة -->
        <div class="space-y-6">
            <!-- نموذج المزايدة -->
            @if($auction->status === 'active' && Auth::user()->role === 'buyer')
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">تقديم مزايدة</h3>
                
                <form id="bidForm">
                    @csrf
                    <input type="hidden" name="auction_id" value="{{ $auction->id }}">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            المبلغ الحالي: <span class="font-bold text-blue-600">{{ number_format($currentBid, 2) }} ر.س</span>
                        </label>
                        
                        <div class="relative">
                            <input type="number" 
                                   name="bid_amount" 
                                   id="bid_amount"
                                   min="{{ $currentBid + 1 }}"
                                   max="{{ $product->max_price }}"
                                   step="1"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl 
                                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                          dark:bg-gray-700 dark:text-white text-lg font-bold text-center"
                                   placeholder="أدخل المبلغ"
                                   required>
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                ر.س
                            </div>
                        </div>
                        
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mt-2">
                            <span>أقل مزايدة: {{ number_format($currentBid + 1, 2) }} ر.س</span>
                            <span>الحد الأقصى: {{ number_format($product->max_price, 2) }} ر.س</span>
                        </div>
                    </div>

                    <!-- أزرار مزايدة سريعة -->
                    <div class="grid grid-cols-3 gap-2 mb-4">
                        @php
                            $quickBids = [$currentBid + 5, $currentBid + 10, $currentBid + 20];
                        @endphp
                        @foreach($quickBids as $quickBid)
                            @if($quickBid <= $product->max_price)
                                <button type="button" 
                                        class="quick-bid-btn bg-gray-100 dark:bg-gray-700 hover:bg-blue-100 dark:hover:bg-blue-900 
                                               border border-gray-300 dark:border-gray-600 rounded-lg py-2 px-3 text-sm font-medium
                                               transition-colors duration-200"
                                        data-amount="{{ $quickBid }}">
                                    +{{ $quickBid - $currentBid }} ر.س
                                </button>
                            @endif
                        @endforeach
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 
                                   text-white py-3 px-4 rounded-xl font-bold text-lg transition-all duration-200 
                                   transform hover:scale-105 shadow-lg">
                        🎯 تقديم المزايدة
                    </button>
                </form>

                <!-- رسائل النتيجة -->
                <div id="bidResult" class="mt-4 hidden"></div>
            </div>
            @elseif($auction->status !== 'active')
            <div class="bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 rounded-2xl p-6 text-center">
                <div class="text-4xl mb-3">⏰</div>
                <h3 class="text-xl font-bold text-red-800 dark:text-red-200 mb-2">انتهى المزاد</h3>
                <p class="text-red-600 dark:text-red-300">لم يعد هذا المزاد نشطاً.</p>
            </div>
            @elseif(Auth::user()->role !== 'buyer')
            <div class="bg-yellow-100 dark:bg-yellow-900 border border-yellow-300 dark:border-yellow-700 rounded-2xl p-6 text-center">
                <div class="text-4xl mb-3">⚠️</div>
                <h3 class="text-xl font-bold text-yellow-800 dark:text-yellow-200 mb-2">غير مسموح</h3>
                <p class="text-yellow-600 dark:text-yellow-300">يجب أن تكون مشترياً للمشاركة في المزايدة.</p>
            </div>
            @endif

            <!-- تاريخ المزايدات -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">تاريخ المزايدات</h3>
                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm">
                        {{ $bids->count() }} مزايدة
                    </span>
                </div>

                <div class="space-y-3 max-h-96 overflow-y-auto" id="bidsList">
                    @forelse($bids as $bid)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg 
                               {{ $bid->user_id === Auth::id() ? 'border-2 border-green-200 dark:border-green-800' : '' }}">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                {{ substr($bid->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $bid->user->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $bid->bid_time->format('H:i - Y/m/d') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-lg text-green-600 dark:text-green-400">
                                {{ number_format($bid->bid_amount, 2) }} ر.س
                            </p>
                            @if($bid->user_id === Auth::id())
                            <p class="text-xs text-green-500">مزايدتك</p>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <div class="text-4xl mb-2">🤷‍♂️</div>
                        <p>لا توجد مزايدات حتى الآن</p>
                        <p class="text-sm">كن أول المزايدين!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Swiper JS -->
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<style>
    .swiper {
        width: 100%;
        height: 100%;
    }
    .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .swiper-slide img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<script>
    // تهيئة معرض الصور
    document.addEventListener('DOMContentLoaded', function() {
        // Swiper Gallery
        if (document.querySelector('.product-gallery')) {
            new Swiper('.product-gallery', {
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        }

        // مؤقت المزاد
        function updateTimer() {
            const endTime = new Date('{{ $auction->end_time }}').getTime();
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance < 0) {
                document.getElementById('timerStatus').textContent = 'انتهى المزاد';
                document.getElementById('timerStatus').className = 'text-center mt-4 text-red-200';
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('days').textContent = days.toString().padStart(2, '0');
            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
        }

        setInterval(updateTimer, 1000);
        updateTimer();

        // أزرار المزايدة السريعة
        document.querySelectorAll('.quick-bid-btn').forEach(button => {
            button.addEventListener('click', function() {
                const amount = this.getAttribute('data-amount');
                document.getElementById('bid_amount').value = amount;
            });
        });

        // نموذج المزايدة
        document.getElementById('bidForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            // تعطيل الزر أثناء الإرسال
            submitBtn.disabled = true;
            submitBtn.textContent = 'جاري تقديم المزايدة...';
            submitBtn.classList.remove('hover:scale-105');

            fetch('{{ route("bids.store", $auction->id) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                const resultDiv = document.getElementById('bidResult');
                resultDiv.className = 'mt-4 p-4 rounded-lg';
                
                if (data.success) {
                    resultDiv.className += ' bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 border border-green-300 dark:border-green-700';
                    resultDiv.innerHTML = `<strong>✅ نجح!</strong> ${data.success}`;
                    
                    // تحديث السعر الحالي
                    document.querySelector('span.font-bold.text-blue-600').textContent = data.new_current_bid + ' ر.س';
                    document.getElementById('bid_amount').min = data.new_current_bid + 1;
                    
                    // إضافة المزايدة الجديدة للقائمة
                    const bidsList = document.getElementById('bidsList');
                    const newBid = document.createElement('div');
                    newBid.className = 'flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border-2 border-green-200 dark:border-green-800';
                    newBid.innerHTML = `
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 dark:text-white">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">الآن</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-lg text-green-600 dark:text-green-400">
                                ${data.bid.bid_amount} ر.س
                            </p>
                            <p class="text-xs text-green-500">مزايدتك</p>
                        </div>
                    `;
                    bidsList.prepend(newBid);

                    // إذا انتهى المزاد
                    if (data.auction_ended) {
                        document.querySelector('.bg-gradient-to-r.from-purple-500.to-blue-600 .text-center').innerHTML = 
                            '<p class="text-2xl font-bold text-yellow-200">🎉 مبروك! فزت بالمزاد</p>';
                    }
                } else {
                    resultDiv.className += ' bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 border border-red-300 dark:border-red-700';
                    resultDiv.innerHTML = `<strong>❌ خطأ!</strong> ${data.error}`;
                }
                
                resultDiv.classList.remove('hidden');
                
                // إعادة تمكين الزر
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                submitBtn.classList.add('hover:scale-105');
            })
            .catch(error => {
                console.error('Error:', error);
                const resultDiv = document.getElementById('bidResult');
                resultDiv.className = 'mt-4 p-4 rounded-lg bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 border border-red-300 dark:border-red-700';
                resultDiv.innerHTML = '<strong>❌ خطأ!</strong> حدث خطأ أثناء تقديم المزايدة.';
                resultDiv.classList.remove('hidden');
                
                // إعادة تمكين الزر
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                submitBtn.classList.add('hover:scale-105');
            });
        });
    });
</script>
@endsection