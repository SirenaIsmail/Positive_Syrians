<?php

namespace App\Http\Controllers;

trait ApiResponse
{

   public function  traitResponse($data = null , $message = null , $status = null)
   {

    $array =[

        'data' => $data ,
        'message' => $message,
        'status' => $status ,
    ];

      return response($array , $status);

   }



}
