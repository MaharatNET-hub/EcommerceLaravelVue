<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartService
{
    public function current(Request $request): Cart
    {
        if ($request->user()) {
            return Cart::firstOrCreate(['user_id' => $request->user()->id]);
        }

        $sessionId = $request->session()->get('cart_session_id');

        if (! $sessionId) {
            $sessionId = (string) Str::uuid();
            $request->session()->put('cart_session_id', $sessionId);
        }

        return Cart::firstOrCreate(['session_id' => $sessionId, 'user_id' => null]);
    }

    public function mergeGuestCartIntoUser(Request $request): void
    {
        $sessionId = $request->session()->get('cart_session_id');

        if (! $sessionId || ! $request->user()) {
            return;
        }

        $guestCart = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();

        if (! $guestCart) {
            return;
        }

        $userCart = Cart::firstOrCreate(['user_id' => $request->user()->id]);

        foreach ($guestCart->items as $item) {
            $existing = $userCart->items()
                ->where('product_id', $item->product_id)
                ->where('product_variant_id', $item->product_variant_id)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $item->quantity);
            } else {
                $userCart->items()->create([
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }
        }

        $guestCart->delete();
        $request->session()->forget('cart_session_id');
    }
}
