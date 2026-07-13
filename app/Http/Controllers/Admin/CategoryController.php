<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesUploads;
use App\Http\Controllers\Concerns\SerializesTranslations;
use App\Http\Controllers\Concerns\SyncsSeo;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    use HandlesUploads, SerializesTranslations, SyncsSeo;

    public function index(Request $request): Response
    {
        $this->authorize('categories.view');

        $categories = Category::query()
            ->with('parent:id,name')
            ->withCount('products')
            ->when($request->search, fn ($q, $search) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Categories/Index', [
            'categories' => $categories,
            'filters' => $request->only('search'),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('categories.create');

        return Inertia::render('Admin/Categories/Form', [
            'category' => null,
            'parents' => Category::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('categories.create');

        $data = $this->validateData($request);
        $data['image'] = $this->storeUpload($request->file('image'), 'categories');
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($this->primaryLocaleValue($data['name']));

        $category = Category::create($data);
        $this->syncSeo($category, $request->input('seo'));

        return redirect()->route('admin.categories.index')->with('success', 'تم إنشاء التصنيف بنجاح.');
    }

    public function edit(Category $category): Response
    {
        $this->authorize('categories.update');

        return Inertia::render('Admin/Categories/Form', [
            'category' => $this->withAllTranslations($category->load('seo')),
            'parents' => Category::where('id', '!=', $category->id)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $this->authorize('categories.update');

        $data = $this->validateData($request, $category->id);
        $data['image'] = $this->replaceUpload($request->file('image'), 'categories', $category->image);
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($this->primaryLocaleValue($data['name']));

        $category->update($data);
        $this->syncSeo($category, $request->input('seo'));

        return redirect()->route('admin.categories.index')->with('success', 'تم تحديث التصنيف بنجاح.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('categories.delete');

        $category->delete();

        return back()->with('success', 'تم حذف التصنيف.');
    }

    private function primaryLocaleValue(array $translations): string
    {
        return $translations['ar'] ?? $translations['en'] ?? '';
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name.ar' => ['required', 'string', 'max:255'],
            'name.en' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categories,slug'.($ignoreId ? ",{$ignoreId}" : '')],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'description.ar' => ['nullable', 'string'],
            'description.en' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
            ...$this->seoValidationRules(),
        ]);
    }
}
