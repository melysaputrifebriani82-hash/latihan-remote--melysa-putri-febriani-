<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): RedirectResponse
    {
        return match ($request->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'seller' => redirect()->route('seller.dashboard'),
            default => redirect()->route('buyer.dashboard'),
        };
    }

    public function admin(): View
    {
        return view('dashboards.admin');
    }

    public function seller(Request $request): View
    {
        $sellerId = $request->user()->id;
        $products = Product::where('seller_id', $sellerId);

        $stats = [
            'total_products' => (clone $products)->count(),
            'pending_products' => (clone $products)->where('status', 'pending')->count(),
            'approved_products' => (clone $products)->where('status', 'approved')->count(),
            'rejected_products' => (clone $products)->where('status', 'rejected')->count(),
            'total_orders' => Order::whereHas('orderItems.product', fn ($query) => $query->where('seller_id', $sellerId))->count(),
            'revenue' => Product::where('seller_id', $sellerId)->join('order_items', 'products.id', '=', 'order_items.product_id')->join('orders', 'orders.id', '=', 'order_items.order_id')->where('orders.status', 'selesai')->sum('order_items.subtotal'),
        ];

        return view('dashboards.seller', compact('stats'));
    }

    public function buyer(): View
    {
        return view('dashboards.buyer');
    }
}
