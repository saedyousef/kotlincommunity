@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 ">
            <div class="panel panel-default">
                <div class="panel-heading">Add Post</div>

                <div class="panel-body">
                	<form class="form-horizontal" role="form" method="POST" >
                        {{ csrf_field() }}
                         <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title" class="col-md-4 control-label">Title</label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}" required autofocus>

                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
                            <label for="body" class="col-md-4 control-label">Body</label>

                            <div class="col-md-6">
                                <textarea id="body" name="body" autofocus="true">{{ old('body') }}</textarea>

                                @if ($errors->has('body'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('body') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Add
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('load_scripts')
    <script type="text/javascript">

        // Initialize "Simplemde" Markdown editor
        var simplemde = new SimpleMDE({
            element: document.getElementById("body"),
            showIcons: ["code", "table"],
            spellChecker: true,
            renderingConfig: {
                codeSyntaxHighlighting: true,
            },
            toolbar: [ "bold", "italic", "|", "link", "code", "quote", "|", "unordered-list", "ordered-list", "|","preview", "clean-block",

                "|", // Separator
                
            ],
        });
    </script>
@endsection


