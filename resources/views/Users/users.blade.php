@extends('layouts.app')

@section('title')
    Users
@endsection

@section('content')

    <div class="row justify-content-center">

        {{--Todo: users table --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Users information

                    {{--? just admins can see create user button & archive  --}}
                    @if (Auth::user()->role_id == 1)

                        {{--Todo: Create user button --}}
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#createuser">
                            Create user
                        </button>

                        {{--Todo: User archive button --}}
                        <a type="button" class="btn btn-secondary" href="{{ route('users.archive') }}">
                            Archive
                        </a>

                    @endif
                </div>

                <div class="card-body">
                    <table class="table table-hover">
                        {{--? if user is admin --}}
                        @if (Auth::user()->role_id == 1)
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">E-mail</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Profile Picture</th>
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
                                            <a role="button" class="btn btn-warning btn-sm" href="{{ route('users.show_img',$user -> id) }}">Profile_Pic</a>
                                        </td>

                                        <td>
                                            {{--Todo: Delete user button --}}
                                            {{--? at href we disabled the refresh from btn, onclick-> goes forward deleteuser function  --}}
                                            <a class="btn btn-danger" data-bs-toggle="modal" title="Delete User"
                                                href="javascript:void(0)" onclick="deleteuser({{ $user -> id }})">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </a>

                                            @if ($user-> id == Auth::user()->id)
                                                {{--Todo: Edit user button --}}
                                                <button type="button" class="btn btn-primary edit" data-bs-toggle="modal" title="Edit User"
                                                    data-bs-target="#edituser" data-edit_route="{{route('users.edit',$user -> id)}}">
                                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                                </button>
                                            @endif

                                        </td>

                                    </tr>
                                </tbody>
                            @endforeach

                        {{--? if user isn't admin --}}
                        @else
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">E-mail</th>
                                    <th scope="col">Profile Picture</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="user_{{ Auth::user()->id }}">

                                    <td class="user_name">{{ Auth::user()->name }}</td>
                                    <td class="user_email">{{ Auth::user()->email }}</td>

                                    <td>
                                        <a role="button" class="btn btn-warning btn-sm" href="{{ route('users.show_img',Auth::user()->id) }}">Profile_Pic</a>
                                    </td>

                                    <td>
                                        {{--Todo: Edit user button --}}
                                        <button type="button" class="btn btn-primary edit" data-bs-toggle="modal" title="Edit User"
                                            data-bs-target="#edituser" data-edit_route="{{route('users.edit',Auth::user()->id)}}">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </button>
                                    </td>

                                </tr>
                            </tbody>

                        @endif
                    </table>
                </div>
            </div>
        </div>

        {{--Todo: Create user modal (just show and work for admins) --}}
        @if (Auth::user()->role_id == 1)
            <div class="modal fade" id="createuser" tabindex="-1" aria-labelledby="createuser" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Create user</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('users.store') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="Name">Name</label>
                                    <input type="text" class="form-control @error ('name') is-invalid @enderror" id="Name" name="name" value="{{ old('name') }}">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="E-mail">E-mail</label>
                                    <input type="email" class="form-control @error ('email') is-invalid @enderror" id="E-mail" name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Password">Password</label>
                                    <input type="password" class="form-control @error ('password') is-invalid @enderror" id="Password" name="password" value="{{ old('password') }}">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Profile_Pic">Profile Picture</label>
                                    <input type="file" accept="image/*" class="form-control" id="Profile_Pic" name="profile_pic" value="{{ old('profile_pic') }}">
                                </div>

                                <div class="form-group">
                                    <label for="Role_id">Role</label>
                                    <select name="role_id" id="Role_id" class="form-control @error ('role_id') is-invalid @enderror" value="{{ old('role_id') }}">
                                        <option disabled selected value>----- Choose role -----</option>
                                        <option value="1">Admin</option>
                                        <option value="2">User</option>
                                    </select>
                                    @error('role_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success">Add</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{--? this condition for checking if users isn't empty --}}
        @if (!empty($user) || !empty(Auth::user()))

            {{--Todo: Edit user modal --}}
            <div class="modal fade" id="edituser" tabindex="-1" aria-labelledby="edituser" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit user</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <form id="updateform" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('put')

                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                    <input type="hidden" id="id" name="id">
                                </div>

                                <div class="form-group">
                                    <label for="email">E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>

                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>

                                <div class="form-group">
                                    <label for="profile_Pic">Profile Picture</label>
                                    <input type="file" accept="image/*" class="form-control" id="profile_Pic" name="profile_pic">
                                </div>

                                {{--? if user isn't admin then let his role_id still = 1 (admin)  --}}
                                @if (Auth::user()->role_id == 1)
                                    <input type="hidden" name="role_id" value="1">
                                {{--? if user isn't admin then let his role_id still = 2 (user)  --}}
                                @else
                                    <input type="hidden" name="role_id" value="2">
                                @endif

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary"  id="submitUpdate">Save changes</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        @endif


    </div>


    <script>
        $.ajaxSetup({
            headers:
            {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    {{--Todo: edit user ajax function --}}
    <script type="text/javascript">

        $(document).on("click",".edit",function()
        {

            var editRoute = $(this).attr("data-edit_route");

            console.log("Clicked on edit button, route:", editRoute);

            $.ajax({
                type:"get",
                url:editRoute,
                dataType:"json",
                success: function (data) {
                    console.log("AJAX success, data:", data);

                    // Update form fields after AJAX success
                    $("#id").val(data.id);
                    $("#Showid").val(data.id);
                    $("#name").val(data.name);
                    $("#email").val(data.email);
                    $("#password").val(data.password);
                    // $("#profile_pic").val(data.profile_pic);
                    $("#role_id").val(data.role_id);

                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                },

            });
        });

    </script>

    {{--Todo: update user ajax fuction --}}
    <script type="text/javascript">

        $(document).ready(function() {
            $('#submitUpdate').click(function() {
                // Trigger the form submission
                $("#updateform").submit();
            });

            $("#updateform").submit(function (e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);
                var idRow = $("#id").val();

                $.ajax({
                    url: '/users/' + idRow,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            // Update the user's name
                            var $userName = $('#user_' + response.data.id + ' .user_name');
                            $userName.html(response.data.name);

                            // Update the user's email
                            var $userEmail = $('#user_' + response.data.id + ' .user_email');
                            $userEmail.html(response.data.email);

                            // // Update the user's password
                            // var $userPassword = $('#user_' + response.data.password + ' .user_password');
                            // $userPassword.html(response.data.password);

                            /*
                                here we take id from update func at controller and put it at tr which has
                                id="user_{ $user -> id }" and go inside it to td which has id="role_id" then
                                we change the value, after changing we change texts (admin,user) and change
                                thier colors from span class
                            */
                                // Update the role_id
                                var $userRole_td = $('#user_' + response.data.id + ' .role_id');

                                // Check response.data.role_id instead of $userRole_id
                                if (response.data.role_id == 1) {
                                    $userRole_td.html('Admin').removeClass('text-bg-primary').addClass('text-bg-success');
                                }
                                else if (response.data.role_id == 2) {
                                    $userRole_td.html('User').removeClass('text-bg-success').addClass('text-bg-primary');
                                }

                            // Close the modal
                            $('#edituser').modal('hide');
                            // Reset the form
                            $('#updateform')[0].reset();
                        }
                        else {
                            console.error("Update failed");
                        }
                    },

                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                    },
                });
            });
        });

    </script>

    {{--Todo: delete user ajax function --}}
    <script type="text/javascript">

        function deleteuser(id)
        {
            if(confirm("Are you sure to delete this user"))
            {
                $.ajax({
                    url:'/users/' + id, //destory function
                    type:'DELETE',
                    success:function(result)
                    {
                        $("#"+result['userData']).slideUp("slow");
                    }
                });
            }
        }

    </script>

@endsection
