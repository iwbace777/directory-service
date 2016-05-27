@extends('admin.layout')

@section('custom-styles')]
    {{ HTML::style('/assets/metronic/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}
@stop

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">Company Management</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Company</span>
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
			<i class="fa fa-pencil-square-o"></i> Create Company
		</div>
	</div>
	<div class="portlet-body form">
        <form class="form-horizontal form-bordered form-row-stripped" role="form" method="post" action="{{ URL::route('admin.company.store') }}" enctype="multipart/form-data">
            <div class="form-body">            
                @foreach ([
                    'name' => 'Name',
                    'email' => 'Email',
                    'password' => 'Password',
                    'phone' => 'Phone',
                    'vat_id' => 'VAT ID',
                    'keyword' => 'Keyword',
                    'photo' => 'Photo',
                    'category' => 'Category',                    
                ] as $key => $value)
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
                    <div class="col-sm-9">
                        @if ($key == 'photo')
                            <div class="fileinput fileinput-new" data-provides="fileinput">
								<div class="fileinput-new thumbnail" style="width: 120px; height: 120px;">
									<img src="{{ HTTP_COMPANY_PATH.DEFAULT_PHOTO }} " alt=""/>
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
                        @elseif ($key == 'category')
                            <div id="js-div-sub-category">
    				        @foreach ($categories as $category)
    				            <div class="col-md-4">
    				                <p><b>{{ $category->name }}</b></p>
    				                @foreach ($category->subCategories as $subCategory)
    				                <p>
    				                    <input type="checkbox" class="form-control" id="js-checkbox-sub-category" value="{{ $subCategory->id }}">&nbsp;{{ $subCategory->name }}
    			                    </p>
    				                @endforeach
    				            </div>
    				        @endforeach                   
    				        </div>     
                        @elseif ($key == 'password')
                        <input type="password" class="form-control" name="{{ $key }}">
                        @else
                        <input type="text" class="form-control" name="{{ $key }}">
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="form-actions fluid">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-success" onclick="return validate()">
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
<script>
function validate() {
    var objList = $("input#js-checkbox-sub-category:checked");
    for (var i = 0; i < objList.length; i++) {
        $("div#js-div-sub-category").append($("<input type='hidden' name='sub_category[]' value=" + objList.eq(i).val() + ">"));
    }
    return true;
}
</script>
@stop

@stop
