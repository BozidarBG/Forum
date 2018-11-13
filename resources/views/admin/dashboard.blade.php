@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info">
                <h4 class="text-white">Latest users</h4>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-25">#</th>
                            <th class="w-50">Name</th>
                            <th class="w-25">Profile</th>
                        </tr>
                    </thead>

                    <tbody >
                    @foreach($users as $user)
                        <tr>
                            <td class="w-25">{{$user->id}}</td>
                            <td class="w-50">{{$user->name}}</td>
                            <td class="w-25"><a href="{{route('show.user', ['hashid'=>$user->hashid])}}" class="btn btn-primary btn-sm">View</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card">
                <div class="card-header bg-success">
                    <h4 class="text-white">Latest Questions</h4>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="w-50">Title</th>
                            <th class="w-50">User</th>
                        </tr>
                        </thead>

                        <tbody >
                        @foreach($questions as $question)
                            <tr>
                                <td class="w-50"><a href="{{route('question.show', ['slug'=>$question->slug])}}">{{$question->title}}</a></td>
                                <td class="w-25"><a href="{{route('show.user', ['hashid'=>$question->user->hashid])}}">{{$question->user->name}}</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-warning">
                <h4 class="text-white">Latest Complaints</h4>
            </div>
            <div class="card-body">
                <table class="table table-responsive-sm">
                    <thead>
                    <tr>
                        <th>Reported</th>
                        <th>Reported by</th>
                        <th>Message</th>
                        <th>Link</th>
                    </tr>
                    </thead>

                    <tbody >
                    @foreach($complaints as $complaint)
                        <tr>
                            <td class="text-danger">{{$complaint->getReportedUser()->name}}<br><br>
                                <div class="btn-group dropdown">
                                    <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Ban user
                                    </button>
                                    <div class="dropdown-menu" data-id="{{$complaint->getReportedUser()->id}}" data-name="{{$complaint->getReportedUser()->name}}">
                                        @if($complaint->getReportedUser()->banned)
                                            <a href="#" class="dropdown-item confirm-modal" data-time="0">Remove ban</a>
                                        @else
                                            <a href="#" class="dropdown-item confirm-modal" data-time="2">Ban for 2 days</a>
                                            <a href="#" class="dropdown-item confirm-modal" data-time="7">Ban for 7 days</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-info">{{$complaint->getUserWhoComplained()->name}}<br><br>
                                <div class="btn-group dropdown">
                                    <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Ban user
                                    </button>
                                    <div class="dropdown-menu" data-id="{{$complaint->getUserWhoComplained()->id}}" data-name="{{$complaint->getUserWhoComplained()->name}}">
                                        @if($complaint->getUserWhoComplained()->banned)
                                            <a href="#" class="dropdown-item confirm-modal" data-time="0">Remove ban</a>
                                        @else
                                            <a href="#" class="dropdown-item confirm-modal" data-time="2">Ban for 2 days</a>
                                            <a href="#" class="dropdown-item confirm-modal" data-time="7">Ban for 7 days</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-justify w-50">{!! $complaint->body !!}</td>
                            <td><a href="{{$complaint->link}}"
                                   class="btn btn-secondary btn-sm" data-toggle="tooltip"
                                   data-placement="left" title="{{$complaint->link}}" target="_blank">Link
                                </a>
                            </td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--confirm modal-->
    <div class="modal fade" id="confirmModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span></span>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit" name="submit" id="send" data-id="">Change</button>
                    <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

    @endsection

@section('scripts')
    <script src="{{asset('js/ban-user.js')}}"></script>
@endsection