{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
<div class="bg-white">
    {{-- الهيرو --}}
    <div class="relative bg-gradient-to-l from-blue-600 to-purple-700 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-right">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                        <i class="bi bi-hammer text-blue-300"></i>
                        نظام المزاد الإلكتروني
                    </h1>
                    <p class="text-xl md:text-2xl text-blue-100 mb-8 leading-relaxed">
                        منصة عربية متكاملة للمزادات الإلكترونية. بيع واشتري بثقة وسهولة في بيئة آمنة ومضمونة.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        @auth
                            <a href="{{ route('dashboard') }}" 
                               class="bg-white text-blue-600 hover:bg-blue-50 px-8 py-4 rounded-lg font-bold text-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                <i class="bi bi-speedometer2 ml-2"></i>
                                لوحة التحكم
                            </a>
                        @else
                            <a href="{{ route('register') }}" 
                               class="bg-white text-blue-600 hover:bg-blue-50 px-8 py-4 rounded-lg font-bold text-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                <i class="bi bi-person-plus ml-2"></i>
                                إنشاء حساب
                            </a>
                            <a href="{{ route('login') }}" 
                               class="border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-4 rounded-lg font-bold text-lg transition-all transform hover:-translate-y-1">
                                <i class="bi bi-box-arrow-in-right ml-2"></i>
                                تسجيل الدخول
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="text-center">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 inline-block">
                        <img src="https://via.placeholder.com/400x300/ffffff/667eea?text=🎯" 
                             alt="نظام المزاد" class="rounded-xl shadow-2xl">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- المميزات --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">مميزات المنصة</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">كل ما تحتاجه في مكان واحد لتجربة مزادات استثنائية</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- بطاقة البائعين --}}
            <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-blue-200 transform hover:-translate-y-2">
                <div class="w-20 h-20 bg-blue-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-500 transition-colors">
                    <i class="bi bi-shop text-3xl text-blue-600 group-hover:text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">للبائعين</h3>
                <p class="text-gray-600 leading-relaxed">
                    عرض منتجاتك وبيعها بأفضل الأسعار من خلال نظام المزاد التنافسي مع إدارة متكاملة للمنتجات والمزادات.
                </p>
            </div>
            
            {{-- بطاقة المشترين --}}
            <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-green-200 transform hover:-translate-y-2">
                <div class="w-20 h-20 bg-green-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-green-500 transition-colors">
                    <i class="bi bi-cart3 text-3xl text-green-600 group-hover:text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">للمشترين</h3>
                <p class="text-gray-600 leading-relaxed">
                    شارك في المزادات واربح منتجات مميزة بأسعار تنافسية مع نظام مزايدات آمن ومرن يناسب احتياجاتك.
                </p>
            </div>
            
            {{-- بطاقة الأمان --}}
            <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-yellow-200 transform hover:-translate-y-2">
                <div class="w-20 h-20 bg-yellow-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-yellow-500 transition-colors">
                    <i class="bi bi-shield-check text-3xl text-yellow-600 group-hover:text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">آمن ومضمون</h3>
                <p class="text-gray-600 leading-relaxed">
                    نظام آمن مع إدارة متكاملة ومراقبة مستمرة للمزادات، مع حماية بيانات المستخدمين وتأمين المعاملات.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection