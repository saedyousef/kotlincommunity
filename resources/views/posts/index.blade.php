@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Posts</div>

                <div class="panel-body">
                    <a href="{{ route('add_post') }}" class="btn btn-primary pull-right">Add Post</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
    <div class="col-md-1"></div>
        <div class="col-md-10">
            @foreach($posts as $post)
                <div>
                    <a  target="_blank" href="{{ url('posts/view/'.$post->id) }}">{{$post->title}}</a><br/>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
@section('load_scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script type="text/javascript">
    var test = $('#upvote').data('test');
     
function delete_post(post_id){
    $.ajax({
        headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: "DELETE",
        url:  '/posts/delete/'+post_id ,
        success: function (data) {
            console.log(data);
        },
        error: function (data) {
            console.log('Error:', data);
        }
    });
}
</script>
@endsection