<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductAttribute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProductAttributeController extends Controller
{
    public function index(): Response
    {
        $this->authorize('products.update');

        return Inertia::render('Admin/Attributes/Index', [
            'attributes' => ProductAttribute::with('values')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('products.update');

        $data = $request->validate(['name' => ['required', 'string', 'max:100']]);
        ProductAttribute::create(['name' => $data['name'], 'slug' => Str::slug($data['name'])]);

        return back()->with('success', 'تمت إضافة الخاصية.');
    }

    public function destroy(ProductAttribute $attribute): RedirectResponse
    {
        $this->authorize('products.update');

        $attribute->delete();

        return back()->with('success', 'تم حذف الخاصية.');
    }

    public function storeValue(Request $request, ProductAttribute $attribute): RedirectResponse
    {
        $this->authorize('products.update');

        $data = $request->validate([
            'value' => ['required', 'string', 'max:100'],
            'color_code' => ['nullable', 'string', 'max:20'],
        ]);

        $attribute->values()->create([
            'value' => $data['value'],
            'slug' => Str::slug($data['value']),
            'color_code' => $data['color_code'] ?? null,
        ]);

        return back()->with('success', 'تمت إضافة القيمة.');
    }

    public function destroyValue(ProductAttribute $attribute, \App\Models\ProductAttributeValue $value): RedirectResponse
    {
        $this->authorize('products.update');

        abort_unless($value->product_attribute_id === $attribute->id, 404);
        $value->delete();

        return back()->with('success', 'تم حذف القيمة.');
    }
}
