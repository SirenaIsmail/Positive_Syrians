<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use ApiResponse;
    public function index()
    {
        $databranch = Branch::get();

        if($databranch)
        {
            return $this->traitResponse($databranch,'SUCCESS', 200);

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
//        $validation = Validator::make($request->all(), [
//            'No' => 'required',
//            'name'=> 'required|unique:branches|max:50',
//
//        ]);
//        if($validation->fails())
//
//        {
//            return $this->traitResponse(null,$validation->errors(),400);
//
//        }

        $databranch = Branch::create($request -> all());

        if($databranch)
        {

            return  $this ->traitResponse( $databranch ,'Saved Successfully' , 200 );
        }

        return  $this->traitResponse(null,'Saved Failed ' , 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $databranch = Branch::find($id);

        if($databranch)
        {
            return $this->traitResponse($databranch , 'SUCCESS' , 200);


        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(Branch $branch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $databranch = Branch::find($id);

        if(!$databranch)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);

        }

        $validation = Validator::make($request->all(), [
            'No' => 'required',
            'name'=> 'required|unique:branches|max:50',

        ]);
        if($validation->fails())

        {
            return $this->traitResponse(null,$validation->errors(),400);

        }

        $databranch->update($request->all());
        if($databranch)
        {
            return $this->traitResponse($databranch , 'Updated Successfully',200);

        }
        return $this->traitResponse(null,'Failed Updated',400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $databranch = Branch::find($id);

        if(!$databranch)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $databranch->delete($id);

        if($databranch)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);
    }
}
