<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'photo_path',
        'user_id',
    ];

    public function musics()
    {
        return $this->hasMany(Music::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
