<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\History;
use App\Models\Subscribe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscribeController extends Controller
{
    use apiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $dataSubscribe = Subscribe::get();

        if($dataSubscribe)
        {
            return $this->traitResponse($dataSubscribe,'SUCCESS', 200);

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
            'subject_id'=>'required',
            'card_id'=>'required',
            'branch_id'=>'required',
            'state'=> 'required|integer',
            'date_id'=> 'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataSubscribe = Subscribe::create([
            'subject_id'=>  $request -> subject_id,
            'card_id'=>  $request -> card_id,
            'branch_id'=>  $request -> branch_id,
            'state'=>  $request -> state,
            'date_id'=>  $request -> date_id,
        ]);

        if($dataSubscribe)
        {

            return  $this ->traitResponse( $dataSubscribe ,'Saved Successfully' , 200 );
        }

        return  $this->traitResponse(null,'Saved Failed ' , 400);

    }




    public function approve($id){
        $Subscribe = Subscribe::find($id);
        if($Subscribe)
        {
            $Subscribe->state = 1;
            $Subscribe->save();

            $card_id = $Subscribe->card_id;  /// عند إضافة المعتمدين إلى سجل الحضور
            $course_id = $Subscribe->course_id;
            $historydata= new Request(
                ['card_id' =>$card_id,
                    'course_id' => $course_id,
                ]);
            $history = ( new HistoryController)->store($historydata);

            return response()->json([
                'Subscribe'=>$Subscribe,
                'history' => $history,
            ]);

        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);

    }




    public function notApprove($id){
        $Subscribe = Subscribe::find($id);
        if($Subscribe)
        {
            $Subscribe->state = 0;
            $Subscribe->save();

            $card_id = $Subscribe->card_id;  /// عند إضافة المعتمدين إلى سجل الحضور
            $course_id = $Subscribe->course_id;
            $historydata= History::with(['card', 'course'])
                ->whereHas('card', function ($query) use ($card_id) {
                $query->where('id', $card_id);
            })->whereHas('course', function ($query) use ($course_id) {
                $query->where('id', $course_id);
            })->first();
            $history = ( new HistoryController);
            $history->destroy($historydata);

            return response()->json([
                'Subscribe' => $Subscribe,
            ]);

        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);

    }





    public function pending($id){
        $Subscribe = Subscribe::find($id);
        if($Subscribe)
        {
            $Subscribe->state = 2;
            $Subscribe->save();

            //something in history

            return response()->json([
                'Subscribe' => $Subscribe,
            ]);

        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);

    }






    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subscribe  $subscribe
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataSubscribe = Subscribe::find($id);

        if($dataSubscribe)
        {
            return $this->traitResponse($dataSubscribe , 'SUCCESS' , 200);


        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);





    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subscribe  $subscribe
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscribe $subscribe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscribe  $subscribe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $dataSubscribe = Subscribe::find($id);

        if(!$dataSubscribe)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);

        }

        $validation = Validator::make($request->all(), [
            'state'=> 'required',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataSubscribe->update($request->all());
        if($dataSubscribe)
        {
            return $this->traitResponse($dataSubscribe , 'Updated Successfully',200);

        }
        return $this->traitResponse(null,'Failed Updated',400);





    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscribe  $subscribe
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataSubscribe = Subscribe::find($id);

        if(!$dataSubscribe)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataSubscribe->delete($id);

        if($dataSubscribe)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);







    }
}
