<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $fillable = [
        'album_id',
        'title',
        'file_path',
        'duration',
        'is_free',
        'slug',
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function genres()
    {
        return $this->belongsToMany(
            Genre::class,
            'genre_tracks'
        );
    }

}
