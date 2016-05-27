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
                        <h3 class="color-white padding-top-xs"><b>Offers</b></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@show

@section('main')
    <div class="container margin-top-xs margin-bottom-xs">
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

        <div class="row">
        @foreach ($company->availableOffers as $key => $offer)
            <div class="col-sm-4">
                <div class="service-item">
                    <div class="pull-left">
                        <h5><b>{{ $offer->name }}</b></h5>
                    </div>
                    <div class="pull-right">
                        <span class="label label-danger">{{ $offer->expire_at }}</span>
                    </div>
                    <div class="clearfix"></div>
                    <div class="color-gray-normal text-center" style="height: 42px; overflow: hidden;"><i>{{ $offer->description }}</i></div>
                    <button class="btn btn-primary btn-block btn-sm" id="js-btn-purchase" data-price="{{ $offer->price }}" data-id="{{ $offer->id }}" data-user="{{ Session::has('user_id') ? Session::get('user_id') : '' }}">
                        <i class="fa fa-heart"></i> Purchase <b>{{ $offer->price.'&euro;'}}</b>
                    </button>
                </div>
            </div>
        @endforeach
        </div>
    </div>

@stop

@stop
