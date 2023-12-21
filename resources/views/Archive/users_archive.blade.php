@extends('layouts.app')

@section('title')
    Users Archive
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Users archive</div>

                <div class="card-body">
                    @if (!empty($users))
                        <table class="table table-hover">

                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">E-mail</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            @php $x = 0 @endphp
                            @foreach ($users as $user)
                                @php $x++ @endphp
                                <tbody>
                                    <tr id="user_{{ $user -> id }}">
                                        <th scope="row">{{ $x }}</th>
                                        <td class="user_name">{{ $user -> name }}</td>
                                        <td class="user_email">{{ $user -> email }}</td>

                                        <td>
                                            @if ($user -> role_id == 1)
                                                <span class="badge rounded-pill text-bg-success role_id">Admin</span>
                                            @else
                                                <span class="badge rounded-pill text-bg-primary role_id">User</span>
                                            @endif
                                        </td>

                                        <td>

                                            {{--Todo: Force delete user button --}}
                                            {{--? at href we disabled the refresh from btn, onclick-> goes forward deleteuser function  --}}
                                            <a class="btn btn-danger" data-bs-toggle="modal" title="Delete user forever"
                                                href="javascript:void(0)" onclick="forcedeleteuser({{ $user-> id }})">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </a>

                                            {{--Todo: Restore user button --}}
                                            <form action="{{ route('users.restore',$user-> id) }}" method="post">
                                                @csrf
                                                <button class="btn btn-info" type="submit" title="Restore User">
                                                    <i class="fa fa-recycle" aria-hidden="true"></i>
                                                </button>
                                            </form>

                                        </td>

                                    </tr>
                                </tbody>
                            @endforeach

                        </table>

                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        $.ajaxSetup({
            headers:
            {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    {{--Todo: delete user ajax function --}}
    <script type="text/javascript">

        function forcedeleteuser(id)
        {
            if(confirm("Are you sure to ForceDelete this user"))
            {
                $.ajax({
                    url: '/users/force_delete/' + id,
                    type: 'DELETE',
                    success: function(result) {
                        $("#" + result['userData']).slideUp("slow");
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                    }
                });
            }
        }

    </script>

@endsection
