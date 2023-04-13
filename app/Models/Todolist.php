<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Todolist extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_todolist';
    public $incrementing = false;
    protected $guarded = [
        'created_at',
        'updated_at',

    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id_todolist = Uuid::uuid4()->toString();
        });
    }
}
