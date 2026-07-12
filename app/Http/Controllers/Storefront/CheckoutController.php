<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Setting;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function __construct(private readonly CartService $cartService) {}

    public function create(Request $request): Response
    {
        $cart = $this->cartService->current($request);
        $cart->load(['items.product', 'items.variant']);

        abort_if($cart->items->isEmpty(), 404);

        return Inertia::render('Storefront/Checkout', [
            'cart' => $cart,
            'addresses' => $request->user()->addresses()->orderByDesc('is_default')->get(),
            'shippingFee' => (float) Setting::get('shipping_flat_fee', 0),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'country' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'address_line' => ['required', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $cart = $this->cartService->current($request);
        $cart->load(['items.product', 'items.variant']);

        abort_if($cart->items->isEmpty(), 404);

        $order = DB::transaction(function () use ($data, $cart, $request) {
            $subtotal = round((float) $cart->items->sum(fn ($item) => (float) $item->price * $item->quantity), 2);

            $coupon = null;
            $discount = 0;

            if (! empty($data['coupon_code'])) {
                $coupon = Coupon::where('code', $data['coupon_code'])->first();

                if ($coupon && $coupon->isValidFor($subtotal)) {
                    $discount = $coupon->calculateDiscount($subtotal);
                } else {
                    $coupon = null;
                }
            }

            $shippingFee = (float) Setting::get('shipping_flat_fee', 0);
            $total = max(0, $subtotal - $discount) + $shippingFee;

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $request->user()->id,
                'coupon_id' => $coupon?->id,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'payment_method' => 'cod',
                'payment_status' => 'pending',
                'shipping_address' => [
                    'full_name' => $data['full_name'],
                    'phone' => $data['phone'],
                    'country' => $data['country'],
                    'city' => $data['city'],
                    'address_line' => $data['address_line'],
                    'postal_code' => $data['postal_code'] ?? null,
                ],
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $item->product->name,
                    'sku' => $item->variant?->sku ?? $item->product->sku,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'total' => round((float) $item->price * $item->quantity, 2),
                ]);

                if ($item->product->manage_stock) {
                    $item->product->decrement('stock_quantity', $item->quantity);
                }
            }

            if ($coupon) {
                $coupon->increment('used_count');
            }

            $cart->items()->delete();

            return $order;
        });

        return redirect()->route('orders.show', $order)->with('success', 'تم إنشاء طلبك بنجاح.');
    }
}
