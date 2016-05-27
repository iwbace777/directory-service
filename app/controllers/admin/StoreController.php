<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Company as CompanyModel;
use City as CityModel;
use Store as StoreModel;
use Category as CategoryModel;
use SubCategory as SubCategoryModel;
use StoreSubCategory as StoreSubCategoryModel;

class StoreController extends \BaseController {
    
    public function index() {
        $param['stores'] = StoreModel::paginate(PAGINATION_SIZE);
        $param['pageNo'] = 12;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('admin.store.index')->with($param);
    }
    
    public function create() {
        $param['pageNo'] = 12;
        $param['categories'] = CategoryModel::all();
        $param['companies'] = CompanyModel::all();
        $param['cities'] = CityModel::all();
        return View::make('admin.store.create')->with($param);
    }
    
    public function edit($id) {
        $param['pageNo'] = 12;
        $param['store'] = StoreModel::find($id);
        $param['categories'] = CategoryModel::all();
        $param['companies'] = CompanyModel::all();
        $param['cities'] = CityModel::all();
        
        return View::make('admin.store.edit')->with($param);
    }
    
    public function store() {
        
        $rules = ['company_id' => 'required',
                  'email'      => 'required|email',
                  'name'       => 'required',
                  'city_id'    => 'required',
        ];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if (Input::has('store_id')) {
                $id = Input::get('store_id');
                $store = StoreModel::find($id);                
            } else {
                $store = new StoreModel;
                $store->photo = DEFAULT_PHOTO;
                
                $store->token = strtoupper(str_random(5));
                $store->salt = str_random(8);
                
                $store->secure_key = md5($store->salt.'');
            }
            
            if (Input::hasFile('photo')) {
                $filename = str_random(24).".".Input::file('photo')->getClientOriginalExtension();
                Input::file('photo')->move(ABS_STORE_PATH, $filename);
                $store->photo = $filename;
            }

            $store->company_id = Input::get('company_id');
            $store->name = Input::get('name');
            $store->email = Input::get('email');
            $store->phone = Input::get('phone');
            $store->city_id = Input::get('city_id');
            $store->zip_code = Input::get('zip_code');
            $store->address = Input::get('address');
            $store->lat = Input::get('lat');
            $store->lng = Input::get('lng');
            $store->description = Input::get('description');
            $store->keyword = Input::get('keyword');
            $store->save();
                        
            StoreSubCategoryModel::where('store_id', $store->id)->delete();
            if (Input::has('sub_category')) {
                foreach (Input::get('sub_category') as $subCategory) {
                    $subCategory = SubCategoryModel::find($subCategory);
                    $storeSubCategory = new StoreSubCategoryModel;
                    $storeSubCategory->store_id = $store->id;
                    $storeSubCategory->category_id = $subCategory->category_id;
                    $storeSubCategory->sub_category_id = $subCategory->id;
                    $storeSubCategory->save();
                }
            }            
            
            $alert['msg'] = 'Store has been saved successfully';
            $alert['type'] = 'success';
        
            return Redirect::route('admin.store')->with('alert', $alert);            
        }
    }
    
    public function delete($id) {
        try {
            StoreModel::find($id)->delete();
            
            $alert['msg'] = 'Store has been deleted successfully';
            $alert['type'] = 'success';            
        } catch(\Exception $ex) {
            $alert['msg'] = 'This Store has been already used';
            $alert['type'] = 'danger';
        }

        return Redirect::route('admin.store')->with('alert', $alert);
    }
}
