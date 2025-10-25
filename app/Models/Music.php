<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Music extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'generated_by',
        'generated_at',
        'description',
        'lyrics',
        'user_id',
        'file_path',
        'album_id'
    ];

    protected $appends = ['is_favorite'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function album()
    {
        return $this->belongsTo(Album::class, 'album_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'music_id');
    }

    public function isFavoriteBy(User $user)
    {
        return $this->favorites()->where('user_id', $user?->id)->exists();
    }

    public function getIsFavoriteAttribute()
    {
        if (!auth()->check()) {
            return false;
        }
        return $this->favorites()->where('user_id', auth()->id())->exists();
    }

    public function streams()
    {
        return $this->hasMany(MusicStream::class, 'music_id');
    }
}
