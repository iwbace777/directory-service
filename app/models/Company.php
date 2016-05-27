<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Company extends Eloquent implements SluggableInterface {
    use SluggableTrait;
    
    protected $table = 'company';
    
    protected $sluggable = array(
        'build_from' => 'name',
        'save_to'    => 'slug',
    );    

    public function opening()
    {
        return $this->hasOne('CompanyOpening', 'company_id');
    }
    
    public function plan()
    {
        return $this->belongsTo('Plan', 'plan_id');
    }    
    
    public function widget()
    {
        return $this->hasOne('CompanyWidget', 'company_id');
    }    

    public function subCategories()
    {
        return $this->hasMany('CompanySubCategory', 'company_id');
    }
    
    public function messages()
    {
        return $this->hasMany('\DirectoryService\Models\Message', 'company_id')
                    ->orderBy('id', 'DESC');
    }        
    
    public function stores()
    {
        return $this->hasMany('Store', 'company_id');
    }
    
    public function contacts()
    {
        return $this->hasMany('Contact', 'company_id');
    }    
    
    public function ratingTypes()
    {
        return $this->hasMany('RatingType', 'company_id');
    }

    public function visibleRatingTypes()
    {
        $tblRatingType =with(new RatingType)->getTable();
        return $this->hasMany('RatingType', 'company_id')->where($tblRatingType.'.is_visible', TRUE);
    }

    public function offers()
    {
        return $this->hasMany('Offer', 'company_id');
    }
    
    public function purchaseOffers()
    {
        $tblOffer =with(new Offer)->getTable();
        return $this->hasMany('Offer', 'company_id')->where($tblOffer.'.is_review', FALSE);
    }
    
    public function availableOffers()
    {
        $tblOffer =with(new Offer)->getTable();
        return $this->hasMany('Offer', 'company_id')
            ->where($tblOffer.'.is_review', FALSE)
            ->where($tblOffer.'.expire_at', '>=', date('Y-m-d'));
    }    
    
    public function loyalties()
    {
        return $this->hasMany('Loyalty', 'company_id');
    }
    
    public function consumers()
    {
        return $this->hasMany('Consumer', 'company_id');
    }
    
    public function scopeCompleted($query) {
        return $query->where('is_completed', TRUE);
    }
    
    public function feedbacks($year = '2015', $month = '04') {
        $tblFeedback =with(new Feedback)->getTable();
        return $this->hasManyThrough('Feedback', 'Store', 'company_id', 'store_id')
                    ->where($tblFeedback.'.created_at', '>=', "$year-$month-01 00:00:00")
                    ->where($tblFeedback.'.created_at', '<=', "$year-$month-31 23:59:59");
    }
    
    public function periodFeedbacks($startDate, $endDate) {
        $tblFeedback =with(new Feedback)->getTable();
        return $this->hasManyThrough('Feedback', 'Store', 'company_id', 'store_id')
                    ->where($tblFeedback.'.created_at', '>=', "$startDate 00:00:00")
                    ->where($tblFeedback.'.created_at', '<=', "$endDate 23:59:59");
    }
    

    public function registers($startDate, $endDate) {
        $tblConsumer = with(new Consumer)->getTable();
        return $this->belongsToMany('User', 'consumer', 'company_id', 'user_id')
                    ->where($tblConsumer.'.created_at', '>=', "$startDate 00:00:00")
                    ->where($tblConsumer.'.created_at', '<=', "$endDate 23:59:59");
    }    
}
