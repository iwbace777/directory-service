<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Company as CompanyModel, Feedback as FeedbackModel;
use Store as StoreModel;

class FeedbackController extends \BaseController {   
    public function index() {
        $year = Input::has('year') ? Input::get('year') : date("Y");
        $month = Input::has('month') ? Input::get('month') : date("m");
        
        $param['storeId'] = Input::has('store_id') ? Input::get('store_id') : '';
        if (Input::has('store_id') && Input::get('store_id') != '') {
            $param['store'] = StoreModel::find(Input::get('store_id'));
        }
        $param['company'] = CompanyModel::find(Session::get('company_id'));
        $param['pageNo'] = 6;
        $param['year'] = $year;
        $param['month'] = $month;
        
        $param['prevYear'] = date("Y", strtotime("-1 month", strtotime("$year-$month-01")));
        $param['prevMonth'] = date("m", strtotime("-1 month", strtotime("$year-$month-01")));
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        $param['statuses'] = ['ST01' => 'Unread', 'ST02' => 'Read', 'ST03' => 'Sent', 
                              'ST04' => 'New', 'ST05' => 'Credited', 'ST06' => 'Archive', ];
        
        return View::make('company.feedback.index')->with($param);
    }

    public function delete($id) {
        try {
            FeedbackModel::find($id)->ratings()->delete();
            FeedbackModel::find($id)->delete();
            $alert['msg'] = 'Feedback has been deleted successfully';
            $alert['type'] = 'success';            
        } catch(\Exception $ex) {
            $alert['msg'] = 'This Feedback has been already used';
            $alert['type'] = 'danger';
        }

        return Redirect::route('company.feedback')->with('alert', $alert);
    }
    
    public function changeStatus($id, $status) {
        $feedback = FeedbackModel::find($id);
        $feedback->status = $status;
        $feedback->save();
        return Redirect::back();
    }
}
