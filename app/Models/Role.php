<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const USER = 'user';
    const ARTIST = 'artist';
    const ADMIN = 'admin';
    
    protected $fillable = [
        'name',
    ];
}
