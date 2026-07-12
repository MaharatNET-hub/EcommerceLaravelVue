<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Inertia\Inertia;
use Inertia\Response;

class BrandController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Storefront/BrandIndex', [
            'brands' => Brand::active()->withCount('products')->orderBy('name')->get(),
        ]);
    }

    public function show(Brand $brand): Response
    {
        abort_unless($brand->is_active, 404);

        return Inertia::render('Storefront/BrandShow', [
            'brand' => $brand->load('seo'),
            'products' => $brand->products()->active()->with('images')->paginate(12),
        ]);
    }
}
