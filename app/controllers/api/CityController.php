<?php namespace Api;

use Illuminate\Routing\Controllers\Controller;
use Input, Response;
use City as CityModel;

class CityController extends \BaseController {
    public function index() {
        $cities = CityModel::all();
        
        $cityList = [];
        foreach ($cities as $key => $value) {
            $data['id'] = $value->id;
            $data['name'] = $value->name;
            $cityList[] = $data;
        }
        
        return Response::json(['result' => 'success', 'msg' => '', 'cities' => $cityList, ], 200);
    }
}