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
			<i class="fa fa-navicon"></i> Sold Offers
		</div>
	</div>
    <div class="portlet-body ">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Offer Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Use Y/N</th>
                    <th>Code</th>
                    <th>Expire At</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($soldOffers as $key => $value)
                <tr>
                    <td>{{ ((Input::has('page') ? Input::get('page') : 1) - 1 ) * PAGINATION_SIZE + ($key + 1) }}</td>
                    <td><a href="{{ URL::route('company.user.detail', $value->user_id) }}">{{ $value->user->name }}</a></td>
                    <td>{{ $value->offer->name }}</td>
                    <td>{{ $value->offer->price }}</td>
                    <td>{{ $value->offer->description }}</td>
                    <td>{{ $value->is_used ? 'Yes' : 'No' }}</td>
                    <td>{{ $value->code }}</td>
                    <td>{{ $value->offer->expire_at }}</td>
                    <td>{{ $value->created_at }}</td>
                </tr>
                @endforeach
            </tbody>            
        </table>
        <div class="pull-right">{{ $soldOffers->links() }}</div>
        <div class="clearfix"></div>        
    </div>
</div>
@stop

@stop
