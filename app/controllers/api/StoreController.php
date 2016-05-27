<?php namespace Api;

use Illuminate\Routing\Controllers\Controller;
use Input, Response, DB;
use Store as StoreModel;
use Feedback as FeedbackModel;

class StoreController extends \BaseController {
    
    public function featured() {
        $storeList = StoreModel::orderBy(DB::raw('RAND()'))->take(12)->get();
        $stores = [];
        foreach ($storeList as $key => $value) {
            $store['id'] = $value->id;
            $store['name'] = $value->name;
            $store['photo'] = HTTP_STORE_PATH.$value->photo;
            $store['address'] = $value->address;
            $store['zip_code'] = $value->zip_code;
            $store['city_name'] = $value->city_id ? $value->city->name : '---';
            $store['phone'] = $value->phone;
            $store['opening_time'] = $value->company->opening->{strtolower(date('D'))."_start"}." - ".$value->company->opening->{strtolower(date('D'))."_end"};
            $store['rating'] = $value->getRatingScore();
            $stores[] = $store;
        }
        return Response::json(['result' => 'success', 'msg' => '', 'stores' => $stores, ], 200);                
    }
    
    public function search() {
        $keyword = Input::has('keyword') ? Input::get('keyword') : '';
        $location = Input::has('location') ? Input::get('location') : '';
        $lat = Input::has('lat') ? Input::get('lat') : '';
        $lng = Input::has('lng') ? Input::get('lng') : '';
        
        if ($lat == '' && $lng == '') {
            $ipAddr = $_SERVER['REMOTE_ADDR'];
            $geoPlugin = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ipAddr));
        
            if (is_numeric($geoPlugin['geoplugin_latitude']) && is_numeric($geoPlugin['geoplugin_longitude'])) {
                $lat = $geoPlugin['geoplugin_latitude'];
                $lng = $geoPlugin['geoplugin_longitude'];
            }
        }        
        
        $result = StoreModel::search($keyword, $location);
        
        $tblStore =with(new StoreModel)->getTable();
        $storeList = $result->groupBy($tblStore.'.id')->orderBy('distance', 'ASC')->get();
    
        $stores = [];
        foreach ($storeList as $key => $value) {
            $store['id'] = $value->id;
            $store['name'] = $value->name;
            $store['phone'] = $value->phone;
            $store['address'] = $value->address;
            $store['zip_code'] = $value->zip_code;
            $store['city_name'] = $value->city_id ? $value->city->name : '---';
            $store['photo'] = HTTP_STORE_PATH.$value->photo;
            $store['opening_time'] = $value->company->opening->{strtolower(date('D'))."_start"}." - ".$value->company->opening->{strtolower(date('D'))."_end"};
            $store['rating'] = $value->getRatingScore();
            $store['description'] = $value->description;
            $store['lat'] = $value->lat;
            $store['lng'] = $value->lng;
            $stores[] = $store;
        }
        return Response::json(['result' => 'success', 'msg' => '', 'stores' => $stores, ], 200);
    }    
    
    public function detail() {
        if (Input::has('store_id')) {
            $storeId = Input::get('store_id');
            $store = StoreModel::find($storeId);
    
            if (Input::has('user_id') && FeedbackModel::where('user_id', Input::get('user_id'))
                            ->where('store_id', $storeId)->get()->count() > 0) {
                $is_rated = TRUE;
            } else {
                $is_rated = FALSE;
            }
    
            $opening = [];
            foreach ([
                    'mon' => 'Monday',
                    'tue' => 'Tuesday',
                    'wed' => 'Wednesday',
                    'thu' => 'Thurday',
                    'fri' => 'Friday',
                    'sat' => 'Saturday',
                    'sun' => 'Sunday',
                ] as $key => $value) {
                $opening[$key."_start"] = $store->company->opening->{$key."_start"};
                $opening[$key."_end"] = $store->company->opening->{$key."_end"};
            }
    
            $categories = [];
            foreach ($store->subCategories as $key => $value) {
                $category['name'] = $value->subCategory->name;
                $categories[] = $category;
            }
    
            $offers = [];
            foreach ($store->company->purchaseOffers as $key => $value) {
                $offer['id'] = $value->id;
                $offer['name'] = $value->name;
                $offer['photo'] = HTTP_OFFER_PATH.$value->photo;
                $offer['description'] = $value->description;
                $offer['price'] = $value->price;
                $offer['expire_at'] = $value->expire_at;
                $offers[] = $offer;
            }
    
            $loyalties = [];
            foreach ($store->company->loyalties as $key => $value) {
                $loyalty['id'] = $value->id;
                $loyalty['name'] = $value->name;
                $loyalty['photo'] = HTTP_LOYALTY_PATH.$value->photo;
                $loyalty['description'] = $value->description;
                $loyalty['count_stamp'] = $value->count_stamp;
                $loyalties[] = $loyalty;
            }
    
            $feedbacks = [];
            foreach ($store->feedbacks as $key => $value) {
                $feedback['user_name'] = $value->user->name;
                $feedback['user_photo'] = HTTP_USER_PATH.$value->user->photo;
                $feedback['description'] = $value->description;
                $feedback['score'] = round($value->ratings()->avg('answer'), 1);
                $feedbacks[] = $feedback;
            }
    
            return Response::json(['result' => 'success', 'msg' => '',
                            'is_rated' => $is_rated,
                            'name' => $store->name, 'city_name' => $store->city_id ? $store->city->name : '---',
                            'email' => $store->email, 'phone' => $store->phone,
                            'vat_id' => $store->company->vat_id, 'zip_code' => $store->zip_code,
                            'address' => $store->address, 'photo' => HTTP_STORE_PATH.$store->photo,
                            'description' => $store->description, 'keyword' => $store->keyword,
                            'score' => round($store->getRatingScore(), 2),
                            'opening_time' => $store->company->opening->{strtolower(date('D'))."_start"}." - ".$store->company->opening->{strtolower(date('D'))."_end"},
                            'lat' => $store->lat, 'lng' => $store->lng,
                            'opening' => $opening, 'categories' => $categories, 
                            'reviews' => $feedbacks, 'service' => ['offers' => $offers, 'loyalties' => $loyalties],
                            ], 200);
        } else {
            return Response::json(['result' => 'failed', 'msg' => 'Invalid Request'], 400);
        }
    }
    
    public function reviewTypes() {
        if (Input::has('store_id')) {
            $store = StoreModel::find(Input::get('store_id'));
            $types = [];
            foreach ($store->company->visibleRatingTypes as $key => $value) {
                $type['id'] = $value->id;
                $type['name'] = $value->name;
                $type['is_score'] = $value->is_score;
                $types[] = $type;
            }
            return Response::json(['result' => 'success', 'msg' => '', 'types' => $types, ], 200);
        } else {
            return Response::json(['result' => 'failed', 'msg' => 'Invalid Request'], 400);
        }
    }    
}