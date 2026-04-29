<?php
// ════════════════════════════════════════════════════════════════
// FILE: app/Models/User.php  — REPLACE FULL FILE
// ════════════════════════════════════════════════════════════════
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
 
class User extends Authenticatable
{
    use HasFactory, Notifiable;
 
    protected $fillable = [
        'name','email','contact_number','address','role','password',
        'otp','otp_expires_at','email_verified_at',
        'license_number','vehicle_info','driver_status','is_active',
    ];
 
    protected $hidden = ['password','remember_token','otp'];
 
    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at'    => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];
 
    public function isAdmin(): bool  { return $this->role === 'admin'; }
    public function isDriver(): bool { return $this->role === 'driver'; }
    public function isUser(): bool   { return $this->role === 'user'; }
 
    public function orders()        { return $this->hasMany(Order::class); }
    public function driverOrders()  { return $this->hasMany(Order::class, 'driver_id'); }
    public function cartItems()     { return $this->hasMany(CartItem::class); }
    public function auditLogs()     { return $this->hasMany(AuditLog::class); }
    public function sentMessages()  { return $this->hasMany(Message::class, 'sender_id'); }
}
 