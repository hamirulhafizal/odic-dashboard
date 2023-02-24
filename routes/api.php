<?php

use App\Http\Controllers\Api\InvestmentController;
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
use App\Http\Middleware\customCors;
use App\Models\UserProfile;
use App\Notifications\PasswordResetNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Notification;

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

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('login', function (Request $request) {


        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::firstWhere(['email' => $request->email]);
        if (! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            return response()->json(["msg" => "Email not yet verification! Email verification link sent on your email address."]);
        }

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
            $user->sendEmailVerificationNotification();


            if($user->id < 10){
                $user->username = 'ODIC00000'.$user->id;
                $user->referrel_url = 'https://onedreamproperty/ODIC00000'.$user->id;
            }elseif($user->id < 100){
                $user->username = 'ODIC0000'.$user->id;
                $user->referrel_url = 'https://onedreamproperty/ODIC0000'.$user->id;
            }elseif($user->id < 1000){
                $user->username = 'ODIC000'.$user->id;
                $user->referrel_url = 'https://onedreamproperty/ODIC000'.$user->id;
            }else{
                $user->username = 'ODIC00'.$user->id;
                $user->referrel_url = 'https://onedreamproperty/ODIC00'.$user->id;
            }

            $user->verified_status = 'Pending';

            $user->save();
            $user->assignRole($request->input('roles'));
            $tokenName = $user->email . '-' . $request->header('User-Agent');
            $tokenObject = $user->createToken($tokenName);

            return response()->json(['token' => $tokenObject->plainTextToken] + $user->toArray());

        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['message' => 'Registration Failed.'], 401);
        }


    });

    Route::get('logout', function (Request $request) {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
            event(new Logout('sanctum', $request->user()));
        }

        return response()->json(['message' => 'Successfully logged out.'], 204);
    })->middleware(['auth:sanctum']);

    // Route::get('forgot-password', function () {
    //     return view('auth.passwords.email');
    // })->middleware('guest')->name('password.request');

    Route::post('forgot-password', function (Request $request) {
        $request->validate(['email' => 'required|email']);

        try {
            $user = User::where('email', '=', $request->email)->first();
        // dd(isset($user));
        if (isset($user) == true) {
            return redirect()->back()->withErrors(['email' => trans('User does not exist')]);
        }

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => Str::random(60),
            'created_at' => Carbon::now()
        ]);

        $tokenData = DB::table('password_resets')->where('email', $request->email)->first();

        $project = [
            'greeting' => 'Hi '.$user->name.',',
            'body' => 'You are receiving this email because we received a password reset request for your account.',
            'line2' => 'This password reset link will expire in 60 minutes.',
            'line2' => 'If you did not request a password reset, no further action is required.',
            'thanks' => 'Thank you this is from codeanddeploy.com',
            'actionText' => 'Reset Password',
            'actionURL' => url('/'),
            'id' => 57
        ];
        // $status = Password::sendResetLink(
        //     $request->only('email')
        // );
        Notification::send($user, new PasswordResetNotification($project));
        } catch (\Throwable $th) {
           dd($th);
        }


    //     return $status === Password::RESET_LINK_SENT
    //                 ? back()->with(['status' => __($status)])
    //                 : back()->withErrors(['email' => __($status)]);
    // })->middleware('guest')->name('password.email');
        })->middleware('guest')->name('passwords.email');

    // Route::post('reset-password', function (Request $request) {
    //     $request->validate([
    //         'token' => 'required',
    //         'email' => 'required|email',
    //         'password' => 'required|min:8|confirmed',
    //     ]);

    //     $status = Password::reset(
    //         $request->only('email', 'password', 'password_confirmation', 'token'),
    //         function ($user, $password) {
    //             $user->forceFill([
    //                 'password' => Hash::make($password)
    //             ])->setRememberToken(Str::random(60));

    //             $user->save();

    //             event(new PasswordReset($user));
    //         }
    //     );

    //     return $status === Password::PASSWORD_RESET
    //                 ? redirect()->route('login')->with('status', __($status))
    //                 : back()->withErrors(['email' => [__($status)]]);
    // })->middleware('guest')->name('password.update');

    // Route::post('forgot-password', function (Request $request) {
    //     $request->validate([
    //         'email' => ['required', 'email'],
    //     ]);

    //     $status = Password::broker('users')->sendResetLink($request->all());

    //     return $status == Password::RESET_LINK_SENT
    //         ? response()->json(['message' => 'Reset link sent.'], 204)
    //         : response()->json(['message' => 'Something went wrong.', 500]);
    // });

    Route::middleware('auth:sanctum')->get('user', function (Request $request) {
        return $request->user();
    });
});

Route::get('/email/verify/{id}/{hash}', function (Request $request) {
    $params = $request->route()->parameters();
    $user = User::find($params['id']);
    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();

        event(new Verified($user));
    }

    return redirect('https://odic.com.my');
    // return redirect('/');

})->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::post('user-profile/edit/{user}', [UserProfileController::class,'update'])->middleware('auth:sanctum')->name('profile.store');
Route::post('investments', [InvestmentController::class, 'store'])->middleware('auth:sanctum')->name('investment.store');
Route::get('investments/{username}', [InvestmentController::class, 'investmentIndex'])->middleware('auth:sanctum')->name('investment.investmentIndex');
Route::get('username/{username}', [InvestmentController::class, 'getUserByUsername'])->middleware('auth:sanctum')->name('investment.getUsername');
