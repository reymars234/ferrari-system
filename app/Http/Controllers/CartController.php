<?php
// FILE: app/Http/Controllers/CartController.php — REPLACE ENTIRE FILE

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Car;
use App\Models\Order;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // ── Cart Index ────────────────────────────────────────────────
    public function index()
    {
        $items = auth()->user()->cartItems()->with('car')->get();
        $total = $items->sum(fn($i) => $i->car->price * $i->quantity);
        return view('cart.index', compact('items', 'total'));
    }

    // ── Add to Cart ───────────────────────────────────────────────
    public function add(Request $request)
    {
        $request->validate(['car_id' => 'required|exists:cars,id']);
        $car = Car::findOrFail($request->car_id);
        abort_unless($car->is_available, 403, 'This car is not available.');

        $item = CartItem::firstOrCreate(
            ['user_id' => auth()->id(), 'car_id' => $car->id],
            ['quantity' => 1]
        );
        if (!$item->wasRecentlyCreated) {
            $item->increment('quantity');
        }

        AuditLogService::log('CART_ADD', 'Cart', "Added {$car->name} to cart.");

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$car->name} added to cart!",
                'count'   => auth()->user()->cartItems()->count(),
            ]);
        }
        return back()->with('success', "{$car->name} added to cart!");
    }

    // ── Remove from Cart ──────────────────────────────────────────
    public function remove(CartItem $cartItem)
    {
        abort_unless($cartItem->user_id === auth()->id(), 403);
        $cartItem->delete();
        return back()->with('success', 'Item removed from cart.');
    }

    // ── Update Quantity ───────────────────────────────────────────
    public function update(Request $request, CartItem $cartItem)
    {
        abort_unless($cartItem->user_id === auth()->id(), 403);
        $request->validate(['quantity' => 'required|integer|min:1|max:10']);
        $cartItem->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Cart updated.');
    }

    // ── Checkout Form ─────────────────────────────────────────────
    public function checkout()
    {
        $items = auth()->user()->cartItems()->with('car')->get();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        $total = $items->sum(fn($i) => $i->car->price * $i->quantity);
        return view('cart.checkout', compact('items', 'total'));
    }

    // ── Submit Checkout (go to payment or place COD) ──────────────
    public function payment(Request $request)
    {
        $request->validate([
            'buyer_name'     => ['required', 'string', 'max:100'],
            'buyer_address'  => 'required|string|max:300',
            'buyer_contact'  => ['required', 'regex:/^[0-9]+$/', 'min:7', 'max:15'],
            'payment_method' => 'required|in:cod,credit_card,paypal',
        ]);

        $items = auth()->user()->cartItems()->with('car')->get();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = $items->sum(fn($i) => $i->car->price * $i->quantity);

        session([
            'checkout' => [
                'buyer_name'     => $request->buyer_name,
                'buyer_address'  => $request->buyer_address,
                'buyer_contact'  => $request->buyer_contact,
                'payment_method' => $request->payment_method,
                'total'          => $total,
            ],
        ]);

        if ($request->payment_method === 'cod') {
            return $this->placeOrders('cod', 'unpaid', null);
        }

        return view('cart.payment', [
            'method' => $request->payment_method,
            'total'  => $total,
            'items'  => $items,
        ]);
    }

    // ── Process Card / PayPal Payment ────────────────────────────
    public function processPayment(Request $request)
    {
        $checkout = session('checkout');
        if (!$checkout) {
            return redirect()->route('cart.checkout')->with('error', 'Session expired. Please try again.');
        }

        $reference = strtoupper('PAY-' . uniqid());
        return $this->placeOrders($checkout['payment_method'], 'paid', $reference);
    }

    // ── Internal: Create Orders from Cart ────────────────────────
    private function placeOrders(string $method, string $payStatus, ?string $ref)
    {
        $checkout = session('checkout');
        $items    = auth()->user()->cartItems()->with('car')->get();

        foreach ($items as $item) {
            $order = Order::create([
                'user_id'           => auth()->id(),
                'car_id'            => $item->car_id,
                'buyer_name'        => $checkout['buyer_name'],
                'buyer_address'     => $checkout['buyer_address'],
                'buyer_contact'     => $checkout['buyer_contact'],
                'total_price'       => $item->car->price * $item->quantity,
                'status'            => 'pending',
                'payment_method'    => $method,
                'payment_status'    => $payStatus,
                'payment_reference' => $ref,
                'admin_accepted'    => false,
            ]);
            AuditLogService::log('ORDER_CREATED', 'Orders', "Order #{$order->id} via {$method}");
        }

        auth()->user()->cartItems()->delete();
        session()->forget('checkout');

        return redirect()->route('orders.index')
            ->with('success', 'Order(s) placed successfully! Awaiting admin confirmation.');
    }
}