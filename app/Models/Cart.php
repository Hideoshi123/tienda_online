<?php

namespace App\Models;

use App\Models\User;
use App\Models\CartProduct;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'user_id',
	];

	protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public function cartProducts()
	{
		return $this->hasMany(CartProduct::class, 'cart_id', 'id');
	}

	// Manejo de eventos
	protected static function boot()
	{
	    parent::boot();

	    static::deleting(function ($cart) {
	        // Eliminar todos los registros de CartProduct relacionados
	        $cart->cartProducts()->delete();
	    });
	}
}
