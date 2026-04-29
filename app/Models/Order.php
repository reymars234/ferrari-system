<?php
// ════════════════════════════════════════════════════════════════
// FILE: app/Models/Order.php — REPLACE ENTIRE FILE
// ════════════════════════════════════════════════════════════════

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'car_id',
        'driver_id',
        'buyer_name',
        'buyer_address',
        'buyer_contact',
        'delivery_latitude',   // NEW
        'delivery_longitude',  // NEW
        'total_price',
        'status',
        'payment_method',
        'payment_status',
        'payment_reference',
        'admin_accepted',
        'admin_accepted_at',
        'delivery_notes',
        'estimated_delivery',
        'refund_status',
        'refund_reference',
        'refunded_at',
        'cancel_reason',
        'cod_paid',
        'cod_paid_at',
        'cod_confirmed_by',
    ];

    protected $casts = [
        'total_price'        => 'decimal:2',
        'estimated_delivery' => 'datetime',
        'admin_accepted_at'  => 'datetime',
        'admin_accepted'     => 'boolean',
        'cod_paid'           => 'boolean',
        'cod_paid_at'        => 'datetime',
        'refunded_at'        => 'datetime',
        'delivery_latitude'  => 'decimal:8',
        'delivery_longitude' => 'decimal:8',
    ];

    // ── Relationships ─────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function codConfirmedBy()
    {
        return $this->belongsTo(User::class, 'cod_confirmed_by');
    }

    // ── Helpers ───────────────────────────────────────────────────

    /**
     * A customer can cancel ONLY when:
     *  - status is 'pending'  AND
     *  - admin has NOT accepted/confirmed the order yet
     *
     * Once admin accepts (admin_accepted = true) or status moves to
     * 'processing' / 'delivered', cancellation is locked.
     */
    public function isCancellable(): bool
    {
        return $this->status === 'pending'
            && $this->admin_accepted === false;
    }

    /**
     * Does this order have pin-dropped coordinates?
     */
    public function hasCoordinates(): bool
    {
        return !is_null($this->delivery_latitude)
            && !is_null($this->delivery_longitude);
    }

    public function isRefundable(): bool
    {
        return $this->payment_method === 'paypal'
            && $this->payment_status === 'paid'
            && is_null($this->refund_status);
    }

    public function isCodMarkable(): bool
    {
        return $this->payment_method === 'cod'
            && $this->status === 'delivered'
            && !$this->cod_paid;
    }
}