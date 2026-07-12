<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('orders.view');

        $orders = Order::query()
            ->with('user:id,name,email')
            ->when($request->search, fn ($q, $search) => $q->where('order_number', 'like', "%{$search}%"))
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Orders/Index', [
            'orders' => $orders,
            'statuses' => Order::STATUSES,
            'filters' => $request->only('search', 'status'),
        ]);
    }

    public function show(Order $order): Response
    {
        $this->authorize('orders.view');

        return Inertia::render('Admin/Orders/Show', [
            'order' => $order->load(['user:id,name,email', 'items.product:id,name,slug', 'coupon:id,code']),
            'statuses' => Order::STATUSES,
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $this->authorize('orders.update');

        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', Order::STATUSES)],
            'payment_status' => ['required', 'in:pending,paid,refunded,failed'],
        ]);

        $order->update($data);

        return back()->with('success', 'تم تحديث الطلب.');
    }
}
