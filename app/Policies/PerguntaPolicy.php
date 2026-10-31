
<?php

namespace App\Policies;

use App\Models\Pergunta;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PerguntaPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, Pergunta $pergunta): bool
    {
        return $user->id === $pergunta->user_id
            || $user->id === $pergunta->evento->user_id;
    }
}
