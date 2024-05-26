<?php

namespace App\Repositories;

use App\Models\User;
use App\Contracts\Logging;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Repositories\Interface\UserInterface;


class UserRepository implements UserInterface
{
    /**
     * @var \App\Models\User
     */
    protected $userModel;

    /**
     * @param  \App\Models\User  $userModel
     */
    public function __construct(
        User $userModel,
    ) {
        $this->userModel = $userModel;
    }

    /**
     * @param  \App\Http\Requests\UserCreateRequest  $request
     * @return \App\Models\User
     */
    public function create(UserCreateRequest $request): User
    {
        $input = $request->safe([
            'name', 'phone_number', 'address', 'roles', 'image', 'password', 'email', 'username'
        ]);

        $photo = $request->file('image');
        if ($photo instanceof UploadedFile) {
            $rawPath = $photo->store('public/photos/user');
            $path = str_replace('public/', '', $rawPath);
        }

        $user = $this->userModel->create([
            'name'      => $input['name'],
            'username'  => $input['username'],
            'email'     => $input['email'],
            'password'  => Hash::make($input['password']),
            'phone_number' => $input['phone_number'],
            'address'   => $input['address'],
            'photo'     => $path ?? 'avatar.jpg',
            'roles'     => $input['roles'] ?? User::ROLE_KARYAWAN,
        ]);

        Logging::log("CREATE USER", $user);

        return $user;
    }

    /**
     * @param  \App\Http\Requests\UserUpdateRequest  $request
     * @param  \App\Models\User  $user
     * @return \App\Models\User
     */
    public function update(UserUpdateRequest $request, User $user): User
    {
        $input = $request->safe([
            'name', 'phone_number', 'address', 'roles', 'image', 'password', 'email', 'username'
        ]);

        $photo = $request->file('image');
        if ($photo instanceof UploadedFile) {
            $file_path = storage_path() . '/app/' . $user->photo;
            if (File::exists($file_path)) {
                unlink($file_path);
            }
            $filename = $photo->store('public/photos/user');
        } else {
            $filename = $user->photo;
        };

        $password = isset($input['password']) == null ? $user->password : Hash::make($input['password']);

        $user->update([
            'name'      => $input['name'] ?? $user->name,
            'username'  => $input['username'] ?? $user->username,
            'email'     => $input['email'] ?? $user->email,
            'password'  => $password ?? $user->passsword,
            'phone_number'  => $input['phone_number'] ?? $user->phone_number,
            'address'       =>  $input['address'] ?? $user->address,
            'photo'         => $filename ?? 'avatar.jpg',
            'roles'         =>  $input['roles'] ?? $user->roles,
        ]);

        Logging::log("UPDATE USER", ["changes" => $user->getChanges(), "user" => $user]);

        return $user;
    }


    /**
     * @param  \App\Models\User  $user
     * @return \App\Models\User
     */
    public function delete(User $user): bool
    {
        Logging::log("DELETE USER", $user);
        $user = $user->delete();
        return $user;
    }
}
