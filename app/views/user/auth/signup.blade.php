@extends('user.layout')

@section('main')
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3 margin-top-xl margin-bottom-xl">
            <form role="form" method="post" action="{{ URL::route('user.doSignup') }}">
                <div class="form-group">
                    <div class="row text-center">
                        <p class="form-control-static">
                            </p><h2 class="text-center text-uppercase">Please fill the form</h2>
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
                    <label class="control-label">Name *</label>
                    <input type="text" class="form-control input-lg" placeholder="Name" name="name">
                </div>
                
                <div class="margin-top-lg"></div>
                <div class="form-group">
                    <label class="control-label">Email Address*</label>
                    <input type="text" class="form-control input-lg" placeholder="Email Address" name="email">
                </div>
                
                <div class="margin-top-lg"></div>
                <div class="form-group">
                    <label class="control-label">Password</label>
                    <input type="password" class="form-control input-lg" placeholder="Password" name="password">
                </div>
                
                <div class="margin-top-lg"></div>
                <div class="form-group">
                    <label class="control-label">Password Confirmation</label>
                    <input type="password" class="form-control input-lg" placeholder="Password Confrimation" name="password_confirmation">
                </div>
                
                <div class="margin-top-lg"></div>
                <div class="form-group">
                    <label class="control-label">Phone</label>
                    <input type="text" class="form-control input-lg" placeholder="Phone (optional)" name="phone">
                </div>                
                
                <div class="margin-top-lg"></div>
                <div class="form-group">
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary btn-lg">
                            Sign Up <span class="glyphicon glyphicon-ok-circle"></span>
                        </button>
                    </div>
                </div>
            </form>    
        </div>
    </div>
</div>
@stop

@stop
