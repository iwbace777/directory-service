<?php namespace User;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, DB, Response;
use Category as CategoryModel, City as CityModel;
use Feedback as FeedbackModel, ReviewPhoto as ReviewPhotoModel;
use Store as StoreModel;
use Consumer as ConsumerModel;
use CommonFunction as CommonFunctionModel;

class StoreController extends \BaseController {
    
    public function home() {
        $param['categories'] = CategoryModel::all();
        $param['cities'] = CityModel::all();
    
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        if (!Session::has('keyword')) {
            Session::set('keyword', '');
            Session::set('location', '');
        }
        
        $ipAddr = $_SERVER['REMOTE_ADDR'];
        $geoPlugin = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ipAddr));
        
        if (is_numeric($geoPlugin['geoplugin_latitude']) && is_numeric($geoPlugin['geoplugin_longitude'])) {
            $lat = $geoPlugin['geoplugin_latitude'];
            $lng = $geoPlugin['geoplugin_longitude'];
        }        
    
        $result = StoreModel::search('', '', $lat, $lng)->orderBy('distance', 'ASC')->take(12)->get();
        
        $rand = [];
        for ($i = 0; $i < count($result); $i++) {
            $rand[] = $i;
        }
        shuffle($rand);
        
        $stores = [];
        for ($i = 0; $i < count($result); $i++) {
            $stores[] = $result[$rand[$i]];
        }
        
        $param['stores'] = $stores;
        $param['feedbacks'] = FeedbackModel::avgReview()->orderBy('id', 'DESC')->take(8)->get();
        
        return View::make('user.store.home')->with($param);
    }    
    
    public function search() {
        $keyword = Input::has('keyword') ? Input::get('keyword') : '';
        $location = Input::has('location') ? Input::get('location') : '';
        $lat = Input::has('lat') ? Input::get('lat') : '';
        $lng = Input::has('lng') ? Input::get('lng') : '';
        $orderBy = Input::has('orderBy') ? Input::get('orderBy') : 1;
        $dt = Input::has('dt') ? Input::get('dt') : 'list';
        
        Session::set('orderBy', $orderBy);
        Session::set('dt', $dt);
        
        if ($lat == '' && $lng == '') {
            $ipAddr = $_SERVER['REMOTE_ADDR'];
            $geoPlugin = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ipAddr));
            
            if (is_numeric($geoPlugin['geoplugin_latitude']) && is_numeric($geoPlugin['geoplugin_longitude'])) {            
                $lat = $geoPlugin['geoplugin_latitude'];
                $lng = $geoPlugin['geoplugin_longitude'];
            }
        }
    
        // $result = StoreModel::completed()->search($keyword, $location);
        $result = StoreModel::search($keyword, $location, $lat, $lng);
    
        $tblStore =with(new StoreModel)->getTable();
    
        $param['categories'] = CategoryModel::all();
        $param['cities'] = CityModel::all();
        
        // $stores = $result->distinct();
        
        if ($orderBy == 1) {
            $stores = $result->orderBy('distance', 'ASC');
        } elseif ($orderBy == 2) {
            $stores = $result->orderBy('avgReview', 'DESC');
        } elseif ($orderBy == 3) {
            $stores = $result->orderBy('count_view', 'DESC');
        } else {
            $stores = $result->orderBy('distance', 'ASC');
        }
        if (Session::get('dt') == 'grid')
            $param['stores'] = $result->orderBy('distance', 'ASC')->paginate(4 * 4);
        else 
            $param['stores'] = $result->orderBy('distance', 'ASC')->paginate(PAGINATION_SIZE);
    
        Session::set('keyword', $keyword);
        Session::set('location', $location);
    
        return View::make('user.store.search')->with($param);
    }    
    
    public function detailProfile($slug) {
        $store = StoreModel::findBySlug($slug);
        $param['store'] = $store;
        
        $store->count_view = $store->count_view + 1;
        $store->save();
        
        $param['categories'] = CategoryModel::all();
        $param['cities'] = CityModel::all();
        $param['subPageNo'] = 1;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        if (Session::has('user_id')) {
            if (FeedbackModel::where('user_id', Session::get('user_id'))
                            ->where('store_id', $param['store']->id)
                            ->where('status', '<>', 'ST03')
                            ->get()
                            ->count() > 0) {
                $param['is_valid'] = FALSE;
            } else {
                $param['is_valid'] = TRUE;
            }
        } else {
            $param['is_valid'] = FALSE;
        }        
        
        return View::make('user.store.detailProfile')->with($param);
    }
    
    
    public function detailPhoto($slug) {
        $param['store'] = StoreModel::findBySlug($slug);
        $param['categories'] = CategoryModel::all();
        $param['cities'] = CityModel::all();
        $param['subPageNo'] = 2;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        if (Session::has('user_id')) {
            if (ReviewPhotoModel::where('user_id', Session::get('user_id'))
                            ->where('store_id', $param['store']->id)
                            ->get()
                            ->count() > 0) {
                $param['is_valid'] = FALSE;
            } else {
                $param['is_valid'] = TRUE;
            }
        } else {
            $param['is_valid'] = FALSE;
        }        
        return View::make('user.store.detailPhoto')->with($param);
    }
    
    public function asyncJoin() {
        if (Session::has('user_id')) {
            $companyId = Input::get('company_id');
            $userId = Session::get('user_id');
            $consumer = ConsumerModel::where('company_id', $companyId)->where('user_id', $userId)->get();
            if ($consumer->count() > 0) {
                $result['result'] = "failed";
                $result['msg'] = "You have already joined on this store.";                
            } else {
                CommonFunctionModel::addConsumer($companyId, $userId, 0);
                $result['result'] = "success";
                $result['msg'] = "You have successfully joined on this store.";                
            }
        } else {
            $result['result'] = "failed";
            $result['msg'] = "You have to login";            
        }

        return Response::json($result, 200);
    }    
}
