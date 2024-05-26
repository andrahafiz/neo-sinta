<?php

namespace App\Http\Controllers\Api\User;

use App\Contracts\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * @var \App\Repositories\UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::orderBy('name', 'asc')
            ->when($request->has('search'), function (Builder $query) use ($request) {
                return $query->where('name', 'like', '%' . $request->query('search') . '%')
                    ->orWhere('email', $request->query('search'))
                    ->orWhere('username', $request->query('search'));
            })->when($request->has('roles'), function (Builder $query) use ($request) {
                return $query->where('roles', $request->query('roles'));
            })
            ->paginate($request->query('show'));

        return Response::json(new UserCollection($users));
    }

    /**
     * Store a newly created resource in storage.
     * @param \App\Http\Requests\UserCreateRequest
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $me = $request->user();
        $newUser = DB::transaction(function () use ($request) {
            $newUser = $this->userRepository->create($request);

            return $newUser;
        });

        return Response::json(
            new UserResource($newUser),
            Response::MESSAGE_CREATED,
            Response::STATUS_CREATED,
        );
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {

        return Response::json(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $updatedUser = DB::transaction(function () use ($user, $request) {
            $updatedUser = $this->userRepository->update($request, $user);

            return $updatedUser;
        });

        return Response::json(new UserResource($updatedUser));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        $me = $request->user();

        $deletedUser = DB::transaction(function () use ($user) {
            $deletedUser = $this->userRepository->delete($user);

            return $deletedUser;
        });

        return Response::noContent();
    }
}
