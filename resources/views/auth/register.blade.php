{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')

@section('title', 'إنشاء حساب جديد')

@section('content')
<div class="min-h-screen gradient-bg flex items-center justify-center p-4">
    <div class="auth-card w-full max-w-md bg-white rounded-2xl overflow-hidden">
        {{-- الهيدر --}}
        <div class="bg-gradient-to-l from-blue-600 to-purple-700 p-8 text-center text-white">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-person-plus text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold mb-2">إنشاء حساب جديد</h2>
            <p class="text-blue-100">انضم إلى منصة المزادات الإلكترونية</p>
        </div>
        
        {{-- الفورم --}}
        <div class="p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf
                
                {{-- الاسم --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-500 @enderror"
                           placeholder="أدخل اسمك الكامل" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- البريد الإلكتروني --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror"
                           placeholder="example@email.com" required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- الهاتف --}}
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('phone') border-red-500 @enderror"
                           placeholder="05XXXXXXXX" required>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- العنوان --}}
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">العنوان</label>
                    <textarea id="address" name="address" rows="2"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('address') border-red-500 @enderror"
                              placeholder="أدخل عنوانك بالتفصيل" required>{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- اختيار الدور --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">نوع الحساب</label>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="role-option border-2 border-gray-200 rounded-lg p-4 text-center cursor-pointer transition-all {{ old('role') == 'buyer' ? 'active border-blue-500 bg-blue-50' : '' }}">
                            <input type="radio" name="role" value="buyer" id="role_buyer" 
                                   {{ old('role') == 'buyer' ? 'checked' : '' }} class="hidden" required>
                            <label for="role_buyer" class="cursor-pointer">
                                <i class="bi bi-cart3 text-2xl text-gray-600 mb-2 block"></i>
                                <span class="font-medium text-gray-700">مشتري</span>
                                <p class="text-xs text-gray-500 mt-1">أريد المشاركة في المزادات</p>
                            </label>
                        </div>
                        <div class="role-option border-2 border-gray-200 rounded-lg p-4 text-center cursor-pointer transition-all {{ old('role') == 'seller' ? 'active border-blue-500 bg-blue-50' : '' }}">
                            <input type="radio" name="role" value="seller" id="role_seller" 
                                   {{ old('role') == 'seller' ? 'checked' : '' }} class="hidden">
                            <label for="role_seller" class="cursor-pointer">
                                <i class="bi bi-shop text-2xl text-gray-600 mb-2 block"></i>
                                <span class="font-medium text-gray-700">بائع</span>
                                <p class="text-xs text-gray-500 mt-1">أريد عرض منتجات للبيع</p>
                            </label>
                        </div>
                    </div>
                    @error('role')
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

                {{-- تأكيد كلمة المرور --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">تأكيد كلمة المرور</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="********" required>
                </div>

                {{-- زر التسجيل --}}
                <button type="submit" 
                        class="w-full bg-gradient-to-l from-blue-600 to-purple-700 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-700 hover:to-purple-800 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all shadow-lg">
                    <i class="bi bi-person-plus ml-2"></i>
                    إنشاء حساب
                </button>

                {{-- رابط تسجيل الدخول --}}
                <div class="text-center">
                    <p class="text-gray-600">
                        لديك حساب بالفعل؟ 
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium transition-colors">
                            سجل الدخول من هنا
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection