@extends('layouts.app')

@section('title')
    Home
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Welcome Again</div>

                    <div class="card-body">
                        <h1 style="text-align: center">
                            @if (Auth::user()->role_id == 1)
                                <span class="text-success">Welcome our Admin</span>
                            @else
                                <span class="text-primary">Welcome our User</span>
                            @endif
                        </h1>

                        <a role="button" href="{{ route('users.index') }}" class="btn btn-info">
                            @if (Auth::user()->role_id == 1)
                                Users
                            @else
                                Your info
                            @endif
                        </a>
                        <a role="button" href="{{ route('posts.index') }}" class="btn btn-secondary">Posts</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
