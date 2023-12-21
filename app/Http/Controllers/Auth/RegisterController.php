<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'profile_pic' => ['file', 'mimetypes:image/*', 'max:2048'],
        ]);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => 2, //* by default user not admin (we can change it to be admin to can test the site then get back)
        ]);

        if (request()->hasFile('profile_pic'))
        {
            //Todods: take the picture name
            $profilePic = request()->file('profile_pic')->getClientOriginalName();

            //Todods:make folder with user id,name and put it at public (we put storage_path that meen at our main folder)
            request()->file('profile_pic')->move(public_path('Profile_pics/'.$user->id.'/'), $profilePic);

            //Todods: store the picture name
            $user->update(['profile_pic'=> $profilePic]);
        }

        //* return all data
        return $user;
    }
}
