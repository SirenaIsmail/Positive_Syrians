<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
//use PHPOpenSourceSaver\JWTAuth\Claims\Subject;

class SubjectController extends Controller
{

    use apiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataSubject = Subject::get();

        if($dataSubject)
        {
            return $this->traitResponse($dataSubject,'SUCCESS', 200);

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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name'=>'required',
            'content'=>'required',
            'price'=>'required',
            'houers'=>'required',
            'number_of_lessons'=>'required',
        ]);

        if($validation->fails())
        {
            return $this->traitResponse(null,$validation->errors(),400);
        }

        $content = $request->file('content');
        if (!$content) {
            return response()->json(['error' => 'No file uploaded.'], 400);
        }
        if (!$content->isValid()) {
            return response()->json(['error' => 'File upload failed.'], 400);
        }

        $path = Storage::disk('public')->put('uploads', $content);
        $dataSubject = Subject::create([
            'name'=> $request->name,
            'content'=> $path,
            'price'=> $request->price,
            'houers'=> $request->houers,
            'number_of_lessons'=> $request->number_of_lessons,
        ]);

        if($dataSubject)
        {
            return  $this ->traitResponse( $dataSubject ,'Saved Successfully' , 200 );
        }

        return  $this->traitResponse(null,'Saved Failed ' , 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Suject  $suject
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataSubject = Subject::find($id);

        if($dataSubject)
        {
            return $this->traitResponse($dataSubject , 'SUCCESS' , 200);


        }

        return  $this->traitResponse(null , 'Sorry Not Found ' , 404);









    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Suject  $suject
     * @return \Illuminate\Http\Response
     */
    public function edit(Suject $suject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Suject  $suject
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dataSubject = Subject::find($id);

        if(!$dataSubject)
        {
            return $this->traitResponse(null,' Sorry Not Found',404);

        }

        $validation = Validator::make($request->all(), [
            'name'=>'required',
            'content'=>'required',
            'price'=>'required',
            'houers'=>'required',
            'number_of_lessons'=>'required'
        ]);

        if($validation->fails())
        {
            return $this->traitResponse(null,$validation->errors(),400);
        }
        $content = $request->file('content');
        if (!$content) {
            return response()->json(['error' => 'No file uploaded.'], 400);
        }
        if (!$content->isValid()) {
            return response()->json(['error' => 'File upload failed.'], 400);
        }

        $path = Storage::disk('public')->put('uploads', $content);
        $dataSubject->update([
            'name'=> $request->name,
            'content'=> $path,
            'price'=> $request->price,
            'houers'=> $request->houers,
            'number_of_lessons'=> $request->number_of_lessons,
        ]);
        if($dataSubject)
        {
            return $this->traitResponse($dataSubject , 'Updated Successfully',200);
        }
        return $this->traitResponse(null,'Failed Updated',400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Suject  $suject
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataSubject = Subject::find($id);

        if(!$dataSubject)
        {
            return $this->traitResponse(null,'Not Found ' , 404);
        }

        $dataSubject->delete($id);

        if($dataSubject)
        {
            return  $this->traitResponse(null , 'Deleted Successfully ' , 200);

        }
        return  $this->traitResponse(null , 'Deleted Failed ' , 404);

    }



    public function upload(Request $request)
    {
        $content = $request->file('content');

        if (!$content) {
            return response()->json(['error' => 'No file uploaded.'], 400);
        }

        if (!$content->isValid()) {
            return response()->json(['error' => 'File upload failed.'], 400);
        }

        $path = Storage::disk('public')->put('uploads', $content);

        return response()->json(['path' => $path], 200);
    }

    public function download($filename){
        $path = Storage::disk('public')->path($filename);

        if (!file_exists($path)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        $file = file_get_contents($path);

        return response()->download($path);
    }



}
