<?php

namespace App\Http\Controllers;
use App\Models\Payment;
use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Subscribe;
use App\Models\FundAccount;
use App\Models\ReceiptStudent;
use App\Models\StudentAccount;
use App\Models\ProcessingFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ReceiptStudentController extends Controller
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

        $Result = DB::table('receipt_students')
        ->join('users', 'users.id', '=', 'receipt_students.user_id')
        ->join('student_accounts', 'student_accounts.receipt_id', '=', 'receipt_students.id')
        ->join('payments', 'payments.id', '=', 'student_accounts.payment_id')
        ->join('branches', 'branches.id', '=', 'payments.branch_id')
        ->select('users.first_name','users.last_name','users.phone_number',
        'payments.ammount','receipt_students.Debit','receipt_students.date')
         ->where('branches.id', '=', $branchId)
        ->whereBetween('receipt_students.date', [$threeMonthsAgo, date('Y-m-d')])
        ->orderBy('receipt_students.date', 'desc')
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
        $Result = DB::table('receipt_students')
        ->join('users', 'users.id', '=', 'receipt_students.user_id')
        ->join('student_accounts', 'student_accounts.receipt_id', '=', 'receipt_students.id')
        ->join('payments', 'payments.id', '=', 'student_accounts.payment_id')
        ->join('branches', 'branches.id', '=', 'payments.branch_id')
        ->select('users.first_name','users.last_name','users.phone_number',
        'payments.ammount','receipt_students.Debit','receipt_students.date','branches.name')
        ->where('receipt_students.id','=', $id)
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

       if ( $subscription->state != 3 && $subscription->state != 4 )
       {   

        DB::beginTransaction();

         $debitTotal = StudentAccount::where('user_id', $request->user_id)
         ->where('payment_id', $request->payment_id)
         ->sum('Debit');

        $creditTotal = StudentAccount::where('user_id', $request->user_id)
         ->where('payment_id', $request->payment_id)
         ->sum('Credit');

        $availableBalance = $debitTotal - $creditTotal;
        $requestedAmount = $request->Debit;
    
       if ($requestedAmount < 3000  && $availableBalance > 3000 ) {
           return response()->json(['error' => 'لا يمكن إدخال قيمة أقل من 3000.'], 422);
         }

         if ($requestedAmount > $availableBalance ) {
            return response()->json([
                'error' => 'المبلغ المراد دفعه يتجاوز المبلغ الواجب دفعه.',
                'available_balance' => $availableBalance
            ], 422);
        }
    
        try {
      
    
            $receipt_student = new ReceiptStudent();
            $receipt_student->user_id = $request->user_id;
            $receipt_student->Debit = $request->Debit;
            $receipt_student->description = $request->description;
            $receipt_student->date = date('Y-m-d');
            $receipt_student->save();
    
            $fund_account = new FundAccount();
            $fund_account->receipt_id = $receipt_student->id;
             $fund_account->branch_id =$branchId;
            $fund_account->Debit = $request->Debit;
            $fund_account->Credit = 0.00;
            $fund_account->description = $request->description;
            $fund_account->date = date('Y-m-d');
            $fund_account->save();
    
            $studentAccount = new StudentAccount();
            $studentAccount->user_id = $request->user_id;
            $studentAccount->payment_id = $request->payment_id;
            $studentAccount->receipt_id = $receipt_student->id;
            $studentAccount->type = 'receipt';
            $studentAccount->Debit = 0.00;
            $studentAccount->Credit = $request->Debit;
            $studentAccount->date = date('Y-m-d');
            $studentAccount->save();

    
            DB::commit();




        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'حدث خطأ أثناء معالجة الطلب.'], 500);
        }

        $debitTotal = StudentAccount::where('user_id', $request->user_id)
        ->where('payment_id', $request->payment_id)
        ->sum('Debit');

       $creditTotal = StudentAccount::where('user_id', $request->user_id)
        ->where('payment_id', $request->payment_id)
        ->sum('Credit');

        $tooo = $debitTotal - $creditTotal ;
        if( $tooo == 0.00)
        {
            $subscription->state = 4;
            $subscription->save();

        }
    
        return response()->json(['success' => 'تم إضافة الإيصال بنجاح.'], 200);
    }
    else{
       
        return response()->json(['error' => 'عذراً لقد تم معالجة الفاتورة'], 422);
    }

    }
     

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReceiptStudent  $receiptStudent
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReceiptStudent  $receiptStudent
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReceiptStudent  $receiptStudent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $branchId = Auth::user()->branch_id;
        $dataReceipt = ReceiptStudent::find($id);
        if (!$dataReceipt) {
            return $this->traitResponse(null,' Sorry Not Found',404);
        }
    
        $payment = Payment::where('id', $request->payment_id)->first();
        $subscription = Subscribe::where('id', $payment->subscribe_id)->first();
       $course = $subscription->course;

       if ( $subscription->state != 3  )
       {   

        DB::beginTransaction();

         $debitTotal = StudentAccount::where('user_id', $request->user_id)
         ->where('payment_id', $request->payment_id)
         ->sum('Debit');

        $creditTotal = StudentAccount::where('user_id', $request->user_id)
         ->where('payment_id', $request->payment_id)
         ->sum('Credit');

        $availableBalance = $debitTotal - $creditTotal;
        $requestedAmount = $request->Debit;
    
       if ($requestedAmount < 3000  && $availableBalance > 3000 ) {
           return response()->json(['error' => 'لا يمكن إدخال قيمة أقل من 3000.'], 422);
         }

         if ($requestedAmount > $availableBalance && $availableBalance > 0.00  ) {
            return response()->json([
                'error' => 'المبلغ المراد دفعه يتجاوز المبلغ الواجب دفعه.',
                'available_balance' => $availableBalance
            ], 422);
        }
    
        try {
      
    
            $receipt_student = new ReceiptStudent();
            $receipt_student->user_id = $request->user_id;
            $receipt_student->Debit = $request->Debit;
            $receipt_student->description = $request->description;
            $receipt_student->date = date('Y-m-d');
            $receipt_student->save();
    
            $fund_account = new FundAccount();
            $fund_account->receipt_id = $receipt_student->id;
            $fund_account->branch_id =$branchId;
            $fund_account->Debit = $request->Debit;
            $fund_account->Credit = 0.00;
            $fund_account->description = $request->description;
            $fund_account->date = date('Y-m-d');
            $fund_account->save();
    
            $studentAccount = new StudentAccount();
            $studentAccount->user_id = $request->user_id;
            $studentAccount->payment_id = $request->payment_id;
            $studentAccount->receipt_id = $receipt_student->id;
            $studentAccount->type = 'receipt';
            $studentAccount->Debit = 0.00;
            $studentAccount->Credit = $request->Debit;
            $studentAccount->date = date('Y-m-d');
            $studentAccount->save();

    
            DB::commit();




        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'حدث خطأ أثناء معالجة الطلب.'], 500);
        }

        $debitTotal = StudentAccount::where('user_id', $request->user_id)
        ->where('payment_id', $request->payment_id)
        ->sum('Debit');

       $creditTotal = StudentAccount::where('user_id', $request->user_id)
        ->where('payment_id', $request->payment_id)
        ->sum('Credit');

        $tooo = $debitTotal - $creditTotal ;
        if( $tooo == 0.00)
        {
            $subscription->state = 4;
            $subscription->save();

        }
    
        return response()->json(['message' => 'تم تحديث البيانات بنجاح']);
    }

    else{

        return response()->json(['error' => 'عذراً لقد تم معالجة الفاتورة'], 422);
    }


        
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReceiptStudent  $receiptStudent
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $dataReceipt= ReceiptStudent::find($id);

        if(!$dataReceipt)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataReceipt->delete($id);

        if($dataReceipt)
        {
            return  $this->traitResponse(null , 'تم الحذف بنجاح ' , 200);

        }
        return  $this->traitResponse(null , 'فشل الحذف' , 404);

    }



    // public function search()
    // {

    //     $accounts = DB::table('student_accounts')->where('user_id', '=', $request->user_id)->get();

    //     foreach ($accounts as $account){
    //     $Result = DB::table('student_accounts')
    //     ->join('payments', 'payments.id', '=', 'student_accounts.payment_id')
    //     ->join('users', 'users.id', '=', 'student_accounts.user_id')
    //     ->select('users.first_name','users.last_name','users.phone_number',
    //     'payments.ammount','student_accounts.Debit','student_accounts.Credit','student_accounts.type','student_accounts.date')
    //     ->where('receipt_students.id','=', $id)
    //     ->get();
    //     if ($Result->count() > 0) {
    //         return $this->traitResponse($Result, 'تم الحصول على البيانات بنجاح', 200);
    //     } else {
    //         return $this->traitResponse(null, ' لا يوجد نتائج ', 200);
    //     }
    // }

    // }

    public function search($barcode)
    {
        $threeMonthsAgo = \Carbon\Carbon::now()->subMonths(3)->format('Y-m-d');

        $temp_table = DB::table('student_accounts')
            ->join('users', 'users.id', '=', 'student_accounts.user_id')
            ->join('cards', 'cards.user_id', '=', 'users.id')
            ->join('payments', 'student_accounts.payment_id', '=', 'payments.id')
            ->join('subscribes', 'payments.subscribe_id', '=', 'subscribes.id')
            ->join('courses', 'subscribes.course_id', '=', 'courses.id')
            ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
            ->select('payments.id', DB::raw('SUM(student_accounts.Credit) as total_credit'), DB::raw('SUM(student_accounts.Debit) as total_debit'))
            ->where('cards.barcode', '=', $barcode)
            ->whereBetween('payments.date', [$threeMonthsAgo, date('Y-m-d')])
            ->groupBy('payments.id');
            
    
        $response = [];
    
        foreach ($temp_table->get() as $row) {
            $payment = DB::table('payments')
                ->join('subscribes', 'payments.subscribe_id', '=', 'subscribes.id')
                ->join('courses', 'subscribes.course_id', '=', 'courses.id')
                ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
                ->select('payments.id', 'users.first_name', 'users.last_name', 'subjects.subjectName', 'payments.date', 'subscribes.state', 'payments.ammount', DB::raw('SUM(student_accounts.Credit) as total_credit'), DB::raw('SUM(student_accounts.Debit) as total_debit'))
                ->joinSub($temp_table, 'temp', function ($join) {
                    $join->on('payments.id', '=', 'temp.id');
                })
                ->join('student_accounts', function ($join) {
                    $join->on('student_accounts.payment_id', '=', 'payments.id')
                        ->join('users', 'users.id', '=', 'student_accounts.user_id');
                })
                ->groupBy('payments.id', 'users.id', 'subjects.id')
                ->orderBy('payments.date', 'desc')
                ->get();
    
            foreach ($payment as $account) {
                $found = false;
                foreach ($response as &$item) {
                    if ($item['payment_id'] == $account->id) {
                        // $item['students'][] = [
                        //     'student_name' => $account->first_name . ' ' . $account->last_name,
                        //     'subject_name' => $account->subjectName,
                        //     'payment_date' => $account->payment_date,
                        //     'payment_amount' => $account->ammount,
                        //     'total_credit' => $account->total_credit,
                        //     'remaining_balance' => $account->total_debit - $account->total_credit
                        // ];
                        // $item['total_payment_amount'] += $account->ammount;
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $response[] = [
                        'payment_id' => $account->id,
                        'students' => [
                            [
                                'student_name' => $account->first_name . ' ' . $account->last_name,
                                'subject_name' => $account->subjectName,
                                'subscribes_state' => $account->state,
                                'payment_amount' => $account->ammount,
                                'total_credit' => $account->total_credit,
                                'remaining_balance' => $account->total_debit - $account->total_credit,
                                'payment_date' => $account->date
                            ]
                        ],
                        
                    ];
                }
            }
        }
    
        return $this->traitResponse($response, 'تم الحصول على البيانات بنجاح', 200);
    }




    public function getImportByBranch()
    {
        $importByBranch = DB::table('fund_accounts')
        ->join('branches', 'branches.id', '=', 'fund_accounts.branch_id')
            ->select('branches.name', DB::raw('SUM(Debit - Credit) as import'))
            ->groupBy('branch_id')
            ->get();
    
        return $importByBranch;
    }



    public function getImportDaily()
{
    $branchId = Auth::user()->branch_id;
    $today = date('Y-m-d');
    $importByBranch = DB::table('fund_accounts')
        ->join('branches', 'branches.id', '=', 'fund_accounts.branch_id')
        ->select( DB::raw('SUM(Debit - Credit) as import'))
        ->where('fund_accounts.date', '=', $today)
        ->where('fund_accounts.branch_id', '=', $branchId)
        ->groupBy('branch_id')
        ->get();

    return $importByBranch;
}



    }

