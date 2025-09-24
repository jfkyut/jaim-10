<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        // 'album_id'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
