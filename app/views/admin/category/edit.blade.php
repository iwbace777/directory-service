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
			<i class="fa fa-pencil-square-o"></i> Edit Category
		</div>
	</div>
	<div class="portlet-body form">
        <form class="form-horizontal form-bordered form-row-stripped" role="form" method="post" action="{{ URL::route('admin.category.store') }}">
            <div class="form-body">
                <input type="hidden" name="category_id" value="{{ $category->id }}">            
                @foreach ([
                    'name' => 'Name',
                    'icon' => 'Icon',
                    'description' => 'Description',
                    'created_at' => 'Created At',
                    'updated_at' => 'Updated At',                    
                ] as $key => $value)
                    @if ($key === 'created_at' || $key === 'updated_at')
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $category->{$key} }} </p>
                        </div>
                    </div>
                    @elseif ($key == 'description')
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
                        <div class="col-sm-9">
                            {{ Form::textarea($key, $category->{$key}, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    @elseif ($key == 'icon')
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
                        <div class="col-sm-9">
                            <input type="hidden" class="form-control" name="{{ $key }}" id="js-hidden-{{ $key }}" value="{{ $category->icon }}">
                            <a class="btn btn-default" data-toggle="modal" href="#js-modal-icon" id="js-a-{{ $key }}">
								<i class="{{ $category->icon }}"></i> 
							</a>
                            <span class="color-gray-normal"><i>&nbsp;&nbsp;&nbsp;( Click the button to change the Icon )</i></span>
                        </div>
                    </div>
                    @else
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
                        <div class="col-sm-9">
                            {{ Form::text($key, $category->{$key}, ['class' => 'form-control']) }}
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

<div class="portlet box blue">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-navicon"></i> Sub Category List
		</div>
		<div class="actions">
		    <a href="{{ URL::route('admin.sub.category.create', $category->id) }}" class="btn btn-default btn-sm">
		        <span class="glyphicon glyphicon-plus"></span>&nbsp;Create
		    </a>								    
	    </div>
	</div>
    <div class="portlet-body ">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th class="text-center">Icon</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th class="th-action">Edit</th>
                    <th class="th-action">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($category->subCategories as $key => $value)
                    <tr>
                        <td>{{ ((Input::has('page') ? Input::get('page') : 1) - 1 ) * PAGINATION_SIZE + ($key + 1) }}</td>
                        <td>{{ $value->name }}</td>
                        <td class="text-center"><i class="{{ $value->icon }}"></i></td>
                        <td>{{ $value->description }}</td>
                        <td>{{ $value->created_at }}</td>
                        <td>
                            <a href="{{ URL::route('admin.sub.category.edit', $value->id) }}" class="btn btn-sm btn-info">
                                <span class="glyphicon glyphicon-edit"></span> Edit
                            </a>
                        </td>
                        <td>
                            <a href="{{ URL::route('admin.sub.category.delete', $value->id) }}" class="btn btn-sm btn-danger" id="js-a-delete">
                                <span class="glyphicon glyphicon-trash"></span> Delete
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
