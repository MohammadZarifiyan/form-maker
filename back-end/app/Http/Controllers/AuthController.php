<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\CommonResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function check(): CommonResource
    {
        return new CommonResource([
            'status' => auth()->check(),
        ]);
    }

    public function register(RegisterRequest $request): CommonResource
    {
        $user = User::create($request->all());

        return $this->responseFreshToken($user);
    }

    public function login(LoginRequest $request): CommonResource
    {
        $user = User::where('email', '=', $request->input('email'))->first();

        if ($user && Hash::check($request->input('password'), $user->password)) {
            return $this->responseFreshToken($user);
        }

        throw ValidationException::withMessages([
            'password' => [
                trans('auth.password'),
            ],
        ]);
    }

    public function responseFreshToken(User $user): CommonResource
    {
        return new CommonResource([
            'token' => $user->createToken('auth')->plainTextToken,
        ]);
    }
}
