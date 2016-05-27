@extends('widget.layout')

@section('main')
    <div class="margin-top-sm margin-bottom-sm container">
        <form method="post" action="{{ URL::route('widget.embed.doSignup', $store->token) }}">
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
                        
                <div class="col-sm-12 margin-top-lg">
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
                        <button class="btn btn-primary btn-block btn-lg"><i class="fa fa-pencil-square-o"></i> Sign Up</button>
                    </div>
                </div>                        
            </div>
        </form>
    </div>
@stop

@stop
