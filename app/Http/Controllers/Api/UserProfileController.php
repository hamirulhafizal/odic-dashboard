<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            $profile = UserProfile::where('user_name', $user)->first();

            if($profile != null){
                $profile->photo = $request->photo ? $request->photo : $profile->photo;
                $profile->nickname = $request->nickname ? $request->nickname : $profile->nickname;
                $profile->firstName = $request->firstName ? $request->firstName : $profile->firstname;
                $profile->lastName = $request->lastName ? $request->lastName : $profile->lastName;
                $profile->identity_no = $request->identity_no ? $request->identity_no : $request->identity_no;
                $profile->description = $request->description ? $request->description : $request->description;
                $profile->facebook = $request->facebook ? $request->facebook : $request->facebook;
                $profile->instagram = $request->instagram ? $request->instagram : $request->instagram;
                $profile->linkedin = $request->linkedin ? $request->linkedin : $request->linkedin;
                $profile->tiktok = $request->tiktok ? $request->tiktok : $request->tiktok;
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
}
