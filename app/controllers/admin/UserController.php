<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use User as UserModel;

class UserController extends \BaseController {
    
    public function index() {
        $param['users'] = UserModel::paginate(PAGINATION_SIZE);
        $param['pageNo'] = 2;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('admin.user.index')->with($param);
    }
    
    public function create() {
        $param['pageNo'] = 2;
        return View::make('admin.user.create')->with($param);
    }
    
    public function edit($id) {
        $param['pageNo'] = 2;
        $param['user'] = UserModel::find($id);
                
        return View::make('admin.user.edit')->with($param);
    }
    
    public function store() {
        
        $rules = ['email'      => 'required|email',
                  'name'       => 'required',
        ];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if (Input::has('user_id')) {
                $id = Input::get('user_id');
                $user = UserModel::find($id);                
            } else {
                $user = new UserModel;
                $user->photo = DEFAULT_PHOTO;
                
                $user->token = strtoupper(str_random(6));
                $user->salt = str_random(8);
                $user->is_active = TRUE;
            }
            
            if (Input::hasFile('photo')) {
                $filename = str_random(24).".".Input::file('photo')->getClientOriginalExtension();
                Input::file('photo')->move(ABS_USER_PATH, $filename);
                $user->photo = $filename;
            }

            $user->name = Input::get('name');
            $user->email = Input::get('email');
            $user->phone = Input::get('phone');
            
            if (Input::has('password')) {
                $user->secure_key = md5($user->salt.Input::get('password'));                
            }
            $user->save();
            
            $alert['msg'] = 'User has been saved successfully';
            $alert['type'] = 'success';
        
            return Redirect::route('admin.user')->with('alert', $alert);            
        }
    }
    
    public function delete($id) {
        try {
            UserModel::find($id)->delete();
            
            $alert['msg'] = 'User has been deleted successfully';
            $alert['type'] = 'success';            
        } catch(\Exception $ex) {
            $alert['msg'] = 'This User has been already used';
            $alert['type'] = 'danger';
        }

        return Redirect::route('admin.user')->with('alert', $alert);
    }
}
