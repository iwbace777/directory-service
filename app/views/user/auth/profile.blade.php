@extends('user.layout')

@section('custom-styles')
    {{ HTML::style('/assets/metronic/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}
@stop

@section('main')
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3 margin-top-normal margin-bottom-xl">
            <form role="form" method="post" action="{{ URL::route('user.updateProfile') }}" enctype="multipart/form-data">
                <div class="form-group">
                    <div class="row text-center">
                        <p class="form-control-static">
                            </p><h2 class="text-center text-uppercase">User Profile</h2>
                        <p></p>
                    </div>
                </div>
                
                @if (isset($alert))
                <div class="margin-top-lg"></div>
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
                
                @if ($errors->has())
                <div class="margin-top-lg"></div>
                <div class="alert alert-danger alert-dismissibl fade in">
                    <button type="button" class="close" data-dismiss="alert">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    @foreach ($errors->all() as $error)
                		<p>{{ $error }}</p>
                	@endforeach
                </div>
                @endif                                
                
                <div class="margin-top-lg"></div>
                <div class="form-group">
                    <label class="control-label">Email *</label>
                    <input type="email" class="form-control input-lg" placeholder="Email Address" name="email" value="{{ $user->email }}">
                </div>
                
                <div class="margin-top-lg"></div>
                <div class="form-group">
                    <label class="control-label">Password</label>
                    <input type="password" class="form-control input-lg" placeholder="Password" name="password">
                </div>
                
                <div class="margin-top-lg"></div>
                <div class="form-group">
                    <label class="control-label">Name *</label>
                    <input type="text" class="form-control input-lg" placeholder="Name" name="name" value="{{ $user->name }}">
                </div>
                
                <div class="margin-top-lg"></div>
                <div class="form-group">
                    <label class="control-label">Phone</label>
                    <input type="text" class="form-control input-lg" placeholder="Phone Number" name="phone" value="{{ $user->phone }}">
                </div>
                
                <div class="margin-top-lg"></div>
				<div class="form-group">
					<label class="control-label col-md-3">Photo</label>
					<div class="col-md-8">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
							<div class="fileinput-new thumbnail" style="width: 120px; height: 120px;">
								<img src="{{ HTTP_USER_PATH.$user->photo }} " alt=""/>
							</div>
							<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 120px; max-height: 120px;"></div>
							<div>
								<span class="btn default btn-file">
								    <span class="fileinput-new">Select image </span>
								    <span class="fileinput-exists">Change </span>
								    <input type="file" name="photo">
								</span>
								<a href="#" class="btn red fileinput-exists" data-dismiss="fileinput">Remove </a>
							</div>
						</div>
					</div>
				</div>                
                
                <div class="margin-top-lg"></div>
                <div class="form-group">
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary btn-lg">
                            Update <span class="glyphicon glyphicon-ok-circle"></span>
                        </button>
                    </div>
                </div>
            </form>    
        </div>
    </div>
</div>
@stop

@section('custom-scripts')
{{ HTML::script('/assets/metronic/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}
@stop

@stop
