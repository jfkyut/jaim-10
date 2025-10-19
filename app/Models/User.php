<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserRole;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'gender',
        'birthday',
        'password',
        'role_id'
    ];

    protected $appends = ['is_following'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(UserRole::class);
    }

    public function albums()
    {
        return $this->hasMany(Album::class, 'user_id');
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class, 'user_id');
    }

    public function tracks()
    {
        return $this->hasMany(Music::class, 'user_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_followers', 'following_id', 'follower_id')
            ->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'user_followers', 'follower_id', 'following_id')
            ->withTimestamps();
    }

    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function getIsFollowingAttribute()
    {
        if (!auth()->check()) {
            return false;
        }
        return $this->followers->contains(auth()->user());
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'user_id');
    }
}
