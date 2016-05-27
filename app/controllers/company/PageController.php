<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, Request;
use Company as CompanyModel;
use Feedback as FeedbackModel;
use Visit as VisitModel;
use UserLoyalty as UserLoyaltyModel;
use UserOffer as UserOfferModel;

class PageController extends \BaseController {   
    public function setting() {
        $param['pageNo'] = 10;
        $param['company'] = CompanyModel::find(Session::get('company_id'));
        return View::make('company.page.setting')->with($param);
    }
    
    public function dashboard() {
        $startDate = Input::has('startDate') ? Input::get('startDate') : '';
        $endDate = Input::has('endDate') ? Input::get('endDate') : '';
        if ($startDate == '' || $endDate == '') {
            $endDate = date('Y-m-d');
            $startDate = substr($endDate, 0, 8)."01";
        }
        $company = CompanyModel::find(Session::get('company_id'));

        $param['userOffers'] = UserOfferModel::soldOffers(Session::get('company_id'), $startDate, $endDate);
        $param['userLoyalties'] = UserLoyaltyModel::usedLoyalties(Session::get('company_id'), $startDate, $endDate);
        
        $param['feedbacks'] = $company->periodFeedbacks($startDate, $endDate)->get();
        $param['registers'] = $company->registers($startDate, $endDate)->get();
        
        $param['startDate'] = $startDate;
        $param['endDate'] = $endDate;
        $param['pageNo'] = 1;
        
        return View::make('company.page.dashboard')->with($param);
    }    
    
}
