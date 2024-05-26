<?php

namespace App\Repositories\Interface;

use App\Models\User;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;

interface UserInterface
{
    /**
     * @param  \App\Http\Requests\UserCreateRequest  $request
     * @return \App\Models\User
     */
    public function create(UserCreateRequest $request): \App\Models\User;

    /**
     * @param  \App\Http\Requests\UserUpdateRequest  $request
     * @param  \App\Models\User  $user
     * @return \App\Models\User
     */
    public function update(UserUpdateRequest $request, User $user): \App\Models\User;

    /**
     * @param  \App\Models\User  $user
     * @return  boolean
     */
    public function delete(User $user): bool;
}
