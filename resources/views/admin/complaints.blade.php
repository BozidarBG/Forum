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
                            <tr>
                                <td class="text-danger">{{$complaint->getReportedUser()}}</td>
                                <td class="text-info">{{$complaint->getUserWhoComplained()}}</td>
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
                    <div class="text-center">
                        {!! $complaints->links() !!}
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection