<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Offer as OfferModel, UserOffer as UserOfferModel;

class OfferController extends \BaseController {   
    public function index() {
        $param['pageNo'] = 7;
        $param['purchaseOffers'] = OfferModel::where('company_id', Session::get('company_id'))->purchase()->paginate(PAGINATION_SIZE);
        $param['activityOffers'] = OfferModel::where('company_id', Session::get('company_id'))->activity()->paginate(PAGINATION_SIZE);        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('company.offer.index')->with($param);
    }
    
    public function createPurchase() {
        $param['pageNo'] = 7;
        return View::make('company.offer.createPurchase')->with($param);
    }
    
    public function editPurchase($id) {
        $param['pageNo'] = 7;
        $param['offer'] = OfferModel::find($id);
        return View::make('company.offer.editPurchase')->with($param);
    }
    
    public function createActivity() {
        $param['pageNo'] = 7;
        return View::make('company.offer.createActivity')->with($param);
    }
    
    public function editActivity($id) {
        $param['pageNo'] = 7;
        $param['offer'] = OfferModel::find($id);
        return View::make('company.offer.editActivity')->with($param);
    }
    
    public function store() {
        
        $rules = ['name' => 'required'];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if (Input::has('offer_id')) {
                $id = Input::get('offer_id');
                $offer = OfferModel::find($id);
            } else {
                $offer = new OfferModel;
                if (!Input::hasFile('photo')) {
                    $offer->photo = DEFAULT_PHOTO;
                }                
            }
            
            if (Input::hasFile('photo')) {
                $filename = str_random(24).".".Input::file('photo')->getClientOriginalExtension();
                Input::file('photo')->move(ABS_OFFER_PATH, $filename);
                $offer->photo = $filename;
            }
            
            $offer->company_id = Session::get('company_id');
            $offer->name = Input::get('name');
            $offer->description = Input::get('description');
            if (Input::get('price') != '')
                $offer->price = Input::get('price');
            $offer->expire_at = Input::has('expire_at') ? Input::get('expire_at') : '';
            $offer->is_review = Input::get('is_review');
            $offer->save();
            
            $alert['msg'] = 'Offer has been saved successfully';
            $alert['type'] = 'success';            
              
            return Redirect::route('company.offer')->with('alert', $alert);            
        }
    }
    
    public function delete($id) {
        try {
            OfferModel::find($id)->delete();
            
            $alert['msg'] = 'Offer has been deleted successfully';
            $alert['type'] = 'success';            
        } catch(\Exception $ex) {
            $alert['msg'] = 'This Offer has been already used';
            $alert['type'] = 'danger';
        }

        return Redirect::route('company.offer')->with('alert', $alert);
    }
    
    public function sold($id) {
        $param['pageNo'] = 7;
        $param['soldOffers'] = UserOfferModel::where('offer_id', $id)->paginate(PAGINATION_SIZE);
        return View::make('company.offer.sold')->with($param);        
    }
}
