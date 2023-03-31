<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user )
    {

        try {
            $profile = User::where('username', $user)->first();

            if($profile != null){
                if($request->file('profile_image')){
                    $file = $request->file('profile_image');
                    if($profile->profile_image != $file->getClientOriginalName()){
                        $filename = date('YmdHi').$file->getClientOriginalName();
                        $file-> move(public_path('profile'), $filename);
                        $profile->profile_image = $filename ? $filename : null;
                    }
                }

                if($request->file('identity_card')){
                    $file = $request->file('identity_card');
                    if($profile->identity_card != $file->getClientOriginalName()){
                        $filename = date('YmdHi').$file->getClientOriginalName();
                        $file-> move(public_path('profile'), $filename);
                        $profile->identity_card = $filename ? $filename : null;
                    }
                }

                $profile->phone_no =  $request->phone_no ? $request->phone_no : $profile->phone_no;
                $profile->bank_account =  $request->bank_account ? $request->bank_account : $profile->bank_account;
                $profile->bank_name =  $request->bank_name ? $request->bank_name : $profile->bank_name;
                $profile->fullname =  $request->fullname ? $request->fullname : $profile->fullname;
                $profile->identity_card_no =  $request->identity_card_no ? $request->identity_card_no : $profile->identity_card_no;
                $profile->address =  $request->address ? $request->address : $profile->address;
                $profile->city =  $request->city ? $request->city : $profile->city;
                $profile->state =  $request->state ? $request->state : $profile->state;
                $profile->postcode =  $request->postcode ? $request->postcode : $profile->postcode;
                
                $profile->od_member =  $request->od_member ? $request->od_member : $profile->od_member;
                $profile->od_partner =  $request->od_partner ? $request->od_partner : $profile->od_partner;
                $role = $profile->getRoleNames();
                if (isset($role[0])):
                    $profile->role = $role[0];
                else:
                    $profile->role = null;
                endif;
                $profile->save();
            }
            return response()->json([$profile], 201);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json('Failed to update user profile!', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    
    public function getPartnerByUsername($username)
    {
        try {
            $data = User::where('username', $username)->select('od_partner')->get();
            return response()->json($data, 201);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json('Failed to get User data!', 401);
        }
    }
    
}
