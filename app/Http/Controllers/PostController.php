<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{

    public function __construct(){
        $this->middleware("auth");
    }

    public function index()
    {
        $posts = Post::with('user')->get();

        return view("Posts.posts", compact("posts"));
    }

    public function create()
    {

    }

    public function store(StorePostRequest $request)
    {
        Post::create([
            "title"=> $request->title,
            "body"=> $request->body,
            "user_id"=> auth()->id(),
        ]);
        return redirect()->route("posts.index");
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::with('comment')->findOrfail($id);
        return view('Posts.comments', compact('post'));
    }

    public function edit($id)
    {
        if(auth()->user()->role_id == 1){
            $data = Post::findOrfail($id);
        }
        elseif(auth()->user()->role_id == 2){
            $data = Post::where('user_id',auth()->id())->findOrfail($id);
        }
        return response()->json($data);
    }

    public function update(UpdatePostRequest $request)
    {
        //? it's should be ajax data
        if($request->ajax()){
            $post = Post::findOrFail($request -> id);

            // Update the post with the data from the request
            $post->update([
                "title"=> $request->title,
                "body"=> $request->body,
            ]);

            //Todos: here we pass data as json to be able to do the function without reload page
            return response()->json($post);
        };
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        //TOdos: we pass postData like the card in blade which named post-id
        return response()->json(['success' => true, 'postData' => 'post-'.$id]);
    }

}
