<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, Request;
use Company as CompanyModel, CompanyWidget as CompanyWidgetModel;

class WidgetController extends \BaseController {

    public function index() {
        $param['pageNo'] = 10;
        $param['company'] = CompanyModel::find(Session::get('company_id'));
        return View::make('company.widget.index')->with($param);
    }
    
    public function store() {
        $company = CompanyModel::find(Session::get('company_id'));
        
        if (count($company->widget) == 0) {
            $companyWidget = new CompanyWidgetModel;
        } else {
            $companyWidget = $company->widget;
        }
        
        $companyWidget->company_id = Session::get('company_id');
        $companyWidget->color = Input::get('color');
        $companyWidget->header = Input::get('header');
        $companyWidget->background = Input::get('background');
        $companyWidget->custom_css = Input::has('custom_css') ? Input::get('custom_css') : '';
        $companyWidget->is_header = Input::get('is_header');
        
        if (Input::hasFile('logo')) {
            $filename = str_random(24).".".Input::file('logo')->getClientOriginalExtension();
            Input::file('logo')->move(ABS_LOGO_PATH, $filename);
            $companyWidget->logo = $filename;
        }
        
        if (count($company->widget) == 0 && !Input::hasFile('logo')) {
            $companyWidget->logo = DEFAULT_PHOTO;
        }
        $companyWidget->save();
        
        $alert['msg'] = 'Widget Setting has been updated successfully.';
        $alert['type'] = 'success';
        
        return Redirect::route('company.widget.index')->with('alert', $alert);        
    }
}
