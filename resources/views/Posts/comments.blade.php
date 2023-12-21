@extends('layouts.app')

@section('title')
    Post - Comments
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ $post->user-> name }}'s post
                    </div>

                    <div class="card-body">
                        <div class="card-body">

                            {{--? Post data --}}
                            <div class="card-title">
                                <h3 style="text-align: center">{{ $post-> title }}</h3>
                                <p>{{ $post-> body }}</p>
                                <img src="{{ asset('Profile_pics/'.$post->user-> id.'/'.$post->user-> profile_pic) }}"
                                alt="{{ $post->user-> name }}'s Profile Picture" width="650px" height="600px" class="img-fluid">
                            </div>

                            {{--? Comments data --}}
                            <h5 class="card-title">Comments</h5>
                            <div class="card-text" id="commentsContainer">
                                @foreach ($post->comment as $comment)

                                    @if (!empty($comment->user-> name))

                                        <p id="comment_{{ $comment-> id }}">

                                            {{ $comment-> content }}
                                            <small style="color: red">by-{{ $comment->user-> name }}</small>

                                            {{--? this condition for let user can delete his comments in general and his posts comments & admin can delete all --}}
                                            @if ( (Auth::user()->id == $post-> user_id || Auth::user()->id == $comment-> user_id) || Auth::user()->role_id == 1)

                                                {{--Todo: Delete comment button --}}
                                                {{--! at href we disabled the refresh from btn, onclick-> goes forward deletecomment function  --}}
                                                <a class="btn btn-danger" data-bs-toggle="modal" href="javascript:void(0)"
                                                    onclick="deletecomment({{ $comment-> id }})" title="delete comment">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </a>

                                            @endif

                                            {{--? the condition to let user can edit just his own comments --}}
                                            @if (Auth::user()->id == $comment-> user_id)

                                                {{--Todo: Edit comment button --}}
                                                <button type="button" class="btn btn-primary edit" data-bs-toggle="modal"data-bs-target="#editcomment"
                                                    data-edit_route="{{route('comments.edit',$comment-> id)}}" title="edit comment">
                                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                                </button>

                                            @endif

                                        </p>
                                    @endif

                                @endforeach
                            </div>

                            {{--Todo: Add comment section  --}}
                            <form action="{{ route('comments.store') }}" method="post" id="addcomment">
                                @csrf
                                <textarea class="form-control" name="content"></textarea>
                                <input type="hidden" name="post_id" value="{{ $post-> id }}">
                                <button type="submit" class="btn btn-primary form-control">Leave Comment</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            @if (!empty($comment))
                {{--? Edit comment modal --}}
                <div class="modal fade" id="editcomment" tabindex="-1" aria-labelledby="editcomment" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Comment</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="updateform" method="POST"
                                    data-update_route="{{ route('comments.update', $comment-> id) }}">
                                    @csrf
                                    @method('put')

                                    <div class="form-group">
                                        <label for="content">Content</label>
                                        <textarea class="form-control" id="content" name="content"></textarea>
                                        <input type="hidden" name="id" id="id" value="">
                                        <input type="hidden" name="post_id"  value="{{ $comment-> post_id }}">
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary"  id="submitUpdate">Save changes</button>
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

    {{--Todo: Add comment ajax function --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#addcomment').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('comments.store') }}',
                    data: $('#addcomment').serialize(),
                    type: 'post',
                    dataType: 'json',
                    success: function(result) {
                        console.log(result);

                        commentsContainer = $('#commentsContainer');
                        commentsContainer.append(
                            `<p>
                                ${result.comment.content}
                                <small style="color: red">by-${result.comment.user.name}</small>
                            </p>`
                        );
                        $('#addcomment')[0].reset();
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                    },
                });
            });
        });


    </script>

    {{--Todo: Edit comment ajax function --}}
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
                    $("#content").val(data.content);

                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                },

            });
        });

    </script>

    {{--Todo: Update post ajax fuction --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#submitUpdate').click(function() {
                event.preventDefault();
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
                        console.log('success function');
                        $('#comment_' + DataBack.comment.id ).html(
                            `${DataBack.comment.content}
                            <small style="color: red">by-${DataBack.comment.user.name}</small>
                            <small style="color: blue">Edited</small>`)

                        // Close the modal
                        $('#editcomment').modal('hide');

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

    {{--Todo: Delete comment ajax function --}}
    <script type="text/javascript">

        function deletecomment(id)
        {
            if(confirm("Are you sure to delete this comment"))
            {
                $.ajax({
                    url: '{{ route('comments.destroy', '') }}' +'/'+ id,  //destory function
                    type:'DELETE',
                    success:function(result)
                    {
                        $("#"+result['commentData']).slideUp("slow");
                    }
                });
            }
        }

    </script>

@endsection
