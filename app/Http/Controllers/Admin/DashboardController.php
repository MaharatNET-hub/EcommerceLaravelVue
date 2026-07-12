<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('dashboard.view');

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'products' => Product::count(),
                'categories' => Category::count(),
                'orders' => Order::count(),
                'customers' => User::count(),
                'pendingOrders' => Order::where('status', 'pending')->count(),
                'revenue' => (float) Order::where('payment_status', 'paid')->sum('total'),
            ],
            'recentOrders' => Order::with('user:id,name')
                ->latest()
                ->limit(8)
                ->get(['id', 'order_number', 'user_id', 'status', 'total', 'created_at']),
            'lowStockProducts' => Product::where('manage_stock', true)
                ->where('stock_quantity', '<=', 5)
                ->orderBy('stock_quantity')
                ->limit(8)
                ->get(['id', 'name', 'slug', 'stock_quantity']),
        ]);
    }
}
