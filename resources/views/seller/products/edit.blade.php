{{-- resources/views/seller/products/edit.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'تعديل المنتج')

@section('content')
<div class="space-y-6">
    <!-- رأس الصفحة -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center space-x-2 space-x-reverse">
                <i class="bi bi-pencil-square text-blue-500"></i>
                <span>تعديل المنتج</span>
            </h1>
            <p class="text-gray-600 mt-2">قم بتعديل معلومات المنتج "{{ $product->name }}"</p>
        </div>
        <a href="{{ route('seller.products.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center space-x-2 space-x-reverse">
            <i class="bi bi-arrow-right"></i>
            <span>رجوع إلى القائمة</span>
        </a>
    </div>

    <!-- معلومات التصحيح -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-yellow-800 mb-2 flex items-center space-x-2 space-x-reverse">
            <i class="bi bi-bug-fill"></i>
            <span>معلومات التصحيح - الصور الحالية</span>
        </h3>
        <div class="text-xs text-yellow-700 space-y-1">
            <div><strong>اسم المنتج:</strong> {{ $product->name }}</div>
            <div><strong>عدد الصور:</strong> {{ count($product->images ?? []) }}</div>
            <div><strong>مصفوفة الصور:</strong> {{ json_encode($product->images) }}</div>
            @if($product->images && count($product->images) > 0)
                <div><strong>الصورة الأولى:</strong> {{ $product->images[0] }}</div>
                <div><strong>مسار التخزين:</strong> storage/app/public/products/{{ $product->images[0] }}</div>
                <div><strong>الملف موجود:</strong> 
                    {{ file_exists(storage_path('app/public/products/' . $product->images[0])) ? 'نعم ✅' : 'لا ❌' }}
                </div>
                <div><strong>رابط الصورة:</strong> {{ $product->getFirstImageUrl() }}</div>
            @endif
        </div>
    </div>

    <!-- نموذج تعديل المنتج -->
    <div class="bg-white rounded-lg shadow-custom">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center space-x-2 space-x-reverse">
                <i class="bi bi-pencil text-blue-500"></i>
                <span>تعديل معلومات المنتج</span>
            </h2>
        </div>
        <div class="p-6">
            <form action="{{ route('seller.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
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
                               value="{{ old('name', $product->name) }}"
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
                            <option value="إلكترونيات" {{ old('category', $product->category) == 'إلكترونيات' ? 'selected' : '' }}>إلكترونيات</option>
                            <option value="أجهزة منزلية" {{ old('category', $product->category) == 'أجهزة منزلية' ? 'selected' : '' }}>أجهزة منزلية</option>
                            <option value="سيارات" {{ old('category', $product->category) == 'سيارات' ? 'selected' : '' }}>سيارات</option>
                            <option value="عقارات" {{ old('category', $product->category) == 'عقارات' ? 'selected' : '' }}>عقارات</option>
                            <option value="أزياء" {{ old('category', $product->category) == 'أزياء' ? 'selected' : '' }}>أزياء</option>
                            <option value="تحف" {{ old('category', $product->category) == 'تحف' ? 'selected' : '' }}>تحف</option>
                            <option value="أخرى" {{ old('category', $product->category) == 'أخرى' ? 'selected' : '' }}>أخرى</option>
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
                              required>{{ old('description', $product->description) }}</textarea>
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
                                   value="{{ old('starting_price', $product->starting_price) }}"
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
                                   value="{{ old('max_price', $product->max_price) }}"
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

                <!-- عرض الصور الحالية مع تحسينات -->
                @if($product->images && count($product->images) > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        الصور الحالية ({{ count($product->images) }} صورة)
                    </label>
                    
                    <!-- معلومات تصحيح إضافية للصور -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-4">
                        <div class="text-xs text-gray-600 space-y-1">
                            <div><strong>تفاصيل الصور:</strong></div>
                            @foreach($product->images as $index => $image)
                                <div class="flex justify-between items-center">
                                    <span>الصورة {{ $index + 1 }}:</span>
                                    <span class="{{ file_exists(storage_path('app/public/products/' . $image)) ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $image }} 
                                        ({{ file_exists(storage_path('app/public/products/' . $image)) ? 'موجود' : 'مفقود' }})
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        @foreach($product->images as $index => $image)
                        <div class="relative group">
                            @if(file_exists(storage_path('app/public/products/' . $image)))
                                <img src="{{ $product->getImageUrl($image) }}" 
                                     alt="صورة المنتج {{ $index + 1 }}" 
                                     class="w-full h-24 object-cover rounded-lg border border-gray-200"
                                     onerror="this.style.display='none'; document.getElementById('fallback-{{ $product->id }}-{{ $index }}').style.display='flex';">
                            @else
                                <div id="fallback-{{ $product->id }}-{{ $index }}" class="w-full h-24 bg-red-100 rounded-lg border border-red-200 flex flex-col items-center justify-center p-2">
                                    <i class="bi bi-exclamation-triangle text-red-500 text-lg mb-1"></i>
                                    <span class="text-xs text-red-600 text-center">الملف مفقود</span>
                                </div>
                            @endif
                            
                            <!-- Fallback في حالة فشل تحميل الصورة -->
                            <div id="fallback-{{ $product->id }}-{{ $index }}" class="w-full h-24 bg-yellow-100 rounded-lg border border-yellow-200 flex flex-col items-center justify-center p-2 hidden">
                                <i class="bi bi-exclamation-triangle text-yellow-500 text-lg mb-1"></i>
                                <span class="text-xs text-yellow-600 text-center">فشل التحميل</span>
                            </div>
                            
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-lg transition-all duration-200 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <button type="button" 
                                        onclick="removeExistingImage({{ $index }})"
                                        class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full transition-colors duration-200">
                                    <i class="bi bi-trash text-sm"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <input type="hidden" id="removed_images" name="removed_images" value="">
                </div>
                @else
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                    <i class="bi bi-image text-gray-400 text-2xl mb-2"></i>
                    <p class="text-gray-600 text-sm">لا توجد صور لهذا المنتج</p>
                </div>
                @endif

                <!-- رفع صور جديدة -->
                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                        صور جديدة (اختياري)
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors duration-200">
                        <input type="file" 
                               id="images" 
                               name="images[]" 
                               multiple 
                               accept="image/*"
                               class="hidden"
                               onchange="previewNewImages(this)">
                        <div id="imageUploadArea" class="cursor-pointer" onclick="document.getElementById('images').click()">
                            <i class="bi bi-cloud-arrow-up text-4xl text-gray-400 mb-3"></i>
                            <p class="text-gray-600 mb-1">انقر لرفع صور جديدة</p>
                            <p class="text-sm text-gray-500">يمكنك رفع صور جديدة لاستبدال الصور الحالية</p>
                        </div>
                        <div id="newImagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-4 hidden"></div>
                    </div>
                    @error('images')
                        <p class="mt-1 text-sm text-red-600 flex items-center space-x-1 space-x-reverse">
                            <i class="bi bi-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- معلومات المزاد -->
                @if($product->auction)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-blue-800 mb-2 flex items-center space-x-2 space-x-reverse">
                        <i class="bi bi-info-circle"></i>
                        <span>معلومات المزاد</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-700">
                        <div>
                            <span class="font-medium">حالة المزاد:</span>
                            @if($product->auction->status === 'active')
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs mr-2">نشط</span>
                            @elseif($product->auction->status === 'ended')
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs mr-2">منتهي</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs mr-2">معلق</span>
                            @endif
                        </div>
                        <div>
                            <span class="font-medium">المزايدات:</span>
                            <span>{{ $product->auction->bids->count() }}</span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- أزرار الإجراءات -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2 space-x-reverse font-medium">
                        <i class="bi bi-check-circle"></i>
                        <span>حفظ التعديلات</span>
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

    // معاينة الصور الجديدة
    function previewNewImages(input) {
        const preview = document.getElementById('newImagePreview');
        const uploadArea = document.getElementById('imageUploadArea');
        
        preview.innerHTML = '';
        
        if (input.files && input.files.length > 0) {
            uploadArea.classList.add('hidden');
            preview.classList.remove('hidden');
            
            Array.from(input.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg border border-gray-200">
                        <button type="button" onclick="removeNewImage(${index})" class="absolute -top-2 -left-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200">
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

    // إزالة صورة جديدة من المعاينة
    function removeNewImage(index) {
        const input = document.getElementById('images');
        const dt = new DataTransfer();
        
        Array.from(input.files).forEach((file, i) => {
            if (i !== index) {
                dt.items.add(file);
            }
        });
        
        input.files = dt.files;
        previewNewImages(input);
    }

    // إزالة الصور الحالية
    let removedImages = [];
    function removeExistingImage(index) {
        removedImages.push(index);
        document.getElementById('removed_images').value = JSON.stringify(removedImages);
        
        // إخفاء الصورة بشكل بصري
        const imageElement = event.target.closest('.relative');
        imageElement.style.opacity = '0.5';
        imageElement.style.pointerEvents = 'none';
        
        // إضافة مؤشر الحذف
        const deleteIndicator = document.createElement('div');
        deleteIndicator.className = 'absolute inset-0 bg-red-500 bg-opacity-20 rounded-lg flex items-center justify-center';
        deleteIndicator.innerHTML = '<span class="text-red-600 font-bold text-sm">سيتم حذفها</span>';
        imageElement.appendChild(deleteIndicator);
    }

    // تسجيل معلومات التصحيح في الكونسول
    document.addEventListener('DOMContentLoaded', function() {
        console.log('=== Edit Product Debug Information ===');
        console.log('Product ID: {{ $product->id }}');
        console.log('Product Name: {{ $product->name }}');
        console.log('Images Array:', @json($product->images));
        @if($product->images && count($product->images) > 0)
            @foreach($product->images as $index => $image)
                console.log('Image {{ $index + 1 }}: {{ $image }}');
                console.log('File exists: {{ file_exists(storage_path('app/public/products/' . $image)) ? 'Yes' : 'No' }}');
                console.log('Full URL: {{ $product->getImageUrl($image) }}');
            @endforeach
        @endif
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