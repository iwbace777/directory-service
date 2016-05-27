<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Offer as OfferModel;
use Company as CompanyModel;

class OfferController extends \BaseController {
    
    public function index() {
        $param['offers'] = OfferModel::purchase()->paginate(PAGINATION_SIZE);
        $param['pageNo'] = 8;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('admin.offer.index')->with($param);
    }
    
    public function create() {
        $param['pageNo'] = 8;
        $param['companies'] = CompanyModel::all();
        return View::make('admin.offer.create')->with($param);
    }
    
    public function edit($id) {
        $param['pageNo'] = 8;
        $param['offer'] = OfferModel::find($id);
        $param['companies'] = CompanyModel::all();
        
        return View::make('admin.offer.edit')->with($param);
    }
    
    public function store() {
        
        $rules = ['name' => 'required',
                  'company_id' => 'required', ];
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
                $offer->photo = DEFAULT_PHOTO;
            }
            $offer->company_id = Input::get('company_id');
            $offer->name = Input::get('name');
            $offer->price = Input::get('price');
            $offer->description = Input::get('description');
            $offer->expire_at = Input::get('expire_at');
            $offer->is_review = FALSE;
            if (Input::has('received')) {
                $offer->received = Input::get('received');
            }
            
            $offer->save();
            
            $alert['msg'] = 'Offer has been saved successfully';
            $alert['type'] = 'success';            
              
            return Redirect::route('admin.offer')->with('alert', $alert);            
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

        return Redirect::route('admin.offer')->with('alert', $alert);
    }
}
