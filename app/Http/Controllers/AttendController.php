<?php

namespace App\Http\Controllers;

use App\Models\Attend;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AttendController extends Controller
{
    use apiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataAttend = Attend::paginate(PAGINATION_COUNT);

        if($dataAttend)
        {
            return $this->traitResponse($dataAttend,'SUCCESS', 200);

        }


        return $this->traitResponse(null, 'Sorry Failed Not Found', 404);




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
        $validation = Validator::make($request->all(), [
            'card_id'=>'required|integer',
            'course_id'=>'required|integer',
            'date_id'=>'required|integer',
            'state'=> 'required',


        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }
        $state=false;
        $dataAttend = Attend::create([
            'card_id' =>$request->card_id,
            'course_id' =>$request->course_id,
            'date_id' => $request->date_id,
            'state' =>$state,
        ]);

        if($dataAttend)
        {

            return  $this ->traitResponse( $dataAttend ,'Saved Successfully' , 200 );
        }

        return  $this->traitResponse(null,'Saved Failed ' , 400);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attend  $attend
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $dataAttend = Attend::find($id);

        if($dataAttend)
        {
            return $this->traitResponse($dataAttend , 'SUCCESS' , 200);


        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);

    }

    public function scanAttend($barcode,Request $request){
        $cardId= DB::table('cards')->where('barcode', $barcode)->first();
        $subscribe = DB::table('subscribes')
            ->where('card_id', $cardId)
            ->where('course_id', $request->course_id)
            ->exists();
        $thsDate =now()->format('Y-m-d');
//        $studentsCount = DB::table('subscribes')->where('course_id', $request->course_id)->count();
//        for ($i = 0; $i < $studentsCount; $i++) {
            if ($subscribe) {
                $state = true;
                $attendReq = new Request([
                    'card_id' => $cardId,
                    'course_id' => $request->course_id,
                    'date_id' => $request->date_id,
                    'state' => $state,
                ]);
                $attend = (new AttendController())->store($attendReq);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Student is attended successfully',
                    'attend' => $attend
                ]);

            } else {
                return response()->json([
                    'status' => 'denied',
                    'message' => 'Student is not subscribed in this course',
                ]);
            }


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attend  $attend
     * @return \Illuminate\Http\Response
     */
    public function edit(Attend $attend)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attend  $attend
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $dataAttend = Attend::find($id);

        if(!$dataAttend)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);

        }

        $validation = Validator::make($request->all(), [
            'card_id'=>'required|integer',
            'course_id'=>'required|integer',
            'date_id'=>'required|integer',
            'state'=> 'required',


        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataAttend->update($request->all());
        if($dataAttend)
        {
            return $this->traitResponse($dataAttend , 'Updated Successfully',200);

        }
        return $this->traitResponse(null,'Failed Updated',400);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attend  $attend
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataAttend = Attend::find($id);

        if(!$dataAttend)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataAttend->delete($id);

        if($dataAttend)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);

    }
}
