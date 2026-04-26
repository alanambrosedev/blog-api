<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(protected UserService $service) {}

    public function index()
    {
        $users = $this->service->getUsers();

        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->service->createUser($request->validated());

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $updated = $this->service->updateUser($user, $request->validated());

        return new UserResource($updated);
    }

    public function destroy(User $user)
    {
        $this->service->deleteUser($user);

        return response()->noContent();
    }
}
