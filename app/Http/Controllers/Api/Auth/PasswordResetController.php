<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\UserPasswordResetRequest;
use App\Notifications\UserPasswordResetSuccess;
use App\User;
use App\UserPasswordReset;

/**
 * @resource PasswordReset
 *
 */
class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->orWhere('phone', $request->email)->first();

        $credentials = null;
        if (is_numeric($request->email)) {
            $credentials = ['phone' => $request->email];
        } elseif (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $request->email];
        }

        if (!$user || !$credentials) {
            return response()->json([
                'message' => __('passwords.user')
            ], 404);
        }

        $passwordReset = UserPasswordReset::updateOrCreate($credentials, [
            'email' => $user->email,
            'phone' => $user->phone,
            'token' => mt_rand(100000, 999999)
        ]);

        if ($passwordReset) {
            $user->notify(new UserPasswordResetRequest($passwordReset->token, $user));
        }

        return response()->json([
            'message' => __('passwords.sent')
        ]);
    }

    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = UserPasswordReset::where('token', $token)->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => __('passwords.token')
            ], 404);
        }

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'message' => __('passwords.token')
            ], 404);
        }

        return response()->json($passwordReset);
    }

    /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);

        $passwordReset = UserPasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])
            ->orWhere([
                ['token', $request->token],
                ['phone', $request->email]
            ])->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => __('passwords.token')
            ], 404);
        }

        $user = null;
        if (is_numeric($request->email)) {
            $user = User::whereNotNull('phone')->where('phone', $passwordReset->phone)->first();

        } elseif (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $user = User::whereNotNull('email')->where('email', $passwordReset->email)->first();
        }

        if (!$user) {
            return response()->json([
                'message' => __('passwords.user')
            ], 404);
        }
        $user->password = bcrypt($request->password);
        $user->save();
        $user->students()->update(['password' => $user->password]);


        $passwordReset->delete();

        $user->notify(new UserPasswordResetSuccess());

        return response()->json($user);
    }
}
