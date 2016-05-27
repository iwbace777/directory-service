@extends('company.layout')

    @section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Subscribe</span>
				</li>
			</ul>
		</div>
	</div>
    @stop
    
    @section('content')
        @if ($company->plan_id == '')
        @foreach ($plans as $key => $value)
        <div class="col-md-4">
            <div class="pricing hover-effect">
                <div class="pricing-head">
                    <h3>{{ $value->name }}</h3>
                    <h4><i>&euro;</i>{{ $value->price."&euro;" }}</h4>
                </div>
                <ul class="pricing-content list-unstyled text-center">
                  <li>
                    <h4>
                        <i class="fa fa-envelope"></i> {{ $value->count_email }} Emails
                    </h4>
                  </li>
                  <li>
                    <h4>
                        <i class="fa fa-envelope"></i> {{ $value->count_sms }} SMS Per Month
                    </h4>
                  </li>                  
                </ul>
                <div class="pricing-footer">
                    <button class="btn red btn-block btn-lg" id="js-btn-buy">
                        <form action="{{ URL::route('company.subscribe.create', $value->code) }}" method="POST">
                            <script
                                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                data-key="{{ STRIPE_PUBLISH_KEY }}"
                                data-amount="{{ $value->price * 100 }}"
                                data-name="{{ SITE_NAME }}"
                                data-description="{{ $value->name }} Subscription"
                                data-image="/assets/img/128x128.png">
                            </script>
                        </form>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
        @else
        <a href="{{ URL::route('company.subscribe.cancel') }}">Calcel Subscription</a>
        @endif
    @stop
@stop
