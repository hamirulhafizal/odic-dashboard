<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Carbon;
use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;
// use DataTables;
use Yajra\DataTables\DataTables;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::all();
        $roleAll = Role::all()->pluck('name')->toArray();
        
        if(!in_array('Partner', $roleAll)){
            Role::create(['name' => 'Partner']);
        }
        
        if(!in_array('Member', $roleAll)){
            Role::create(['name' => 'Member']);
        }
        $from = Carbon::parse($request->get('filter_from'));
        $to_at = Carbon::parse( $request->get('filter_to'));
        $to = $to_at->format('Y-m-d 23:59:59');

        // $date_string = "{$from->format('Y-m-d')},{$to->format('Y-m-d')}";
        if ($request->ajax()) {
            $data = User::select('*');

            if(!empty($request->filter_from)){
                $data->whereBetween('created_at', array($from, $to))
                ->get();
            }

            // dd();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($data){
                        $user = auth()->user();

                        $btn = '<a href="javascript:void(0)" id="show" class="show btn btn-primary btn-sm" data-id="'.$data->id.'" data-fullname="'.$data->fullname.'" data-name="'.$data->name.'" data-email="'.$data->email.'" data-identity_card_no="'.$data->identity_card_no.'" data-bank_name="'.$data->bank_name.'" data-bank_account="'.$data->bank_account.'"  data-address="'.$data->address.'"  data-city="'.$data->city.'"  data-state="'.$data->state.'"  data-postcode="'.$data->postcode.'" data-profile_image="'.$data->profile_image.'" data-identity_card="'.$data->identity_card.'" data-referrel_url="'.$data->referrel_url.'" data-verified_status="'.$data->verified_status.'" data-phone_no="'.$data->phone_no.'" data-role="'.preg_replace('/[^A-Za-z0-9\-]/', '', $data->getRoleNames()).'" data-bs-toggle="modal" data-bs-target="#showUserModal">View & Approve</a>';
                        // if ($user->can('user-edit')) {
                        //     $btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-warning btn-sm" data-id="'.$data->id.'" data-name="'.$data->name.'" data-email="'.$data->email.'" data-role="'.preg_replace('/[^A-Za-z0-9\-]/', '', $data->getRoleNames()).'" data-bs-toggle="modal" data-bs-target="#editUserModal">Edit</a>';
                        // }
                        // if ($user->can('user-delete')) {
                        //     $btn = $btn.'<a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-id="'.$data->id.'" data-name="'.$data->name.'" data-bs-toggle="modal" data-bs-target="#deleteUserModal">Delete</a>';
                        // }

                        // $$btn = $btn.'<a href="javascript:void(0)" class="btn btn-primary edit" id="btn-edit" data-id="{{$data->id}}" data-name="{{$data->name}}" value="{{$data->id}}" >Edit</a>';
                        return $btn;
                })
                ->addColumn('user_role', function($data){
                    $getRole = $data->getRoleNames()->toArray();
                    return $getRole[0] ?? null;
                })
                ->editColumn('created_at', function($data){ $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('Y-m-d H:i:s'); return $formatedDate; })
                ->editColumn('phone_no', function($data){ $formatedDate = 'https://api.whatsapp.com/send/?phone='.$data->created_at;  return $formatedDate; })
                    ->editColumn('email_verified_at', function($data){
                        if($data->email_verified_at != null){
                            $verified = 'yes';
                        }else{
                            $verified = 'no';
                        }

                            return $verified;
                        })
                    ->rawColumns(['action', 'user_role'])
                ->order(function ($data) {
                    $data->orderBy('created_at', 'desc');
                })
                ->make(true);
        }

        return view('users.index' , compact('roles'));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $input = $request->all();

        $input['password'] = FacadesHash::make($input['password']);

        $user = User::create($input);
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
        $user->verified_status = 'Approved';
        $user->save();
        $user->assignRole($request->input('roles'));

        return response()->json(['success' => 'User created successfully']);
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
    public function update(Request $request, $id)
    {
        try{
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            return response()->json(['success' => 'User Edited successfully']);
        }catch (\Throwable $th) {
            // DB::rollBack();
            throw $th;
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
        try{
            $user = User::find($id);
            $user->delete();

            return response()->json(['success' => 'User deleted successfully']);
        }catch (\Throwable $th) {
            // DB::rollBack();
            throw $th;
        }
    }

    public function approved($id){
        try{
            $user = User::find($id);
            $user->verified_status = 'Approved';
            $user->save();
            $message = array('message' => 'User account approved!', 'title' => 'Success!');
            return response()->json($message);
        }catch (\Throwable $th) {
            // DB::rollBack();
            $message = array('message' => 'Failed to approved!', 'title' => 'Failed!');
            return response()->json($message);
        }
    }
    public function export(Request $request)
    {
        // $export = new UserExport($request->all());
        return Excel::download(new UserExport($request->all()), 'visitors-list.xlsx');
    }

    public function exportPDF(Request $request)
    {
        return Excel::download(new UserExport($request->all()), 'visitors-list.pdf');
    }
}
