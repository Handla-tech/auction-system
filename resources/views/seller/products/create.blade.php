{{-- resources/views/seller/products/create.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'إضافة منتج جديد')

@section('content')
<div class="space-y-6">
    <!-- رأس الصفحة -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center space-x-2 space-x-reverse">
                <i class="bi bi-plus-circle text-blue-500"></i>
                <span>إضافة منتج جديد</span>
            </h1>
            <p class="text-gray-600 mt-2">أضف منتج جديد لبدء مزاد عليه</p>
        </div>
        <a href="{{ route('seller.products.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center space-x-2 space-x-reverse">
            <i class="bi bi-arrow-right"></i>
            <span>رجوع إلى القائمة</span>
        </a>
    </div>

    <!-- نموذج إضافة المنتج -->
    <div class="bg-white rounded-lg shadow-custom">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center space-x-2 space-x-reverse">
                <i class="bi bi-info-circle text-blue-500"></i>
                <span>معلومات المنتج</span>
            </h2>
        </div>
        <div class="p-6">
            <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- الصف الأول: اسم المنتج والفئة -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- اسم المنتج -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            اسم المنتج *
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('name') border-red-500 @enderror"
                               placeholder="أدخل اسم المنتج"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 flex items-center space-x-1 space-x-reverse">
                                <i class="bi bi-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- الفئة -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            الفئة *
                        </label>
                        <select id="category" 
                                name="category"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('category') border-red-500 @enderror"
                                required>
                            <option value="">اختر الفئة</option>
                            <option value="إلكترونيات" {{ old('category') == 'إلكترونيات' ? 'selected' : '' }}>إلكترونيات</option>
                            <option value="أجهزة منزلية" {{ old('category') == 'أجهزة منزلية' ? 'selected' : '' }}>أجهزة منزلية</option>
                            <option value="سيارات" {{ old('category') == 'سيارات' ? 'selected' : '' }}>سيارات</option>
                            <option value="عقارات" {{ old('category') == 'عقارات' ? 'selected' : '' }}>عقارات</option>
                            <option value="أزياء" {{ old('category') == 'أزياء' ? 'selected' : '' }}>أزياء</option>
                            <option value="تحف" {{ old('category') == 'تحف' ? 'selected' : '' }}>تحف</option>
                            <option value="أخرى" {{ old('category') == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600 flex items-center space-x-1 space-x-reverse">
                                <i class="bi bi-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- وصف المنتج -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        وصف المنتج *
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('description') border-red-500 @enderror"
                              placeholder="أدخل وصفاً مفصلاً للمنتج..."
                              required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 flex items-center space-x-1 space-x-reverse">
                            <i class="bi bi-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- الصف الثاني: الأسعار -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- السعر الابتدائي -->
                    <div>
                        <label for="starting_price" class="block text-sm font-medium text-gray-700 mb-2">
                            السعر الابتدائي (ر.س) *
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="starting_price" 
                                   name="starting_price" 
                                   value="{{ old('starting_price') }}"
                                   min="0" 
                                   step="0.01"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('starting_price') border-red-500 @enderror"
                                   placeholder="0.00"
                                   required>
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                ر.س
                            </div>
                        </div>
                        @error('starting_price')
                            <p class="mt-1 text-sm text-red-600 flex items-center space-x-1 space-x-reverse">
                                <i class="bi bi-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- السعر الأقصى -->
                    <div>
                        <label for="max_price" class="block text-sm font-medium text-gray-700 mb-2">
                            السعر الأقصى (ر.س) *
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="max_price" 
                                   name="max_price" 
                                   value="{{ old('max_price') }}"
                                   min="0" 
                                   step="0.01"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('max_price') border-red-500 @enderror"
                                   placeholder="0.00"
                                   required>
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                ر.س
                            </div>
                        </div>
                        @error('max_price')
                            <p class="mt-1 text-sm text-red-600 flex items-center space-x-1 space-x-reverse">
                                <i class="bi bi-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- الصف الثالث: توقيت المزاد -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- بداية المزاد -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                            بداية المزاد *
                        </label>
                        <input type="datetime-local" 
                               id="start_time" 
                               name="start_time" 
                               value="{{ old('start_time') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('start_time') border-red-500 @enderror"
                               required>
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600 flex items-center space-x-1 space-x-reverse">
                                <i class="bi bi-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- نهاية المزاد -->
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                            نهاية المزاد *
                        </label>
                        <input type="datetime-local" 
                               id="end_time" 
                               name="end_time" 
                               value="{{ old('end_time') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('end_time') border-red-500 @enderror"
                               required>
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600 flex items-center space-x-1 space-x-reverse">
                                <i class="bi bi-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- رفع الصور -->
                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                        صور المنتج
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors duration-200">
                        <input type="file" 
                               id="images" 
                               name="images[]" 
                               multiple 
                               accept="image/*"
                               class="hidden"
                               onchange="previewImages(this)">
                        <div id="imageUploadArea" class="cursor-pointer" onclick="document.getElementById('images').click()">
                            <i class="bi bi-cloud-arrow-up text-4xl text-gray-400 mb-3"></i>
                            <p class="text-gray-600 mb-1">انقر لرفع الصور أو اسحبها هنا</p>
                            <p class="text-sm text-gray-500">يمكنك رفع أكثر من صورة (الحد الأقصى 5 صور، حجم كل صورة لا يتعدى 2MB)</p>
                        </div>
                        <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-4 hidden"></div>
                    </div>
                    @error('images')
                        <p class="mt-1 text-sm text-red-600 flex items-center space-x-1 space-x-reverse">
                            <i class="bi bi-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- أزرار الإجراءات -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2 space-x-reverse font-medium">
                        <i class="bi bi-check-circle"></i>
                        <span>إضافة المنتج</span>
                    </button>
                    <a href="{{ route('seller.products.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2 space-x-reverse font-medium">
                        <i class="bi bi-x-circle"></i>
                        <span>إلغاء</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // التحقق من أن سعر البداية أقل من السعر الأقصى
    document.getElementById('max_price').addEventListener('change', function() {
        const startingPrice = parseFloat(document.getElementById('starting_price').value);
        const maxPrice = parseFloat(this.value);
        
        if (maxPrice <= startingPrice) {
            alert('السعر الأقصى يجب أن يكون أكبر من السعر الابتدائي');
            this.value = startingPrice + 1;
        }
    });

    // التحقق من أن وقت النهاية بعد وقت البداية
    document.getElementById('end_time').addEventListener('change', function() {
        const startTime = new Date(document.getElementById('start_time').value);
        const endTime = new Date(this.value);
        
        if (endTime <= startTime) {
            alert('وقت نهاية المزاد يجب أن يكون بعد وقت البداية');
            // إضافة ساعة واحدة لوقت النهاية
            startTime.setHours(startTime.getHours() + 1);
            this.value = startTime.toISOString().slice(0, 16);
        }
    });

    // معاينة الصور
    function previewImages(input) {
        const preview = document.getElementById('imagePreview');
        const uploadArea = document.getElementById('imageUploadArea');
        
        preview.innerHTML = '';
        
        if (input.files && input.files.length > 0) {
            uploadArea.classList.add('hidden');
            preview.classList.remove('hidden');
            
            Array.from(input.files).forEach((file, index) => {
                if (index >= 5) return; // الحد الأقصى 5 صور
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg">
                        <button type="button" onclick="removeImage(${index})" class="absolute -top-2 -left-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <i class="bi bi-x"></i>
                        </button>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        } else {
            uploadArea.classList.remove('hidden');
            preview.classList.add('hidden');
        }
    }

    // إزالة صورة من المعاينة
    function removeImage(index) {
        const input = document.getElementById('images');
        const dt = new DataTransfer();
        
        Array.from(input.files).forEach((file, i) => {
            if (i !== index) {
                dt.items.add(file);
            }
        });
        
        input.files = dt.files;
        previewImages(input);
    }

    // تعيين القيم الافتراضية للوقت
    document.addEventListener('DOMContentLoaded', function() {
        const now = new Date();
        const startTime = new Date(now.getTime() + 60 * 60 * 1000); // بعد ساعة من الآن
        const endTime = new Date(startTime.getTime() + 24 * 60 * 60 * 1000); // بعد 24 ساعة من البداية
        
        if (!document.getElementById('start_time').value) {
            document.getElementById('start_time').value = startTime.toISOString().slice(0, 16);
        }
        if (!document.getElementById('end_time').value) {
            document.getElementById('end_time').value = endTime.toISOString().slice(0, 16);
        }
    });
</script>

<style>
    .shadow-custom {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
    
    input:focus, textarea:focus, select:focus {
        outline: none;
        ring: 2px;
    }
</style>
@endsection