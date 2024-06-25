<?php

namespace ProjectCode\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use ProjectCode\User\Models\User;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            $response = [
                "status" => "error",
                "data" => $validator->errors(),
            ];
            return response()->json($response);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            $response = [
                "status" => "success",
                "data" => Auth::user(),
            ];
        } else {

            $response = [
                "status" => "error",
                "data" => [
                    'email' => ['The provided credentials do not match our records.'],
                ]
            ];
        }
        return response()->json($response);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
