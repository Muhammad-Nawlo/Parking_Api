<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;
/**
 * @group Profile
 */
class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json($request->user()->only('id', 'name'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => "required|string",
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($request->user())]
        ]);
        $request->user()->update($validatedData);
        return response()->json($validatedData, Response::HTTP_ACCEPTED);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json([
            'message' => 'Your password has been updated.',
        ], Response::HTTP_ACCEPTED);
    }
}
