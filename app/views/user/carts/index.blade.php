@extends('user.layout')

@section('main')
<div class="container">
    <div class="row margin-top-xs margin-bottom-sm">
        <div class="col-sm-7">
            @if (count($carts) > 0)
                <div class="pull-right">{{ $carts->links() }}</div>
                <div class="clearfix"></div>
                @foreach ($carts as $key => $cart)
                <?php $store = $cart->store; ?>
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
                <div class="pull-right">{{ $carts->links() }}</div>
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
</div>
@stop

@section('custom-scripts')
{{ HTML::script('/assets/js/bootstrap-tooltip.js') }}
<script>
$(document).ready(function() {
    $("button#js-btn-remove-cart").click(function() {
        var obj = $(this);
        $.ajax({
            url: "{{ URL::route('async.user.cart.remove') }}",
            dataType : "json",
            type : "POST",
            data : { cart_id : $(this).attr('data-id') },
            success : function(data){
                obj.parents("div.margin-top-xs").eq(0).next().remove();
                obj.parents("div.margin-top-xs").eq(0).remove();
                bootbox.alert(data.msg);
                window.setTimeout(function(){
                    bootbox.hideAll();
                }, 2000);
            }
        });        
    });
});
function initialize() {

    var mapPosition = new google.maps.LatLng( {{ DEFAULT_LAT }}, {{ DEFAULT_LNG }});
    var mapOptions = { zoom: 12, center: mapPosition };
    
    var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

    var marker = [];
    var infowindow = [];
    @foreach ($carts as $key => $cart)
    <?php $value = $cart->store; ?>
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
</script>
@stop

@stop
