<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenreTrack extends Model
{
    //
    protected $fillable = [
        'genre_id',
        'track_id'
    ];
    public $timestamps = false;
}
