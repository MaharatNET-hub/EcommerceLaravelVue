<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CouponController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('coupons.view');

        return Inertia::render('Admin/Coupons/Index', [
            'coupons' => Coupon::query()
                ->when($request->search, fn ($q, $search) => $q->where('code', 'like', "%{$search}%"))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'filters' => $request->only('search'),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('coupons.create');

        return Inertia::render('Admin/Coupons/Form', ['coupon' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('coupons.create');

        Coupon::create($this->validateData($request));

        return redirect()->route('admin.coupons.index')->with('success', 'تم إنشاء الكوبون.');
    }

    public function edit(Coupon $coupon): Response
    {
        $this->authorize('coupons.update');

        return Inertia::render('Admin/Coupons/Form', ['coupon' => $coupon]);
    }

    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $this->authorize('coupons.update');

        $coupon->update($this->validateData($request, $coupon->id));

        return redirect()->route('admin.coupons.index')->with('success', 'تم تحديث الكوبون.');
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $this->authorize('coupons.delete');

        $coupon->delete();

        return back()->with('success', 'تم حذف الكوبون.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code'.($ignoreId ? ",{$ignoreId}" : '')],
            'type' => ['required', 'in:fixed,percent'],
            'value' => ['required', 'numeric', 'min:0'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'max_uses' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after:starts_at'],
            'is_active' => ['boolean'],
        ]);
    }
}
