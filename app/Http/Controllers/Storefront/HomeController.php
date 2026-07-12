<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Slider;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Storefront/Home', [
            'sliders' => Slider::active()->orderBy('sort_order')->get(),
            'categories' => Category::active()->whereNull('parent_id')->orderBy('sort_order')->limit(8)->get(),
            'featuredProducts' => Product::active()->featured()->with(['images', 'category:id,name,slug'])->latest()->limit(12)->get(),
            'newProducts' => Product::active()->with(['images', 'category:id,name,slug'])->latest()->limit(8)->get(),
            'brands' => Brand::active()->orderBy('sort_order')->limit(10)->get(),
            'seo' => [
                'title' => Setting::get('seo_default_meta_title'),
                'description' => Setting::get('seo_default_meta_description'),
                'og_image' => Setting::get('seo_default_og_image'),
            ],
        ]);
    }
}
