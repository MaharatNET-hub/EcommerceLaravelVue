<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $xml = Cache::remember('sitemap.xml', now()->addHours(6), function () {
            $urls = collect();

            $urls->push(['loc' => url('/'), 'priority' => '1.0']);

            Category::query()->active()->select('slug', 'updated_at')->get()->each(
                fn (Category $category) => $urls->push([
                    'loc' => url('/categories/'.$category->slug),
                    'lastmod' => $category->updated_at->toAtomString(),
                    'priority' => '0.8',
                ])
            );

            Brand::query()->active()->select('slug', 'updated_at')->get()->each(
                fn (Brand $brand) => $urls->push([
                    'loc' => url('/brands/'.$brand->slug),
                    'lastmod' => $brand->updated_at->toAtomString(),
                    'priority' => '0.7',
                ])
            );

            Product::query()->active()->select('slug', 'updated_at')->get()->each(
                fn (Product $product) => $urls->push([
                    'loc' => url('/products/'.$product->slug),
                    'lastmod' => $product->updated_at->toAtomString(),
                    'priority' => '0.9',
                ])
            );

            Page::query()->active()->select('slug', 'updated_at')->get()->each(
                fn (Page $page) => $urls->push([
                    'loc' => url('/p/'.$page->slug),
                    'lastmod' => $page->updated_at->toAtomString(),
                    'priority' => '0.5',
                ])
            );

            return view('sitemap', ['urls' => $urls])->render();
        });

        return response($xml, 200)->header('Content-Type', 'text/xml');
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /cart',
            'Disallow: /checkout',
            'Sitemap: '.url('/sitemap.xml'),
        ];

        return response(implode("\n", $lines), 200)->header('Content-Type', 'text/plain');
    }
}
