<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use ApiResponseTrait;

    public function index(){
        $users = Post::all();
        return $this->apiresponse($users,"get users successfully",200);
    }

    public function show($id){
        $user = Post::find($id);

        //? if user founded
        if($user){
            return $this->apiresponse($user,"",200);
        }
        //? if not founded
        return $this->apiresponse(null,"This user not found",404);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiresponse(null,$validator->errors(),400);
        }

        $user = Post::create($request->all());
        return $this->apiresponse($user,"",201);
    }
}
