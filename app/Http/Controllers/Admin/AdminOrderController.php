<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Order;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $query = Order::with(['user', 'car', 'driver'])->latest();

        if (request('status') && request('status') !== 'all') {
            $query->where('status', request('status'));
        }

        $orders = $query->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'car', 'driver', 'messages.sender']);

        $drivers = User::where('role', 'driver')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $adminId = auth()->id();

        // Admin ↔ Driver messages
        $driverMessages = collect();
        if ($order->driver_id) {
            $driverId = $order->driver_id;
            $driverMessages = Message::where('order_id', $order->id)
                ->where('chat_type', 'admin_driver')
                ->where(function ($q) use ($adminId, $driverId) {
                    $q->where(function ($i) use ($adminId, $driverId) {
                        $i->where('sender_id', $adminId)->where('receiver_id', $driverId);
                    })->orWhere(function ($i) use ($adminId, $driverId) {
                        $i->where('sender_id', $driverId)->where('receiver_id', $adminId);
                    });
                })
                ->with('sender')
                ->orderBy('created_at')
                ->get();
        }

        // Admin ↔ Customer messages
        $userId = $order->user_id;
        $userMessages = Message::where('order_id', $order->id)
            ->where('chat_type', 'admin_user')
            ->where(function ($q) use ($adminId, $userId) {
                $q->where(function ($i) use ($adminId, $userId) {
                    $i->where('sender_id', $adminId)->where('receiver_id', $userId);
                })->orWhere(function ($i) use ($adminId, $userId) {
                    $i->where('sender_id', $userId)->where('receiver_id', $adminId);
                });
            })
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        return view('admin.orders.show', compact('order', 'drivers', 'driverMessages', 'userMessages'));
    }

    public function accept(Order $order)
    {
        // Block: cancelled orders cannot be accepted
        if ($order->status === 'cancelled') {
            return back()->with('error', "Order #{$order->id} has been cancelled by the customer and cannot be accepted.");
        }

        $order->update([
            'admin_accepted'    => true,
            'admin_accepted_at' => now(),
            'status'            => 'processing',
        ]);

        AuditLogService::log('ORDER_ACCEPTED', 'Orders', "Admin accepted Order #{$order->id}");

        return back()->with('success', "Order #{$order->id} accepted and set to Processing.");
    }

    public function updateStatus(Request $request, Order $order)
    {
        // Block: cancelled orders cannot be updated
        if ($order->status === 'cancelled') {
            return back()->with('error', "Order #{$order->id} has been cancelled and its status cannot be changed.");
        }

        $request->validate([
            'status'             => 'required|in:pending,processing,delivered,cancelled',
            'delivery_notes'     => 'nullable|string|max:500',
            'estimated_delivery' => 'nullable|date',
        ]);

        $old = $order->status;
        $order->update($request->only('status', 'delivery_notes', 'estimated_delivery'));

        AuditLogService::log('ORDER_STATUS_UPDATED', 'Orders', "Order #{$order->id} status: {$old} → {$order->status}");

        return back()->with('success', 'Order status updated.');
    }

    public function assignDriver(Request $request, Order $order)
    {
        // Block: cancelled orders cannot have drivers assigned
        if ($order->status === 'cancelled') {
            return back()->with('error', "Order #{$order->id} has been cancelled. A driver cannot be assigned.");
        }

        $request->validate(['driver_id' => 'required|exists:users,id']);

        $driver = User::findOrFail($request->driver_id);
        abort_unless($driver->isDriver(), 422, 'Selected user is not a driver.');

        $order->update(['driver_id' => $driver->id]);

        AuditLogService::log('DRIVER_ASSIGNED', 'Orders', "Driver {$driver->name} assigned to Order #{$order->id}");

        return back()->with('success', "Driver {$driver->name} assigned to Order #{$order->id}.");
    }
}