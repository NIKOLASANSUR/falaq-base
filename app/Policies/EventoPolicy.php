
<?php

namespace App\Policies;

use App\Models\Evento;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventoPolicy
{
    use HandlesAuthorization;

    // Sprint 07 - Autorizacao para moderar eventos
    public function moderate(User $user, Evento $evento): bool
    {
        return $user->id === $evento->user_id;
    }
}
