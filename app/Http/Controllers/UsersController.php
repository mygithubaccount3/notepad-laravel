<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetUserRequest;
use App\Http\Requests\Users\LogInUserRequest;
use App\Http\Requests\Users\SignUpUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Request;

class UsersController extends Controller
{
    public function get(GetUserRequest $request)
    {
        if ($request->input('email')) {
            return User::whereEmail($request->input('email'))->first();
        } else if ($request->input('id')) {
            return User::whereId($request->input('id'))->first();
        }
    }

    /**
     * @return Application|Factory|View
     */
    public function showLoginPage()
    {
        return view('layouts.frontend.pages.auth.login');
    }

    /**
     * @param LogInUserRequest $request
     * @return RedirectResponse|JsonResponse|Authenticatable
     */
    public function logIn(LogInUserRequest $request)
    {
        $inputs = $request->only(['email', 'password']);
        $isApiRequest = $request->is('api/*');

        if (!Auth::attempt($inputs)) {
            if ($isApiRequest) {
                return response()->json([
                    'errors' => [
                        'password' => "The password doesn't match the username"
                    ]
                ], 422);
            } else {
                return redirect()->back()->withErrors([
                    'password' => "The password doesn't match the username"
                ]);
            }
        } else {
            if ($isApiRequest) {
                return response()->json([
                    'token' => Auth::user()->createToken('auth')->accessToken,
                    'user_id' => Auth::id()
                ]);
            } else {
                return redirect(route('notes.index', ['locale' => 'en']));
            }
        }
    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function logOut()
    {
        Auth::logout();
        return redirect('/login');
    }

    /**
     * @return Application|Factory|View
     */
    public function showSignupPage()
    {
        return view('layouts.frontend.pages.auth.signup');
    }

    /**
     * @param SignUpUserRequest $request
     * @return User|Application|Builder|Model|RedirectResponse|Redirector|object
     */
    public function signUp(SignUpUserRequest $request)
    {
        event(new Registered(User::create(array_merge($request->only([
            'username', 'email'
        ]), [
            'password' => Hash::make($request->input('password')),
        ]))));

        if ($request->is('api/*')) {
            return new UserResource(User::where('email', $request->input('email'))->first());
        } else {
            $request->session()->flash('message', 'You are signed up');
            return redirect('/login');
        }
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function sendEmailVerificationLink(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    }

    /**
     * @param EmailVerificationRequest $request
     * @return Application|RedirectResponse|Redirector
     * @throws AuthorizationException
     */
    public function verifyEmail(EmailVerificationRequest $request)
    {
        $this->authorize('verifyEmail', User::class);
        $request->fulfill();
        return redirect('/notes');
    }
}
