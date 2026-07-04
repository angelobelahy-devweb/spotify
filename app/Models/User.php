<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, Billable;

    /**
     * Get the attributes that should be cast.
    *
    * @return array<string, string>
    */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'slug',
        'role_id',
        'pdp',
        'pdc',
    ];

    protected $hidden =[
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token'
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function artist()
    {
        return $this->hasOne(Artist::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function follows(): HasMany
    {
        return $this->hasMany(Follow::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * 🛡️ Sécurité : Récupère le type de plan actif de l'utilisateur
     */
    public function getActivePlanName(): string
    {
        $activeSubscription = $this->subscriptions()
            ->where('stripe_status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>', now());
            })
            ->first();

        // Si aucun abonnement stripe n'est actif, l'utilisateur possède le plan 'basic' (Free)
        return $activeSubscription ? strtolower($activeSubscription->type) : 'basic';
    }

    /**
     * 🛡️ Sécurité : Vérifie si l'utilisateur peut créer un album selon ses quotas
     */
    public function canCreateAlbum(): bool
    {
        $plan = $this->getActivePlanName();

        if ($plan === 'vip') {
            return true; // Version illimitée
        }

        $limits = [
            'basic'   => 1,
            'premium' => 10,
        ];

        $maxAlbums = $limits[$plan] ?? 1;
        $currentAlbumsCount = $this->artist ? $this->artist->albums()->count() : 0;

        return $currentAlbumsCount < $maxAlbums;
    }

    /**
     * 🛡️ Sécurité : Vérifie si l'utilisateur peut créer un track (morceau) selon ses quotas
     */
    public function canCreateTrack(): bool
    {
        $plan = $this->getActivePlanName();

        if ($plan === 'vip') {
            return true; // Version illimitée
        }

        $limits = [
            'basic'   => 5,
            'premium' => 50,
        ];

        $maxTracks = $limits[$plan] ?? 5;

        // Compte tous les morceaux reliés à l'artiste (soit directement via une relation tracks,
        // soit à travers ses albums)
        $currentTracksCount = $this->artist ? $this->artist->tracks()->count() : 0;

        return $currentTracksCount < $maxTracks;
    }
}
