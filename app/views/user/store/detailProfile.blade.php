@extends('user.layout')

@section('custom-styles')
{{ HTML::style('/assets/metronic/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}
<style>
.faq-tabbable i {
    color: #000 !important;
}
</style>
@stop

@section('main')
<div class="container">
    <div class="row">
        <div class="col-sm-3 margin-top-sm">
            @include('user.store.info')
        </div>
        <div class="col-md-9 margin-top-normal">
            
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
            
            @include('user.store.topMenu')		
            <div class="page-content">
                <div class="tab-content">
                    <div class="tab-pane active" id="company-profile">
                        <div class="row">
                            <div class="col-sm-8">
                                <h2 class="color-default pull-left">{{ $store->name }}</h2>
                                <div class="pull-right">
                                    <input id="js-number-rating" type="number" class="rating" min=0 max=5 step=1 data-show-clear=false data-show-caption=false data-size='xs' value="{{ $store->getRatingScore() }}" readonly=true style="cursor: pointer;">                                
                                </div>
                                <div class="clearfix"></div>
                                <div class="blog-tags margin-bottom-20">
                                    <ul>
                                        @foreach ($store->subCategories as $key => $value)
                                        <li><a href="{{ URL::route('store.search').'?keyword='.$value->subCategory->name }}"><i class="fa fa-tags"></i> {{ $value->subCategory->name }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                                        
                                <h4 class="margin-top-sm">About Us</h4>
                                <p>
                                    {{ $store->description }}
                                </p>
                                <hr/>
                                <p>
                                    <img class="img-responsive img-rounded" alt="" src="{{ HTTP_STORE_PATH.$store->photo }}">
                                </p>                               
                            </div>
                            <div class="col-sm-4">
                                <?php 
                                    $tooltip = "<p><b>Opening Times : </b></p>";
                                    foreach ([
                                                    'mon' => 'Monday',
                                                    'tue' => 'Tuesday',
                                                    'wed' => 'Wednesday',
                                                    'thu' => 'Thurday',
                                                    'fri' => 'Friday',
                                                    'sat' => 'Saturday',
                                                    'sun' => 'Sunday',
                                                    ] as $key => $value) {
                                        $tooltip .= "<p>";
                                        $tooltip .= "<b>".$value." : </b>";
                                        $tooltip .= $store->company->opening->{$key."_start"}."-".$store->company->opening->{$key."_end"};
                                        $tooltip .= "</p>";
                                    }
                                ?>
                                                                
                                <p>
                                    <b>Today : </b>{{ $store->company->opening->{strtolower(date('D'))."_start"}." - ".$store->company->opening->{strtolower(date('D'))."_end"} }}
                                    <span data-toggle="tooltip" data-placement="bottom" data-html="true" data-title="{{ $tooltip }}" style="padding-left: 30px;">
                                        <i class="fa fa-clock-o"></i>
                                    </span>
                                </p>
                                <p><b>Phone : </b>{{ $store->phone }}</p>
                                <div id="map-canvas" style="height: 200px; width: 100%;" class="margin-top-xs"></div>
                                <p class="text-center margin-top-xs">
                                    <i class="fa fa-map-marker"></i>
                                    <b>{{ $store->address }}</b>
                                </p>
                                
                                <div class="company-service margin-bottom-normal">
                                    <h4>Keywords</h4>
                                    <?php $keywords = explode(",", $store->keyword); ?>
                                    @foreach ($keywords as $key => $value)
                                    <button class="btn-default btn btn-xs margin-bottom-xs">{{ $value }}</button>
                                    @endforeach
                                </div>                                 
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-sm-5 col-sm-offset-1">
                                <button class="btn btn-primary btn-lg btn-block" id="js-btn-join" data-id="{{ $store->company->id }}"><i class="fa fa-user"></i> Join As Customer</button>
                            </div>
                            <div class="col-sm-5">
                                <button class="btn btn-primary btn-lg btn-block" id="js-btn-send-message"><i class="fa fa-pencil-square-o"></i> Send A Message</button>
                            </div>
                        </div>
                        <div class="row margin-top-normal" id="js-div-message" style="display: none;">
                            <div class="col-sm-10 col-sm-offset-1">
                                <h4 class="color-default">Send Us A Message</h4>
                                <form action="{{ URL::route('user.sendMessage') }}" role="form" method="post">
                                    <input type="hidden" name="store_id" value="{{ $store->id }}"/>
                                    <div class="form-group">
                                        <label for="contacts-name">Name</label>
                                        <input type="text" class="form-control" name="name">
                                    </div>
                                    <div class="form-group">
                                        <label for="contacts-email">Email</label>
                                        <input type="email" class="form-control" name="email">
                                    </div>
                                    <div class="form-group">
                                        <label for="contacts-message">Message</label>
                                        <textarea class="form-control" rows="5" name="description"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary pull-right"><i class="icon-ok"></i> Send</button>
                                </form>                            
                            </div>
                        </div>
                        @if (count($store->company->availableOffers) != 0)
                            <h2 class="margin-top-normal">Offers</h2>
                            <div class="row">
                            @foreach ($store->company->availableOffers as $key => $offer)
                                <div class="col-sm-4 text-center">
                                    <div class="service-item">
                                        <h4>{{ $offer->name }}</h4>
                                        <div style="height: 40px; overflow: hidden;"><i>{{ $offer->description }}</i></div>
                                        <h3 class="color-default">{{ $offer->price.'&euro;'}}</h3>
                                        <div class="row">
                                            <button class="btn btn-primary col-sm-8 col-sm-offset-2" id="js-btn-purchase" data-price="{{ $offer->price }}" data-id="{{ $offer->id }}" data-user="{{ Session::has('user_id') ? Session::get('user_id') : '' }}">
                                                <i class="fa fa-heart"></i> Purchase
                                            </button>
                                        </div>
                                        <p class="padding-top-xs"><b><i>Expires At : {{ $offer->expire_at }}</i></b></p>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-sm-12"><hr/></div>
                        </div>
                        
                        @if (count($store->company->loyalties) != 0)
                        <h2 class="margin-top-lg">Loyalties</h2>
                            <div class="row">
                            @foreach ($store->company->loyalties as $key => $loyalty)
                                <div class="col-sm-4 text-center">
                                    <div class="service-item">
                                        <h4>{{ $loyalty->name }}</h4>
                                        <div style="height: 40px; overflow: hidden;"><i>{{ $loyalty->description }}</i></div>
                                        <div class="row">
                                            <button class="btn btn-primary col-sm-8 col-sm-offset-2">
                                                Requires {{ $loyalty->count_stamp." Stamps" }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        @endif                        
                        
                        @if ($is_valid || count($store->feedbacks) > 0)
                        <h2 class="margin-top-normal">Review &amp; Feedbacks</h2>
                        @endif
                        
                        @if ($is_valid)
                        <form method="post" action="{{ URL::route('user.giveFeedback') }}">
                            <input type="hidden" name="store_id" value="{{ $store->id }}"/>
                            <div class="row margin-top-xs">
                                <div class="col-sm-6">
                                    @foreach ($store->company->visibleRatingTypes as $key => $value)
                                    @if ($value->is_score)
                                    <div class="row">
                                        <div class="col-sm-6 text-right"><p style="padding-top: 10px;"><b>{{ $value->name }} : </b></p></div>
                                        <div class="col-sm-6">
                                            <input id="js-number-rating" type="number" name="rating[]" class="rating" min=0 max=5 step=1 data-show-caption=false data-show-clear=false data-size='xs' value=3>
                                            <input type="hidden" name="type_id[]" value="{{ $value->id }}">
                                        </div>
                                    </div>
                                    @else
                                    <div class="row">
                                        <div class="form-group form-radio">
                                            <div class="col-sm-6 text-right"><p style="padding-top: 10px;"><b>{{ $value->name }}</b></p></div>
    										<div class="col-md-6 padding-top-xs">
    										    <input type="hidden" name="type_id[]" value="{{ $value->id }}">
    										    <input type="hidden" name="rating[]" id="js-hidden-rating">
    											<div class="btn-group" data-toggle="buttons">
													<label class="btn btn-default btn-sm" data-val=1 id="js-label-answer"><input type="radio" class="toggle"> Yes </label>
													<label class="btn btn-default btn-sm" data-val=0 id="js-label-answer"><input type="radio" class="toggle"> No </label>
												</div>
    										</div>
									    </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                                <div class="col-sm-6">
                                    <textarea class="form-control" name="description" rows="9" placeholder="Write feedback here..."></textarea>
                                    <button class="btn btn-primary pull-right margin-top-sm" onclick="return validate()">Give Feedback</button>
                                </div>
                            </div>
                        </form>
                        @endif
                        
                        @if (count($store->feedbacks) > 0)
                        <table class="table table-hover margin-top-lg">
                            <thead>
                                <tr>
                                    <th style="width: 120px;"></th>
                                    @foreach ($store->company->visibleRatingTypes as $ratingType)
                                    <th class="text-center">{{ $ratingType->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($store->feedbacks as $feedback)
                            <tr>
                                <td rowspan="2">
                                    By {{ $feedback->user->name }}
                                    <p class="margin-top-xs">
                                        <i class="fa fa-clock-o"></i>&nbsp;{{ date(DATE_FORMAT, strtotime($feedback->created_at)) }}
                                    </p>
                                </td>
                                @foreach ($store->company->visibleRatingTypes as $ratingType)
                                <td class="text-center">
                                    @if ($ratingType->is_score)
                                    <input id="js-number-rating" type="number" class="rating" min=0 max=5 step=1 data-show-clear=false data-show-caption=false data-size='xxs' value="{{ $feedback->getTypeScore($ratingType->id) }}" readonly=true>
                                    @else
                                        {{ $feedback->getTypeScore($ratingType->id) == -1 ? '--' :  ($feedback->getTypeScore($ratingType->id) == 0 ? 'No' : 'Yes') }}
                                    @endif                                
                                </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td colspan="{{ count($store->company->visibleRatingTypes) }}" style="border-top: none; padding-top: 0px; padding-bottom: 5px;"><i>{{ $feedback->description }}</i></td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>            
    </div>
</div>

<form id="js-frm-payment" method="post" action="{{ 'https://'.PAYPAL_SERVER.'/cgi-bin/webscr' }}" class="hide">
	<input type="hidden" name="business" value="{{ htmlspecialchars(PAYPAL_BUSINESS) }}">
	<input type="hidden" name="cmd" value="_xclick">
	<input type="hidden" name="item_name" value="{{ SITE_NAME }} Offer Purchase">
	<input type="hidden" name="amount">
	<input type="hidden" name="custom" >
	<input type="hidden" name="currency_code" value="EUR">
	<input type="hidden" name="notify_url" value="{{ URL::route('offer.purchase.ipn') }}">
	<input type="hidden" name="return" value="{{ URL::route('offer.purchase.success', $store->slug) }}">
	<input type="hidden" name="cancel_return" value="{{ URL::route('offer.purchase.failed', $store->slug) }}">
	<input type="hidden" name="no_shipping" value="1">
	<input type="hidden" name="email">
</form>

@stop

@section('custom-scripts')
{{ HTML::script('/assets/metronic/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}

<script>
$(document).ready(function() {
    $("label#js-label-answer").click(function() {
        $(this).parents("div.form-radio").find("input#js-hidden-rating").val($(this).attr('data-val'));
    });
});

function validate() {
    var objList = $("input#js-hidden-rating");
    for (var i = 0; i < objList.length; i++) {
        if (objList.eq(i).val() == '') {
            bootbox.alert("Please answer the question.");
            return false;
        }
    }
}

$(document).ready(function() {
    $("button#js-btn-add-cart").click(function() {
        $.ajax({
            url: "{{ URL::route('async.user.cart.add') }}",
            dataType : "json",
            type : "POST",
            data : { company_id : $(this).attr('data-id') },
            success : function(data){
                bootbox.alert(data.msg);
                window.setTimeout(function(){
                    bootbox.hideAll();
                }, 2000);                   
            }
        });        
    });

    $("button#js-btn-send-message").click(function() {
        if ($("div#js-div-message").css("display") == "none") {
            $("div#js-div-message").show();
        } else {
            $("div#js-div-message").hide();
        }
    });

    $("button#js-btn-purchase").click(function() {
        var userId = $(this).attr('data-user');
        var offerId = $(this).attr('data-id');
        var price = $(this).attr('data-price');
        if (userId == '') {
            window.location.href = "{{ URL::route('user.login').'?redirect='.urlencode(URL::route('store.detail', $store->slug)) }}";
        } else {
            var custom = '{"uid":' + userId + ',"oid":"' + offerId + '"}';
            $("input[name='custom']").val(custom);
            $("input[name='amount']").val(price);
            $("form#js-frm-payment").submit();
        }
    });

    $("button#js-btn-join").click(function() {
        $.ajax({
            url: "{{ URL::route('async.user.store.join') }}",
            dataType : "json",
            type : "POST",
            data : { company_id : $(this).attr('data-id') },
            success : function(data){
                if (data.result == "success") {
                    bootbox.alert(data.msg);
                    window.setTimeout(function(){
                        bootbox.hideAll();
                    }, 2000);
                } else {
                    bootbox.alert(data.msg);
                    window.setTimeout(function(){
                        window.location.href = "{{ URL::route('user.login').'?redirect='.urlencode(URL::route('store.detail', $store->slug)) }}";
                    }, 2000);                    
                }
            }
        }); 
    });
        
    var mapPosition = new google.maps.LatLng({{ $store->lat}}, {{ $store->lng }});
    var mapOptions = { zoom: 15, center: mapPosition };
    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
    var marker = new google.maps.Marker({position: new google.maps.LatLng({{ $store->lat }}, {{ $store->lng }}), map: map, title: '{{ $store->name }}'});
});
</script>
@stop

@stop
