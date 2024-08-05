<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'fileable_id',
        'fileable_type',
        'route'
    ];

	protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function fileable()
    {
        return $this->morphTo();
    }
}
