<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $dataCard = Card::paginate(PAGINATION_COUNT);
       if($dataCard)
       {
           return $this->traitResponse($dataCard,'SUCCESS',200);

       }

       return $this->traitResponse(null, 'Sorry Not Found',404);

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
        $validation=validator::make($request->all(),[

            'user_id'=>'required',
           'branch_id'=>'required',


        ]);

        if($validation->fails())
        {
            return $this->traitResponse(null,$validation->errors(),400);
        }

        $dataCard = Card::create($request->all());
        if($dataCard)
        {
            return $this->traitResponse($dataCard,'Saved Successfully',200);
        }
        return $this->traitResponse(null,'The Card Not Saved',400);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $dataCard = Card::find($id);

        if($dataCard)
        {
            return $this->traitResponse($dataCard,'SUCCESS',200);

        }
        return $this->traitResponse(null,'Sorry Not Found ',404);


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function edit(Card $card)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $dataCard = Card::find($id);

        if(!$dataCard)
        {
            return $this->traitResponse(null,'Sorry Not Found ' ,404);

        }

        $validation=validator::make($request->all(),[

           'user_id'=>'required',
           'branch_id'=>'required',

        ]);

        if($validation->fails())
        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $dataCard->update($request->all());

        if($dataCard)
        {
            return $this->traitResponse($dataCard,'Updated Successfully ',200);

        }


        return $this->traitResponse(null,' Updated Failed',400);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataCard = Card::find($id);

        if(!$dataCard)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataCard->delete($id);

        if($dataCard)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);



    }
}
