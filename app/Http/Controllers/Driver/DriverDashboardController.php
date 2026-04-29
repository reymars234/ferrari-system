<?php
// FILE: app/Http/Controllers/Driver/DriverDashboardController.php — REPLACE ENTIRE FILE

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Message;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class DriverDashboardController extends Controller
{
    // ── Dashboard ─────────────────────────────────────────────────
    public function index()
    {
        $driver = auth()->user();

        $stats = [
            'total'   => Order::where('driver_id', $driver->id)->count(),
            'pending' => Order::where('driver_id', $driver->id)->where('status', 'pending')->count(),
            'active'  => Order::where('driver_id', $driver->id)->where('status', 'processing')->count(),
            'done'    => Order::where('driver_id', $driver->id)->where('status', 'delivered')->count(),
            'unread'  => Message::where('receiver_id', $driver->id)->where('is_read', false)->count(),
            // COD orders awaiting driver's payment confirmation
            'cod_pending' => Order::where('driver_id', $driver->id)
                ->where('status', 'delivered')
                ->where('payment_method', 'cod')
                ->where('cod_paid', false)
                ->count(),
        ];

        $recentOrders = Order::where('driver_id', $driver->id)
            ->with(['user', 'car'])
            ->latest()
            ->take(5)
            ->get();

        return view('driver.dashboard', compact('stats', 'recentOrders'));
    }

    // ── All Orders ────────────────────────────────────────────────
    public function orders()
    {
        $orders = Order::where('driver_id', auth()->id())
            ->with(['user', 'car'])
            ->latest()
            ->paginate(15);

        return view('driver.orders', compact('orders'));
    }

    // ── Mark Order as Delivered ───────────────────────────────────
    public function markDelivered(Order $order)
    {
        abort_unless($order->driver_id === auth()->id(), 403);
        abort_unless($order->status === 'processing', 403, 'Order is not in processing state.');

        $order->update(['status' => 'delivered']);

        AuditLogService::log(
            'ORDER_DELIVERED',
            'Orders',
            "Driver marked Order #{$order->id} as Delivered."
        );

        return back()->with('success', "Order #{$order->id} marked as Delivered!");
    }

    // ── Mark COD as Paid (customer paid cash to driver) ───────────
    public function markCodPaid(Order $order)
    {
        abort_unless($order->driver_id === auth()->id(), 403);

        // Only delivered COD orders that haven't been marked paid
        abort_unless($order->isCodMarkable(), 403, 'This order cannot be marked as paid.');

        $order->update([
            'cod_paid'          => true,
            'cod_paid_at'       => now(),
            'cod_confirmed_by'  => auth()->id(),
            'payment_status'    => 'paid',   // update payment_status so admin sees it
        ]);

        AuditLogService::log(
            'COD_PAYMENT_CONFIRMED',
            'Orders',
            "Driver confirmed COD payment received for Order #{$order->id} — ₱" . number_format($order->total_price, 2)
        );

        return back()->with('success', "COD payment for Order #{$order->id} confirmed! Admin has been notified.");
    }

    
}   