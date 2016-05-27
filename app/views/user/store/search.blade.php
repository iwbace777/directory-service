@extends('user.layout')

@section('main')
<div class="container">

    <div class="row margin-top-xs">
        <div class="col-sm-7 text-right">
            <button class="btn {{ (Session::get('orderBy') == 1) ? 'btn-primary' : 'btn-default' }}  btn-circle-lg" value=1 id="js-btn-order-by">
                <i class="fa fa-tachometer"></i>&nbsp;Distance
            </button>
            &nbsp;&nbsp;&nbsp;
            <button class="btn {{ (Session::get('orderBy') == 2) ? 'btn-primary' : 'btn-default' }} btn-circle-lg" value=2 id="js-btn-order-by">
                <i class="fa fa-star"></i>&nbsp;Reviews
            </button>
            &nbsp;&nbsp;&nbsp;
            <button class="btn {{ (Session::get('orderBy') == 3) ? 'btn-primary' : 'btn-default' }} btn-circle-lg" value=3 id="js-btn-order-by">
                <i class="fa fa-eye"></i>&nbsp;Views
            </button>
        </div>
        
        <div class="col-sm-2 text-right col-sm-offset-3">
            <button class="btn {{ (Session::get('dt') == 'grid') ? 'btn-primary' : 'btn-default' }}" id="js-btn-display" data-display="grid"><i class="fa fa-th"></i></button>
            <button class="btn {{ (Session::get('dt') == 'list') ? 'btn-primary' : 'btn-default' }}" id="js-btn-display" data-display="list"><i class="fa fa-th-list"></i></button>
        </div>
    </div>
    
    @if (Session::get('dt') == 'grid')
    <div class="row margin-top-xs margin-bottom-sm">
        @if (count($stores) > 0)
            <div class="col-sm-12 pull-right">{{ $stores->appends(Request::input())->links() }}</div>
            <div class="clearfix"></div>    
            @foreach ($stores as $key => $store)
            <div class="col-sm-3 margin-top-normal">
                <?php 
                    $tooltip = "<h4>".$store->name."</h4>";
                    $tooltip.= "<p><b>Keywords : </b>";
                    $keywords = explode(",", $store->keyword);
                    foreach ($keywords as $subKey => $value) {
                        if ($subKey != 0)
                            $tooltip.=", ";
                        $tooltip.= $value;
                    }
                    $tooltip.= "</p>";
                    $tooltip.= "<p><b>Email : </b>".$store->email."</p>";
                    $tooltip.= "<p><b>Phone : </b>".$store->phone."</p>";
                    $tooltip.= "<p><b>Opening : </b>".$store->company->opening->{strtolower(date('D'))."_start"}." - ".$store->company->opening->{strtolower(date('D'))."_end"}."</p>";
                    $tooltip.= "<p><b>VAT ID : </b>".$store->company->vat_id."</p>";
                    $tooltip.= "<p><b>Zip Code : </b>".$store->zip_code."</p>";
                    $tooltip.= "<p><b>Address : </b>".$store->address."</p>";
                    $tooltip.= "<p><b>Description : </b>".substr($store->description, 0, 300)."</p>";
                ?>
                            
                <div class="thumb-store-item" data-toggle="tooltip" data-placement="{{ $key % 4 == 3 ? 'left' : 'right' }}" data-html="true" data-title="{{ $tooltip }}" data-href="{{ URL::route('store.detail', $store->slug) }}">
                    <div class="thumb-store-photo" style="background: url({{ HTTP_STORE_PATH.$store->photo }});"></div>
                    
                    <div class="thumb-store-offer">
                    @foreach ($store->company->availableOffers as $offer)
                        <div class="color-white store-offers">
                            <i>
                            {{ $offer->name." : " }}{{ $offer->price."&euro;" }}
                            </i>
                        </div>
                    @endforeach
                    </div>
                    
                    <div class="thumb-store-name">
                        <div class="store-highlight">
                            {{ $store->name }}
                        </div>                    
                        <div class="pull-left">                            
                            <input id="js-number-rating" type="number" class="rating rating-xxs" min=0 max=5 step=1 data-show-clear=false data-show-caption=false data-size='xxs' value="{{ $store->getRatingScore() }}" readonly=true>
                        </div>
                        <div class="store-highlight pull-left store-highlight-review">
                            ( {{ count($store->feedbacks) }} Reviews )
                        </div>
                        <div class="clearfix"></div>                    
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="note note-info">
			    <h4 class="block">There is no search result.</h4>
			</div>
        @endif
    </div>
    @else
    <div class="row margin-top-xs margin-bottom-sm">
        <div class="col-sm-7">
            @if (count($stores) > 0)
                <div class="pull-right">{{ $stores->appends(Request::input())->links() }}</div>
                <div class="clearfix"></div>
                @foreach ($stores as $key => $store)
                <div class="row padding-top-xs padding-bottom-xs border-bottom-gray company-item" data-id="{{ $key }}">
                    <div class="col-md-4 col-sm-4">
                        <a href="{{ URL::route('store.detail', $store->slug) }}">
                            <?php 
                                $tooltip = "<h4>".$store->name."</h4>";
                                $tooltip.= "<p><b>Keywords : </b>";
                                $keywords = explode(",", $store->keyword);
                                foreach ($keywords as $subKey => $value) {
                                    if ($subKey != 0)
                                        $tooltip.=", ";
                                    $tooltip.= $value;
                                }
                                $tooltip.= "</p>";
                                $tooltip.= "<p><b>Email : </b>".$store->email."</p>";
                                $tooltip.= "<p><b>Phone : </b>".$store->phone."</p>";
                                $tooltip.= "<p><b>Opening : </b>".$store->company->opening->{strtolower(date('D'))."_start"}." - ".$store->company->opening->{strtolower(date('D'))."_end"}."</p>";
                                $tooltip.= "<p><b>VAT ID : </b>".$store->company->vat_id."</p>";
                                $tooltip.= "<p><b>Zip Code : </b>".$store->zip_code."</p>";
                                $tooltip.= "<p><b>Address : </b>".$store->address."</p>";
                                $tooltip.= "<p><b>Description : </b>".substr($store->description, 0, 300)."</p>";
                            ?>
                            <div class="img-rounded" style="height: 100px; width: 100%; background: url({{ HTTP_STORE_PATH.$store->photo }}); background-size: cover;" data-toggle="tooltip" data-placement="right" data-html="true" data-title="{{ $tooltip }}"></div>                            
                        </a>
                    </div>
                    <div class="col-md-8 col-sm-8">
                        <h3 class="pull-left">
                            <a href="{{ URL::route('store.detail', $store->slug) }}" data-toggle="tooltip" data-placement="right" data-html="right" data-title="{{ $tooltip }}">{{ $store->name }}</a>
                        </h3>
                        <div class="pull-right" style="position: relative;">
                            <input id="js-number-rating" type="number" class="rating" min=0 max=5 step=1 data-show-clear=false data-show-caption=false data-size='xxs' value="{{ $store->getRatingScore() }}" readonly=true>
                                                        
                            @if (Session::has('user_id'))
                            <button class="btn btn-primary btn-xs margin-top-xs" style="position: absolute; right: 0px; top: 15px;" data-id="{{ $store->id }}" id="js-btn-add-cart" title="Add Cart">Add Cart</button>
                            @endif                            
                        </div>                        
                        <div class="clearfix"></div>
                        
                        <ul class="blog-info">
                            <li><i class="fa fa-phone"></i> <b>{{ $store->phone }}</b></li>
                            <li><i class="fa fa-clock-o"></i> <b>{{ $store->company->opening->{strtolower(date('D'))."_start"}." - ".$store->company->opening->{strtolower(date('D'))."_end"} }}</b></li>
                        </ul>
                        
                        <ul class="blog-info">
                            <li><span class="color-default"><b>Address : </b></span> {{ $store->address }}</li>
                            <li><span class="color-default"><b>Zip Code : </b></span> {{ $store->zip_code }}</li>
                            <li><span class="color-default"><b>City : </b></span> {{ isset($store->city_id) ? $store->city->name : '---' }}</li>
                        </ul>
                    </div>
                </div>
                @endforeach
                <div class="pull-right">{{ $stores->appends(Request::input())->links() }}</div>
                <div class="clearfix"></div>
            @else
                <div class="note note-info">
				    <h4 class="block">There is no search result.</h4>
				</div>
            @endif
        </div>
                
        <div class="col-sm-5">
            <div class="clearfix"></div>
            <div id="map-canvas" style="height: 500px; width: 100%; border: 2px solid #E6400C;" class="margin-top-xs margin-bottom-xs"></div>
            <a href="http://inquirymall.com" target="_blank" class="margin-top-xs"><img src="/assets/img/partner1.jpg" class="img-responsive"/></a>
            <a href="http://socialheadhunter.org" target="_blank" class="margin-top-xs"><img src="/assets/img/partner2.jpg" class="img-responsive"/></a>
        </div>
    </div>
    @endif
</div>
@stop

@section('custom-scripts')
{{ HTML::script('/assets/js/bootstrap-tooltip.js') }}
<script>
$(document).ready(function() {
    
    $("button#js-btn-order-by").click(function() {
        $("form#js-frm-search").find("input[name='orderBy']").val($(this).val());
        $("button#js-btn-search").click();        
    });
    
    $("button#js-btn-add-cart").click(function() {
        $.ajax({
            url: "{{ URL::route('async.user.cart.add') }}",
            dataType : "json",
            type : "POST",
            data : { store_id : $(this).attr('data-id') },
            success : function(data){
                bootbox.alert(data.msg);
                window.setTimeout(function(){
                    bootbox.hideAll();
                }, 2000);                   
            }
        });        
    });

    $("button#js-btn-display").click(function() {
        $("input[name='dt']").val($(this).attr('data-display'));
        $("button#js-btn-search").click();
    });

    $("div.company-item").find("*").mouseover(function(event) {
        event.preventDefault();
    });

    $("div.company-item").hover(function() {
        var ind = $(this).attr("data-id");
        var lat = marker[ind].getPosition().lat();
        var lng = marker[ind].getPosition().lng();
        map.setCenter(new google.maps.LatLng(lat, lng));
        infowindow[ind].open(map, marker[ind]);
        $(this).addClass('background-gray');
    },function() {
        for (var i  = 0; i < infowindow.length; i++) {
            infowindow[i].close();
        }
        $(this).removeClass('background-gray');
    });
});
@if (Session::get('dt') == 'list')
    var marker = [];
    var infowindow = [];
    var mapPosition = new google.maps.LatLng( {{ DEFAULT_LAT }}, {{ DEFAULT_LNG }});
    var mapOptions = { zoom: 12, center: mapPosition };
    var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);    
    
    function initialize() {
        @foreach ($stores as $key => $value)
        
        var contentString = '' 
                + '<div style="width: 220px;">'
                + '    <p><b>Name : </b>{{ $value->name }}</p>'
                + '    <p><b>Description : </b>{{ addslashes(json_encode(substr($value->description, 0, 150))) }}</p>'
                + '    <p><i class="fa fa-phone"></i> {{ $value->phone }}&nbsp;&nbsp;&nbsp;'
                + '       <i class="fa fa-clock-o"></i> {{ $value->company->opening->{strtolower(date("D"))."_start"}." - ".$value->company->opening->{strtolower(date("D"))."_end"} }}</p>'
                + '    <p><b>Address : </b> {{ $value->address }}</p>'
                + '    <p><b>Zip Code : </b> {{ $value->zip_code }}&nbsp;&nbsp;&nbsp;'
                + '       <b>City : </b> {{ $value->city->name }}</p>'
                + '</div>';
        infowindow[{{ $key }}] = new google.maps.InfoWindow({
            content: contentString
        });    
        
        marker[{{ $key }}] = new google.maps.Marker({position: new google.maps.LatLng({{ $value->lat }}, {{ $value->lng }}), map: map, title: '{{ $value->name }}' });
        
        google.maps.event.addListener(marker[{{ $key }}], 'mouseover', function() {
            infowindow[{{ $key }}].open(map, marker[{{ $key }}]);
        });    
        google.maps.event.addListener(marker[{{ $key }}], 'mouseout', function() {
            infowindow[{{ $key }}].close();
        });    
        @endforeach
    }
    google.maps.event.addDomListener(window, 'load', initialize);
@endif
</script>
@stop

@stop
