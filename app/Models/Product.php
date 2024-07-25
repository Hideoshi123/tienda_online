<?php

namespace App\Models;

use App\Models\Category;
use App\Models\CartProduct;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'category_id',
		'name',
		'description',
		'stock',
    ];


	protected $appends = [
		'capital_letters',
		'capital_letter'
	];

	//Accesores
	public function setCapitalLettersAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }


	public function setCapitalLetterAttribute($value)
    {
        $this->attributes['description'] = ucfirst($value);
    }


	//Mutadores
	public function setLowerLettersAttribute($key, $value)
    {
        // Convierte los atributos especificados a minúsculas
        if (in_array($key, $this->transformedAttributes)) {
            $value = strtolower($value);
        }

        parent::setAttribute($key, $value);
    }


	public function category()
	{
		return $this->belongsTo(Category::class, 'category_id', 'id');
	}


	public function cartProducts()
	{
		return $this->hasMany(CartProduct::class, 'product_id', 'id');
	}
}
