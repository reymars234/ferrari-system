<?php
// ════════════════════════════════════════════════════════════════
// FILE: app/Http/Controllers/OrderController.php — REPLACE ENTIRE FILE
// ════════════════════════════════════════════════════════════════

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Order;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()
            ->orders()
            ->with('car', 'driver')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function create(Car $car)
    {
        abort_unless($car->is_available, 404);
        return view('orders.create', compact('car'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_id'             => 'required|exists:cars,id',
            'buyer_name'         => ['required', 'string', 'max:100'],
            'buyer_address'      => 'required|string|max:300',
            'buyer_contact'      => ['required', 'regex:/^[0-9]+$/', 'min:7', 'max:15'],
            'payment_method'     => 'required|in:cod,credit_card,paypal',
            'delivery_latitude'  => 'nullable|numeric|between:-90,90',
            'delivery_longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $car = Car::findOrFail($request->car_id);
        abort_unless($car->is_available, 403);

        // Store in session for payment page redirect
        session(['single_checkout' => $request->only(
            'car_id', 'buyer_name', 'buyer_address', 'buyer_contact',
            'payment_method', 'delivery_latitude', 'delivery_longitude'
        )]);

        if ($request->payment_method === 'cod') {
            $order = Order::create([
                'user_id'            => auth()->id(),
                'car_id'             => $car->id,
                'buyer_name'         => $request->buyer_name,
                'buyer_address'      => $request->buyer_address,
                'buyer_contact'      => $request->buyer_contact,
                'delivery_latitude'  => $request->delivery_latitude,
                'delivery_longitude' => $request->delivery_longitude,
                'total_price'        => $car->price,
                'status'             => 'pending',
                'payment_method'     => 'cod',
                'payment_status'     => 'unpaid',
                'admin_accepted'     => false,
            ]);
            AuditLogService::log('ORDER_CREATED', 'Orders', "Order #{$order->id} COD");
            return redirect()->route('orders.show', $order)
                ->with('success', 'Order placed! Awaiting admin confirmation.');
        }

        // Card or PayPal → payment page
        return view('orders.payment', ['car' => $car, 'method' => $request->payment_method]);
    }

    public function processPayment(Request $request)
    {
        $checkout = session('single_checkout');
        if (!$checkout) {
            return redirect()->route('shop')->with('error', 'Session expired. Please try again.');
        }

        $car = Car::findOrFail($checkout['car_id']);
        $ref = strtoupper('PAY-' . uniqid());

        $order = Order::create([
            'user_id'            => auth()->id(),
            'car_id'             => $car->id,
            'buyer_name'         => $checkout['buyer_name'],
            'buyer_address'      => $checkout['buyer_address'],
            'buyer_contact'      => $checkout['buyer_contact'],
            'delivery_latitude'  => $checkout['delivery_latitude'] ?? null,
            'delivery_longitude' => $checkout['delivery_longitude'] ?? null,
            'total_price'        => $car->price,
            'status'             => 'pending',
            'payment_method'     => $checkout['payment_method'],
            'payment_status'     => 'paid',
            'payment_reference'  => $ref,
            'admin_accepted'     => false,
        ]);

        session()->forget('single_checkout');
        AuditLogService::log('ORDER_CREATED', 'Orders', "Order #{$order->id} paid via {$checkout['payment_method']}");

        return redirect()->route('orders.show', $order)
            ->with('success', 'Payment successful! Awaiting admin confirmation.');
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);
        $order->load('car', 'driver');
        return view('orders.show', compact('order'));
    }

    public function cancel(Request $request, Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        // ── Cancellation rules ────────────────────────────────────
        // Only cancellable when: status=pending AND admin has NOT accepted
        if (!$order->isCancellable()) {
            $reason = $order->admin_accepted
                ? 'This order has already been confirmed by admin and cannot be cancelled.'
                : 'This order cannot be cancelled in its current status (' . ucfirst($order->status) . ').';
            return back()->with('error', $reason);
        }

        $request->validate([
            'cancel_reason' => 'nullable|string|max:300',
        ]);

        // ── PayPal refund simulation ──────────────────────────────
        $refundStatus    = null;
        $refundReference = null;

        if ($order->payment_method === 'paypal' && $order->payment_status === 'paid') {
            $refundStatus    = 'processed';
            $refundReference = strtoupper('REF-' . uniqid());
            AuditLogService::log('PAYPAL_REFUND', 'Orders',
                "Refund {$refundReference} for Order #{$order->id}");
        }

        $orderId    = $order->id;
        $carName    = $order->car->name;
        $totalPrice = $order->total_price;

        // Delete the order completely (per requirement)
        $order->delete();

        AuditLogService::log('ORDER_CANCELLED', 'Orders',
            "Order #{$orderId} ({$carName}) cancelled." .
            ($refundStatus === 'processed' ? " Refund: {$refundReference}" : ''));

        $message = "Order #{$orderId} cancelled successfully.";
        if ($refundStatus === 'processed') {
            $message .= " PayPal refund of ₱" . number_format($totalPrice, 2)
                . " processed (Ref: {$refundReference}).";
        }

        return redirect()->route('orders.index')->with('success', $message);
    }
}