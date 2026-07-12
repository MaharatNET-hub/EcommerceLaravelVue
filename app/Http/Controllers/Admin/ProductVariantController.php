<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('products.update');

        $data = $this->validateData($request);
        $attributeValueIds = $data['attribute_value_ids'] ?? [];
        unset($data['attribute_value_ids']);

        $variant = $product->variants()->create($data);
        $variant->attributeValues()->sync($attributeValueIds);

        return back()->with('success', 'تمت إضافة المتغير.');
    }

    public function update(Request $request, Product $product, ProductVariant $variant): RedirectResponse
    {
        $this->authorize('products.update');

        abort_unless($variant->product_id === $product->id, 404);

        $data = $this->validateData($request, $variant->id);
        $attributeValueIds = $data['attribute_value_ids'] ?? [];
        unset($data['attribute_value_ids']);

        $variant->update($data);
        $variant->attributeValues()->sync($attributeValueIds);

        return back()->with('success', 'تم تحديث المتغير.');
    }

    public function destroy(Product $product, ProductVariant $variant): RedirectResponse
    {
        $this->authorize('products.update');

        abort_unless($variant->product_id === $product->id, 404);
        $variant->delete();

        return back()->with('success', 'تم حذف المتغير.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'sku' => ['required', 'string', 'max:100', 'unique:product_variants,sku'.($ignoreId ? ",{$ignoreId}" : '')],
            'price' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'attribute_value_ids' => ['nullable', 'array'],
            'attribute_value_ids.*' => ['exists:product_attribute_values,id'],
        ]);
    }
}
