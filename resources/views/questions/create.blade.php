@extends('layouts.frontend')
@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
    @endsection
@section('content')

    <div class="col-md-10 mt-3">

        <div class="card">
            <div class="card-header">
                <h3>Ask a question</h3>
            </div>
            <div class="card-body">
                <form action="{{route('question.store')}}" method="post">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="tags">Pick a tag (multiple available)</label>
                        <select class="form-control" name="tags[]" id="select" multiple>

                            @foreach($tags as $tag)
                            <option value="{{$tag->id}}">{{$tag->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title">Question Title</label>
                        <input type="text" class="form-control" name="title">
                    </div>
                    <div class="form-group">
                        <label for="body">Question body</label>
                        <textarea name="body" id="" class="form-control" cols="30" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Ask" class="form-control btn btn-outline-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.10.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'body' );
        CKEDITOR.config.removePlugins = 'image';
    </script>
    <script>
        $('#select').select2({
            'placeholder': 'Please select tags',

        });
    </script>
@endsection