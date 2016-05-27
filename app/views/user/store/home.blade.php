@extends('user.layout')

@section('custom-styles')
{{ HTML::style('/assets/metronic/global/plugins/carousel-owl-carousel/owl-carousel/owl.carousel.css') }}
<style>
    #owl-demo .item{
      margin: 7px;
    }
</style>
@stop

@section('main')
    <div class="container">

        <div class="row">
            <div class="col-sm-12 margin-bottom-sm">
                <div class="row padding-top-normal">
                    <div class="col-sm-12 text-center"><h2 class="color-default">Featured Company</h2></div>        
                </div>            
                <div class="row">
                    @foreach ($stores as $key => $store)
                    <div class="col-sm-3 margin-top-normal">
                        <?php 
                            $tooltip = "<h4>".$store->name."</h4>";
                            if ($store->keyword) {
                                $tooltip.= "<p><b>Keywords : </b>";
                                $keywords = explode(",", $store->keyword);
                                foreach ($keywords as $subKey => $value) {
                                    if ($subKey != 0)
                                        $tooltip.=", ";
                                    $tooltip.= $value;
                                }
                                $tooltip.= "</p>";
                            }
                            
                            if ($store->email) {
                                $tooltip.= "<p><b>Email : </b>".$store->email."</p>";
                            }
                            if ($store->phone) {
                                $tooltip.= "<p><b>Phone : </b>".$store->phone."</p>";
                            }
                            
                            $tooltip.= "<p><b>Opening : </b>".$store->company->opening->{strtolower(date('D'))."_start"}." - ".$store->company->opening->{strtolower(date('D'))."_end"}."</p>";
                            
                            if ($store->company->vat_id) {
                                $tooltip.= "<p><b>VAT ID : </b>".$store->company->vat_id."</p>";
                            }
                            
                            if ($store->zip_code) {
                                $tooltip.= "<p><b>Zip Code : </b>".$store->zip_code."</p>";
                            }
                            
                            if ($store->address) {
                                $tooltip.= "<p><b>Address : </b>".$store->address."</p>";
                            }
                            
                            if ($store->description) {
                                $tooltip.= "<p><b>Description : </b>".substr($store->description, 0, 300)."</p>";
                            }
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
                </div>
            </div>
            
        </div>
        <hr/>
        <div class="row padding-top-normal padding-bottom-normal">
            <div class="col-sm-12 text-center"><h2 class="color-default">Recent Reviews</h2></div>        
        </div>
        <div class="row padding-bottom-normal">
            <div class="col-sm-12">
                <div id="owl-demo" class="owl-carousel owl-theme">
                    @foreach ($feedbacks as $feedback)
                    <div class="item">
                        <div class="row">
                            <div class="col-sm-3">
                                <img class="pull-left img-rounded" src="{{ HTTP_USER_PATH.$feedback->user->photo }}" style="width: 100%; height: 100%;">
                            </div>
                            <div class="col-sm-9">
                                <div>
                                    <b>{{ $feedback->user->name }}</b> reviewed
                                    <a class="font-helvetica" href="{{ URL::route('store.detail', $feedback->store->slug) }}"> 
                                        {{ $feedback->store->name }}
                                    </a>
                                </div>
                                
                                <div class="pull-left">
                                    <input id="js-number-rating" type="number" class="rating rating-xxs" min=0 max=5 step=1 data-show-clear=false data-show-caption=false data-size='xxs' value="{{ $feedback->avgReview }}" readonly=true>
                                </div>
                                <div class="pull-left">&nbsp;&nbsp;( {{ date(DATE_FORMAT, strtotime($feedback->created_at)) }} )</div>
                                <div class="clearfix"></div>
                                <p class="padding-top-xs font-helvetica">
                                    <i class="color-gray-normal">{{ $feedback->description }}</i>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>    
            </div>        
        </div>         
    </div>
@stop

@section('custom-scripts')
    {{ HTML::script('/assets/metronic/global/plugins/carousel-owl-carousel/owl-carousel/owl.carousel.min.js') }}
    {{ HTML::script('/assets/js/bootstrap-tooltip.js') }}
    @include('js.user.home.index')
    <script>
    $(document).ready(function() {
        $("#owl-demo").owlCarousel({            
            autoPlay: 3000, //Set AutoPlay to 3 seconds       
            items : 4,
            itemsDesktop : [1199,3],
            itemsDesktopSmall : [979,3]
        });
    });    
    </script>    
@stop

@stop
