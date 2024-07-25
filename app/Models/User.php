<?php

namespace App\Models;

use App\Models\Cart;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'number_id',
        'name',
        'last_name',
        'address',
        'phone_number',
        'email',
        'password',
    ];

    protected $appends = ['capital_letters'];

    protected $hidden = [
		'name',
		'last_name',
		'address',
        'created_at',
        'updated_at',
        'deleted_at',
        'password',
        'remember_token',
    ];

    // Lista de atributos a capitalizar y convertir a minúsculas
    protected $transformedAttributes = ['name', 'last_name', 'address'];

    // Accesor
    public function getCapitalLettersAttribute()
    {
        $capitalizedAttributes = [];

        foreach ($this->transformedAttributes as $attribute) {
            $value = $this->getAttribute($attribute);
            if ($value) {
                $capitalizedAttributes[$attribute] = ucwords(strtolower($value));
            }
        }

        return $capitalizedAttributes;
    }

    // Mutador
    public function setAttribute($key, $value)
    {
        // Convierte los atributos especificados a minúsculas
        if (in_array($key, $this->transformedAttributes)) {
            $value = strtolower($value);
        }

        return parent::setAttribute($key, $value);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    // Relaciones entre tablas
    public function cart()
    {
        return $this->hasOne(Cart::class, 'user_id', 'id');
    }

	//Manejo de eventos
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            // Eliminar registros relacionados en la tabla carts
            $user->cart()->delete();
        });
    }
}
