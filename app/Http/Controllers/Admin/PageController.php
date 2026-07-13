<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\SerializesTranslations;
use App\Http\Controllers\Concerns\SyncsSeo;
use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    use SerializesTranslations, SyncsSeo;

    public function index(Request $request): Response
    {
        $this->authorize('pages.view');

        return Inertia::render('Admin/Pages/Index', [
            'pages' => Page::query()
                ->when($request->search, fn ($q, $search) => $q->where('title', 'like', "%{$search}%"))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'filters' => $request->only('search'),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('pages.create');

        return Inertia::render('Admin/Pages/Form', ['page' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('pages.create');

        $data = $this->validateData($request);
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($this->primaryLocaleValue($data['title']));

        $page = Page::create($data);
        $this->syncSeo($page, $request->input('seo'));

        return redirect()->route('admin.pages.index')->with('success', 'تم إنشاء الصفحة.');
    }

    public function edit(Page $page): Response
    {
        $this->authorize('pages.update');

        return Inertia::render('Admin/Pages/Form', ['page' => $this->withAllTranslations($page->load('seo'))]);
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $this->authorize('pages.update');

        $data = $this->validateData($request, $page->id);
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($this->primaryLocaleValue($data['title']));

        $page->update($data);
        $this->syncSeo($page, $request->input('seo'));

        return redirect()->route('admin.pages.index')->with('success', 'تم تحديث الصفحة.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $this->authorize('pages.delete');

        $page->delete();

        return back()->with('success', 'تم حذف الصفحة.');
    }

    private function primaryLocaleValue(array $translations): string
    {
        return $translations['ar'] ?? $translations['en'] ?? '';
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title.ar' => ['required', 'string', 'max:255'],
            'title.en' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:pages,slug'.($ignoreId ? ",{$ignoreId}" : '')],
            'content.ar' => ['nullable', 'string'],
            'content.en' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'published_at' => ['nullable', 'date'],
            ...$this->seoValidationRules(),
        ]);
    }
}
