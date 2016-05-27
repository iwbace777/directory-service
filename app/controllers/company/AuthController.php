<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Company as CompanyModel, CompanyDetail as CompanyDetailModel, CompanyOpening as CompanyOpeningModel;
use City as CityModel, Category as CategoryModel, CompanySubCategory as CompanySubCategoryModel, SubCategory as SubCategoryModel;
use RatingType as RatingTypeModel;

class AuthController extends \BaseController {

    public function index() {
        if (Session::has('company_id')) {
            return Redirect::route('company.dashboard');
        } else {
            return Redirect::route('company.auth.login');
        }
    }
    
    public function login() {
        if (Session::has('company_id')) {
            return Redirect::route('company.dashboard');
        }
        
        $param['pageNo'] = 51;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        return View::make('company.auth.login')->with($param);
    }
    
    public function doLogin() {
        $email = Input::get('email');
        $password = Input::get('password');
        
        $company = CompanyModel::whereRaw('email = ? and secure_key = md5(concat(salt, ?))', array($email, $password))->get();
    
        if (count($company) != 0 > 0) {
            Session::set('company_id', $company[0]->id);
            Session::set('company_name', $company[0]->name);
            return Redirect::route('company.dashboard');
        } else {
            $alert['msg'] = 'Invalid username and password';
            $alert['type'] = 'danger';
            return Redirect::route('company.auth.login')->with('alert', $alert);
        }
    }
    
    public function signup() {
        if (Session::has('company_id')) {
            return Redirect::route('company.dashboard');
        }

        $param['pageNo'] = 52;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        return View::make('company.auth.signup')->with($param);
    }

    public function doSignup() {
        
        $rules = ['email'      => 'required|email|unique:company',
                  'password'   => 'required|confirmed',
                  'password_confirmation' => 'required',
                  'name'       => 'required',
        ];
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
            ->withErrors($validator)
            ->withInput();
        } else {
            $company = new CompanyModel;
            $company->name = Input::get('name');
            $company->email = Input::get('email');
            $company->phone = Input::get('phone');
            $company->photo = DEFAULT_PHOTO;
            $company->keyword = Input::get('keyword');
            $company->count_email = 0;
            $company->count_sms = 0;
            $company->is_completed = FALSE;
            $company->token = strtoupper(str_random(8));
            $company->salt = str_random(8);
            $company->secure_key = md5($company->salt.Input::get('password'));
            $company->save();

            $companyOpening = new CompanyOpeningModel;
            $companyOpening->company_id = $company->id;
            foreach (['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun', ] as $key) {
                $companyOpening->{ $key.'_start'} = DEFAULT_START_TIME;
                $companyOpening->{ $key.'_end'} = DEFAULT_END_TIME;
            }                                                                        
            $companyOpening->save();
            
            foreach (['Service', 'Quality', 'Clean'] as $value) {
                $ratingType = new RatingTypeModel;
                $ratingType->company_id = $company->id;
                $ratingType->name = $value;
                $ratingType->save();
            }
            

            $alert['msg'] = 'Please login and complete your profile';
            $alert['type'] = 'success';
        
            return Redirect::route('company.auth.login')->with('alert', $alert);
        }
    }    
    
    public function logout() {
        Session::forget('company_id');
        Session::forget('company_name');
        return Redirect::route('company.auth.login');
    }
    
    public function profile($id = 1) {
        $param['pageNo'] = 9;
        $param['tabNo'] = $id;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        $param['company'] = CompanyModel::find(Session::get('company_id'));
        $param['cities'] = CityModel::all();
        $param['categories'] = CategoryModel::all();
        
        return View::make('company.auth.profile')->with($param);
    }

    public function updateOpeningHours() {
        $companyOpening = CompanyModel::find(Session::get('company_id'))->opening;
        foreach (['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun', ] as $key) {
            $companyOpening->{ $key.'_start'} = Input::get($key.'_start');
            $companyOpening->{ $key.'_end'} = Input::get($key.'_end');
        }
        $companyOpening->save();
        
        $alert['msg'] = 'Opening Hours has been updated successfully';
        $alert['type'] = 'success';

        return Redirect::route('company.profile', 2)->with('alert', $alert);        
    }
    
    public function changePassword() {
        $rules = ['password_current' => 'required',
                  'password' => 'required|confirmed',
                  'password_confirmation' => 'required',
        ];
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
        } else {
            $company = CompanyModel::find(Session::get('company_id'));
            if ($company->secure_key == md5($company->salt.Input::get('password_current'))) {
                $company->secure_key = md5($company->salt.Input::get('password'));
                $company->save();
                
                $alert['msg'] = 'Password has been updated successfully';
                $alert['type'] = 'success';                                
            } else {
                $alert['msg'] = 'Current Password is incorrect';
                $alert['type'] = 'danger';                
            }
            return Redirect::route('company.profile', 4)->with('alert', $alert);            
        }        
    }
    
    public function updateCompany() {
        $company = CompanyModel::find(Session::get('company_id'));
        $company->name = Input::get('name');
        $company->email = Input::get('email');
        $company->phone = Input::get('phone');
        $company->vat_id = Input::get('vat_id');
        $company->keyword = Input::get('keyword');
        $company->is_completed = TRUE;
        $company->save();
        
        CompanySubCategoryModel::where('company_id', Session::get('company_id'))->delete();
        if (Input::has('sub_category')) {
            foreach (Input::get('sub_category') as $subCategory) {
                $subCategory = SubCategoryModel::find($subCategory);
            
                $companySubCategory = new CompanySubCategoryModel;
                $companySubCategory->company_id = Session::get('company_id');
                $companySubCategory->category_id = $subCategory->category_id;
                $companySubCategory->sub_category_id = $subCategory->id;
                $companySubCategory->save();
            }            
        }
        
        $alert['msg'] = 'Company has been updated successfully';
        $alert['type'] = 'success';        
        
        return Redirect::route('company.profile', 1)->with('alert', $alert);
    }
    
    public function updatePhoto() {
        $company = CompanyModel::find(Session::get('company_id'));
        if (Input::hasFile('photo')) {
            $filename = str_random(24).".".Input::file('photo')->getClientOriginalExtension();
            Input::file('photo')->move(ABS_COMPANY_PATH, $filename);
            $company->photo = $filename;
        }
        $company->save();        
        
        $alert['msg'] = 'Company Photo has been updated successfully';
        $alert['type'] = 'success';
        
        return Redirect::route('company.profile', 3)->with('alert', $alert);
    }
}
