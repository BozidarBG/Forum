@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Complaints</h4>
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
                            <tr class="table-row">
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
                                    <br>
                                    <form action="{{route('admin.complaint.delete',['id'=>$complaint->id])}}" method="post">
                                        @csrf
                                        <button type="submit" class="mt-3 btn btn-danger btn-sm delete" data-id="{{$complaint->id}}">Del</button>
                                    </form>

                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-center">
                        {!! $complaints->links() !!}
                    </div>

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
                        <button class="btn btn-success" id="send" >Confirm</button>
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