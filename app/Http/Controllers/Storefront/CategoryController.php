<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function show(Request $request, Category $category): Response
    {
        abort_unless($category->is_active, 404);

        $categoryIds = $category->children()->pluck('id')->push($category->id);

        $products = \App\Models\Product::query()
            ->active()
            ->with(['images', 'brand:id,name,slug'])
            ->whereIn('category_id', $categoryIds)
            ->when($request->brand_id, fn ($q, $brandId) => $q->where('brand_id', $brandId))
            ->when($request->min_price, fn ($q, $min) => $q->where('price', '>=', $min))
            ->when($request->max_price, fn ($q, $max) => $q->where('price', '<=', $max))
            ->when($request->sort === 'price_asc', fn ($q) => $q->orderBy('price'))
            ->when($request->sort === 'price_desc', fn ($q) => $q->orderByDesc('price'))
            ->when($request->sort === 'newest', fn ($q) => $q->latest())
            ->when(! $request->sort, fn ($q) => $q->orderBy('sort_order'))
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Storefront/CategoryShow', [
            'category' => $category->load(['seo', 'children' => fn ($q) => $q->active()]),
            'products' => $products,
            'brands' => Brand::active()->whereIn('id', \App\Models\Product::whereIn('category_id', $categoryIds)->pluck('brand_id'))->get(),
            'filters' => $request->only('brand_id', 'min_price', 'max_price', 'sort'),
            'breadcrumbs' => $this->breadcrumbs($category),
        ]);
    }

    private function breadcrumbs(Category $category): array
    {
        $trail = collect();
        $node = $category;

        while ($node) {
            $trail->prepend(['name' => $node->name, 'slug' => $node->slug]);
            $node = $node->parent;
        }

        return $trail->values()->all();
    }
}
