<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware("auth");
        $this->middleware("is_admin")->except("edit","update","index","show_img");
    }

    public function index()
    {
        //Todos: if user isn't admin call just his data
        if(Auth::user()->role_id == 2)
        {
            $users = User::select(
                'name',
                'email',
                'role_id',
            )->get();
        }
        else
        {
            $users = User::all();
        }
        return view("Users.users", compact("users"));
    }

    public function create()
    {

    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
        ]);

        //Todos: Check if a profile picture is uploaded
        if ($request->hasFile('profile_pic'))
        {
            //? Take the picture name
            $profilePic = $request->file('profile_pic')->getClientOriginalName();

            //? Make the folder with the user id and put it in the 'Profile_pics' directory
            $request->file('profile_pic')->move(public_path('Profile_pics/'.$user->id.'/'), $profilePic);

            //? Update the user's profile_pic field with the file name
            $user->update(['profile_pic' => $profilePic]);
        }

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

    }

    public function edit($id){

        $data = User::findOrfail($id);
        return response()->json($data);
    }

    public function update(UpdatePostRequest $request){

        if($request->ajax()){

            $user = User::findOrfail($request->id);

            //Todos: Check if a profile picture is uploaded
            if ($request->hasFile('profile_pic'))
            {
                //? Take the picture name
                $profilePic = $request->file('profile_pic')->getClientOriginalName();

                //? Make the folder with the user id and put it in the 'Profile_pics' directory
                $request->file('profile_pic')->move(public_path('Profile_pics/'.$user->id.'/'), $profilePic);

                //? Update the user's data, profile_pic field with the file name
                $user -> update([
                    'name'=> $request->name,
                    'email'=> $request->email,
                    'password'=> bcrypt($request->password),
                    'role_id'=> $request->role_id,
                    'profile_pic' => $profilePic
                ]);
            }
            else
            {
                //? Update the user's data, let the previous profile_pic
                $user -> update([
                    'name'=> $request->name,
                    'email'=> $request->email,
                    'password'=> bcrypt($request->password),
                    'role_id'=> $request->role_id,
                ]);
            }

            //Todos: here we pass data as json to be able to do the function without reload page
            return response()->json(['success' => true, 'data' => $user]);
        };
    }

    public function destroy($id){
        $user = User::findOrFail($id);
        $user->delete();

        //? we pass userData like the card in blade which named user-id
        return response()->json(['success' => true, 'userData' => 'user_'.$id]);
    }

    //Todos: showing profile image dependence on role_id and user's id
    public function show_img($id){

        //? when user is admin let his access on all images
        if (Auth::user()->role_id == 1){
            $user = User::findorfail($id);
        }

        //? when user isn't admin just access on his image
        elseif (Auth::user()->role_id == 2){
            $user = User::where('id','=', auth()->id())->find($id);

            //* if user isn't admin & try to access hisn't image the show him 403 code (FORBIDDEN)
            if(auth()->id() != $id)
            {
                abort(403);
            };
        }

        return view('Users.user_image',compact('user'));
    }

    //Todos: User archive (Deleted users)
    public function archive(){
        $users = User::onlyTrashed()->get();
        return view("Archive.users_archive",compact("users"));
    }

    //Todos: Restore deleted users from archive
    public function restore($id){
        //? we use it for restore deleted users
        User::withTrashed()->where('id',$id)->restore();

        return redirect()->route('users.index');
    }

    //Todos: Delete user forever
    public function force_delete($id){
        //? we use it for delete users from database
        $user = User::withTrashed()->findOrFail($id);
        $user ->forceDelete();

        //? we pass userData like the card in blade which named user-id
        return response()->json(['success' => true, 'userData' => 'user_'.$id]);
    }
}
