@extends('company.layout')

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Offer</span>
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
			<i class="fa fa-pencil-square-o"></i> Edit Offer
		</div>
	</div>
	<div class="portlet-body form">
        <form class="form-horizontal form-bordered form-row-stripped" role="form" method="post" action="{{ URL::route('company.offer.store') }}" enctype="multipart/form-data">
            <div class="form-body">
                <input type="hidden" name="is_review" value="1"/>
                <input type="hidden" name="offer_id" value="{{ $offer->id }}">            
                @foreach ([
                    'name' => 'Name',
                    'description' => 'Description',
                    'photo' => 'Photo',
                    'created_at' => 'Created At',
                    'updated_at' => 'Updated At',
                ] as $key => $value)
                @if ($key == 'created_at' || $key == 'updated_at')
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">{{ $offer->{$key} }}</p>
                    </div>
                </div>                
                @elseif ($key == 'photo')
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
                    <div class="col-sm-9">
                        <input type="file" class="form-control" name="{{ $key }}">
                    </div>
                </div>                
                @else
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
                    <div class="col-sm-9">
                        {{ Form::text($key, $offer->{$key}, ['class' => 'form-control']) }}
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
                    <a href="{{ URL::route('company.offer') }}" class="btn btn-primary">
                        <span class="glyphicon glyphicon-share-alt"></span> Back
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@stop
