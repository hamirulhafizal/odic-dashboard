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
use App\Models\Investments;
use App\Models\InvestmentStatus;
use App\Models\UserProfile;
use App\Notifications\PasswordResetNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

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
        try {
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            $investment = Investments::get();

            foreach($investment as $i){
                $formatted_date = date('Y-m-d H:i:s');

                if($i->dividen_date <= $formatted_date){

                    $investmentStatus = InvestmentStatus::find($i->id);

                    if(isset($investmentStatus->name) && $investmentStatus->name == 'Progress'){
                        $investmentStatus->name = 'Withdraw';
                        $investmentStatus->save();
                    }
                }
            }

            $user = User::firstWhere(['email' => $request->email]);

            if (isset($user) && ! $user->hasVerifiedEmail()) {
                $user->sendEmailVerificationNotification();
                return response()->json(["msg" => "Email not yet verification! Email verification link sent on your email address."]);
            }

            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $tokenName = $user->email . '-' . $request->header('User-Agent');
                $tokenObject = $user->createToken($tokenName);
                // dd($user);
                $role = $user->getRoleNames();

                if (!$user->hasRole('Admin') || !$user->hasRole('Partner') && !$user->hasRole('Member')) {
                    // If not, assign the normal role
                    $normalRole = Role::where('name', 'Normal')->first();
                    $user->assignRole($normalRole);
                       $user->role = $role[0];
                } else {


                    $user->role = $role[0];

                }




                return response()->json(['token' => $tokenObject->plainTextToken] + $user->toArray());
            }else{
                return response()->json(['error'=>'Login details are not valid']);
            }



        } catch (\Throwable $th) {
            dd($th);
        }

    });

    Route::post('register', function (Request $request) {
        $roles = Role::all()->pluck('name')->toArray();

        if(!in_array('Partner', $roles)){
            Role::create(['name' => 'Partner']);
        }

        if(!in_array('Member', $roles)){
            Role::create(['name' => 'Member']);
        }

        if(!in_array('Normal', $roles)){
            Role::create(['name' => 'Normal']);
        }
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:6',
                'od_partner' => 'required',
                'od_member' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $input = [
                'email' => $request->email,
                'name' => $request->name,
                'phone_no' => $request->phone_no,
                'password' => Hash::make($request->password),
                'od_partner' => strtoupper($request->od_partner),
                'od_member' => strtoupper($request->od_member)
            ];

            $user = User::create($input);
            $user->sendEmailVerificationNotification();


            if($user->id < 10){
                $user->username = 'ODIC00000'.$user->id;
                $user->referrel_url = 'https://odic.com.my/ODIC00000'.$user->id;
            }elseif($user->id < 100){
                $user->username = 'ODIC0000'.$user->id;
                $user->referrel_url = 'https://odic.com.my/ODIC0000'.$user->id;
            }elseif($user->id < 1000){
                $user->username = 'ODIC000'.$user->id;
                $user->referrel_url = 'https://odic.com.my/ODIC000'.$user->id;
            }else{
                $user->username = 'ODIC00'.$user->id;
                $user->referrel_url = 'https://odic.com.my/ODIC00'.$user->id;
            }

            $user->verified_status = 'Pending';
            $user->assignRole('Member');
            $user->save();
            // $user->assignRole($request->input('roles'));
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

    Route::post('forgot-pwd', function (Request $request) {
        $request->validate(['email' => 'required|email']);

        try {
            $user = User::where('email', '=', $request->email)->first();
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => Str::random(60),
                'created_at' => Carbon::now()
            ]);

            $tokenData = DB::table('password_resets')->where('email', $request->email)->first();

           // $forgetURL = 'https://odic.com.my/forget-password/'.$tokenData->token;

            $forgetURL = "https://odic.com.my/forget-password/{$tokenData->token}?email={$request->email}";

            $project = [
                'greeting' => 'Hi '.$user->name.',',
                'body' => 'You are receiving this email because we received a password reset request for your account.',
                'line' => 'This password reset link will expire in 60 minutes.',
                'thanks' => 'Thank you this is from One Dream Property.',
                'actionText' => 'Reset Password',
                'actionURL' => url($forgetURL),
            ];

            Notification::send($user, new PasswordResetNotification($project));
            return response()->json(['token' => $tokenData->token] + $user->toArray());

            if (isset($user) == true) {
                return redirect()->back()->withErrors(['email' => trans('User does not exist')]);
            }

        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['message' => trans('Failed to send an Email.')]);
        }
    });

    Route::post('new-pwd', function (Request $request) {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'token_' => 'required',
            'password' => 'required|confirmed|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $pwd = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token_)
            ->first();

            if($pwd != null){
                $user = User::where('email', $request->email)->first();
                $user->update(['password' => Hash::make($request->password)]);
            }

            return response()->json(['token' => $user->toArray()]);
        } catch (\Throwable $th) {
            //throw $th;
        }

    });
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


Route::get('partners', function (Request $request) {
    try {
    //  $partner = User::role('Partner')->get();
        $partner = User::role('Partner')->select('username','od_partner')->get();
      return $partner->toArray();

    } catch (\Throwable $th) {
        return response()->json(['message' => 'Failed to get Partners.'], 204);
    }

 });

 Route::get('total-direct-sales', function (Request $request) {
    $user = auth()->user();

    try {


      $investments = Investments::where('od_member', $user->username)
    ->select('id','username', 'od_member', 'amount', 'slot', 'roi', 'roi_amount', 'dividen_date', 'total_direct_sales')
    ->get();

    foreach ($investments as $investment) {
        $investmentStatus = InvestmentStatus::find($investment->id);
        $investment->status = $investmentStatus ? $investmentStatus->name : null;
    }

    return $investments;


    } catch (\Throwable $th) {
        return response()->json(['message' => 'Failed to get Partners.'], 204);
    }

 })->middleware(['auth:sanctum']);

 Route::get('total-empire-sales', function (Request $request) {
    $user = auth()->user();

    try {

      $investments = Investments::where('od_partner', $user->od_partner)
      ->select('id','username', 'od_member', 'od_partner', 'amount', 'slot', 'roi', 'roi_amount', 'dividen_date', 'total_empire_sales')
      ->get();

    foreach ($investments as $investment) {
        $investmentStatus = InvestmentStatus::find($investment->id);
        $investment->status = $investmentStatus ? $investmentStatus->name : null;
    }

    return $investments;

    } catch (\Throwable $th) {
        return response()->json(['message' => 'Failed to get Partners.'], 204);
    }

 })->middleware(['auth:sanctum']);



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
Route::post('investments', [InvestmentController::class, 'store'])->name('investment.store');
Route::post('investments/agreement', [InvestmentController::class, 'investmentStoreAgreement'])->name('investment.investmentStoreAgreement');
Route::get('investments/agreement-download', [InvestmentController::class, 'investmentAgreementDownload'])->middleware('auth:sanctum')->name('investment.store');
Route::get('investments/{username}', [InvestmentController::class, 'investmentIndex'])->middleware('auth:sanctum')->name('investment.investmentIndex');
Route::post('withdraw/{investment}', [InvestmentController::class, 'withdrawInvestment'])->middleware('auth:sanctum')->name('investment.investmentWithdraw');
Route::get('username/{username}', [InvestmentController::class, 'getUserByUsername'])->middleware('auth:sanctum')->name('investment.getUsername');



Route::get('partner/{username}', [UserProfileController::class, 'getPartnerByUsername']);
Route::get('status/{username}', [UserProfileController::class, 'getStatusRoleByUsername']);


