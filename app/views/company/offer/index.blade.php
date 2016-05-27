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
			<i class="fa fa-navicon"></i> Purchase Offer List
		</div>
		<div class="actions">
		    <a href="{{ URL::route('company.offer.purchase.create') }}" class="btn btn-default btn-sm">
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
                    <th>Photo</th>
                    <th>Description</th>
                    <th>Expire At</th>
                    <th>Revenue / Received</th>
                    <th>Created At</th>
                    <th class="th-action">Solds</th>
                    <th class="th-action">Edit</th>
                    <th class="th-action">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseOffers as $key => $value)
                    <tr>
                        <td>{{ ((Input::has('page') ? Input::get('page') : 1) - 1 ) * PAGINATION_SIZE + ($key + 1) }}</td>
                        <td>{{ $value->name }}</td>
                        <td><img src="{{ HTTP_OFFER_PATH.$value->photo }}" style="height: 40px;"></td>
                        <td>{{ $value->description }}</td>
                        <td>{{ $value->expire_at }}</td>
                        <td>
                            {{ count($value->userOffers) * $value->price }} /
                            {{ $value->received ? $value->received : '---' }}
                        </td>
                        <td>{{ $value->created_at }}</td>
                        <td>
                            <a href="{{ URL::route('company.offer.sold', $value->id) }}" class="btn btn-sm btn-success">
                                <i class="fa fa-list"></i> Sold
                            </a>
                        </td>
                        <td>
                            <a href="{{ URL::route('company.offer.purchase.edit', $value->id) }}" class="btn btn-sm btn-info">
                                <span class="glyphicon glyphicon-edit"></span> Edit
                            </a>
                        </td>
                        <td>
                            <a href="{{ URL::route('company.offer.delete', $value->id) }}" class="btn btn-sm btn-danger" id="js-a-delete">
                                <span class="glyphicon glyphicon-trash"></span> Delete
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pull-right">{{ $purchaseOffers->links() }}</div>
        <div class="clearfix"></div>
    </div>
</div>

<div class="portlet box red">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-navicon"></i> Activity Offer List
		</div>
		<div class="actions">
		    <a href="{{ URL::route('company.offer.activity.create') }}" class="btn btn-default btn-sm">
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
                    <th>Photo</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th class="th-action">Solds</th>
                    <th class="th-action">Edit</th>
                    <th class="th-action">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activityOffers as $key => $value)
                    <tr>
                        <td>{{ ((Input::has('page') ? Input::get('page') : 1) - 1 ) * PAGINATION_SIZE + ($key + 1) }}</td>
                        <td>{{ $value->name }}</td>
                        <td><img src="{{ HTTP_OFFER_PATH.$value->photo }}" style="height: 40px;"></td>
                        <td>{{ $value->description }}</td>
                        <td>{{ $value->created_at }}</td>
                        <td>
                            <a href="{{ URL::route('company.offer.sold', $value->id) }}" class="btn btn-sm btn-success">
                                <i class="fa fa-list"></i> Sold
                            </a>
                        </td>
                        <td>
                            <a href="{{ URL::route('company.offer.activity.edit', $value->id) }}" class="btn btn-sm btn-info">
                                <span class="glyphicon glyphicon-edit"></span> Edit
                            </a>
                        </td>
                        <td>
                            <a href="{{ URL::route('company.offer.delete', $value->id) }}" class="btn btn-sm btn-danger" id="js-a-delete">
                                <span class="glyphicon glyphicon-trash"></span> Delete
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pull-right">{{ $activityOffers->links() }}</div>
        <div class="clearfix"></div>
    </div>
</div>

@stop

@stop
