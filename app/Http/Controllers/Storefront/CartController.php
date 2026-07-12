<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService) {}

    public function index(Request $request): Response
    {
        $cart = $this->cartService->current($request);
        $cart->load(['items.product.images', 'items.variant.attributeValues.attribute']);

        return Inertia::render('Storefront/Cart', ['cart' => $cart]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'product_variant_id' => ['nullable', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $variantId = $data['product_variant_id'] ?? null;

        $product = Product::findOrFail($data['product_id']);
        $variant = $variantId ? ProductVariant::find($variantId) : null;
        $price = $variant ? $variant->effective_price : ($product->sale_price ?: $product->price);

        $cart = $this->cartService->current($request);

        $item = $cart->items()
            ->where('product_id', $product->id)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($item) {
            $item->increment('quantity', $data['quantity']);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'product_variant_id' => $variantId,
                'quantity' => $data['quantity'],
                'price' => $price,
            ]);
        }

        return back()->with('success', 'تمت الإضافة إلى السلة.');
    }

    public function update(Request $request, \App\Models\CartItem $item): RedirectResponse
    {
        $cart = $this->cartService->current($request);
        abort_unless($item->cart_id === $cart->id, 404);

        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1']]);
        $item->update(['quantity' => $data['quantity']]);

        return back()->with('success', 'تم تحديث الكمية.');
    }

    public function destroy(Request $request, \App\Models\CartItem $item): RedirectResponse
    {
        $cart = $this->cartService->current($request);
        abort_unless($item->cart_id === $cart->id, 404);

        $item->delete();

        return back()->with('success', 'تم حذف المنتج من السلة.');
    }
}
