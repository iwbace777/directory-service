@extends('company.layout')

@section('custom-styles')
    <style>
    .control-label {
        font-weight: bold;
    }
    .page-container {
        background: #FFF;
    }
    </style>
@stop

@section('main')
<div class="page-container">
    <div class="page-contect-wrapper">
	    <div class="page-content"> 
            <div class="col-sm-8 col-sm-offset-2 margin-top-normal padding-bottom-lg">             
                <form class="form-horizontal" role="form" method="post" action="{{ URL::route('company.auth.doSignup') }}">
                    <div class="form-group">
                        <div class="row text-center">
                            <p class="form-control-static">
                                <h2 class="color-default"><b>Create the Account as Company</b></h2>
                            </p>
                            <p class="form-control-static">
                                <h3>Please fill the forms</h3>
                            </p>
                        </div>
                    </div>
                    
                    @if (isset($alert))
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-1">                
                            <div class="alert alert-{{ $alert['type'] }} alert-dismissibl fade in">
                                <button type="button" class="close" data-dismiss="alert">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <p>
                                    {{ $alert['msg'] }}
                                </p>
                            </div>
                        </div>
                    </div>                
                    @endif                 

                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">Email *</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control input-lg" placeholder="Email Address" name="email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">Password *</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control input-lg" placeholder="Password" name="password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">Password Confirmation *</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control input-lg" placeholder="Password Confirmation" name="password_confirmation">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">Name *</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control input-lg" placeholder="Company Name" name="name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">Phone No</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control input-lg" placeholder="Phone No" name="phone">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">Keyword</label>
                        <div class="col-sm-8">
                            <textarea class="form-control input-lg" placeholder="Company Keyword" name="keyword" rows="5"></textarea>
                        </div>
                    </div>
                                        
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-1">
                            <hr/>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-9 text-right">
                            <button type="submit" class="btn red btn-lg btn-circle">
                                Sign Up <span class="glyphicon glyphicon-ok-circle"></span>
                            </button>
                        </div>
                    </div>
                </form>                
            </div>
        </div>
    </div>
</div>
@stop

@stop
