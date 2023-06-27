<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Withdraw;
use App\Models\FundAccount;
use App\Models\Subscribe;
use App\Models\ReceiptStudent;
use App\Models\StudentAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class WithdrawController extends Controller
{
    use apiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function index()

     {
         $threeMonthsAgo = \Carbon\Carbon::now()->subMonths(3)->format('Y-m-d');
 
         if (auth()->check())
         {
             $branchId = Auth::user()->branch_id;
 
             $Result = DB::table('withdraws')
             ->join('users', 'users.id', '=', 'withdraws.user_id')
             ->join('student_accounts', 'student_accounts.withdraw_id', '=', 'withdraws.id')
             ->join('payments', 'payments.id', '=', 'student_accounts.payment_id')
             ->join('branches', 'branches.id', '=', 'payments.branch_id')
             ->select('users.first_name','users.last_name','users.phone_number',
                 'payments.ammount','withdraws.amount','withdraws.date')
         ->where('branches.id', '=', $branchId)
         ->whereBetween('withdraws.date', [$threeMonthsAgo, date('Y-m-d')])
         ->orderBy('withdraws.date', 'desc')
         ->paginate(10);
 
         if ($Result->count() > 0) {
             return $this->traitResponse($Result, 'تم عرض البيانات بنجاخ', 200);
         } else {
             return $this->traitResponse(null, 'لا يوجد نتائج', 200);
         }
     } else {
         return $this->traitResponse(null, 'User not authenticated', 401);
     }
     
     }




    public function indexing($id)
    {
        $Result = DB::table('withdraws')
        ->join('users', 'users.id', '=', 'withdraws.user_id')
        ->join('student_accounts', 'student_accounts.withdraw_id', '=', 'withdraws.id')
        ->join('payments', 'payments.id', '=', 'student_accounts.payment_id')
        ->join('branches', 'branches.id', '=', 'payments.branch_id')
        ->select('users.first_name','users.last_name','users.phone_number',
            'payments.ammount','withdraws.amount','withdraws.date','branches.name')
            ->where('withdraws.id','=', $id)
              ->get();
    
    if ($Result->count() > 0) {
        return $this->traitResponse($Result, 'تم الحصول على البيانات بنجاح', 200);
    } else {
        return $this->traitResponse(null, ' لا يوجد نتائج ', 200);
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
         $branchId = Auth::user()->branch_id;

        $payment = Payment::where('id', $request->payment_id)->first();
               $subscription = Subscribe::where('id', $payment->subscribe_id)->first();
              $course = $subscription->course;

              if ( ($course->approved == 4 || (date('Y-m-d') <= $course->start)) && $subscription->state != 3 )
              {
                
        DB::beginTransaction();

        $debitTotal = StudentAccount::where('user_id', $request->user_id)
        ->where('payment_id', $request->payment_id)
        ->sum('Debit');

        $creditTotal = StudentAccount::where('user_id', $request->user_id)
            ->where('payment_id', $request->payment_id)
            ->where('type', 'receipt')
            ->sum('Credit');

            $CreditStudent = $creditTotal;

            $processing = $debitTotal - $creditTotal;
        
            $availableBalance =  $creditTotal;
       
           $requestedAmount = $request->amount;
         
        if ($requestedAmount != $availableBalance) {
            return response()->json([
                'error' => '  المبلغ  المراد سحبه  يجب أن يكون مساوي للمبلغ الذي دفعه الطالب',
                'CreditStudent' => $CreditStudent
            ], 422);
        }
  

        try{

             $withdraw = new Withdraw();
             $withdraw->user_id = $request->user_id;
             $withdraw->amount = $request->amount;
             $withdraw->description = $request->description;
             $withdraw->date = date('Y-m-d');
             $withdraw->save();


             $fund_account = new FundAccount();
             $fund_account->withdraw_id = $withdraw->id;
              $fund_account->branch_id =$branchId;
             $fund_account->Debit =  0.00;
             $fund_account->Credit =$request->amount;
             $fund_account->description = $request->description;
             $fund_account->date = date('Y-m-d');
             $fund_account->save();


            $studentAccount = new StudentAccount();
            $studentAccount->user_id = $request->user_id;
            $studentAccount->payment_id =  $request->payment_id;
            $studentAccount->withdraw_id = $withdraw->id;
            $studentAccount->type = 'processing';
            $studentAccount->Debit = 0.00;
            $studentAccount->Credit = $processing ;
            $studentAccount->date = date('Y-m-d');
            $studentAccount->save();
        

    
        DB::commit();

        if ( $course->approved == 1) {
            
            $subscriptionCount = Subscribe::where('course_id', $course->id)->count();
            if ($subscriptionCount - 1 < $course->min_students) {
             
                $course->approved = 0;
                $course->save();
            }
        }

            $subscription->state = 3;
            $subscription->save();
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

    else{

        return response()->json(['error' => 'عذراً لا يمكن السحب  '], 422);

    }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */

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
    public function update(Request $request, $id)
    {
     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $datawithdraw= Withdraw::find($id);

        if(!$datawithdraw)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $datawithdraw->delete($id);

        if($datawithdraw)
        {
            return  $this->traitResponse(null , 'تم الحذف بنجاح ' , 200);

        }
        return  $this->traitResponse(null , 'فشل الحذف' , 404);
    }
}
