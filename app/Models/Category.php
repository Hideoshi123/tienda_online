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


	//Accesores
	public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }


	public function products()
	{
		return $this->hasMany(Product::class, 'category_id', 'id');
	}
}
