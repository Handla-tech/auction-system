<?php
// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Auction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    // عرض قائمة منتجات البائع
    public function index()
    {
        $products = Product::where('seller_id', auth()->id())->latest()->get();
        return view('seller.products.index', compact('products'));
    }

    // عرض نموذج إضافة منتج
    public function create()
    {
        return view('seller.products.create');
    }

    // حفظ المنتج الجديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'starting_price' => 'required|numeric|min:0',
            'max_price' => 'required|numeric|min:0|gt:starting_price',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
        ]);

        // رفع الصور أولاً
        $uploadedImages = $this->uploadImages($request->file('images'));
        
        // تسجيل معلومات التصحيح
        Log::info('Upload attempt - Files received: ' . ($request->hasFile('images') ? 'Yes' : 'No'));
        if ($request->hasFile('images')) {
            Log::info('Number of files: ' . count($request->file('images')));
            Log::info('Uploaded images count: ' . count($uploadedImages));
        }
        
        // حفظ المنتج
        $product = Product::create([
            'seller_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'starting_price' => $request->starting_price,
            'max_price' => $request->max_price,
            'images' => $uploadedImages,
        ]);

        // إنشاء المزاد للمنتج
        Auction::create([
            'product_id' => $product->id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'current_bid' => $request->starting_price,
            'status' => 'active',
        ]);

        return redirect()->route('seller.products.index')
            ->with('success', 'تم إضافة المنتج بنجاح وسيبدأ المزاد في الوقت المحدد.');
    }

    // عرض تفاصيل المنتج
    public function show(Product $product)
    {
        $product->load(['auction', 'seller']);
        return view('products.show', compact('product'));
    }

    // عرض نموذج تعديل المنتج
    public function edit(Product $product)
    {
        // التحقق من ملكية المنتج
        if ($product->seller_id !== auth()->id()) {
            abort(403);
        }

        return view('seller.products.edit', compact('product'));
    }

    // تحديث المنتج
    public function update(Request $request, Product $product)
    {
        // التحقق من ملكية المنتج
        if ($product->seller_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'starting_price' => 'required|numeric|min:0',
            'max_price' => 'required|numeric|min:0|gt:starting_price',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // معالجة الصور
        $images = $product->images ?? [];
        if ($request->hasFile('images')) {
            // حذف الصور القديمة
            if (!empty($images)) {
                foreach ($images as $image) {
                    if (Storage::exists('public/products/' . $image)) {
                        Storage::delete('public/products/' . $image);
                    }
                }
            }
            // رفع الصور الجديدة
            $images = $this->uploadImages($request->file('images'));
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'starting_price' => $request->starting_price,
            'max_price' => $request->max_price,
            'images' => $images,
        ]);

        return redirect()->route('seller.products.index')
            ->with('success', 'تم تحديث المنتج بنجاح.');
    }

    // حذف المنتج
    public function destroy(Product $product)
    {
        // التحقق من ملكية المنتج
        if ($product->seller_id !== auth()->id()) {
            abort(403);
        }

        // حذف الصور
        if ($product->images && !empty($product->images)) {
            foreach ($product->images as $image) {
                if (Storage::exists('public/products/' . $image)) {
                    Storage::delete('public/products/' . $image);
                }
            }
        }

        $product->delete();

        return redirect()->route('seller.products.index')
            ->with('success', 'تم حذف المنتج بنجاح.');
    }

    // 🎯 دالة محدثة: عرض المنتجات المتاحة للمشتري مع البحث والتصفية
    public function buyerProducts(Request $request)
    {
        $query = Product::with(['auction', 'seller'])
            ->whereHas('auction', function($query) {
                $query->where('status', 'active')
                      ->where('end_time', '>', now());
            });

        // البحث بالكلمات المفتاحية
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // التصفية حسب الفئة
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // التصفية حسب السعر
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('starting_price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('max_price', '<=', $request->max_price);
        }

        // الترتيب
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('starting_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('max_price', 'desc');
                break;
            case 'ending_soon':
                $query->join('auctions', 'products.id', '=', 'auctions.product_id')
                      ->orderBy('auctions.end_time', 'asc')
                      ->select('products.*');
                break;
            case 'most_bids':
                $query->join('auctions', 'products.id', '=', 'auctions.product_id')
                      ->join('bids', 'auctions.id', '=', 'bids.auction_id')
                      ->groupBy('products.id')
                      ->orderByRaw('COUNT(bids.id) DESC')
                      ->select('products.*');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12);
        $categories = Product::distinct()->pluck('category');

        return view('buyer.products', compact('products', 'categories'));
    }

    // 🎯 دالة جديدة: قائمة المنتجات العامة
    public function publicProducts(Request $request)
    {
        $query = Product::with(['auction', 'seller'])
            ->whereHas('auction', function($query) {
                $query->where('status', 'active')
                      ->where('end_time', '>', now());
            });

        // البحث بالكلمات المفتاحية
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // التصفية حسب الفئة
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // التصفية حسب السعر
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('starting_price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('max_price', '<=', $request->max_price);
        }

        // الترتيب
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('starting_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('max_price', 'desc');
                break;
            case 'ending_soon':
                $query->join('auctions', 'products.id', '=', 'auctions.product_id')
                      ->orderBy('auctions.end_time', 'asc')
                      ->select('products.*');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12);
        $categories = Product::distinct()->pluck('category');

        return view('products.index', compact('products', 'categories'));
    }

    // 🎯 دالة جديدة: الحصول على فئات المنتجات (للاستخدام في API)
    public function getCategories()
    {
        $categories = Product::distinct()->pluck('category');
        return response()->json($categories);
    }

    // دالة مساعدة لرفع الصور - محدثة
    private function uploadImages($images)
    {
        // إذا لم توجد صور أو لم يتم رفع أي صورة، ارجع مصفوفة فارغة
        if (!$images || (is_array($images) && count($images) === 0)) {
            Log::info('No images provided for upload');
            return [];
        }

        $uploadedImages = [];
        
        // تأكد أن $images هي مصفوفة
        $images = is_array($images) ? $images : [$images];
        
        // التأكد من وجود المجلد
        $productsPath = storage_path('app/public/products');
        if (!File::exists($productsPath)) {
            File::makeDirectory($productsPath, 0755, true);
            Log::info('Created products directory: ' . $productsPath);
        }
        
        foreach ($images as $image) {
            // تأكد أن العنصر هو ملف صحيح
            if (!$image) {
                Log::warning('Image file is null');
                continue;
            }
            
            if (!$image->isValid()) {
                Log::warning('Image file is not valid: ' . $image->getError());
                continue;
            }
            
            $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            try {
                // رفع الصورة مع التحقق من النجاح
                $path = $image->storeAs('public/products', $fileName);
                
                if ($path) {
                    $uploadedImages[] = $fileName;
                    Log::info('Image uploaded successfully: ' . $fileName . ' to ' . $path);
                    
                    // التحقق من وجود الملف فعلياً
                    $fullPath = storage_path('app/' . $path);
                    if (File::exists($fullPath)) {
                        Log::info('File confirmed to exist at: ' . $fullPath);
                    } else {
                        Log::error('File uploaded but not found at: ' . $fullPath);
                    }
                } else {
                    Log::error('Failed to upload image: ' . $fileName);
                }
            } catch (\Exception $e) {
                Log::error('Exception during image upload: ' . $e->getMessage());
            }
        }

        Log::info('Total images uploaded: ' . count($uploadedImages));
        return $uploadedImages;
    }
}