<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Queue;

use Subscriber as SubscriberModel;

class SubscriberController extends \BaseController {    
    
    public function index() {
        $param['pageNo'] = 14;
        
        $param['subscribers'] = SubscriberModel::paginate(PAGINATION_SIZE);
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('admin.subscriber.index')->with($param);
    }    
    
    public function send() {
        $param['pageNo'] = 14;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        return View::make('admin.subscriber.send')->with($param);
    }
    
    public function doSend() {
        $alert['msg'] = 'The newsletter has been sent successfully';
        $alert['type'] = 'success';
        
        $body = Input::get('body');
        
        Queue::push('\DirectoryService\Queue\SendSubscriber', ['body' => $body] );
        
        return Redirect::route('admin.subscriber')->with('alert', $alert);        
    }
    
    public function delete($id) {
        try {
            SubscriberModel::find($id)->delete();
    
            $alert['msg'] = 'Subscriber has been deleted successfully';
            $alert['type'] = 'success';
        } catch(\Exception $ex) {
            $alert['msg'] = 'This Subscriber has been already used';
            $alert['type'] = 'danger';
        }
        return Redirect::route('admin.subscriber')->with('alert', $alert);
    }
    
}