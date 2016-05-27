@extends('company.layout')

@section('custom-styles')
    {{ HTML::style('/assets/metronic/global/plugins/typeahead/typeahead.css') }}
@stop

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Consumer</span>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<span>Register</span>
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
			<i class="fa fa-pencil-square-o"></i> Register Consumer
		</div>
	</div>
	<div class="portlet-body form">
        <form class="form-horizontal form-bordered form-row-stripped" role="form" method="post" action="{{ URL::route('company.user.doRegister') }}">
            <div class="form-body">            
                @foreach ([
                    'email' => 'Email',
                    'name' => 'Name',
                    'phone' => 'Phone',                    
                ] as $key => $value)
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="{{ $key }}">
                    </div>
                </div>
                @endforeach
            </div>
            <div class="form-actions fluid">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-success">
                        <span class="glyphicon glyphicon-ok-circle"></span> Save
                    </button>
                    <a href="{{ URL::route('company.loyalty') }}" class="btn btn-primary">
                        <span class="glyphicon glyphicon-share-alt"></span> Back
                    </a>
                </div>
            </div>            
        </form>
    </div>
</div>
@stop

@section('custom-scripts')
{{ HTML::script('/assets/metronic/global/plugins/typeahead/handlebars.min.js') }}
{{ HTML::script('/assets/metronic/global/plugins/typeahead/typeahead.bundle.min.js') }}
<script>
var custom = new Bloodhound({
    datumTokenizer: function(d) { return d.tokens; },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: "{{ URL::route('async.company.email.autoComplete') }}" + "?query=%QUERY"
});

custom.initialize();
    
$("input[name='email']").typeahead(null, {
    name: 'da-js-text-email',
    displayKey: 'email',
    hint: true,
    source: custom.ttAdapter()
});
</script>
@stop

@stop
