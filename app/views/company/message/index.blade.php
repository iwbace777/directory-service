@extends('company.layout')

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Message</span>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<span>List</span>
				</li>
			</ul>
			
		</div>
	</div>    
@stop

@section('content')
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
                    
<div class="portlet box red">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-navicon"></i> Message List
		</div>
	</div>
    <div class="portlet-body ">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Feedback</th>
                    <th>Consumer</th>
                    <th>Created At</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($messages as $key => $message)
                    <tr>
                        <td>{{ ($key + 1) }}</td>
                        <td>{{ $message->feedback->description }}</td>
                        <td>{{ $message->user->name }}</td>
                        <td>{{ $message->created_at }}</td>
                        <td><a href="{{ URL::route('company.message.detail', $message->feedback_id) }}" class="btn btn-success btn-sm">Detail</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop

@stop
