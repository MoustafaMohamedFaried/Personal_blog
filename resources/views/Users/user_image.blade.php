@extends('layouts.app')

@section('title')
    Profile Image
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @if (!empty($user->profile_pic))
                        @if (Auth::user()->role_id == 1)
                            <div class="card-header">{{ $user->name }}'s Profile Picture</div>

                            <div class="card-body">
                                <img src="{{ asset('Profile_pics/'.$user-> id.'/'.$user-> profile_pic) }}"
                                alt="{{ $user-> name }}'s Profile Picture" width="650px" height="600px">
                            </div>
                        @else
                            <div class="card-header">{{ Auth::user()->name }}'s Profile Picture</div>

                            <div class="card-body">
                                <img src="{{ asset('Profile_pics/'.Auth::user()-> id.'/'.Auth::user()-> profile_pic) }}"
                                alt="{{ Auth::user()-> name }}'s Profile Picture" width="650px" height="600px">

                            </div>
                        @endif
                    @else
                        <div class="card-body">
                            <h1 style="text-align: center">This user doesn't have profile image</h1>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
