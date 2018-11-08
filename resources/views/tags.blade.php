@extends('layouts.frontend')
@section('styles')

@endsection
@section('content')
    <div class="col-md-10 mt-5">
        <h2>Tags</h2>
        <p>A tag is a keyword or label that categorizes your question with other, similar questions. Using the right tags makes it easier for others to find and answer your question.
        </p>
        <div class="row">
        @foreach($tags as $tag)
            <div class="col-md-4">
                <div class="card my-1">
                    <div class="card-body">
                        <a href="{{route('tags', ['slug'=>$tag->slug])}}" class="btn btn-primary">{{$tag->title}}</a>
                        <p>{!! $tag->body !!}</p>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
        <ul class="pagination mt-3">
            {{$tags->links()}}
        </ul>
    </div>
@endsection