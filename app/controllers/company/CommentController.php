<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Comment as CommentModel;

class CommentController extends \BaseController {
    public function index() {
        $param['comments'] = CommentModel::where('company_id', Session::get('company_id'))->orderBy('id', 'DESC')->paginate(PAGINATION_SIZE);
        $param['pageNo'] = 4;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('company.comment.index')->with($param);
    }

    public function delete($id) {
        try {
            CommentModel::find($id)->delete();
            $alert['msg'] = 'Comment has been deleted successfully';
            $alert['type'] = 'success';            
        } catch(\Exception $ex) {
            $alert['msg'] = 'This Comment has been already used';
            $alert['type'] = 'danger';
        }

        return Redirect::route('company.comment')->with('alert', $alert);
    }
}
