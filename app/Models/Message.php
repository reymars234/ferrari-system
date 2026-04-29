<?php
// app/Models/Message.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'order_id',
        'chat_type',   // ← ADDED
        'sender_id',
        'receiver_id',
        'body',
        'is_read'
    ];

    protected $casts = ['is_read' => 'boolean'];

    public function sender()   { return $this->belongsTo(User::class, 'sender_id'); }
    public function receiver() { return $this->belongsTo(User::class, 'receiver_id'); }
    public function order()    { return $this->belongsTo(Order::class); }
}