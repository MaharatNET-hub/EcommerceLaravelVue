<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\SyncsSeo;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    use SyncsSeo;

    public function index(Request $request): Response
    {
        $this->authorize('products.view');

        $products = Product::query()
            ->with(['category:id,name', 'brand:id,name'])
            ->withCount('images')
            ->when($request->search, fn ($q, $search) => $q->where(fn ($qq) => $qq
                ->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%")))
            ->when($request->category_id, fn ($q, $categoryId) => $q->where('category_id', $categoryId))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'filters' => $request->only('search', 'category_id'),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('products.create');

        return Inertia::render('Admin/Products/Form', [
            'product' => null,
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'brands' => Brand::orderBy('name')->get(['id', 'name']),
            'attributes' => \App\Models\ProductAttribute::with('values')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('products.create');

        $data = $this->validateData($request);
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['name']);
        unset($data['images']);

        $product = Product::create($data);
        $this->syncSeo($product, $request->input('seo'));
        $this->storeImages($product, $request);

        return redirect()->route('admin.products.index')->with('success', 'تم إنشاء المنتج بنجاح.');
    }

    public function edit(Product $product): Response
    {
        $this->authorize('products.update');

        return Inertia::render('Admin/Products/Form', [
            'product' => $product->load(['seo', 'images', 'variants.attributeValues.attribute']),
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'brands' => Brand::orderBy('name')->get(['id', 'name']),
            'attributes' => \App\Models\ProductAttribute::with('values')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('products.update');

        $data = $this->validateData($request, $product->id);
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['name']);
        unset($data['images']);

        $product->update($data);
        $this->syncSeo($product, $request->input('seo'));
        $this->storeImages($product, $request);

        return redirect()->route('admin.products.index')->with('success', 'تم تحديث المنتج بنجاح.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('products.delete');

        $product->delete();

        return back()->with('success', 'تم حذف المنتج.');
    }

    public function destroyImage(Product $product, ProductImage $image): RedirectResponse
    {
        $this->authorize('products.update');

        abort_unless($image->product_id === $product->id, 404);

        \Illuminate\Support\Facades\Storage::disk('public')->delete($image->path);
        $image->delete();

        return back()->with('success', 'تم حذف الصورة.');
    }

    private function storeImages(Product $product, Request $request): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $hasPrimary = $product->images()->where('is_primary', true)->exists();
        $nextOrder = (int) $product->images()->max('sort_order');

        foreach ($request->file('images') as $index => $file) {
            $path = $file->store('products', 'public');

            $product->images()->create([
                'path' => $path,
                'sort_order' => ++$nextOrder,
                'is_primary' => ! $hasPrimary && $index === 0,
            ]);
        }
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug'.($ignoreId ? ",{$ignoreId}" : '')],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'.($ignoreId ? ",{$ignoreId}" : '')],
            'description' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'manage_stock' => ['boolean'],
            'in_stock' => ['boolean'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:4096'],
            ...$this->seoValidationRules(),
        ]);
    }
}
