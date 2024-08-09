<?php

namespace App\Models;

use App\Models\Category;
use App\Models\CartProduct;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'category_id',
		'name',
		'description',
		'price',
		'stock',
    ];


	protected $appends = [
		'capital_letters_name',
		'capital_letter_description',
        'format_description'
	];

	protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

	//Accesores
	public function getCapitalLettersNameAttribute()
    {
        return ucwords($this->name);
    }


	public function getCapitalLetterDescriptionAttribute()
    {
        return ucfirst($this->description);
    }

    public function formatDescription(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes){
                return Str::limit($attributes['description'], 50, '...');
            },
        );
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


	public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }


	//Manejar los eventos
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (is_null($product->category_id)) {
                // Obtener el ID de la categoría con el nombre 'sincategoria'
                $category = Category::where('name', 'uncategorized')->first();
                if ($category) {
                    $product->category_id = $category->id; // Asignar el ID de la categoría
                } else {
                    throw new \Exception('Categoría "uncategorized" no encontrada');
                }
            }
        });

		static::deleting(function ($product) {
            foreach ($product->cartProducts as $cartProduct) {
                $cartProduct->delete();
            }
        });
    }
}
