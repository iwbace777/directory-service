@if (count($store->similar()) == 0)
    <div class="col-sm-12">
    <h3 class="color-default">There is no similars</h3>
    </div>
@endif
    
@foreach ($store->similar() as $item)
    <div class="col-sm-12 margin-bottom-xs">
        <?php 
            $tooltip = "<h4>".$item->name."</h4>";
            $tooltip.= "<p><b>Keywords : </b>";
            $keywords = explode(",", $item->keyword);
            foreach ($keywords as $subKey => $value) {
                if ($subKey != 0)
                    $tooltip.=", ";
                $tooltip.= $value;
            }
            $tooltip.= "</p>";
            $tooltip.= "<p><b>Email : </b>".$item->email."</p>";
            $tooltip.= "<p><b>Phone : </b>".$item->phone."</p>";
            $tooltip.= "<p><b>Opening : </b>".$item->company->opening->{strtolower(date('D'))."_start"}." - ".$item->company->opening->{strtolower(date('D'))."_end"}."</p>";
            $tooltip.= "<p><b>VAT ID : </b>".$item->company->vat_id."</p>";
            $tooltip.= "<p><b>Zip Code : </b>".$item->zip_code."</p>";
            $tooltip.= "<p><b>Address : </b>".$item->address."</p>";
            $tooltip.= "<p><b>Description : </b>".substr($item->description, 0, 300)."</p>";
        ?>
                    
        <div class="thumb-store-item" data-toggle="tooltip" data-placement="right" data-html="true" data-title="{{ $tooltip }}" data-href="{{ URL::route('store.detail', $item->slug) }}">
            <div class="thumb-store-photo" style="background: url({{ HTTP_STORE_PATH.$item->photo }});"></div>
            
            <div class="thumb-store-offer">
            @foreach ($item->company->availableOffers as $offer)
                <div class="color-white store-offers">
                    <i>
                    {{ $offer->name." : " }}{{ $offer->price."&euro;" }}
                    </i>
                </div>
            @endforeach
            </div>
            
            <div class="thumb-store-name">
                <div class="store-highlight">
                    {{ $item->name }}
                </div>                    
                <div class="pull-left">                            
                    <input id="js-number-rating" type="number" class="rating rating-xxs" min=0 max=5 step=1 data-show-clear=false data-show-caption=false data-size='xxs' value="{{ $item->getRatingScore() }}" readonly=true>
                </div>
                <div class="store-highlight pull-left store-highlight-review">
                    ( {{ count($item->feedbacks) }} Reviews )
                </div>
                <div class="clearfix"></div>                    
            </div>
        </div>
    </div>
@endforeach