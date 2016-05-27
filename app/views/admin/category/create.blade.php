@extends('admin.layout')

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">Category Management</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Category</span>
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
			<i class="fa fa-pencil-square-o"></i> Create Category
		</div>
	</div>
	<div class="portlet-body form">
        <form class="form-horizontal form-bordered form-row-stripped" role="form" method="post" action="{{ URL::route('admin.category.store') }}">
            <div class="form-body">            
                @foreach ([
                    'name' => 'Name',
                    'icon' => 'Icon',
                    'description' => 'Description',
                ] as $key => $value)
                    @if ($key == 'description')
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
                        <div class="col-sm-9">
                            {{ Form::textarea($key, null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    @elseif ($key == 'icon')
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
                        <div class="col-sm-9">
                            <input type="hidden" class="form-control" name="{{ $key }}" id="js-hidden-{{ $key }}" value="{{ DEFAULT_ICON }}">
                            <a class="btn btn-default" data-toggle="modal" href="#js-modal-icon" id="js-a-{{ $key }}">
								<i class="{{ DEFAULT_ICON }}"></i> 
							</a>
                            <span class="color-gray-normal"><i>&nbsp;&nbsp;&nbsp;( Click the button to change the Icon )</i></span>
                        </div>
                    </div>
                    @else
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
                        <div class="col-sm-9">
                            {{ Form::text($key, null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    @endif                
                @endforeach
            </div>
            <div class="form-actions fluid">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-success">
                        <span class="glyphicon glyphicon-ok-circle"></span> Save
                    </button>
                    <a href="{{ URL::route('admin.category') }}" class="btn btn-primary">
                        <span class="glyphicon glyphicon-share-alt"></span> Back
                    </a>
                </div>
            </div>            
        </form>
    </div>
</div>

<div class="modal fade bs-modal-lg" id="js-modal-icon" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title">Please select the Icon</h4>
			</div>
			<div class="modal-body">
			    @foreach ($icons as $key => $value)
				    <button class="btn btn-default" style="margin-top: 1px;" id="js-btn-icon"><i class="{{ $value }}"></i></button>
				@endforeach
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn blue" id="js-btn-select" data-dismiss="modal">Select</button>
			</div>
		</div>
	</div>
</div>
@stop

@section('custom-scripts')
<script>
$(document).ready(function() {
    $("button#js-btn-icon").click(function() {
        $("button#js-btn-icon").removeClass("red");
        $("button#js-btn-icon").addClass("btn-default");
        $(this).addClass('red');
        $(this).removeClass('btn-default');
    });
    $("button#js-btn-select").click(function() {
        if ($("button#js-btn-icon.red").size() > 0) {
            var icon = $("button#js-btn-icon.red").find("i").attr('class');
            $("#js-hidden-icon").val(icon);
            $("#js-a-icon").find("i").attr('class', icon);
            $("#js-modal-icon").modal('toggle');
        } else {
            bootbox.alert('Please select the Icon');
        }
    });
    
});
</script>
@stop

@stop
