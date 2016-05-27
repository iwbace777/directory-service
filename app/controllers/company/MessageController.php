<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, Request, Mail;
use Feedback as FeedbackModel;
use \DirectoryService\Models\Message as MessageModel;
use Company as CompanyModel;

class MessageController extends \BaseController {

    public function index() {
        $param['pageNo'] = 14;
        $param['messages'] = CompanyModel::find(Session::get('company_id'))->messages()->orderBy('id', 'DESC')->groupBy('feedback_id')->get();
        return View::make('company.message.index')->with($param);
    }
    
    public function detail($id) {
        $param['pageNo'] = 14;
        $param['feedback'] = FeedbackModel::find($id);
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
                
        return View::make('company.message.detail')->with($param);
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
        $message->is_company_sent = TRUE;
        $message->save();
        
        $feedback->status = 'ST03';
        $feedback->save();
        
        $company = CompanyModel::find(Session::get('company_id'));
        $company->count_email = $company->count_email - 1;
        $company->save();
        
        $data = [ 'company_name' => $company->name,
                  'description' => $description,
                ];
        
        $info = [ 'reply_name'  => REPLY_NAME,
                  'reply_email' => REPLY_EMAIL,
                  'email'       => $feedback->user->email,
                  'name'        => $feedback->user->name,
                  'subject'     => SITE_NAME,
                ];
        
        Mail::send('email.message', $data, function($message) use ($info) {
            $message->from($info['reply_email'], $info['reply_name']);
            $message->to($info['email'], $info['name'])
                    ->subject($info['subject']);
        });
        
        $alert['msg'] = 'Message has been sent successfully';
        $alert['type'] = 'success';
        
        return Redirect::route('company.message.detail', $feedbackId)->with('alert', $alert);        
    }
}
