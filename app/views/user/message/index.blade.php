@extends('user.layout')

@section('main')
<div class="container">
@if (isset($alert))
<div class="alert alert-{{ $alert['type'] }} alert-dismissibl fade in">
    <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <p>
        {{ $alert['msg'] }}
    </p>
</div>
@endif

    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 margin-top-normal margin-bottom-normal">
            <div class="portlet box red margin-top-lg">
                <div class="portlet-title">
                    <div class="caption">
                        Messages
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Feedback</th>
                                <th>Store</th>
                                <th>Created At</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($messages as $key => $message)
                                <tr>
                                    <td>{{ ($key + 1) }}</td>
                                    <td>{{ $message->feedback->description }}</td>
                                    <td>{{ $message->company->name }}</td>
                                    <td>{{ $message->created_at }}</td>
                                    <td><a href="{{ URL::route('user.message.detail', $message->feedback_id) }}" class="btn btn-success btn-sm">Detail</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>                      
                </div>
            </div>                         
        </div>
    </div>
</div>
@stop

@stop
