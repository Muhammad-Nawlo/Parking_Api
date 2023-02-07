<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
/**
 * @group Auth
 */
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::default()]
        ]);
        $user = User::query()->create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);
        event(new Registered($user));
        $device = substr($request->userAgent() ?? "", 0, 255);
        return response()->json([
            'access_token' => $user->createToken($device)->plainTextToken
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
        $user = User::query()->where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $device = substr($request->userAgent() ?? "", 0, 255);
        $expiresAt = $request->remember ? null : now()->addMinutes(config('session.lifetime'));
        return response()->json([
            'access_token' => $user->createToken($device, expiresAt: $expiresAt)->plainTextToken
        ], Response::HTTP_CREATED);
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        return response()->noContent();
    }
}
