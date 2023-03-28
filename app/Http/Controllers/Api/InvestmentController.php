<?php

namespace App\Http\Controllers\Api;

use App\Exports\InvestmentExport;
use App\Http\Controllers\Controller;
use App\Models\Investments;
use App\Models\InvestmentStatus;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $from = Carbon::parse($request->get('filter_from'));
        $to_at = Carbon::parse( $request->get('filter_to'));
        $to = $to_at->format('Y-m-d 23:59:59');
        $investment = Investments::get();

        foreach($investment as $i){
            $date = date(today());

            $formatted_date = date('Y-m-d H:i:s');
            // dump($i->dividen_date, $date->format('Y-m-d H:i:s'));
            if($i->dividen_date <= $formatted_date){
                $investmentStatus = InvestmentStatus::find($i->id);
                if($investmentStatus->name == 'Progress'){
                    $investmentStatus->name = 'Withdraw';
                    $investmentStatus->save();
                }

            }
        }
        // $date_string = "{$from->format('Y-m-d')},{$to->format('Y-m-d')}";
        if ($request->ajax()) {
            $data = Investments::join('investment_status', 'investment_status.investment_id', '=' ,'investments.id')
            ->select('investments.*', 'investment_status.name as status')
            ->get();

            if(!empty($request->filter_from)){
                $data->whereBetween('created_at', array($from, $to))
                ->get();
            }

            // dd();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($data){
                        $user = auth()->user();

                        $btn = '<a href="javascript:void(0)" id="show" class="show btn btn-primary btn-sm" data-id="'.$data->id.'" data-amount="'.$data->amount.'" data-roi="'.$data->roi.'" data-slot="'.$data->slot.'" data-roi_amount="'.$data->roi_amount.'" data-status="'.$data->status.'" data-receipt="'.$data->receipt.'" data-username="'.$data->username.'" data-bs-toggle="modal" data-bs-target="#showUserModal">View & Approve</a>';
                        // if ($user->can('user-edit')) {
                        //     $btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-warning btn-sm" data-id="'.$data->id.'" data-name="'.$data->name.'" data-email="'.$data->email.'" data-role="'.preg_replace('/[^A-Za-z0-9\-]/', '', $data->getRoleNames()).'" data-bs-toggle="modal" data-bs-target="#editUserModal">Edit</a>';
                        // }
                        // if ($user->can('user-delete')) {
                        //     $btn = $btn.'<a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-id="'.$data->id.'" data-name="'.$data->name.'" data-bs-toggle="modal" data-bs-target="#deleteUserModal">Delete</a>';
                        // }

                        // $$btn = $btn.'<a href="javascript:void(0)" class="btn btn-primary edit" id="btn-edit" data-id="{{$data->id}}" data-name="{{$data->name}}" value="{{$data->id}}" >Edit</a>';
                        return $btn;
                })
                ->editColumn('created_at', function($data){ $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('Y-m-d H:i:s'); return $formatedDate; })
                ->editColumn('amount', function($data){ $formatedDate = number_format($data->amount); return $formatedDate; })
                ->editColumn('roi_amount', function($data){ $formatedDate = number_format($data->roi_amount); return $formatedDate; })
                ->rawColumns(['action'])
                // ->order(function ($data) {
                //     $data->orderBy('created_at', 'desc');
                // })
                ->make(true);
        }

        return view('investment.index');
    }

    public function investmentIndex($username)
    {
        try {
            $data = Investments::where('username', $username)
            ->join('investment_status', 'investment_status.investment_id', '=' ,'investments.id')
            ->select('investments.*', 'investment_status.name as status')
            // ->orderBy('investment_status.created_at', 'DESC')
            ->get();
            return response()->json($data, 201);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json('Failed to get Investment data!', 401);
        }
    }

    public function getUserByUsername($username)
    {
        try {
            $data = User::where('username', $username)->get();
            $first = User::where('username', $username)->first();
            $role = $first->getRoleNames();
            
            $data[0]->role = $role[0];

            return response()->json($data, 201);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json('Failed to get User data!', 401);
        }
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
            'amount' => 'required|numeric|min:1000',
            'receipt' => 'required',
            'username' => 'required'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->messages();
            if(isset($error['amount'][0])){
                $message = array('message' => $error['amount'][0], 'title' => 'Failed!');
            }elseif(isset($error['receipt'][0])){
                $message = array('message' => $error['receipt'][0], 'title' => 'Failed!');
            }elseif(isset($error['username'][0])){
                $message = array('message' => $error['username'][0], 'title' => 'Failed!');
            }

            return response()->json($message, 400);
        }

        
       
        try {
            $investment = new Investments();
            $slot = $request->amount / 1000;
            $investment->username = strtoupper($request->username);
            $investment->amount = $request->amount;
            if($request->amount < 10000 ){
                $roi_amount =  $request->amount * 25 / 100;
                $investment->roi = 25;
                $investment->roi_amount = $roi_amount;
            }elseif($request->amount >= 10000 && $request->amount < 30000){
                $roi_amount =  $request->amount * 27 / 100;
                $investment->roi = 27;
                $investment->roi_amount = $roi_amount;
                
            }elseif($request->amount >= 30000){
                $roi_amount =  $request->amount * 30 / 100;
                $investment->roi = 30;
                $investment->roi_amount = $roi_amount;
            }

            $investment->slot = $slot;
            if($request->file('receipt')){
                $file = $request->file('receipt');
                if($investment->image != $file->getClientOriginalName()){
                    $filename = date('YmdHi').$file->getClientOriginalName();
                    $file-> move(public_path('investment'), $filename);
                    $investment->receipt = $filename ? $filename : null;
                }
            }
            $investment->save();
            $investmentStatus = new InvestmentStatus();
            $investmentStatus->investment_id = $investment->id;
            $investmentStatus->name = 'Pending';
            $investmentStatus->save();

            $message = array('Investment_id'=> $investment->id, 'message' => 'Investment created successfully!', 'title' => 'Success!');
            return response()->json($message);
            // return back()->with('success', 'Success! User created');
        } catch (\Throwable $th) {
            $message = array('message' => 'Failed to create Investment!', 'title' => 'Failed!');
            return response()->json($message);
        }
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

    public function withdrawInvestment(Request $request, $investment)
    {
        try {
            $investProgress = InvestmentStatus::where('investment_id', $investment)->first();
            $investProgress->name = 'Floating';
            $investProgress->save();

            $message = array('message' => 'Investment status updated successfully!', 'title' => 'Success!');
            return response()->json($message);
        } catch (\Throwable $th) {
            $message = array('message' => 'Failed to updated Investment status!', 'title' => 'Failed!');
            return response()->json($message);
        }
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
        //
    }

    public function approval(Request $request, $id)
    {
        try {
            $investProgress = InvestmentStatus::where('investment_id', $id)->first();
            $investment = Investments::find($id);
            $created_at = $investProgress->created_at;
            $effectiveDate = date('Y-m-d H:i:s', strtotime("+14 months", strtotime($created_at)));
            $date = Carbon::parse($effectiveDate);

            if($investProgress->name == 'Pending'){
                $total_direct_sales = $investment->amount * 0.02; //od member
                $total_empire_sales = $investment->amount * 0.01; //od partner
                $investProgress->name = $request->status;
                $investment->dividen_date = $date;
                $user = User::where('username', strtoupper($investment->username))->first();
                $investment->od_partner = $user->od_partner;
                $investment->od_member = $user->od_member;
                $investment->total_direct_sales = $total_direct_sales;
                $investment->total_empire_sales = $total_empire_sales;
                if(($user->getRoleNames() != 'Admin' || $user->getRoleNames() != 'Partner' || $user->getRoleNames() != 'Member') && $investment->amount >= 10000){
                    $user->assignRole('Member');
                }

                if(($user->getRoleNames() != 'Admin' || $user->getRoleNames() != 'Partner' || $user->getRoleNames() != 'Member') && $investment->amount < 10000){
                    $user->assignRole('Normal');
                }
                $investment->save();
                $investProgress->save();

            }else{
                $formatted_date = date('Y-m-d H:i:s');
                if($investment->dividen_date <= $formatted_date && $investProgress->name == 'Floating'){
                    $investProgress->name = 'Completed';
                    $investProgress->save();
                }else{
                    $message = array('message' => 'Investment is not matured to withdraw', 'title' => 'Failed!');
                    return response()->json($message);
                }
            }

            $message = array('message' => 'Investment status updated successfully!', 'title' => 'Success!');
            return response()->json($message);
        } catch (\Throwable $th) {
            $message = array('message' => 'Failed to updated Investment status!', 'title' => 'Failed!');
            return response()->json($message);
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

    public function export(Request $request)
    {
        return Excel::download(new InvestmentExport($request->all()), 'investment-list.xlsx');
    }

    public function exportPDF(Request $request)
    {
        return Excel::download(new InvestmentExport($request->all()), 'investment-list.pdf');
    }
}
