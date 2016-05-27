@extends('user.layout')

@section('main')
<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script>
    FB.init({ appId:'{{ FACEBOOK_APP_ID }}',cookie:true, status:true, xfbml:true });
</script>
	
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3 margin-top-xl margin-bottom-xl">
            <form role="form" method="post" action="{{ URL::route('user.doLogin') }}">
                <input type="hidden" name="redirect" value="{{ $redirect }}" />
                <div class="form-group">
                    <div class="row text-center">
                        <p class="form-control-static">
                            </p><h2 class="text-center text-uppercase">Welcome back!</h2>
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
                    <label class="control-label">Email</label>
                    <input type="text" class="form-control input-lg" placeholder="Email Address" name="email">
                </div>
                <div class="margin-top-lg"></div>
                <div class="form-group">
                    <label class="control-label">Password</label>
                    <input type="password" class="form-control input-lg" placeholder="Password" name="password">
                </div>
                
                <div class="margin-top-lg"></div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-5 padding-top-xs">
                            <a href="{{ URL::route('user.signup') }}">
                                <b>Register</b>
                            </a>
                            |
                            <a href="{{ URL::route('user.forgotPassword') }}">
                                <b>Forgot Password</b>
                            </a>
                        </div>
                        
                        <div class="col-sm-7 text-right">                            
                            <label class="checkbox-inline">
							    <input type="checkbox" id="js-chk-is-remember" name="is_remember" value="1"> Remember Me&nbsp;&nbsp;
							</label>
                            <button type="submit" class="btn btn-primary btn-lg">
                                Sign In <span class="glyphicon glyphicon-ok-circle"></span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="margin-top-lg"></div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <button class="btn blue btn-block" id="js-btn-login-facebook" type="button">
                                <i class="fa fa-facebook-square"></i>&nbsp;Login with Facebook
                            </button>
                        </div>
                    </div>
                </div>                
            </form>    
        </div>
    </div>
</div>
@stop

@section('custom-scripts')
<script>
$(document).ready(function() {
    $("button#js-btn-login-facebook").click(function() {
        FB.login(function(response) {
            if (response.authResponse) {
               	var accessToken = FB.getAuthResponse()['accessToken'];
               	var is_remember = $("input#js-chk-is-remember").attr("checked") ? 1 : 0;
               	FB.api('/me', function(response) {
              	   	$.ajax({
              			type: "POST",
              			url: "{{ URL::route('async.user.loginFacebook') }}",
              			data : { response : response, accessToken : accessToken, is_remember : is_remember },
              			success: function(data) {
              			    if (data.result == 'success'){
                  			    if ($("input[name='redirect']").val() == "") {
                  				    window.location.href = "{{ URL::route('user.home') }}";
                  			    } else {
                  			      window.location.href = $("input[name='redirect']").val();
                  			    }
              		        }
              		    }
              	    });
                });
            } else {
                
            }
        }, {scope: 'offline_access, publish_actions, email, publish_stream'});         
    });
});
</script>
@stop

@stop
