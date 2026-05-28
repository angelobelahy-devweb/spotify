<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = [
        'surname',
        'user_id',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    public function follows()
    {
        return $this->hasMany(Follow::class);
    }

    public function socialMedias()
    {
        return $this->hasMany(SocialMedia::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
