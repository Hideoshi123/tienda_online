<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Product;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartProduct extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'cart_id',
		'product_id',
		'quantity',
    ];

	protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

	public function product()
	{
		return $this->belongsTo(Product::class, 'product_id', 'id');
	}

	public function cart()
	{
		return $this->belongsTo(Cart::class, 'cart_id', 'id');
	}

	// Manejo de eventos
    protected static function boot()
	{
	    parent::boot();

	    static::deleting(function ($cartProduct) {
	        // Obtener el producto relacionado
	        $product = $cartProduct->product;

	        if ($product) {
	            // Restar la cantidad al stock del producto
	            $product->stock += $cartProduct->quantity; // Aumentar el stock
	            $product->save();
	        }
	    });
	}
}
