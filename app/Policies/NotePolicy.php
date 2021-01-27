<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class NotePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param User|null $user
     * @param Note $note
     * @return mixed
     */
    public function view(?User $user, Note $note)
    {
        if (!$user) {
            return $note->visibility === 'public';
        }

        return ($note->user_id === $user->id) || (
            $note->shared_notes()->where('user_id', $user->id)->exists());
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->exists
            ? Response::allow()
            : Response::deny('You have to log in to access this page');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Note $note
     * @return mixed
     */
    public function update(User $user, Note $note)
    {
        return $user->id === $note->user_id
            ? Response::allow()
            : Response::deny("You don't have permission to edit this note");
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Note $note
     * @return mixed
     */
    public function delete(User $user, Note $note)
    {
        return $user->id === $note->user_id
            ? Response::allow('Note deleted')
            : Response::deny("You don't have permission to remove this note");
    }
}
