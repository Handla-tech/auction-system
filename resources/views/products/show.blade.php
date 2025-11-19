@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- مسار التنقل -->
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 space-x-reverse md:space-x-3 md:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
                        <i class="bi bi-house-door ml-2"></i>
                        الرئيسية
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="bi bi-chevron-left text-gray-400"></i>
                        <a href="{{ route('buyer.products') }}" class="mr-1 text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
                            المنتجات
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="bi bi-chevron-left text-gray-400"></i>
                        <span class="mr-1 text-sm font-medium text-gray-400 dark:text-gray-500">
                            {{ Str::limit($product->name, 30) }}
                        </span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- معرض الصور -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            @if($product->images && count($product->images) > 0)
                <!-- الصورة الرئيسية -->
                <div class="relative">
                    <img id="mainImage" 
                         src="{{ $product->getFirstImageUrl() }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-96 object-cover cursor-zoom-in"
                         onclick="openImageModal('{{ $product->getFirstImageUrl() }}')">
                    
                    <!-- حالة المزاد -->
                    <div class="absolute top-4 left-4">
                        @if($product->auction->isActive())
                            @if($product->auction->isEndingSoon())
                                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                    ⏰ ينتهي قريباً
                                </span>
                            @else
                                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                    🔥 مزاد نشط
                                </span>
                            @endif
                        @else
                            <span class="bg-gray-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                ⏰ انتهى المزاد
                            </span>
                        @endif
                    </div>

                    <!-- زر تكبير الصورة -->
                    <button onclick="openImageModal('{{ $product->getFirstImageUrl() }}')" 
                            class="absolute top-4 right-4 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-70 transition-all duration-200">
                        <i class="bi bi-zoom-in text-lg"></i>
                    </button>
                </div>

                <!-- معرض الصور المصغرة -->
                @if(count($product->images) > 1)
                    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex space-x-2 space-x-reverse overflow-x-auto">
                            @foreach($product->images as $index => $image)
                                <img src="{{ $product->getImageUrl($image) }}" 
                                     alt="{{ $product->name }} - {{ $index + 1 }}"
                                     class="w-16 h-16 object-cover rounded-lg cursor-pointer border-2 
                                            {{ $index === 0 ? 'border-blue-500' : 'border-transparent' }}
                                            hover:border-blue-300 transition-all duration-200"
                                     onclick="changeMainImage('{{ $product->getImageUrl($image) }}', this)">
                            @endforeach
                        </div>
                    </div>
                @endif
            @else
                <!-- صورة افتراضية -->
                <div class="w-full h-96 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <div class="text-center">
                        <i class="bi bi-image text-gray-400 text-6xl mb-4"></i>
                        <p class="text-gray-500 dark:text-gray-400">لا توجد صور للمنتج</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- معلومات المنتج -->
        <div class="space-y-6">
            <!-- العنوان والفئة -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">
                        {{ $product->name }}
                    </h1>
                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 
                                px-3 py-1 rounded-full text-sm font-medium">
                        {{ $product->category }}
                    </span>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-lg leading-relaxed">
                    {{ $product->description }}
                </p>
            </div>

            <!-- معلومات البائع -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                            {{ Str::substr($product->seller->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">البائع</p>
                            <p class="font-bold text-gray-800 dark:text-white">{{ $product->seller->name }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600 dark:text-gray-400">معدل التقييم</p>
                        <div class="flex items-center justify-end">
                            <i class="bi bi-star-fill text-yellow-400 ml-1"></i>
                            <span class="font-bold text-gray-800 dark:text-white">4.8</span>
                            <span class="text-gray-500 text-sm mr-1">(24)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- معلومات المزاد -->
            <div class="bg-gradient-to-r from-purple-500 to-blue-600 rounded-2xl p-6 text-white">
                <h3 class="text-lg font-bold mb-4 text-center">⏰ معلومات المزاد</h3>
                
                <!-- مؤقت العد التنازلي -->
                @if($product->auction->isActive())
                    <div class="grid grid-cols-4 gap-4 text-center mb-4" id="auctionTimer">
                        <div>
                            <div class="text-2xl font-bold" id="days">00</div>
                            <div class="text-xs">أيام</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold" id="hours">00</div>
                            <div class="text-xs">ساعات</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold" id="minutes">00</div>
                            <div class="text-xs">دقائق</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold" id="seconds">00</div>
                            <div class="text-xs">ثواني</div>
                        </div>
                    </div>
                    <p class="text-center text-yellow-200 text-sm" id="timerStatus">المزاد نشط - شارك الآن!</p>
                @else
                    <div class="text-center py-4">
                        <p class="text-xl font-bold mb-2">⏰ انتهى المزاد</p>
                        <p class="text-sm opacity-90">انتهى في {{ $product->auction->end_time->format('Y-m-d H:i') }}</p>
                    </div>
                @endif
            </div>

            <!-- الأسعار -->
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">السعر الابتدائي</p>
                    <p class="text-xl font-bold text-green-600 dark:text-green-400">
                        {{ number_format($product->starting_price, 2) }} ر.س
                    </p>
                </div>
                
                <div class="text-center bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">السعر الحالي</p>
                    <p class="text-xl font-bold text-blue-600 dark:text-blue-400">
                        {{ number_format($product->auction->current_bid, 2) }} ر.س
                    </p>
                </div>
                
                <div class="text-center bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">الحد الأقصى</p>
                    <p class="text-xl font-bold text-red-600 dark:text-red-400">
                        {{ number_format($product->max_price, 2) }} ر.س
                    </p>
                </div>
            </div>

            <!-- إحصائيات المزاد -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">عدد المزايدات:</span>
                        <span class="font-bold text-gray-800 dark:text-white">{{ $product->auction->bids->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">تاريخ البدء:</span>
                        <span class="font-bold text-gray-800 dark:text-white">{{ $product->auction->start_time->format('Y-m-d') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">المزايدون:</span>
                        <span class="font-bold text-gray-800 dark:text-white">{{ $product->auction->bids->groupBy('user_id')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">تاريخ النهاية:</span>
                        <span class="font-bold text-gray-800 dark:text-white">{{ $product->auction->end_time->format('Y-m-d') }}</span>
                    </div>
                </div>
            </div>

            <!-- أزرار الإجراء -->
            <div class="flex space-x-4 space-x-reverse">
                @if($product->auction->isActive())
                    @auth
                        @if(auth()->user()->isBuyer() && auth()->user()->canBidOnProduct($product->id))
                            <a href="{{ route('buyer.auction.show', $product->id) }}" 
                               class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 
                                      text-white py-4 px-6 rounded-xl font-bold text-lg transition-all duration-200 
                                      transform hover:scale-105 shadow-lg text-center">
                                🎯 ابدأ المزايدة
                            </a>
                        @elseif(auth()->user()->isSeller() && auth()->user()->id === $product->seller_id)
                            <button class="flex-1 bg-gray-400 text-white py-4 px-6 rounded-xl font-bold text-lg cursor-not-allowed">
                                ⚠️ منتجك
                            </button>
                        @else
                            <button class="flex-1 bg-gray-400 text-white py-4 px-6 rounded-xl font-bold text-lg cursor-not-allowed">
                                ⚠️ غير مسموح
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" 
                           class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 
                                  text-white py-4 px-6 rounded-xl font-bold text-lg transition-all duration-200 
                                  transform hover:scale-105 shadow-lg text-center">
                            🔐 سجل الدخول للمزايدة
                        </a>
                    @endauth
                @else
                    <button class="flex-1 bg-gray-400 text-white py-4 px-6 rounded-xl font-bold text-lg cursor-not-allowed">
                        ⏰ انتهى المزاد
                    </button>
                @endif

                <button class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 
                             text-gray-700 dark:text-gray-300 p-4 rounded-xl transition-colors duration-200
                             flex items-center justify-center"
                        onclick="toggleWatchlist({{ $product->id }})">
                    <i class="bi bi-heart text-xl"></i>
                </button>
                
                <button class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 
                             text-gray-700 dark:text-gray-300 p-4 rounded-xl transition-colors duration-200
                             flex items-center justify-center"
                        onclick="shareProduct()">
                    <i class="bi bi-share text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- قسم المعلومات الإضافية -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- تفاصيل إضافية -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">📋 تفاصيل المنتج</h2>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="font-bold text-gray-700 dark:text-gray-300 mb-2">المعلومات الأساسية</h3>
                            <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                <li class="flex justify-between">
                                    <span>رقم المنتج:</span>
                                    <span class="font-bold text-gray-800 dark:text-white">#{{ $product->id }}</span>
                                </li>
                                <li class="flex justify-between">
                                    <span>تاريخ الإضافة:</span>
                                    <span class="font-bold text-gray-800 dark:text-white">{{ $product->created_at->format('Y-m-d') }}</span>
                                </li>
                                <li class="flex justify-between">
                                    <span>آخر تحديث:</span>
                                    <span class="font-bold text-gray-800 dark:text-white">{{ $product->updated_at->format('Y-m-d') }}</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div>
                            <h3 class="font-bold text-gray-700 dark:text-gray-300 mb-2">حالة المزاد</h3>
                            <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                <li class="flex justify-between">
                                    <span>الحالة:</span>
                                    <span class="font-bold {{ $product->auction->isActive() ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $product->auction->isActive() ? 'نشط' : 'منتهي' }}
                                    </span>
                                </li>
                                <li class="flex justify-between">
                                    <span>مدة المزاد:</span>
                                    <span class="font-bold text-gray-800 dark:text-white">
                                        {{ $product->auction->start_time->diffInDays($product->auction->end_time) }} يوم
                                    </span>
                                </li>
                                <li class="flex justify-between">
                                    <span>الفائز:</span>
                                    <span class="font-bold text-gray-800 dark:text-white">
                                        {{ $product->auction->winner ? $product->auction->winner->name : 'لم يتم تحديده' }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- الوصف الكامل -->
                    <div>
                        <h3 class="font-bold text-gray-700 dark:text-gray-300 mb-2">الوصف الكامل</h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            {{ $product->description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- معلومات البائع -->
        <div class="space-y-6">
            <!-- بطاقة البائع -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">👤 معلومات البائع</h2>
                
                <div class="text-center">
                    <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-3">
                        {{ Str::substr($product->seller->name, 0, 1) }}
                    </div>
                    <h3 class="font-bold text-gray-800 dark:text-white text-lg mb-1">{{ $product->seller->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">بائع معتمد</p>
                    
                    <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex justify-between">
                            <span>العنوان:</span>
                            <span class="font-bold text-gray-800 dark:text-white">{{ $product->seller->address ?? 'غير محدد' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>الهاتف:</span>
                            <span class="font-bold text-gray-800 dark:text-white">{{ $product->seller->phone ?? 'غير محدد' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>البريد:</span>
                            <span class="font-bold text-gray-800 dark:text-white">{{ $product->seller->email }}</span>
                        </div>
                    </div>
                    
                    <button class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 
                                 text-gray-700 dark:text-gray-300 mt-4 py-2 rounded-lg transition-colors duration-200">
                        <i class="bi bi-chat-left-text ml-2"></i>
                        تواصل مع البائع
                    </button>
                </div>
            </div>

            <!-- منتجات مشابهة -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">🛍️ منتجات مشابهة</h2>
                <div class="space-y-3">
                    @for($i = 1; $i <= 3; $i++)
                        <div class="flex items-center space-x-3 space-x-reverse p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:border-blue-300 transition-colors">
                            <div class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                <i class="bi bi-image text-gray-400"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-800 dark:text-white text-sm">منتج مشابه {{ $i }}</h4>
                                <p class="text-xs text-gray-500">السعر: {{ number_format(rand(100, 1000), 0) }} ر.س</p>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>

<!-- نافذة تكبير الصورة -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 hidden">
    <div class="relative max-w-4xl max-h-full mx-4">
        <button onclick="closeImageModal()" 
                class="absolute -top-12 left-0 text-white text-2xl hover:text-gray-300 transition-colors duration-200">
            <i class="bi bi-x-lg"></i>
        </button>
        <img id="modalImage" src="" alt="" class="max-w-full max-h-screen object-contain">
    </div>
</div>

<script>
    // تبديل الصورة الرئيسية
    function changeMainImage(src, element) {
        document.getElementById('mainImage').src = src;
        
        // إزالة الحد من جميع الصور المصغرة
        document.querySelectorAll('.flex.space-x-2 img').forEach(img => {
            img.classList.remove('border-blue-500');
            img.classList.add('border-transparent');
        });
        
        // إضافة الحد للصورة المحددة
        element.classList.remove('border-transparent');
        element.classList.add('border-blue-500');
    }

    // فتح نافذة تكبير الصورة
    function openImageModal(src) {
        document.getElementById('modalImage').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // إغلاق نافذة تكبير الصورة
    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // إغلاق النافذة عند النقر خارج الصورة
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImageModal();
        }
    });

    // مؤقت العد التنازلي
    function updateTimer() {
        const endTime = new Date('{{ $product->auction->end_time }}').getTime();
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance < 0) {
            document.getElementById('timerStatus').textContent = 'انتهى المزاد';
            document.getElementById('timerStatus').className = 'text-center text-red-200 text-sm';
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

    @if($product->auction->isActive())
        setInterval(updateTimer, 1000);
        updateTimer();
    @endif

    // دوال مساعدة
    function toggleWatchlist(productId) {
        // هنا يمكن إضافة منطق إضافة/إزالة من المفضلة
        alert('سيتم إضافة المنتج إلى المفضلة - هذه وظيفة تجريبية');
    }

    function shareProduct() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $product->name }}',
                text: '{{ Str::limit($product->description, 100) }}',
                url: window.location.href,
            });
        } else {
            // نسخ الرابط
            navigator.clipboard.writeText(window.location.href);
            alert('تم نسخ رابط المنتج!');
        }
    }
</script>

<style>
.cursor-zoom-in {
    cursor: zoom-in;
}
</style>
@endsection