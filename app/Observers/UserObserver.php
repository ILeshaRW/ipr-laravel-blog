<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\Register;

class UserObserver
{
    /**
     * Уведомление о регистрации
     *
     * @param User $user
     * @return void
     */
    public function created(User $user)
    {
        $user->notify(new Register());
    }
}
