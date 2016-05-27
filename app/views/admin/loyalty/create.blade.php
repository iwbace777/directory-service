@extends('admin.layout')

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">Loyalty Management</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Loyalty</span>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<span>Create</span>
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
			<i class="fa fa-pencil-square-o"></i> Create Loyalty
		</div>
	</div>
	<div class="portlet-body form">
        <form class="form-horizontal form-bordered form-row-stripped" role="form" method="post" action="{{ URL::route('admin.loyalty.store') }}">
            <div class="form-body">            
                @foreach ([
                    'company_id' => 'Company',
                    'name' => 'Name',
                    'description' => 'Description',
                    'count_stamp' => 'Count Stamp',
                ] as $key => $value)
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
                    <div class="col-sm-9">
                        @if ($key === 'description')
                            {{ Form::textarea($key, null, ['class' => 'form-control', 'rows' => 5, ]) }}
                        @elseif ($key === 'company_id')
                            {{ Form::select($key
                             , array('' => 'Select Company') + $companies->lists('name', 'id')
                             , NULL
                             , array('class' => 'form-control')) }}                        
                        @else
                            {{ Form::text($key, null, ['class' => 'form-control']) }}
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
                    <a href="{{ URL::route('admin.loyalty') }}" class="btn btn-primary">
                        <span class="glyphicon glyphicon-share-alt"></span> Back
                    </a>
                </div>
            </div>            
        </form>
    </div>
</div>
@stop

@stop
