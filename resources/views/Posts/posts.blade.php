@extends('layouts.app')

@section('title')
    Posts
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">

            {{--? Posts card --}}
            <div class="container text-left">

                {{--Todo: Create post button --}}
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#createpost">
                    Create Post
                </button>

                <div class="row row-cols-4">
                    @foreach ($posts as $post)
                        @if (!empty($post->user-> id))
                            <div class="col" id="post-{{ $post-> id }}">
                                <div class="card" >
                                    <img class="card-img-top" src="{{ asset('Profile_pics/'.$post->user-> id.'/'.$post->user-> profile_pic) }}"
                                    class="img-fluid" height="250px" width="300px">

                                    <div class="card-body">
                                        <h5 class="card-title">{{ $post-> title }}</h5>
                                        <p class="card-text">{{ $post-> body }}</p>
                                        <p class="card-data">
                                            <small class="text-body-secondary">
                                                <p>
                                                    {{ $post->user-> name }}' Post
                                                </p>

                                                {{--Todo: comments page button  --}}
                                                <a role="button" class="btn btn-warning" href="{{ route('posts.show',$post-> id) }}">
                                                    Comments
                                                </a>

                                                {{--? this condition for letting user delete his own posts & admin can do that for all posts --}}
                                                @if (Auth::user()->name == $post->user-> name || Auth::user()->role_id == 1)
                                                    {{--Todo: Delete post button --}}
                                                    {{--! at href we disabled the refresh from btn, onclick-> goes forward deletepost function  --}}
                                                    <a class="btn btn-danger" data-bs-toggle="modal" data-deleteRoute="{{ route('posts.destroy',$post -> id) }}"
                                                        href="javascript:void(0)" onclick="deletepost({{ $post -> id }})">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </a>

                                                @endif

                                                {{--? the condition to let user can edit just his own posts --}}
                                                @if (Auth::user()->name == $post->user-> name)

                                                    {{--Todo: Edit post button --}}
                                                    <button type="button" class="btn btn-primary edit" data-bs-toggle="modal"data-bs-target="#editpost"
                                                        data-edit_route="{{route('posts.edit',$post -> id)}}">
                                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                                    </button>

                                                @endif

                                            </small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{--? Create post Modal --}}
            <div class="modal fade" id="createpost" tabindex="-1" aria-labelledby="createpost" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Create Post</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('posts.store') }}" method="POST" id="addpost">
                                @csrf

                                <div class="form-group">
                                    <label for="Title">Title</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="Title" name="title" value="{{ old('title') }}">
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Body">Body</label>
                                    <textarea type="text" class="form-control @error('body') is-invalid @enderror" id="Body" name="body">
                                        {{ old('body') }}
                                    </textarea>
                                    @error('body')
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

            @if (!empty($post))

                {{--? Edit post modal --}}
                <div class="modal fade" id="editpost" tabindex="-1" aria-labelledby="editpost" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Post</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="updateform" method="POST"
                                    data-update_route="{{ route('posts.update', $post -> id) }}">
                                    @csrf
                                    @method('put')

                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" class="form-control" id="title" name="title">
                                        <input type="hidden" name="id" id="id" value="">
                                    </div>

                                    <div class="form-group">
                                        <label for="body">Body</label>
                                        <textarea class="form-control" id="body" name="body"></textarea>
                                    </div>

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
    </div>


    <script>
        $.ajaxSetup({
            headers:
            {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    {{--Todo: edit post ajax function --}}
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
                    $("#title").val(data.title);
                    $("#body").val(data.body);

                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                },

            });
        });

    </script>

    {{--Todo: update post ajax fuction --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#submitUpdate').click(function() {
                // Serialize the form data
                var formData = $('#updateform').serialize();

                // Get the update route from the data attribute
                var updateRoute = $('#updateform').data('update_route');

                console.log("Clicked on update button, route:", updateRoute);

                // Make the AJAX request
                $.ajax({
                    url: updateRoute,
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(DataBack) {
                        // Handle success DataBack
                        console.log(DataBack);

                        // Update the card title
                        var $cardTitle = $('#post-' + DataBack.id + ' .card-title');
                        $cardTitle.empty().html(DataBack.title);

                        // Update the card text
                        var $cardText = $('#post-' + DataBack.id + ' .card-text');
                        $cardText.empty().html(DataBack.body);

                        // Close the modal
                        $('#editpost').modal('hide');

                        // Reset the form
                        $('#updateform')[0].reset();
                    },
                    error: function(xhr, status, error) {
                        // Handle error DataBack
                        console.error("AJAX Error:", status, error);
                    }
                });
            });
        });
    </script>

    {{--Todo: delete post ajax function --}}
    <script type="text/javascript">

        function deletepost(id)
        {
            if(confirm("Are you sure to delete this post"))
            {
                $.ajax({
                    url:'/posts/' + id, //destory function
                    type:'DELETE',
                    success:function(result)
                    {
                        $("#"+result['postData']).slideUp("slow");
                    }
                });
            }
        }

    </script>

@endsection

