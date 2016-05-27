<?php namespace User;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Request, Response, Mail, URL, Cookie;
use User as UserModel, City as CityModel, Category as CategoryModel, Cart as CartModel;
use Comment as CommentModel, Contact as ContactModel, Offer as OfferModel, UserOffer as UserOfferModel;
use Feedback as FeedbackModel, Rating as RatingModel, ReviewPhoto as ReviewPhotoModel;
use Company as CompanyModel, Consumer as ConsumerModel, CommonFunction as CommonFunctionModel;
use Store as StoreModel;
use Subscriber as SubscriberModel;
use UserSns as UserSnsModel;

class UserController extends \BaseController {
    
    public function login() {
        if (Session::has('user_id')) {
            return Redirect::route('user.home');
        }
        
        $param['pageNo'] = 51;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        $param['redirect'] = Input::has('redirect') ? Input::get('redirect') : '';
        
        $param['categories'] = CategoryModel::all();
        $param['cities'] = CityModel::all();        
        return View::make('user.auth.login')->with($param);        
    }
    
    public function signup() {
        if (Session::has('user_id')) {
            return Redirect::route('user.home');
        }
    
        $param['pageNo'] = 52;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        $param['redirect'] = Input::has('redirect') ? Input::get('redirect') : '';
        
        $param['categories'] = CategoryModel::all();
        $param['cities'] = CityModel::all();        
        return View::make('user.auth.signup')->with($param);
    }    
    
    public function active($token) {
        $user = UserModel::where('token', $token)->firstOrFail();
        $user->is_active = TRUE;
        $user->save();
        
        $alert['msg'] = 'You have successfully active your account';
        $alert['type'] = 'success';
                
        return Redirect::route('user.home')->with('alert', $alert);
    }
    
    public function doLogin() {
        $email = Input::get('email');
        $password = Input::get('password');
        $is_remember = Input::get('is_remember');
        
        $user = UserModel::where('email', $email)->whereRaw('secure_key = md5(concat(salt, ?))', [$password])->get();
        if (count($user) != 0) {
            if ($user[0]->is_active) {
                Session::set('user_id', $user[0]->id);
                Session::set('user_name', $user[0]->name);
                if ($is_remember == 1) {
                    Cookie::queue('ut', $user[0]->salt, 60 * 24 * 60);
                }
                if (Input::has('redirect'))
                    return Redirect::to(Input::get('redirect'));
                else
                    return Redirect::route('user.home');
            } else {
                $alert['msg'] = "You must verify your account to login. <button class='btn btn-default pull-right btn-sm' data-id=".$user[0]->id." id='js-btn-resend'>Resend Email</button>";
                $alert['type'] = 'danger';
                return Redirect::route('user.login')->with('alert', $alert);                
            }
        } else {
            $alert['msg'] = 'Invalid Email and Password';
            $alert['type'] = 'danger';
            return Redirect::route('user.login')->with('alert', $alert);
        }
    }

    public function doSignup() {
        $rules = ['email'      => 'required|email|unique:user',
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
            $is_exist = FALSE;
            $users = UserModel::where('email', Input::get('email'))->get();
            if ($users->count() > 0) {
                $user = $users[0];
                if ($user->secure_key != '') {
                    $is_exist = TRUE;
                }
            }  else {
                $user = new UserModel;
            }
            
            if ($is_exist) {
                $alert['msg'] = 'The account is already exist';
                $alert['type'] = 'danger';           
            } else {
                $user->name = Input::get('name');
                $user->email = Input::get('email');
                $user->phone = Input::get('phone');
                $user->photo = DEFAULT_PHOTO;
                $user->token = strtoupper(str_random(6));
                $user->salt = str_random(8);
                $user->secure_key = md5($user->salt.Input::get('password'));
                $user->is_active = FALSE;
                $user->save();
                
                $param = ['active_link' => URL::route('user.active', $user->token)];
                
                $info = [ 'reply_name'  => REPLY_NAME,
                          'reply_email' => REPLY_EMAIL,
                          'email'       => $user->email,
                          'name'        => $user->name,
                          'subject'     => SITE_NAME,
                    ];
                
                Mail::send('email.active', $param, function($message) use ($info) {
                    $message->from($info['reply_email'], $info['reply_name']);
                    $message->to($info['email'], $info['name'])
                        ->subject($info['subject']);
                });
                
                $alert['msg'] = 'Check your email to verify your account';
                $alert['type'] = 'success';
            }            

            return Redirect::route('user.signup')->with('alert', $alert);
        }
    }
    
    public function doSignout() {
        Session::forget('user_id');
        Session::forget('user_name');
        Cookie::queue('ut', '', -1);
        return Redirect::route('user.home');
    }
    
    public function forgotPassword() {
        if (Session::has('user_id')) {
            return Redirect::route('user.home');
        }
        
        $param['pageNo'] = 51;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        $param['categories'] = CategoryModel::all();
        $param['cities'] = CityModel::all();        
        return View::make('user.auth.forgotPassword')->with($param);
    }
    
    public function resetPassword($slug) {
        
        if (Session::has('user_id')) {
            return Redirect::route('user.home');
        }
        
        $users = UserModel::where('salt', $slug)->get();
        if (count($users) > 0) {
            $param['pageNo'] = 51;
            if ($alert = Session::get('alert')) {
                $param['alert'] = $alert;
            }
            $param['slug'] = $slug;
            $param['categories'] = CategoryModel::all();
            $param['cities'] = CityModel::all();            
            return View::make('user.auth.resetPassword')->with($param);            
        } else {
            $alert['msg'] = "Email is not exist";
            $alert['type'] = 'danger';
            return Redirect::route('user.forgotPassword')->with('alert', $alert);
        }
    }
    
    public function doResetPassword($slug) {
        $rules = ['password' => 'required'];
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
        } else {
            $user = UserModel::where('salt', $slug)->firstOrFail();
            $user->secure_key = md5($user->salt.Input::get('password'));
            $user->save();
        
            $alert['msg'] = "Password has been reset successfully";
            $alert['type'] = 'success';
            return Redirect::route('user.login')->with('alert', $alert);
        }        
    }

    public function sendResetPasswordEmail() {
        $rules = ['email' => 'required|email'];
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
        } else {
            $users = UserModel::where('email', Input::get('email'))->get();
            if (count($users) > 0) {
                $user = $users[0];

                $param = ['reset_link' => URL::route('user.resetPassword', $user->salt), ];
                $info = [ 'reply_name'  => REPLY_NAME,
                          'reply_email' => REPLY_EMAIL,
                          'email'       => $user->email,
                          'name'        => $user->name,
                          'subject'     => SITE_NAME,
                    ];
                
                Mail::send('email.resetPassword', $param, function($message) use ($info) {
                    $message->from($info['reply_email'], $info['reply_name']);
                    $message->to($info['email'], $info['name'])
                            ->subject($info['subject']);
                });
                
                $alert['msg'] = "Password changes email has been sent";
                $alert['type'] = 'success';
            } else {
                $alert['msg'] = "Email does not exist.";
                $alert['type'] = 'danger';
            }
            return Redirect::route('user.forgotPassword')->with('alert', $alert);
        }
    }
    
    public function profile() {
        $param['pageNo'] = 2;
        $param['categories'] = CategoryModel::all();
        $param['cities'] = CityModel::all();        
        $user = UserModel::find(Session::get('user_id'));
        $param['user'] = $user;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }        
        return View::make('user.auth.profile')->with($param);        
    }
    
    public function updateProfile() {
        $rules = ['email'      => 'required|email',
                  'name'       => 'required',
            ];
        
        $user = UserModel::find(Session::get('user_id'));
        $user->email = Input::get('email');
        $user->name = Input::get('name');
        $user->phone = Input::get('phone');
        
        if (Input::hasFile('photo')) {
            $filename = str_random(24).".".Input::file('photo')->getClientOriginalExtension();
            Input::file('photo')->move(ABS_USER_PATH, $filename);
            $user->photo = $filename;
        }
        
        if (Input::get('password') != '') {
            $user->salt = str_random(8);
            $user->secure_key = md5($user->salt.Input::get('password'));            
        }
        $user->save();
        
        $alert['msg'] = 'User profile has been updated successfully';
        $alert['type'] = 'success';
        
        return Redirect::route('user.profile')->with('alert', $alert);        
    }
    
    public function cart() {
        $param['pageNo'] = 1;
        $param['categories'] = CategoryModel::all();
        $param['cities'] = CityModel::all();
        $param['carts'] = CartModel::where('user_id', Session::get('user_id'))->paginate(PAGINATION_SIZE);
        return View::make('user.carts.index')->with($param);
    }
    

    public function offers() {
        $param['pageNo'] = 3;
        $param['categories'] = CategoryModel::all();
        $param['cities'] = CityModel::all();
        $param['userOffers'] = UserOfferModel::where('user_id', Session::get('user_id'))->where('is_used', FALSE)->get();
        return View::make('user.offers.index')->with($param);
    }    
    
    public function sendMessage() {
        $store = StoreModel::find(Input::get('store_id'));
        $contact = new ContactModel;
        $contact->company_id = $store->company_id;
        $contact->name = Input::get('name');
        $contact->email = Input::get('email');
        $contact->description = Input::get('description');
        $contact->save();
        
        $alert['msg'] = 'You send contact successfully';
        $alert['type'] = 'success';
        return Redirect::route('store.detail', $store->slug)->with('alert', $alert);
    }
    
    public function giveFeedback() {
        $store = StoreModel::find(Input::get('store_id'));
        $feedbacks = FeedbackModel::where('store_id', $store->id)->where('user_id', Session::get('user_id'));
        if ($feedbacks->count() > 0) {
            // RatingModel::where('feedback_id', $feedbacks[0]->id)->delete();
            $feedbacks->delete();
        }
        
        $feedback = new FeedbackModel;
        $feedback->store_id = $store->id;
        $feedback->user_id = Session::get('user_id');
        $feedback->description = Input::get('description');
        $feedback->save();
        
        foreach (Input::get('rating') as $key => $value) {
            $rating = new RatingModel;
            $rating->feedback_id = $feedback->id;
            $rating->type_id = Input::get('type_id')[$key];
            $rating->answer = $value;
            $rating->save();            
        }
        
        
        $alert['msg'] = 'You left the feedback successfully';
        $alert['type'] = 'success';
    
        CommonFunctionModel::addConsumer($store->company->id, Session::get('user_id'), 0);
        CommonFunctionModel::addOffer($store->company->id, Session::get('user_id'));
    
        return Redirect::route('store.detail', $store->slug)->with('alert', $alert);
    }
    
    public function uploadPhoto() {
        $store = CompanyModel::find(Input::get('store_id'));
        if (Input::hasFile('photo')) {
            $filename = str_random(24).".".Input::file('photo')->getClientOriginalExtension();
            Input::file('photo')->move(ABS_REVIEW_PATH, $filename);
    
            $reviewPhoto = new ReviewPhotoModel;
            $reviewPhoto->photo = $filename;
            $reviewPhoto->description = Input::get('description');
            $reviewPhoto->store_id = $store->id;
            $reviewPhoto->user_id = Session::get('user_id');
            $reviewPhoto->save();
            $alert['msg'] = 'Image has been uploaded successfully';
            $alert['type'] = 'success';
        } else {
            $alert['msg'] = 'Please select file to upload';
            $alert['type'] = 'danger';
        }       
    
        return Redirect::route('store.detail.photo', $company->slug)->with('alert', $alert);
    }
    
    public function asyncAddCart() {
        if (Input::has('store_id') && Session::has('user_id')) {
            if (CartModel::where('store_id', Input::get('store_id'))->where('user_id', Session::get('user_id'))->get()->count() > 0) {
                $result['result'] = "failed";
                $result['msg'] = "This store is already added on the cart.";
                return Response::json($result, 200);
            } else {
                $cart = new CartModel;
                $cart->store_id = Input::get('store_id');
                $cart->user_id = Session::get('user_id');
                $cart->save();
                $result['result'] = "success";
                $result['msg'] = "This store has been added on cart successfully.";
                return Response::json($result, 200);
            }
        } else {
            $result['result'] = "failed";
            $result['msg'] = "Invalid Request";
            return Response::json($result, 401);
        }
    }
    
    public function asyncRemoveCart() {
        CartModel::find(Input::get('cart_id'))->delete();
        $result['result'] = "success";
        $result['msg'] = "The company has been removed succssfully from the cart";
        return Response::json($result, 200);
    }
    
    public function asyncDoSubscriber() {
        $email = Input::get('email');
        $subscribers = SubscriberModel::where('email', $email)->get();
        if (count($subscribers) > 0) {
            return Response::json(['result' => 'failed', 'msg' => "You have already registered as Subscriber", ]);
        } else {
            $subscriber = new SubscriberModel;
            $subscriber->email = $email;
            $subscriber->save();
            return Response::json(['result' => 'success', 'msg' => "You have registered successfully as Subscriber", ]);
        }        
    }
    
    public function asyncLoginFacebook() {
        $accessToken = Input::get('accessToken');
        $response = Input::get('response');
        $is_remember = Input::get('is_remember');
        
        $facebookId = $response['id'];
        $facebookEmail = $response['email'];
        $facebookName = $response['name'];
        
        $users = UserModel::where('email', $facebookEmail)->get();
        
        if (count($users) == 0) {
            $photoUrl = "http://graph.facebook.com/".$facebookId."/picture?type=large";
            $photoContent = file_get_contents($photoUrl);
            $filename = str_random(24).".jpg";
            file_put_contents(ABS_USER_PATH.$filename, $photoContent);

            $user = new UserModel;
            $user->name = $facebookName;
            $user->email = $facebookEmail;
            $user->photo = $filename;
            $user->token = strtoupper(str_random(6));
            $user->salt = str_random(8);
            $user->secure_key = "";
            $user->is_active = TRUE;
            $user->save();
        } else {
            $user = $users[0];
            $user->is_active = TRUE;
            $user->save();
        }
        
        $userSnses = UserSnsModel::where('type', 'FB')->where('user_id', $user->id)->get();
        if (count($userSnses) == 0) {
            $userSns = new UserSnsModel;
            $userSns->user_id = $user->id;
            $userSns->type = 'FB';
            $userSns->sns_id = $facebookId;
            $userSns->token = $accessToken;
            $userSns->save();            
        }
        
        Session::set('user_id', $user->id);
        Session::set('user_name', $user->name);
        if ($is_remember == 1) {
            Cookie::queue('ut', $user->salt, 60 * 24 * 60);
        }
        return Response::json(['result' => 'success', 'msg' => '', ]);        
    }
}
