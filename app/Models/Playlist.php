<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
    ];

    public function musics()
    {
        return $this->belongsToMany(Music::class, 'playlist_musics');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
