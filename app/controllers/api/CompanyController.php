<?php namespace Api;

use Illuminate\Routing\Controllers\Controller;
use Input, Response, DB;
use Company as CompanyModel, Feedback as FeedbackModel, ReviewPhoto as ReviewPhotoModel;

class CompanyController extends \BaseController {
    public function featured() {
        $companies = CompanyModel::completed()->orderBy(DB::raw('RAND()'))->take(8)->get();
        $companyList = [];
        foreach ($companies as $key => $value) {
            $company['id'] = $value->id;
            $company['name'] = $value->name;
            $company['photo'] = HTTP_COVER_PATH.$value->detail->cover_photo;
            $company['address'] = $value->detail->address;
            $company['zip_code'] = $value->detail->zip_code;
            $company['city_name'] = $value->city->name;
            $company['phone'] = $value->detail->phone;
            $company['opening_time'] = $value->opening->{strtolower(date('D'))."_start"}." - ".$value->opening->{strtolower(date('D'))."_end"};
            $company['rating'] = $value->getRatingScore();
            $companyList[] = $company;
        }        
        return Response::json(['result' => 'success', 'msg' => '', 'companies' => $companyList, ], 200);                
    }
    
    public function search() {
        $keyword = Input::has('keyword') ? Input::get('keyword') : '';
        $location = Input::has('location') ? Input::get('location') : '';
        $lat = Input::has('lat') ? Input::get('lat') : '';
        $lng = Input::has('lng') ? Input::get('lng') : '';
        
        $result = CompanyModel::completed()->search($keyword, $location);
        $tblCompany =with(new CompanyModel)->getTable();
        $companies = $result->groupBy($tblCompany.'.id')->get();
        
        $companyList = [];
        foreach ($companies as $key => $value) {
            $company['id'] = $value->id;
            $company['name'] = $value->name;
            $company['sub_name'] = $value->sub_name;
            $company['phone'] = $value->phone;
            $company['address'] = $value->address;
            $company['zip_code'] = $value->zip_code;
            $company['city_name'] = $value->city_name;
            $company['photo'] = HTTP_COVER_PATH.$value->cover_photo;
            $company['opening_time'] = $value->{strtolower(date('D'))."_start"}." - ".$value->{strtolower(date('D'))."_end"};
            $company['rating'] = $value->getRatingScore();
            $company['description'] = $value->description;
            $company['lat'] = $value->lat;
            $company['lng'] = $value->lng;
            $companyList[] = $company;
        }
        return Response::json(['result' => 'success', 'msg' => '', 'companies' => $companyList, ], 200);
    }
    
    public function detail() {
        if (Input::has('company_id')) {
            $company = CompanyModel::find(Input::get('company_id'));
            
            if (Input::has('user_id') && FeedbackModel::where('user_id', Input::get('user_id'))
                                ->where('company_id', Input::get('company_id'))->get()->count() > 0) {
                $is_rated = TRUE;
            } else {
                $is_rated = FALSE;
            }
            
            if (Input::has('user_id') && ReviewPhotoModel::where('user_id', Input::get('user_id'))
                                ->where('company_id', Input::get('company_id'))->get()->count() > 0) {
                $is_photoed = TRUE;
            } else {
                $is_photoed = FALSE;
            }
            
            $opening = [];
            foreach ([
                    'mon' => 'Monday',
                    'tue' => 'Tuesday',
                    'wed' => 'Wednesday',
                    'thu' => 'Thurday',
                    'fri' => 'Friday',
                    'sat' => 'Saturday',
                    'sun' => 'Sunday',
                ] as $key => $value) {
                $opening[$key."_start"] = $company->opening->{$key."_start"};
                $opening[$key."_end"] = $company->opening->{$key."_end"};                
            }
            
            $comments = [];
            foreach ($company->comments as $key => $value) {
                $comment['user_name'] = $value->user->name;
                $comment['user_photo'] = HTTP_USER_PATH.$value->user->photo;
                $comment['description'] = $value->description;
                $comment['created_at'] = date(TIME_FORMAT, strtotime($value->created_at));
                $comments[] = $comment;
            }
            
            $categories = [];
            foreach ($company->subCategories as $key => $value) {
                $category['name'] = $value->subCategory->name;
                $categories[] = $category;
            }
            
            $photos = [];
            foreach ($company->photos as $key => $value) {
                $photo['photo'] = HTTP_REVIEW_PATH.$value->photo;
                $photo['user_name'] = $value->user->name;
                $photo['description'] = $value->description;
                $photo['created_at'] = date(TIME_FORMAT, strtotime($value->created_at));
                $photos[] = $photo;
            }
            
            $offers = [];
            foreach ($company->offers as $key => $value) {
                $offer['name'] = $value->name;
                $offer['photo'] = HTTP_OFFER_PATH.$value->photo;
                $offer['description'] = $value->description;
                $offer['price'] = $value->is_review ? 'Review' : $value->price;
                $offers[] = $offer;
            }
            
            $loyalties = [];
            foreach ($company->loyalties as $key => $value) {
                $loyalty['name'] = $value->name;
                $loyalty['photo'] = HTTP_LOYALTY_PATH.$value->photo;
                $loyalty['description'] = $value->description;
                $loyalty['count_visit'] = $value->count_visit;
                $loyalties[] = $loyalty;
            }
            
            $feedbacks = [];
            foreach ($company->feedbacks as $key => $value) {
                $feedback['user_name'] = $value->user->name;
                $feedback['user_photo'] = HTTP_USER_PATH.$value->user->photo;
                $feedback['description'] = $value->description;
                $feedback['score'] = round($value->ratings()->avg('val'), 1);
                $feedbacks[] = $feedback;
            }

            return Response::json(['result' => 'success', 'msg' => '',
                            'is_rated' => $is_rated, 'is_photoed' => $is_photoed,
                            'name' => $company->name, 'city_name' => isset($company->city_id) ? $company->city->name : '---',
                            'email' => $company->email, 'phone' => $company->detail->phone, 
                            'vat_id' => $company->detail->vat_id, 'zip_code' => $company->detail->zip_code,
                            'address' => $company->detail->address, 'revenue' => $company->detail->revenue,
                            'employs' => $company->detail->employs, 'year' => $company->detail->year, 'website' => $company->detail->website,
                            'sub_name' => $company->detail->sub_name, 'photo' => HTTP_COVER_PATH.$company->detail->cover_photo,
                            'description' => $company->detail->description, 'keyword' => $company->keyword,
                            'lat' => $company->detail->lat, 'lng' => $company->detail->lng,
                            'opening' => $opening, 'categories' => $categories, 'comments' => $comments,
                            'reviews' => $feedbacks, 'photos' => $photos, 'service' => ['offers' => $offers, 'loyalties' => $loyalties],
                            ], 200);
        } else {
            return Response::json(['result' => 'failed', 'msg' => 'Invalid Request'], 400);
        }        
    }
    
    public function reviewTypes() {
        if (Input::has('company_id')) {
            $company = CompanyModel::find(Input::get('company_id'));
            $types = [];
            foreach ($company->ratingTypes as $key => $value) {
                $type['id'] = $value->id;
                $type['name'] = $value->name;
                $types[] = $type;
            }
            return Response::json(['result' => 'success', 'msg' => '', 'types' => $types, ], 200);
        } else {
            return Response::json(['result' => 'failed', 'msg' => 'Invalid Request'], 400);
        }
    }
}