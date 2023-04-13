<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Note extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_note';
    public $incrementing = false;
    protected $guarded = [
        'created_at',
        'updated_at',

    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id_note = Uuid::uuid4()->toString();
        });
    }

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d/m/Y H:s:i');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }
}
