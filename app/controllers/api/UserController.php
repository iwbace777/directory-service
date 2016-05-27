<?php namespace Api;

use Illuminate\Routing\Controllers\Controller;
use Input, Response, Mail, URL;
use User as UserModel, Cart as CartModel, ReviewPhoto as ReviewPhotoModel;
use UserOffer as UserOfferModel, Feedback as FeedbackModel, Rating as RatingModel;
use Offer as OfferModel, Visit as VisitModel, CommonFunction as CommonFunctionModel;
use Store as StoreModel;

class UserController extends \BaseController {
    public function login() {
        if (Input::has('email') && Input::has('password')) {
            $email = Input::get('email');
            $password = Input::get('password');
            
            $user = UserModel::where('email', $email)->whereRaw('secure_key = md5(concat(salt, ?))', array($password))->get();
            if (count($user) != 0) {
                if ($user[0]->is_active) {
                    return Response::json(['result' => 'success', 'msg' => '', 
                                           'user_id' => $user[0]->id, 
                                           'name' => $user[0]->name,
                                           'email' => $user[0]->email,
                                           'phone' => $user[0]->phone,
                                           'photo' => HTTP_USER_PATH.$user[0]->photo, ], 200);
                } else {
                    return Response::json(['result' => 'failed', 'msg' => 'You must verify your account to login.'], 200);
                }
            } else {
                return Response::json(['result' => 'failed', 'msg' => 'Invalid Email and Password'], 200);
            }            
        } else {
            return Response::json(['result' => 'failed', 'msg' => 'Invalid Request'], 400);            
        }
    }

    public function signup() {
        if (Input::has('email') && Input::has('password') && Input::has('password_confirmation') && Input::has('name')) {
            if (Input::get('password') !== Input::get('password_confirmation')) {
                return Response::json(['result' => 'failed', 'msg' => 'Password and Confirmation is incorrect'], 200);
            } elseif (UserModel::where('email', Input::get('email'))->get()->count() > 0) {
                return Response::json(['result' => 'failed', 'msg' => 'Email already has been used.'], 200);
            } else {
                $user = new UserModel;
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
                return Response::json(['result' => 'success', 'msg' => 'Check your email to verify your account.'], 200);
            }
        } else {
            return Response::json(['result' => 'failed', 'msg' => 'Invalid Request'], 400);            
        }
    }
    
    public function profile() {
        if (Input::has('user_id')) {
            $user = UserModel::find(Input::get('user_id'));
            return Response::json(['result' => 'success', 
                                   'msg' => '',
                                   'name' => $user->name, 
                                   'email' => $user->email,
                                   'phone' => $user->phone,
                                   'photo' => HTTP_USER_PATH.$user->photo,], 200);
        } else {
            return Response::json(['result' => 'failed', 'msg' => 'Invalid Request'], 400);
        }
    }
    
    public function updateProfile() {
        if (Input::has('user_id') && Input::has('name') && Input::has('email')) {
            $user = UserModel::find(Input::get('user_id'));
            $user->email = Input::get('email');
            $user->name = Input::get('name');
            $user->phone = Input::get('phone');
            if (Input::get('password') != '') {
                $user->salt = str_random(8);
                $user->secure_key = md5($user->salt.Input::get('password'));
            }
            $user->save();            

            return Response::json(['result' => 'success',
                                   'msg' => 'User profile has been updated successfully',], 200);
        } else {
            return Response::json(['result' => 'failed', 'msg' => 'Invalid Request'], 400);
        }
    }
    
    public function addCart() {
        if (Input::has('user_id') && Input::has('store_id')) {
            if (CartModel::where('store_id', Input::get('store_id'))->where('user_id', Input::get('user_id'))->get()->count() > 0) {
                return Response::json(['result' => 'failed', 'msg' => 'This Store is already added on the cart.'], 200);
            } else {
                $cart = new CartModel;
                $cart->store_id = Input::get('store_id');
                $cart->user_id = Input::get('user_id');
                $cart->save();
                return Response::json(['result' => 'success', 'msg' => 'This Store has been added on cart successfully.'], 200);                
            }
        } else {
            return Response::json(['result' => 'failed', 'msg' => 'Invalid Request'], 400);
        }
    }
    
    public function cart() {
        if (Input::has('user_id')) {
            $cart = CartModel::where('user_id', Input::get('user_id'))->get();
            $stores = [];
            foreach ($cart as $key => $item) {
                $value = $item->store;
                $store['id'] = $value->id;
                $store['name'] = $value->name;
                $store['phone'] = $value->phone;
                $store['address'] = $value->address;
                $store['zip_code'] = $value->zip_code;
                $store['city_name'] = $value->city_id ? $value->city->name : '---';
                $store['photo'] = HTTP_STORE_PATH.$value->photo;
                $store['opening_time'] = $value->company->opening->{strtolower(date('D'))."_start"}." - ".$value->company->opening->{strtolower(date('D'))."_end"};
                $store['rating'] = $value->getRatingScore();
                $store['description'] = $value->description;
                $store['lat'] = $value->lat;
                $store['lng'] = $value->lng;
                $stores[] = $store;
            }
            return Response::json(['result' => 'success', 'msg' => '', 'stores' => $stores], 200);
        } else {
            return Response::json(['result' => 'failed', 'msg' => 'Invalid Request'], 400);
        }
    }
    
    public function offers() {
        if (Input::has('user_id')) {
            $userOffers = UserOfferModel::where('user_id', Input::get('user_id'))->where('is_used', FALSE)->get();
            $offers = [];
            foreach ($userOffers as $key => $value) {
                $offer['name'] = $value->offer->name;
                $offer['photo'] = HTTP_OFFER_PATH.$value->offer->photo;
                $offer['description'] = $value->offer->description;
                $offer['price'] = $value->offer->is_review ? 'By Activity': $value->offer->price;
                $offer['code'] = $value->code;
                $offer['company_name'] = $value->offer->company->name;
                $offer['created_at'] = date(TIME_FORMAT, strtotime($value->created_at));
                $offers[] = $offer;
            }
            return Response::json(['result' => 'success', 'msg' => '', 'offers' => $offers], 200);
        } else {
            return Response::json(['result' => 'failed', 'msg' => 'Invalid Request'], 400);
        }
    }
    
    public function removeCart() {
        if (Input::has('user_id') && Input::has('store_id')) {
            $cart = CartModel::where('store_id', Input::get('store_id'))->where('user_id', Input::get('user_id'));
            if ($cart->count() > 0) {
                $cart->delete();
                return Response::json(['result' => 'success', 'msg' => 'The Store has been removed successfully from your cart.'], 200);
            } else {
                return Response::json(['result' => 'failed', 'msg' => 'The Store is not exist on the cart.'], 200);
            }
        } else {
            return Response::json(['result' => 'failed', 'msg' => 'Invalid Request'], 400);
        }
    }

    public function giveReview() {
        if (Input::has('store_id') && Input::has('user_id') && Input::has('ratings')) {
            $feedback = new FeedbackModel;
            $feedback->store_id = Input::get('store_id');
            $feedback->user_id = Input::get('user_id');
            $feedback->description = Input::has('description') ? Input::get('description') : '';
            $feedback->save();
            foreach (Input::get('ratings') as $key => $value) {
                $rating = new RatingModel;
                $rating->feedback_id = $feedback->id;
                $rating->type_id = $value['type_id'];
                $rating->answer = $value['answer'];
                $rating->save();
            }
            
            $store = StoreModel::find(Input::get('store_id'));
            
            CommonFunctionModel::addConsumer($store->company->id, Input::get('user_id'), 0);
            CommonFunctionModel::addOffer($store->company->id, Input::get('user_id'));
            
            return Response::json(['result' => 'success', 'msg' => 'Review has been provided successfully'], 200);
        } else {
            return Response::json(['result' => 'failed', 'msg' => 'Invalid Request'], 400);
        }
    }
    
    public function uploadPhoto() {
        if (Input::has('store_id') && Input::has('user_id')) {
            
            $filename = str_random(24)."_".date().".jpg";
            file_put_contents( ABS_REVIEW_PATH.$filename, base64_decode( str_replace(" ", "+", Input::get('photo_data'))));

            $reviewPhoto = new ReviewPhotoModel;
            $reviewPhoto->photo = $filename;
            $reviewPhoto->description = Input::get('description');
            $reviewPhoto->store_id = Input::get('store_id');
            $reviewPhoto->user_id = Input::get('user_id');
            $reviewPhoto->save();
            
            $store = StoreModel::find(Input::get('store_id'));
            
            CommonFunctionModel::addConsumer($store->company->id, Input::get('user_id'), 0);
            CommonFunctionModel::addOffer($store->company->id, Input::get('user_id'));
            
            return Response::json(['result' => 'success', 'msg' => 'Photo has been uploaded successfully'], 200);
        } else {
            return Response::json(['result' => 'failed', 'msg' => 'Invalid Request'], 400);
        }        
    }
}