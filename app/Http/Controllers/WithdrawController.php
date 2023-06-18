<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Withdraw;
use App\Models\FundAccount;
use App\Models\ReceiptStudent;
use App\Models\StudentAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class WithdrawController extends Controller
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
        DB::beginTransaction();

        try{

             $withdraw = new Withdraw();
             $withdraw->user_id = $request->user_id;
             $withdraw->amount = $request->amount;
             $withdraw->description = $request->description;
             $withdraw->date = date('Y-m-d');
             $withdraw->save();


             $fund_account = new FundAccount();
             $fund_account->withdraw_id = $withdraw->id;
             $fund_account->Debit =  0.00;
             $fund_account->Credit =$request->amount;
             $fund_account->description = $request->description;
             $fund_account->date = date('Y-m-d');
             $fund_account->save();


            $studentAccount = new StudentAccount();
            $studentAccount->user_id = $request->user_id;
            $studentAccount->payment_id =  $request->payment_id;
            $studentAccount->withdraw_id = $withdraw->id;
            $studentAccount->type = 'withdraw';
            $studentAccount->Debit = 0.00;
            $studentAccount->Credit =0.00;
            $studentAccount->date = date('Y-m-d');
            $studentAccount->save();
        
        

        DB::commit();

 }
 
     catch( \Exception $e){
        
         DB::rollback();
         return redirect()->back()->withErrors(['error' => $e->getMessage()]);

     }
     if($withdraw)
     {
        return response()->json(['message' => 'تم إضافة البيانات بنجاح']);

     }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function show(Withdraw $withdraw)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function edit(Withdraw $withdraw)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Withdraw $withdraw)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function destroy(Withdraw $withdraw)
    {
        //
    }
}
