{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('title', 'تسجيل الدخول')

@section('content')
<div class="min-h-screen gradient-bg flex items-center justify-center p-4">
    <div class="auth-card w-full max-w-md bg-white rounded-2xl overflow-hidden">
        {{-- الهيدر --}}
        <div class="bg-gradient-to-l from-blue-600 to-purple-700 p-8 text-center text-white">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-box-arrow-in-right text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold mb-2">تسجيل الدخول</h2>
            <p class="text-blue-100">مرحباً بعودتك إلى المنصة</p>
        </div>
        
        {{-- الفورم --}}
        <div class="p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                {{-- البريد الإلكتروني --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror"
                           placeholder="example@email.com" required autofocus>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- كلمة المرور --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور</label>
                    <input type="password" id="password" name="password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 @enderror"
                           placeholder="********" required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- تذكرني --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" 
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="remember" class="mr-2 text-sm text-gray-700">تذكرني</label>
                    </div>
                    
                    {{-- نسيت كلمة المرور --}}
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-700 transition-colors">
                        نسيت كلمة المرور؟
                    </a>
                </div>

                {{-- زر تسجيل الدخول --}}
                <button type="submit" 
                        class="w-full bg-gradient-to-l from-blue-600 to-purple-700 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-700 hover:to-purple-800 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all shadow-lg">
                    <i class="bi bi-box-arrow-in-right ml-2"></i>
                    تسجيل الدخول
                </button>

                {{-- رابط إنشاء حساب --}}
                <div class="text-center">
                    <p class="text-gray-600">
                        لا تملك حساباً؟ 
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-medium transition-colors">
                            أنشئ حساب من هنا
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection