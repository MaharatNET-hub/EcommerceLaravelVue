<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function show(Product $product): Response
    {
        abort_unless($product->is_active, 404);

        $product->increment('views_count');

        $product->load([
            'seo',
            'category:id,name,slug',
            'brand:id,name,slug',
            'images',
            'variants.attributeValues.attribute',
            'approvedReviews.user:id,name',
        ]);

        $related = Product::active()
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->with('images')
            ->limit(8)
            ->get();

        return Inertia::render('Storefront/ProductShow', [
            'product' => $product,
            'related' => $related,
        ]);
    }
}
