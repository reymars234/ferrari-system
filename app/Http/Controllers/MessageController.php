<?php
namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    private function resolveChatContext(Order $order): array
    {
        $user  = auth()->user();
        $role  = $user->role;
        $route = request()->route()->getName();

        if ($role === 'admin') {
            $chatType   = 'admin_driver';
            $receiverId = $order->driver_id;

        } elseif ($role === 'driver') {
            if (str_contains($route, 'admin-chat')) {
                $chatType   = 'admin_driver';
                $receiverId = User::where('role', 'admin')->value('id');
            } else {
                $chatType   = 'user_driver';
                $receiverId = $order->user_id;
            }

        } else {
            $chatType   = 'user_driver';
            $receiverId = $order->driver_id;
        }

        return [$chatType, $receiverId];
    }

    public function show(Order $order)
    {
        $user = auth()->user();
        [$chatType] = $this->resolveChatContext($order);

        $messages = Message::where('order_id', $order->id)
            ->where('chat_type', $chatType)
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        Message::where('order_id', $order->id)
            ->where('chat_type', $chatType)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $role  = $user->role;
        $route = request()->route()->getName();

        if ($role === 'admin') {
            $view = 'admin.chat';
        } elseif ($role === 'driver') {
            $view = str_contains($route, 'admin-chat') ? 'driver.chat-admin' : 'driver.chat';
        } else {
            $view = 'chat.show';
        }

        return view($view, compact('order', 'messages', 'chatType'));
    }

    public function send(Request $request, Order $order)
    {
        $request->validate(['body' => 'required|string|max:1000']);

        $user = auth()->user();
        [$chatType, $receiverId] = $this->resolveChatContext($order);

        // Admin can override receiver_id via JSON request
        if ($user->role === 'admin' && $request->has('receiver_id') && $request->receiver_id) {
            $receiverId = $request->receiver_id;
        }

        Message::create([
            'order_id'    => $order->id,
            'chat_type'   => $chatType,
            'sender_id'   => $user->id,
            'receiver_id' => $receiverId,
            'body'        => $request->body,
            'is_read'     => false,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back();
    }

    public function poll(Order $order)
    {
        $user = auth()->user();
        [$chatType] = $this->resolveChatContext($order);

        $since = (int) request('since', 0);

        $messages = Message::where('order_id', $order->id)
            ->where('chat_type', $chatType)
            ->where('id', '>', $since)
            ->with('sender')
            ->orderBy('created_at')
            ->get()
            ->map(fn($m) => [
                'id'         => $m->id,
                'body'       => $m->body,
                'sender_id'  => $m->sender_id,
                'sender'     => $m->sender->name,
                'mine'       => $m->sender_id === $user->id,
                'time'       => $m->created_at->format('h:i A'),
                'created_at' => $m->created_at->format('h:i A'),
            ]);

        Message::where('order_id', $order->id)
            ->where('chat_type', $chatType)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    public function unreadCount()
    {
        $count = Message::where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}