
<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relacionamento com eventos
    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class);
    }

    // Relacionamento com perguntas criadas
    public function perguntas(): HasMany
    {
        return $this->hasMany(Pergunta::class);
    }

    // Sprint 06 - Perguntas votadas pelo usuario
    public function perguntasVotadas(): BelongsToMany
    {
        return $this->belongsToMany(
            Pergunta::class,
            'pergunta_user'
        )->withTimestamps();
    }
}
