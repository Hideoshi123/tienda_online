<?php

namespace App\Models;

use App\Models\Product;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
		'name',
    ];

	protected $appends = ['capital_letter'];

	protected $hidden = [
		'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

	//Accesor
	public function getCapitalLetterAttribute()
	{
		return ucwords($this->name);
	}

	//Mutador
	public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }


	public function products()
	{
		return $this->hasMany(Product::class, 'category_id', 'id');
	}

	//Manejo de eventos
	protected static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            // Obtener la categoría "sincategoria"
            $defaultCategory = Category::where('name', 'uncategorized')->first();

            if ($defaultCategory) {
                // Reasignar productos a la categoría "sincategoria"
                Product::where('category_id', $category->id)->update(['category_id' => $defaultCategory->id]);
            } else {
                // Lanzar una excepción o manejar el caso en que no exista "sincategoria"
                throw new \Exception('La categoría "uncategorized" no existe. Asegúrate de crearla antes de eliminar esta categoría.');
            }
        });
    }
}
