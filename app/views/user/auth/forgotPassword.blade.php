@extends('user.layout')

@section('main')
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3 margin-top-xl margin-bottom-xl">
            <form role="form" method="post" action="{{ URL::route('user.sendResetPasswordEmail') }}">
                <div class="form-group">
                    <div class="row text-center">
                        <p class="form-control-static">
                            <h2 class="text-center">Did you forgot the password?</h2>
                        </p>
                        <p class="form-control-static">
                            <h4 class="text-center">Enter your email to reset</h4>
                        </p>
                    </div>
                </div>

                @if ($errors->has())
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
                
                <div class="margin-top-lg"></div>
                <div class="form-group">
                    <label class="control-label">Email</label>
                    <input type="text" class="form-control input-lg" placeholder="Email Address" name="email">
                </div>
                
                <div class="margin-top-lg"></div>
                <div class="form-group">
                    <div class="text-right">                            
                        <button type="submit" class="btn green btn-lg">
                            Submit <span class="glyphicon glyphicon-ok-circle"></span>
                        </button>
                    </div>
                </div>
                <div class="margin-top-lg"></div>
            </form>    
        </div>
    </div>
</div>
@stop

@stop
