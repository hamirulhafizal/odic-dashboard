<?php

namespace App\Http\Controllers\Api;

use App\Exports\CommisionExport;
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
use Illuminate\Support\Facades\DB;

class CommisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');
        $sql = 'SELECT username, sum(roi_amount) AS total_roi_amount, sum(amount) AS total_amount FROM investments WHERE dividen_date BETWEEN ? AND ? GROUP BY username';
        $data = DB::select($sql, [$start_date, $end_date]);

        return view('commision.index', compact('data'));
    }

    public function getByUsername($username)
    {  
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');
        $sql = 'SELECT * FROM investments WHERE dividen_date BETWEEN ? AND ? AND username = ?';
        $data = DB::select($sql, [$start_date, $end_date, $username]);
        $total = 0;
        foreach($data as $d){
            $total += $d->total_direct_sales;
        }
  
        return view('commision.all', compact('data', 'total', 'username'));
    }

    public function updateWithdrawStatus($username)
    {  
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');
        $sql = 'SELECT * FROM investments WHERE dividen_date BETWEEN ? AND ? AND username = ?';
        $data = DB::select($sql, [$start_date, $end_date, $username]);
 
        foreach($data as $d){
            $investment = Investments::find($d->id);
            $investment->commision = 'Yes';
            $investment->save();
        }
  
        return back();
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
                $role = $user->getRoleNames();
                
                
                if($role[0] == 'Normal' && $investment->amount >= 10000){
                    $user->removeRole($role[0]);                   
                    $user->assignRole('Member');
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
        return Excel::download(new CommisionExport($request->all()), 'commision-list.xlsx');
    }

    public function exportPDF(Request $request)
    {
        return Excel::download(new CommisionExport($request->all()), 'commision-list.pdf');
    }
}
