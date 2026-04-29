<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
 
class CartItem extends Model
{
    protected $fillable = ['user_id','car_id','quantity'];
    public function car()  { return $this->belongsTo(Car::class); }
    public function user() { return $this->belongsTo(User::class); }
}