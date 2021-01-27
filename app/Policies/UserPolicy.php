<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can verify email.
     *
     * @param User $user
     * @return bool
     */
    public function verifyEmail(User $user) {
        return $user && $user->email_verified_at == null;
    }

    /**
     * Determine whether the user can log out.
     *
     * @param User $user
     * @return bool
     */
    public function logOut(User $user) {
        return $user ? true : false;
    }

    /**
     * Determine whether the user can log in.
     *
     * @param User $user
     * @return mixed
     */
    public function logIn(User $user) {
        return $user == null
            ? Response::allow('Action is allowed')
            : Response::deny('Action not allowed');
    }
}
