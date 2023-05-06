<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Branche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ClassRoomController extends Controller
{
    use apiResponse;
 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


       $dataclass= ClassRoom::paginate(PAGINATION_COUNT);



        if ($dataclass) {
            return $this->traitResponse($dataclass, 'SUCCESS', 200);
       }
        return $this->traitResponse(null, 'Sorry Failed Not Fond', 404);


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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validation = validator::make($request->all(), [

            'size' => 'required',


        ]);
        if ($validation->fails()) {
            return $this->traitResponse(null, $validation->errors(), 400);

        }


        $dataClass = ClassRoom::create($request->all());

        if ($dataClass) {
            return $this->traitResponse($dataClass, 'Saved Successfully', 200);

        }
        return $this->traitResponse(null, 'Saved Failed', 400);


    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\ClassRoom $classRoom
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataClass = ClassRoom::find($id);

        if ($dataClass) {
            return $this->traitResponse($dataClass, 'SUCCESS', 200);


        }

        return $this->traitResponse(null, 'Sorry Not Found', 404);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\ClassRoom $classRoom
     * @return \Illuminate\Http\Response
     */
    public function edit(ClassRoom $classRoom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ClassRoom $classRoom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $dataClass = ClassRoom::find($id);

        if (!$dataClass) {
            return $this->traitResponse(null, ' Sorry Not Found', 404);

        }

        $validation = Validator::make($request->all(), [
            'size' => 'required|max:100',

        ]);
        if ($validation->fails()) {
            return $this->traitResponse(null, $validation->errors(), 400);

        }

        $dataClass->update($request->all());
        if ($dataClass) {
            return $this->traitResponse($dataClass, 'Updated Successfully', 200);

        }
        return $this->traitResponse(null, 'Failed Updated', 400);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ClassRoom $classRoom
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $dataClass = ClassRoom::find($id);

        if (!$dataClass) {
            return $this->traitResponse(null, 'Not Found ', 404);
        }

        $dataClass->delete($id);

        if ($dataClass) {
            return $this->traitResponse(null, 'Deleted Successfully ', 200);

        }
        return $this->traitResponse(null, 'Deleted Failed ', 404);

    }


    public function search($filter)
    {
    
         
        $branchId = Auth::user()->branch_id;
        
        $filterResult = DB::table('branches')
            ->join('class_rooms','class_rooms.branch_id','=','branches.id')
            ->select('class_rooms.className','class_rooms.Number','class_rooms.size','branches.No','branches.name',[$branchId])
            ->where("class_rooms.className", "like","%".$filter."%")
            ->orWhere("class_rooms.Number", "like","%".$filter."%")
            ->paginate(PAGINATION_COUNT);

        if ($filterResult) {

            return $this->traitResponse(  $filterResult, 'Search Successfully', 200);

        

        }
    }
}
