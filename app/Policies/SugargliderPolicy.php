<?php

namespace App\Policies;

use App\Models\SugargliderModel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class SugargliderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SugargliderModel  $sugargliderModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, SugargliderModel $sugargliderModel)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SugargliderModel  $sugargliderModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, SugargliderModel $sugarglider)
    {
        return $user->id === $sugarglider->user_id;
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SugargliderModel  $sugargliderModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, SugargliderModel $sugargliderModel)
    {
        return $user->id === $sugargliderModel->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SugargliderModel  $sugargliderModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, SugargliderModel $sugargliderModel)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SugargliderModel  $sugargliderModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, SugargliderModel $sugargliderModel)
    {
        //
    }
}
