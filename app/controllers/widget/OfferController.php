<?php namespace Widget;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, DB, Mail, Validator, URL;
use Company as CompanyModel, User as UserModel;
use Feedback as FeedbackModel, Rating as RatingModel, Offer as OfferModel, UserOffer as UserOfferModel;
use CommonFunction as CommonFunctionModel;
use Visit as VisitModel;
class OfferController extends \BaseController {    
    public function home($slug) {
        $companies = CompanyModel::where('token', $slug)->get();
        if (count($companies) > 0) {
            $company = $companies[0];
            $param['company'] = $company;
        } else {
            return Redirect::route('user.home');
        }
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }        
        return View::make('widget.offer.home')->with($param);        
    }
}
