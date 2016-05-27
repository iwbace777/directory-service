@extends('user.layout')

@section('main')
<div class="container">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 margin-top-normal margin-bottom-normal">
            <div class="portlet box red margin-top-lg">
                <div class="portlet-title">
                    <div class="caption">
                        My Offers
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-hover">
                        <tbody>
                            @foreach ($userOffers as $key => $value)
                            <tr>
                                <td style="line-height: 40px;" class="text-center"><span class="badge badge-danger">{{ $key + 1 }}</span></td>
                                <td>
                                    <div style="margin-left: 10px;">
                                        <div><b>{{ $value->offer->name }}</b></div>
                                        <div class="font-size-sm"><i>{{ $value->offer->description }}</i></div>                                        
                                    </div>
                                </td>
                                <td style="line-height: 40px;" class="text-center">
                                    <h4>{{ $value->code }}</h4>
                                </td>
                                <td style="line-height: 40px;" class="text-center">
                                    <h4 class="color-default">{{ !$value->offer->is_review ? $value->offer->price.'&euro;' : 'By Activity' }}</h4>
                                </td>
                                <td>
                                    <div><a href="{{ URL::route('store.detail', $value->offer->company->slug) }}">{{ $value->offer->company->name }}</a></div>
                                    <div><i>Get At : {{ $value->created_at }}</i></div>
                                    <div><i>Expire At : {{ $value->offer->expire_at }}</i></div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>    
                </div>
            </div>                         
        </div>
    </div>
</div>
@stop

@stop
