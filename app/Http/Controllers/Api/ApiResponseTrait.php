<?php
namespace App\Http\Controllers\Api;

//Todos: this trait class for making specific response function
trait ApiResponseTrait {

    public function apiresponse($data = null, $message = null, $status = null) {
        $array = [
            "data"=> $data,
            "message"=> $message,
            "status"=> $status,
        ];
        return response($array, $status);
    }

}
