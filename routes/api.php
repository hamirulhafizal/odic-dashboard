<?php

use App\Http\Controllers\Api\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Logout;
use App\Models\User;
use App\Notifications\WelcomeEmailNotification;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\UserProfile;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::put('user-profile/edit/{user}', [UserProfileController::class,'update']);

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('login', function (Request $request) {

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::firstWhere(['email' => $request->email]);
        $tokenName = $user->email . '-' . $request->header('User-Agent');
        $tokenObject = $user->createToken($tokenName);

        return response()->json(['token' => $tokenObject->plainTextToken] + $user->toArray());
    });

    Route::post('register', function (Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:6'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $input = [
                'email' => $request->email,
                'name' => $request->name,
                'phone_no' => $request->phone_no,
                'password' => Hash::make($request->password),
            ];

            $user = User::create($input);

            if($user->id < 10){
                $user->username = 'odic00000'.$user->id;
                $user->referrel_url = 'https://onedreamproperty/odic00000'.$user->id;
            }elseif($user->id < 100){
                $user->username = 'odic0000'.$user->id;
                $user->referrel_url = 'https://onedreamproperty/odic0000'.$user->id;
            }elseif($user->id < 1000){
                $user->username = 'odic000'.$user->id;
                $user->referrel_url = 'https://onedreamproperty/odic000'.$user->id;
            }else{
                $user->username = 'odic00'.$user->id;
                $user->referrel_url = 'https://onedreamproperty/odic00'.$user->id;
            }

            $user->verified_status = 'Pending';

            $user->save();
            $user->assignRole($request->input('roles'));

        } catch (\Throwable $th) {
            return response()->json(['Register Failed'], 429);
        }

        $tokenName = $user->email . '-' . $request->header('User-Agent');
        $tokenObject = $user->createToken($tokenName);

        return response()->json(['token' => $tokenObject->plainTextToken] + $user->toArray());
    });

    Route::get('logout', function (Request $request) {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
            event(new Logout('sanctum', $request->user()));
        }

        return response()->json(['message' => 'Successfully logged out.'], 204);
    })->middleware(['auth:sanctum']);

    Route::get('forgot-password', function () {
        return view('auth.passwords.email');
    })->middleware('guest')->name('password.request');

    Route::post('forgot-password', function (Request $request) {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    })->middleware('guest')->name('password.email');


    Route::post('reset-password', function (Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    })->middleware('guest')->name('password.update');


    Route::middleware('auth:sanctum')->get('user', function (Request $request) {
        return $request->user();
    });
});

