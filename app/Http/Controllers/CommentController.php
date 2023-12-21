<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(){
        $this->middleware("auth");
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $comment = Comment::create([
            "content"=> $request-> content,
            "post_id"=> $request-> post_id,
            "user_id"=> auth()->user()->id,
        ]);

        //* this line to return the comment&user relation to use it at ajax code
        $comment->user = $comment->user;

        return response()->json(["comment" => $comment]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = Comment::findOrfail($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        //? it's should be ajax data
        if($request->ajax()){
            $comment = Comment::findOrfail($id);
            $comment -> update([
                "content"=> $request-> content,
                "post_id"=> $request-> post_id,
                "user_id"=> auth()->user()->id,
            ]);

            //* this line to return the comment&user relation to use it at ajax code
            $comment->user = $comment->user;

            return response()->json(["comment" => $comment]);
        }
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        //TODos: we pass postData like the card in blade which named post-id
        return response()->json(['success' => true, 'commentData' => 'comment_'.$id]);
    }
}
