@extends('admin.layout')

@section('custom-styles')]
    {{ HTML::style('/assets/metronic/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}
@stop

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">User Management</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>User</span>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<span>Edit</span>
				</li>
			</ul>
			
		</div>
	</div>    
@stop

@section('content')

@if ($errors->has())
<div class="alert alert-danger alert-dismissibl fade in">
    <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    @foreach ($errors->all() as $error)
		{{ $error }}		
	@endforeach
</div>
@endif

<div class="portlet box blue">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-pencil-square-o"></i> Edit User
		</div>
	</div>
	<div class="portlet-body form">
        <form class="form-horizontal form-bordered form-row-stripped" role="form" method="post" action="{{ URL::route('admin.user.store') }}" enctype="multipart/form-data">
            <input type="hidden" name="user_id" value="{{ $user->id }}"/>
            <div class="form-body">            
                @foreach ([
                    'name' => 'Name',
                    'email' => 'Email',
                    'password' => 'Password',
                    'phone' => 'Phone',
                    'photo' => 'Photo',                    
                ] as $key => $value)
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
                    <div class="col-sm-9">
                        @if ($key == 'photo')
                            <div class="fileinput fileinput-new" data-provides="fileinput">
								<div class="fileinput-new thumbnail" style="width: 120px; height: 120px;">
									<img src="{{ HTTP_USER_PATH.$user->photo }} " alt=""/>
								</div>
								<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 120px; max-height: 120px;"></div>
								<div>
									<span class="btn default btn-file">
									    <span class="fileinput-new">Select image </span>
									    <span class="fileinput-exists">Change </span>
									    <input type="file" name="{{ $key }}">
									</span>
									<a href="#" class="btn red fileinput-exists" data-dismiss="fileinput">Remove </a>
								</div>
							</div>
                        @elseif ($key == 'password')
                        <input type="password" class="form-control" name="{{ $key }}" value="">
                        @else
                        <input type="text" class="form-control" name="{{ $key }}" value="{{ $user->{$key} }}">
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="form-actions fluid">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-success">
                        <span class="glyphicon glyphicon-ok-circle"></span> Save
                    </button>
                    <a href="{{ URL::route('admin.company') }}" class="btn btn-primary">
                        <span class="glyphicon glyphicon-share-alt"></span> Back
                    </a>
                </div>
            </div>            
        </form>
    </div>
</div>
@stop

@section('custom-scripts')
{{ HTML::script('/assets/metronic/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}
@stop

@stop
