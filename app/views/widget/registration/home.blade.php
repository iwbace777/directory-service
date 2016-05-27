@extends('widget.layout')

@section('header')
    @if (($company->widget) && ($company->widget->is_header))
    <div class="header-background-color">
        <div class="container">
            <div class="row padding-top-xs">
                <div class="col-sm-12 margin-bottom-xs">
                    <div class="col-sm-2">
                        <a href="{{ URL::route('widget.registration.home', $company->token) }}">
                            <img src="{{ ($company->widget) ? HTTP_LOGO_PATH.$company->widget->logo : HTTP_LOGO_PATH.DEFAULT_PHOTO }}" style="height: 50px;"/>
                        </a>
                    </div>
                    <div class="col-sm-10 text-center">
                        <h3 class="color-white padding-top-xs"><b>Registration Page</b></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@show

@section('main')
    <div class="margin-top-sm margin-bottom-sm container">
        <form method="post" action="{{ URL::route('widget.embed.doSignup', $company->token) }}">
            <div class="row">
                @if (isset($alert))
                    <div class="col-sm-12 margin-top-normal">
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
                @endif
                        
                <div class="col-sm-12 margin-top-sm">
                    <div class="form-group">
                        <label class="control-label">Name *</label>
                        <input type="text" class="form-control input-lg" placeholder="Name" name="name">
                    </div>
                </div>
                        
                <div class="col-sm-12 margin-top-sm">
                    <div class="form-group">
                        <label class="control-label">Email *</label>
                        <input type="text" class="form-control input-lg" placeholder="Email Address" name="email">
                    </div>
                </div>
                
                <div class="col-sm-12 margin-top-sm">
                    <div class="form-group">
                        <label class="control-label">Password *</label>
                        <input type="password" class="form-control input-lg" placeholder="Password" name="password">
                    </div>
                </div>
                
                <div class="col-sm-12 margin-top-sm">
                    <div class="form-group">
                        <label class="control-label">Password Confirmation *</label>
                        <input type="password" class="form-control input-lg" placeholder="Password Confirmation" name="password_confirmation">
                    </div>
                </div>
                
                <div class="col-sm-12 margin-top-sm">
                    <div class="form-group">
                        <label class="control-label">Phone</label>
                        <input type="text" class="form-control input-lg" placeholder="Phone No (optional)" name="phone">
                    </div>
                </div>           
                
                <div class="col-sm-12 margin-top-sm">
                    <div class="form-group">
                        <button class="btn btn-primary btn-block btn-lg"><i class="fa fa-pencil-square-o"></i> Register</button>
                    </div>
                </div>                        
            </div>
        </form>
    </div>
@stop

@stop
