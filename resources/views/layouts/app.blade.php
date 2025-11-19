{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - نظام المزاد الإلكتروني</title>
    
    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    {{-- Custom Styles --}}
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --secondary: #64748b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .role-option {
            transition: all 0.3s ease;
        }
        
        .role-option.active {
            border-color: var(--primary);
            background-color: #eff6ff;
        }
        
        .auth-card {
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    {{-- الشريط العلوي --}}
    <nav class="bg-gray-900 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- الشعار --}}
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 space-x-reverse text-xl font-bold text-white">
                        <i class="bi bi-hammer text-blue-400"></i>
                        <span>نظام المزاد الإلكتروني</span>
                    </a>
                </div>

                {{-- قائمة التنقل --}}
                <div class="flex items-center space-x-4 space-x-reverse">
                    @auth
                        <div class="relative group">
                            <button class="flex items-center space-x-2 space-x-reverse px-3 py-2 rounded-lg hover:bg-gray-800 transition-colors">
                                <i class="bi bi-person-circle text-blue-400"></i>
                                <span>{{ auth()->user()->name }}</span>
                                <i class="bi bi-chevron-down text-sm"></i>
                            </button>
                            
                            <div class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 border border-gray-200">
                                <div class="py-1">
                                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 space-x-reverse px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="bi bi-speedometer2 text-blue-500"></i>
                                        <span>لوحة التحكم</span>
                                    </a>
                                    <hr class="my-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center space-x-2 space-x-reverse w-full text-right px-4 py-2 text-gray-700 hover:bg-gray-100">
                                            <i class="bi bi-box-arrow-right text-red-500"></i>
                                            <span>تسجيل الخروج</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center space-x-2 space-x-reverse px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>تسجيل الدخول</span>
                        </a>
                        <a href="{{ route('register') }}" class="flex items-center space-x-2 space-x-reverse bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition-colors">
                            <i class="bi bi-person-plus"></i>
                            <span>إنشاء حساب</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- المحتوى الرئيسي --}}
    <main>
        @yield('content')
    </main>

    {{-- الرسائل --}}
    @if(session('success'))
        <div class="fixed top-20 left-4 right-4 sm:left-auto sm:right-4 sm:max-w-md bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg shadow-lg z-50 transition-all duration-300">
            <div class="flex items-center space-x-2 space-x-reverse">
                <i class="bi bi-check-circle-fill text-green-500"></i>
                <span class="flex-1">{{ session('success') }}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="text-green-500 hover:text-green-700">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-20 left-4 right-4 sm:left-auto sm:right-4 sm:max-w-md bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg shadow-lg z-50 transition-all duration-300">
            <div class="flex items-center space-x-2 space-x-reverse">
                <i class="bi bi-exclamation-triangle-fill text-red-500"></i>
                <span class="flex-1">{{ session('error') }}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="text-red-500 hover:text-red-700">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>
        </div>
    @endif

    {{-- Custom Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تفعيل اختيار الدور
            const roleOptions = document.querySelectorAll('.role-option');
            roleOptions.forEach(option => {
                option.addEventListener('click', function() {
                    roleOptions.forEach(opt => opt.classList.remove('active', 'border-blue-500', 'bg-blue-50'));
                    this.classList.add('active', 'border-blue-500', 'bg-blue-50');
                    const radio = this.querySelector('input[type="radio"]');
                    radio.checked = true;
                });
            });

            // إخفاء الرسائل تلقائياً
            setTimeout(() => {
                const alerts = document.querySelectorAll('[class*="bg-"]');
                alerts.forEach(alert => {
                    if (alert.style.position === 'fixed') {
                        alert.remove();
                    }
                });
            }, 5000);
        });
    </script>
    
    @yield('scripts')
</body>
</html>