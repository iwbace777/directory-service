@extends('admin.layout')

@section('custom-styles')
    {{ HTML::style('/assets/metronic/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}
    {{ HTML::style('/assets/metronic/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css') }}
    {{ HTML::style('/assets/metronic/global/plugins/bootstrap-summernote/summernote.css') }}
@stop

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">Newsletter Management</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Newsletter</span>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
				    <span>Send</span>
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

<div class="portlet box blue">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-pencil-square-o"></i> Send Newsletter
		</div>
	</div>
	<div class="portlet-body">
        <form class="form-horizontal" role="form" method="post" action="{{ URL::route('admin.subscriber.doSend') }}">
            <div id="js-div-body"></div>
            <input type="hidden" name="body"/>
            
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8 text-center">
                    <button class="btn btn-success margin-top-sm" onclick="return validate();">
                        <span class="glyphicon glyphicon-ok-circle"></span> Submit
                    </button>
                </div>
            </div>
          </form>
    </div>
</div>   
@stop

@section('custom-scripts')
    {{ HTML::script('/assets/metronic/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js') }}
    {{ HTML::script('/assets/metronic/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js') }}
    {{ HTML::script('/assets/metronic/global/plugins/bootstrap-markdown/lib/markdown.js') }}
    {{ HTML::script('/assets/metronic/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js') }}
    {{ HTML::script('/assets/metronic/global/plugins/bootstrap-summernote/summernote.min.js') }}
    
<script>
$(document).ready(function() {
    $('#js-div-body').html("");
    $('#js-div-body').summernote({
        height: 300,
        tabsize: 4,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']]
		  ]            
    });
    
});
function validate() {
    $("input[name='body']").val($('#js-div-body').code());
    return true;
}
</script>
@stop

@stop
