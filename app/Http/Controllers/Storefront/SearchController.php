<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    public function index(Request $request): Response
    {
        $term = (string) $request->input('q', '');

        $products = Product::query()
            ->active()
            ->with(['images', 'category:id,name,slug', 'brand:id,name,slug'])
            ->when($term, fn ($q) => $q->where(fn ($qq) => $qq
                ->where('name', 'like', "%{$term}%")
                ->orWhere('sku', 'like', "%{$term}%")
                ->orWhere('short_description', 'like', "%{$term}%")))
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Storefront/Search', [
            'products' => $products,
            'query' => $term,
        ]);
    }
}
