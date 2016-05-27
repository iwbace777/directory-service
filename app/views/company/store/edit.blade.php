@extends('company.layout')

@section('custom-styles')]
    <style>
      #map-canvas {
        height: 300px;
      }
    </style>
    {{ HTML::style('/assets/metronic/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}
@stop

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Store</span>
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

<div class="portlet box red">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-pencil-square-o"></i> Edit Store
		</div>
	</div>
	<div class="portlet-body form">
        <form class="form-horizontal form-bordered form-row-stripped" role="form" method="post" action="{{ URL::route('company.store.store') }}" enctype="multipart/form-data">
            <input type="hidden" name="company_id" value="{{ $company->id }}"/>
            <input type="hidden" name="store_id" value="{{ $store->id }}"/>
            <div class="form-body">
            
                <div class="form-group">
                    <label class="col-sm-3 control-label">Embed Widget</label>
                    <div class="col-sm-9">
                        <p>
                            <textarea class="form-control readonly" readonly><iframe width='800' height='600' src='{{ URL::route('widget.embed.home', $company->token) }}' frameborder='0'></iframe></textarea>
                        </p>
                        <p>
                            <input type="text" class="form-control readonly" readonly value="{{ URL::route('widget.embed.home', $store->token) }}">
                        </p>
                    </div>
                </div>
                            
                @foreach ([
                    'name' => 'Name',
                    'email' => 'Email',
                    'phone' => 'Phone',
                    'zip_code' => 'Zip Code',
                    'address' => 'Address',
                    'description' => 'Description',
                    'keyword' => 'Keyword',
                    'city_id' => 'City *',
                    'photo' => 'Photo',
                ] as $key => $value)
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
                    <div class="col-sm-9">
                        @if ($key == 'description' || $key == 'keyword')
                        <textarea class="form-control" name="{{ $key }}" rows="3">{{ $store->{$key} }}</textarea>
                        @elseif ($key == 'photo')
                        <div class="fileinput fileinput-new" data-provides="fileinput">
    						<div class="fileinput-new thumbnail" style="width: 120px; height: 120px;">
    							<img src="{{ HTTP_STORE_PATH.$store->{$key} }} " alt=""/>
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
                        @elseif ($key == 'city_id')
                            {{ Form::select($key
                             , array('' => 'Select City') + $cities->lists('name', 'id')
                             , $store->{$key}
                             , array('class' => 'form-control')) }}                        
                        @else
                        <input type="text" class="form-control" name="{{ $key }}" value="{{ $store->{$key} }}">
                        @endif
                    </div>
                </div>
                @endforeach
				<div class="form-group">
					<label class="control-label col-md-3">Location</label>
					<div class="col-md-9">
					    <div id="map-canvas"></div>
					</div>
					<input type="hidden" name="lat" value="{{ $store->lat }}"/>
					<input type="hidden" name="lng" value="{{ $store->lng}}"/>
				</div>
                <div class="form-group" id="js-div-sub-category">
                    <label class="col-sm-3 control-label">Category</label>
                    <div class="col-md-9">
				        <?php
				        $subCategories = [];
				        foreach ($store->subCategories as $item) {
                            $subCategories[] = $item->sub_category_id;
                        } 
				        ?>
				        
				        @foreach ($categories as $category)
				            <div class="col-md-4">
				                <p><b>{{ $category->name }}</b></p>
				                @foreach ($category->subCategories as $subCategory)
				                <p>
				                    <input type="checkbox" class="form-control" id="js-checkbox-sub-category" value="{{ $subCategory->id }}" 
				                        {{ in_array($subCategory->id, $subCategories) ? 'checked' : '' }}>&nbsp;{{ $subCategory->name }}
			                    </p>
				                @endforeach
				            </div>
				        @endforeach                    
                    </div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-sm-12 text-center">
                    <button type="submit" onclick="return validate()" class="btn btn-success">
                        <span class="glyphicon glyphicon-ok-circle"></span> Save
                    </button>
                    <a href="{{ URL::route('company.store') }}" class="btn btn-primary">
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
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>

<script>
var map;
var marker;
$(document).ready(function() {
    var myLatlng = new google.maps.LatLng($("input[name='lat']").val(), $("input[name='lng']").val());
    var mapOptions = {
      zoom: 10,
      center: myLatlng
    }
    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

    marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        title: 'Store Location'
    });

    google.maps.event.addListener(map, 'click', function(event) {
        marker.setPosition(event.latLng);
        $("input[name='lat']").val(event.latLng.lat());
        $("input[name='lng']").val(event.latLng.lng());
    });
});
function validate() {
    if ($("select[name='city_id']").val() == '') {
        bootbox.alert("Please select the city");
        return false;
    }
        
    var objList = $("input#js-checkbox-sub-category:checked");
    for (var i = 0; i < objList.length; i++) {
        $("div#js-div-sub-category").append($("<input type='hidden' name='sub_category[]' value=" + objList.eq(i).val() + ">"));
    }
    return true;
}
</script>

@stop

@stop
