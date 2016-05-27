<?php namespace User;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, Request, Mail;
use Feedback as FeedbackModel;
use \DirectoryService\Models\Message as MessageModel;
use User as UserModel;
use City as CityModel;
use Category as CategoryModel;

class MessageController extends \BaseController {

    public function index() {
        $param['categories'] = CategoryModel::all();
        $param['cities'] = CityModel::all();
                
        $param['pageNo'] = 4;
        $param['messages'] = UserModel::find(Session::get('user_id'))->messages()->orderBy('id', 'DESC')->groupBy('feedback_id')->get();
        return View::make('user.message.index')->with($param);
    }
    
    public function detail($id) {
        $param['categories'] = CategoryModel::all();
        $param['cities'] = CityModel::all();
                
        $param['pageNo'] = 4;
        $param['feedback'] = FeedbackModel::find($id);
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }

        return View::make('user.message.detail')->with($param);
    }
    
    public function doSend() {
        $feedbackId = Input::get('feedback_id');
        $description = Input::get('description');
        $feedback = FeedbackModel::find($feedbackId);
        
        $message = new MessageModel;
        $message->feedback_id = $feedbackId;
        $message->company_id = $feedback->store->company->id;
        $message->user_id = $feedback->user_id;
        $message->description = $description;
        $message->is_company_sent = FALSE;
        $message->save();
        
        $feedback->status = 'ST04';
        $feedback->save();
        
        $alert['msg'] = 'Message has been sent successfully';
        $alert['type'] = 'success';
        
        return Redirect::route('user.message.detail', $feedbackId)->with('alert', $alert);        
    }
}
