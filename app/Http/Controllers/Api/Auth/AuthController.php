<?php

namespace App\Http\Controllers\Api\Auth;

use App\Avatar;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Notifications\SignupActivate;
use App\Notifications\SignupActivated;
use App\User;
use App\Role;
use App\Http\Resources\User as UserResource;
use Illuminate\Validation\Rule;
use Socialite;
use Illuminate\Support\Facades\Log;

/**
 * @resource Auth
 *
 */
class AuthController extends Controller
{
    /**
     * Create user deactivated and send notification to activate the account
     *
     * @param  [string] username
     * @param  [string] name
     * @param  [string] phone
     * @param  [string] address
     * @param  [file] image
     * @param  [string] school
     * @param  [string] school_id
     * @param  [string] country_id
     * @param  [string] level_id
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request, $role)
    {
        $request->validate([
            'username' => [
                'string',
                'unique:users',
                'max:255',
                Rule::requiredIf(in_array($role, ['STUDENT'])),
            ],
            'name' => 'nullable|string',
            'phone' => [
                'unique:users',
                'max:255',
                Rule::requiredIf(in_array($role, ['PARENT', 'TEACHER', 'SCHOOL_ADMIN']) && !$request->email),
            ],
            'address' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'avatar_id' => 'nullable|integer|exists:avatars,id',
            'school' => 'nullable|string|max:255',
            'school_id' => [
                'exists:schools,id',
                Rule::requiredIf(in_array($role, ['TEACHER', 'SCHOOL_ADMIN'])),
            ],
            'country_id' => 'nullable|exists:countries,id',
            'level_id' => 'nullable|exists:levels,id',
            'email' => [
                'email',
                'unique:users',
                'max:255',
                Rule::requiredIf(in_array($role, ['PARENT', 'TEACHER', 'SCHOOL_ADMIN']) && !$request->phone),
            ],
            'password' => [
                'string',
                'confirmed',
                'max:255',
                Rule::requiredIf(!in_array($role, ['PARENT', 'TEACHER', 'SCHOOL_ADMIN'])),
            ],
        ]);

        $user = new User;
        $user->username = $request->username ? strtolower($request->username) : null;
        $user->phone = $request->phone ? $request->phone : null;
        $user->email = $request->email ? strtolower($request->email) : null;
        $user->name = $request->name;
        $user->address = $request->address;
        $user->school = $request->school;
        $user->school_id = $request->school_id;
        $user->level_id = $request->level_id;
        $user->country_id = $request->country_id;
        $user->password = bcrypt($request->password);
        $user->activation_token = str_random(60);
        //to remove after email setup
        $user->active = true;

        if (request('image')) {
            $imageName = time() . '.' . request('image')->getClientOriginalExtension();
            request('image')->move(public_path('uploads/user/'), $imageName);
            $user->image = $imageName;
        }

        $user->avatar_id = request('avatar_id');

        $user->save();

        $user
            ->roles()
            ->attach(Role::where('name', $role)->first());


        //$user->notify(new SignupActivate($user));

        // return response()->json([
        //     'message' => __('auth.signup_success')
        // ], 201);
        return new UserResource($user);
    }

    /**
     * Confirm your account user (Activate)
     *
     * @param  [type] $token
     * @return [string] message
     * @return [obj] user
     */
    public function signupActivate($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return response()->json([
                'message' => __('auth.token_invalid')
            ], 404);
        }

        $user->active = true;
        $user->activation_token = '';
        $user->save();

        //$user->notify(new SignupActivated($user));

        return $user;
    }


    /**
     * Login user and create token
     *
     * @param  [string] username
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     * @return [json] user object
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        if (is_numeric($request->username)) {
            $credentials = ['phone' => strtolower($request->username), 'password' => $request->password];
        } elseif (filter_var($request->get('username'), FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => strtolower($request->username), 'password' => $request->password];
        } else {
            $credentials = ['username' => strtolower($request->username), 'password' => $request->password];
        }

//        $credentials['active'] = 1;
        $credentials['enabled'] = 1;


        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => __('auth.login_failed')
            ], 401);
        }

        $user = $request->user();

        if ($user->hasRole('STUDENT') && (isset($credentials['email']) || isset($credentials['phone']))) {
            $user = $user->getOrCreateParent();
        }

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
            'user' => new UserResource($user)
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => __('auth.logout_success')
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return new UserResource($request->user());
    }

    /**
     * Get social login provider url
     *
     */
    public function providerUrl($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
    }

    /**
     * Social login via provider code or access_token
     *
     * @param  [string] code
     * @param  [string] access_token
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     * @return [json] user object
     */
    public function callback($provider)
    {
        if (!request('code') && !request('access_token')) {
            return response()->json([
                'message' => 'Access token or code is required'
            ], 422);
        }
        try {
            if (request('access_token')) {
                $userSocial = Socialite::driver($provider)->userFromToken(request('access_token'));
            } elseif (request('code')) {
                $userSocial = Socialite::driver($provider)->stateless()->user();
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 401);
        }
        $user = User::where([
            ['email', '!=', null],
            ['email', '=', $userSocial->getEmail()]
        ])
            ->orWhere([
                ['provider_id', '=', $userSocial->getId()],
                ['provider', '=', $provider]
            ])
            ->first();
        if ($user) {
            if ($user->hasRole('STUDENT')) {
                $user = $user->getOrCreateParent();
            }
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();

            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                'user' => new UserResource($user)
            ]);
        } else {
            $user = User::create([
                'name' => $userSocial->getName(),
                'email' => $userSocial->getEmail(),
                'image' => $userSocial->getAvatar(),
                'provider_id' => $userSocial->getId(),
                'provider' => $provider,
            ]);
            $user
                ->roles()
                ->attach(Role::where('name', 'PARENT')->first());
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();

            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                'user' => new UserResource($user)
            ]);
        }
    }
}
