<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Car, Order, AuditLog};

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'    => User::where('role', 'user')->count(),
            'total_cars'     => Car::count(),
            'total_orders'   => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue'  => Order::whereIn('status', ['processing','delivered'])->sum('total_price'),
        ];

        $recentOrders = Order::with(['user','car'])->latest()->take(5)->get();
        $recentLogs   = AuditLog::with('user')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentLogs'));
    }
}