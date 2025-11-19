{{-- resources/views/layouts/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - نظام المزاد</title>
    
    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        /* تأكد من أن التصميم RTL يعمل */
        [dir="rtl"] {
            direction: rtl;
            text-align: right;
        }
        
        .sidebar {
            transition: all 0.3s ease;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
        }
        
        .active-nav {
            background-color: #3b82f6;
            color: white;
        }
        
        .active-nav:hover {
            background-color: #2563eb;
            color: white;
        }
        
        .active-nav i {
            color: white;
        }
        
        /* تحسينات إضافية */
        .hover-scale:hover {
            transform: scale(1.02);
            transition: transform 0.2s ease;
        }
        
        .shadow-custom {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="bg-gray-50 font-sans" dir="rtl">
    {{-- الشريط العلوي --}}
    <nav class="bg-white shadow-custom border-b border-gray-200 sticky top-0 z-40">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- الجزء الأيمن: زر القائمة والشعار --}}
                <div class="flex items-center">
                    <button id="menuToggle" class="lg:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors">
                        <i class="bi bi-list text-xl"></i>
                    </button>
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 space-x-reverse mr-4 hover-scale">
                        <div class="w-10 h-10 bg-gradient-to-l from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="bi bi-hammer text-white text-lg"></i>
                        </div>
                        <div>
                            <span class="font-bold text-gray-800 text-lg">نظام المزاد</span>
                            <p class="text-xs text-gray-500">منصة المزادات الإلكترونية</p>
                        </div>
                    </a>
                </div>

                {{-- الجزء الأيسر: إشعارات والمستخدم --}}
                <div class="flex items-center space-x-4 space-x-reverse">
                    {{-- الإشعارات --}}
                    <div class="relative">
                        <button class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors relative group">
                            <i class="bi bi-bell text-xl"></i>
                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white"></span>
                            
                            {{-- قائمة الإشعارات --}}
                            <div class="absolute left-0 mt-2 w-80 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 border border-gray-200">
                                <div class="p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                                    <h3 class="font-bold text-gray-800 flex items-center space-x-2 space-x-reverse">
                                        <i class="bi bi-bell text-blue-500"></i>
                                        <span>الإشعارات</span>
                                    </h3>
                                </div>
                                <div class="max-h-60 overflow-y-auto">
                                    <div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-start space-x-3 space-x-reverse">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                <i class="bi bi-info-circle text-blue-500"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-700">مرحباً بك في نظام المزاد</p>
                                                <p class="text-xs text-gray-500 mt-1">الآن</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Removed placeholder link --}}
                            </div>
                        </button>
                    </div>

                    {{-- الملف الشخصي --}}
                    <div class="relative">
                        <button id="profileToggle" class="flex items-center space-x-2 space-x-reverse p-2 rounded-lg hover:bg-gray-100 transition-colors group">
                            <div class="w-8 h-8 bg-gradient-to-l from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="text-right hidden md:block">
                                <span class="text-gray-700 font-medium block">{{ auth()->user()->name }}</span>
                                <span class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</span>
                            </div>
                            <i class="bi bi-chevron-down text-sm text-gray-500 transition-transform group-hover:rotate-180"></i>
                        </button>
                        
                        {{-- قائمة الملف الشخصي --}}
                        <div id="profileMenu" class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-lg opacity-0 invisible transition-all duration-300 z-50 border border-gray-200">
                            <div class="p-4 border-b border-gray-100 bg-gradient-to-l from-blue-50 to-purple-50 rounded-t-lg">
                                <div class="flex items-center space-x-3 space-x-reverse">
                                    <div class="w-12 h-12 bg-gradient-to-l from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                                        <p class="text-sm text-gray-600">{{ auth()->user()->email }}</p>
                                        <p class="text-xs text-gray-500 capitalize mt-1 bg-white px-2 py-1 rounded-full inline-block">
                                            {{ auth()->user()->role }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 space-x-reverse px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 rounded-lg transition-colors">
                                    <i class="bi bi-speedometer2 text-blue-500 text-lg"></i>
                                    <span>لوحة التحكم</span>
                                </a>
                                <hr class="my-2 border-gray-200">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center space-x-3 space-x-reverse w-full text-right px-3 py-2 text-sm text-gray-700 hover:bg-red-50 rounded-lg transition-colors">
                                        <i class="bi bi-box-arrow-right text-red-500 text-lg"></i>
                                        <span>تسجيل الخروج</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex min-h-screen">
        {{-- الشريط الجانبي --}}
        <aside class="sidebar fixed lg:static inset-y-0 right-0 z-30 w-64 bg-white shadow-custom border-l border-gray-200 lg:translate-x-0">
            <div class="h-full overflow-y-auto">
                {{-- رأس الشريط الجانبي --}}
                <div class="p-4 border-b border-gray-200 bg-gradient-to-l from-gray-50 to-white">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center space-x-2 space-x-reverse">
                        <i class="bi bi-layout-sidebar text-blue-500"></i>
                        <span>القائمة الرئيسية</span>
                    </h2>
                </div>
                
                <nav class="p-4">
                    {{-- القائمة الرئيسية --}}
                    <div class="mb-8">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 px-3">التنقل الرئيسي</h3>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('dashboard') }}" 
                                   class="flex items-center space-x-3 space-x-reverse px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'active-nav shadow-sm' : '' }}">
                                    <i class="bi bi-house text-lg"></i>
                                    <span class="font-medium">الرئيسية</span>
                                </a>
                            </li>
                            
                            {{-- عناصر القائمة حسب الدور --}}
                            @if(auth()->user()->role === 'seller')
                                {{-- لوحة تحكم البائع --}}
                                <li>
                                    <a href="{{ route('seller.dashboard') }}" 
                                       class="flex items-center space-x-3 space-x-reverse px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('seller.dashboard') ? 'active-nav shadow-sm' : '' }}">
                                        <i class="bi bi-speedometer2 text-lg"></i>
                                        <span class="font-medium">لوحة التحكم</span>
                                    </a>
                                </li>
                                
                                {{-- إدارة المنتجات --}}
                                <li>
                                    <a href="{{ route('seller.products.index') }}" 
                                       class="flex items-center space-x-3 space-x-reverse px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ 
                                            request()->routeIs('seller.products.index') || 
                                            request()->routeIs('seller.products.edit') ? 'active-nav shadow-sm' : '' }}">
                                        <i class="bi bi-box text-lg"></i>
                                        <span class="font-medium">المنتجات</span>
                                        <span class="bg-blue-100 text-blue-600 text-xs px-2 py-1 rounded-full mr-auto">
                                            {{ auth()->user()->products->count() }}
                                        </span>
                                    </a>
                                </li>
                                
                                {{-- إضافة منتج جديد --}}
                                <li>
                                    <a href="{{ route('seller.products.create') }}" 
                                       class="flex items-center space-x-3 space-x-reverse px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('seller.products.create') ? 'active-nav shadow-sm' : '' }}">
                                        <i class="bi bi-plus-circle text-lg"></i>
                                        <span class="font-medium">إضافة منتج</span>
                                    </a>
                                </li>
                                
                            @elseif(auth()->user()->role === 'buyer')
                                {{-- لوحة تحكم المشتري --}}
                                <li>
                                    <a href="{{ route('buyer.dashboard') }}" 
                                       class="flex items-center space-x-3 space-x-reverse px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('buyer.dashboard') ? 'active-nav shadow-sm' : '' }}">
                                        <i class="bi bi-speedometer2 text-lg"></i>
                                        <span class="font-medium">لوحة التحكم</span>
                                    </a>
                                </li>
                                
                                {{-- المنتجات --}}
                                <li>
                                    <a href="{{ route('buyer.products') }}" 
                                       class="flex items-center space-x-3 space-x-reverse px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('buyer.products') ? 'active-nav shadow-sm' : '' }}">
                                        <i class="bi bi-box text-lg"></i>
                                        <span class="font-medium">المنتجات</span>
                                    </a>
                                </li>
                                
                                {{-- مزايداتي --}}
                                <li>
                                    <a href="{{ route('buyer.my-bids') }}" 
                                       class="flex items-center space-x-3 space-x-reverse px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('buyer.my-bids') ? 'active-nav shadow-sm' : '' }}">
                                        <i class="bi bi-hammer text-lg"></i>
                                        <span class="font-medium">مزايداتي</span>
                                    </a>
                                </li>
                                
                                {{-- المزادات النشطة --}}
                                <li>
                                    <a href="{{ route('auctions.active') }}" 
                                       class="flex items-center space-x-3 space-x-reverse px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('auctions.active') ? 'active-nav shadow-sm' : '' }}">
                                        <i class="bi bi-fire text-lg"></i>
                                        <span class="font-medium">مزادات نشطة</span>
                                    </a>
                                </li>
                            @elseif(auth()->user()->role === 'admin')
                                {{-- لوحة تحكم المسؤول --}}
                                <li>
                                    <a href="{{ route('admin.dashboard') }}" 
                                       class="flex items-center space-x-3 space-x-reverse px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'active-nav shadow-sm' : '' }}">
                                        <i class="bi bi-speedometer2 text-lg"></i>
                                        <span class="font-medium">لوحة التحكم</span>
                                    </a>
                                </li>
                                
                                {{-- المستخدمين --}}
                                <li>
                                    <a href="{{ route('admin.users') }}" 
                                       class="flex items-center space-x-3 space-x-reverse px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('admin.users') || request()->routeIs('admin.user-details') ? 'active-nav shadow-sm' : '' }}">
                                        <i class="bi bi-people text-lg"></i>
                                        <span class="font-medium">المستخدمين</span>
                                    </a>
                                </li>
                                
                                {{-- المنتجات --}}
                                <li>
                                    <a href="{{ route('admin.products') }}" 
                                       class="flex items-center space-x-3 space-x-reverse px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('admin.products') || request()->routeIs('admin.product-details') ? 'active-nav shadow-sm' : '' }}">
                                        <i class="bi bi-box text-lg"></i>
                                        <span class="font-medium">المنتجات</span>
                                    </a>
                                </li>
                                
                                {{-- المزادات --}}
                                <li>
                                    <a href="{{ route('admin.auctions') }}" 
                                       class="flex items-center space-x-3 space-x-reverse px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('admin.auctions') || request()->routeIs('admin.auction-details') ? 'active-nav shadow-sm' : '' }}">
                                        <i class="bi bi-hammer text-lg"></i>
                                        <span class="font-medium">المزادات</span>
                                    </a>
                                </li>
                                
                                {{-- التقارير --}}
                                <li>
                                    <a href="{{ route('admin.reports') }}" 
                                       class="flex items-center space-x-3 space-x-reverse px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('admin.reports') ? 'active-nav shadow-sm' : '' }}">
                                        <i class="bi bi-graph-up text-lg"></i>
                                        <span class="font-medium">التقارير</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>

                    {{-- القائمة العامة --}}
                    <div>
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 px-3">عام</h3>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('home') }}" class="flex items-center space-x-3 space-x-reverse px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-all duration-200">
                                    <i class="bi bi-globe text-lg"></i>
                                    <span class="font-medium">الموقع الرئيسي</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('auctions.active') }}" class="flex items-center space-x-3 space-x-reverse px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('auctions.active') ? 'active-nav shadow-sm' : '' }}">
                                    <i class="bi bi-fire text-lg"></i>
                                    <span class="font-medium">المزادات النشطة</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('auctions.ended') }}" class="flex items-center space-x-3 space-x-reverse px-3 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('auctions.ended') ? 'active-nav shadow-sm' : '' }}">
                                    <i class="bi bi-check-circle text-lg"></i>
                                    <span class="font-medium">المزادات المنتهية</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </aside>

        {{-- المحتوى الرئيسي --}}
        <main class="flex-1 lg:mr-16 transition-all duration-300 bg-gray-50">
            <div class="p-6">
                {{-- الرسائل --}}
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg shadow-sm">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <i class="bi bi-check-circle-fill text-green-500 text-xl"></i>
                            <div>
                                <span class="font-medium">نجاح!</span>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg shadow-sm">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <i class="bi bi-exclamation-circle-fill text-red-500 text-xl"></i>
                            <div>
                                <span class="font-medium">خطأ!</span>
                                <p class="text-sm">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- محتوى الصفحة --}}
                <div class="bg-white rounded-lg shadow-custom p-6">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    {{-- Overlay للجوال --}}
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden hidden"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // عناصر DOM
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('overlay');
            const profileToggle = document.getElementById('profileToggle');
            const profileMenu = document.getElementById('profileMenu');

            // تبديل القائمة الجانبية للجوال
            if (menuToggle && sidebar && overlay) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('open');
                    overlay.classList.toggle('hidden');
                });

                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('open');
                    overlay.classList.add('hidden');
                });
            }

            // تبديل قائمة الملف الشخصي
            if (profileToggle && profileMenu) {
                profileToggle.addEventListener('click', function() {
                    profileMenu.classList.toggle('opacity-0');
                    profileMenu.classList.toggle('invisible');
                    profileMenu.classList.toggle('opacity-100');
                    profileMenu.classList.toggle('visible');
                });

                // إغلاق قائمة الملف الشخصي عند النقر خارجها
                document.addEventListener('click', function(event) {
                    if (!profileToggle.contains(event.target) && !profileMenu.contains(event.target)) {
                        profileMenu.classList.add('opacity-0', 'invisible');
                        profileMenu.classList.remove('opacity-100', 'visible');
                    }
                });
            }

            // إغلاق القائمة عند النقر على رابط في الجوال
            if (window.innerWidth < 1024) {
                const navLinks = document.querySelectorAll('.sidebar a');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        sidebar.classList.remove('open');
                        overlay.classList.add('hidden');
                    });
                });
            }
        });
    </script>

    @yield('scripts')
</body>
</html>