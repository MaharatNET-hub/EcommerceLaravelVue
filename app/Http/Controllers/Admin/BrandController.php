<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesUploads;
use App\Http\Controllers\Concerns\SyncsSeo;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BrandController extends Controller
{
    use HandlesUploads, SyncsSeo;

    public function index(Request $request): Response
    {
        $this->authorize('brands.view');

        $brands = Brand::query()
            ->withCount('products')
            ->when($request->search, fn ($q, $search) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Brands/Index', [
            'brands' => $brands,
            'filters' => $request->only('search'),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('brands.create');

        return Inertia::render('Admin/Brands/Form', ['brand' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('brands.create');

        $data = $this->validateData($request);
        $data['logo'] = $this->storeUpload($request->file('logo'), 'brands');
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['name']);

        $brand = Brand::create($data);
        $this->syncSeo($brand, $request->input('seo'));

        return redirect()->route('admin.brands.index')->with('success', 'تم إنشاء البراند بنجاح.');
    }

    public function edit(Brand $brand): Response
    {
        $this->authorize('brands.update');

        return Inertia::render('Admin/Brands/Form', ['brand' => $brand->load('seo')]);
    }

    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $this->authorize('brands.update');

        $data = $this->validateData($request, $brand->id);
        $data['logo'] = $this->replaceUpload($request->file('logo'), 'brands', $brand->logo);
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['name']);

        $brand->update($data);
        $this->syncSeo($brand, $request->input('seo'));

        return redirect()->route('admin.brands.index')->with('success', 'تم تحديث البراند بنجاح.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $this->authorize('brands.delete');

        $brand->delete();

        return back()->with('success', 'تم حذف البراند.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:brands,slug'.($ignoreId ? ",{$ignoreId}" : '')],
            'description' => ['nullable', 'string'],
            'website' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:4096'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
            ...$this->seoValidationRules(),
        ]);
    }
}
