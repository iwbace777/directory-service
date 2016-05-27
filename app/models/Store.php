<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Store extends Eloquent implements SluggableInterface {
    use SluggableTrait;
    
    protected $table = 'store';
    
    protected $sluggable = array(
        'build_from' => 'name',
        'save_to'    => 'slug',
    );
    
    public function city()
    {
        return $this->belongsTo('City', 'city_id');
    }     
    
    public function company()
    {
        return $this->belongsTo('Company', 'company_id');
    }    

    public function subCategories()
    {
        return $this->hasMany('StoreSubCategory', 'store_id');
    }
    
    public function feedbacks()
    {
        return $this->hasMany('Feedback', 'store_id')->orderBy('created_at', 'DESC');
    }

    public function getRatingScore() {
        $prefix = DB::getTablePrefix();
        
        $tblFeedback = with(new Feedback)->getTable();
        $tblRating = with(new Rating)->getTable();
        $tblRatingType = with(new RatingType)->getTable();
        
        $sql = "SELECT IFNULL(AVG(t3.answer), 0) AS avgScore
                  FROM ".$prefix."$this->table as t1, ".$prefix."$tblFeedback t2, ".$prefix."$tblRating t3, ".$prefix."$tblRatingType t4
                 WHERE t1.id = t2.store_id
                   AND t2.id = t3.feedback_id
                   AND t3.type_id = t4.id
                   AND t4.is_score
                   AND t1.id = $this->id";
        
        $result = DB::select($sql);
        return $result[0]->avgScore;
    }
    
    public function scopeReviewScore($query) {
        $tblFeedback = with(new Feedback)->getTable();
        $tblRating =with(new Rating)->getTable();
        $tblRatingType =with(new RatingType)->getTable();
        
        $result = $query->select($this->table.'.*', DB::raw("AVG(answer) as avgReview"))
                         ->leftJoin($tblFeedback, $tblFeedback.'.store_id', '=', $this->table.'.id')
                         ->leftJoin($tblRating, $tblRating.'.feedback_id', '=', $tblFeedback.'.id')
                         ->leftJoin($tblRatingType, $tblRatingType.'.id', '=', $tblRating.'.type_id')
                         ->where($tblRatingType.'.is_score', '=', TRUE)
                         ->where($tblRatingType.'.is_visible', '=', TRUE)
                         ->groupBy($this->table.'.id');
        return $result;
    }
    
    public function scopeSimilar($query) {
        $storeId = $this->id;
        
        $prefix = DB::getTablePrefix();
        
        $tblStoreSubCategory =with(new StoreSubCategory)->getTable();
        $tblCategory = with(new Category)->getTable();
        $tblSubCategory = with(new SubCategory)->getTable();
        $tblCity = with(new City)->getTable();
        
        $sql = "SELECT sub_category_id 
                  FROM ".$prefix.$tblStoreSubCategory."
                 WHERE store_id = $storeId";
        $subCategoreis = DB::select($sql);
        
        $subCategoryIds = [];
        $subCategoryIds[] = 0;
        foreach ($subCategoreis as $key => $value) {
            $subCategoryIds[] = $value->sub_category_id;
        }
        
        return $query->select($this->table.'.*')
                        ->leftJoin($tblStoreSubCategory, $tblStoreSubCategory.'.store_id', '=', $this->table.'.id')
                        ->leftJoin($tblCategory, $tblStoreSubCategory.'.category_id', '=', $tblCategory.'.id')
                        ->leftJoin($tblSubCategory, $tblStoreSubCategory.'.sub_category_id', '=', $tblSubCategory.'.id')
                        ->leftJoin($tblCity, $this->table.'.city_id', '=', $tblCity.'.id')
                        ->whereIn('sub_category_id', $subCategoryIds)
                        ->where($this->table.'.id', '<>', $storeId)
                        ->groupBy($this->table.'.id')
                        ->orderBy(DB::raw('rand()'))
                        ->take(6)
                        ->get();
    }

    public function scopeSearch($query, $keyword, $location, $lat = 0, $lng = 0)
    {
        $prefix = DB::getTablePrefix();
        
        $tblFeedback = with(new Feedback)->getTable();
        $tblRating =with(new Rating)->getTable();
        $tblRatingType =with(new RatingType)->getTable();      
        $tblStoreSubCategory =with(new StoreSubCategory)->getTable();
        $tblCategory = with(new Category)->getTable();
        $tblSubCategory = with(new SubCategory)->getTable();
        $tblCity = with(new City)->getTable();            
        
        $result =  $query->select($this->table.'.*', DB::raw(pow(($this->table.".lat" - $lat), 2) + pow(($this->table.".lng" - $lng), 2)." as distance, AVG(answer) as avgReview"))
                        ->leftJoin($tblStoreSubCategory, $tblStoreSubCategory.'.store_id', '=', $this->table.'.id')
                        ->leftJoin($tblCategory, $tblStoreSubCategory.'.category_id', '=', $tblCategory.'.id')
                        ->leftJoin($tblSubCategory, $tblStoreSubCategory.'.sub_category_id', '=', $tblSubCategory.'.id')
                        ->leftJoin($tblCity, $this->table.'.city_id', '=', $tblCity.'.id')
                        ->leftJoin($tblFeedback, $tblFeedback.'.store_id', '=', $this->table.'.id')
                        ->leftJoin($tblRating, $tblRating.'.feedback_id', '=', $tblFeedback.'.id')
                        ->leftJoin($tblRatingType, function($join) use ($tblRatingType, $tblRating) {
                            $join->on($tblRatingType.'.id', '=', $tblRating.'.type_id')
                                 ->where($tblRatingType.'.is_score', '=', TRUE)
                                 ->where($tblRatingType.'.is_visible', '=', TRUE);                            
                        });

        if ($keyword != '') {
            $result = $result->where(function($query) use ($keyword, $tblCategory, $tblSubCategory) {
                $query->where($tblCategory.'.name', 'like', '%'.$keyword.'%')
                    ->orWhere($tblSubCategory.'.name', 'like', '%'.$keyword.'%')
                    ->orWhere($this->table.'.name', 'like', '%'.$keyword.'%')
                    ->orWhere($this->table.'.keyword', 'like', '%'.$keyword.'%');
            });
        }

        if ($location != '') {
            $result = $result->where($tblCity.'.name', 'like', '%'.$location.'%');
        }
        
        $result = $result->groupBy($this->table.'.id');
        
        return $result;
    }
    
    public function scopePeriodFeedback($query, $year = '2015', $month = '04') {
        $start = $year."-".$month."-01 00:00:00";
        $end = $year."-".$month."-31 23:59:59";
        
        return $this->hasMany('Feedback', 'store_id')
                ->where('created_at', '>=', $start)
                ->where('created_at', '<=', $end)
                ->orderBy('created_at', 'DESC');
    }
    
    public function scopeProvidedFeedbacks($query, $userId) {
        $tblFeedback =with(new Feedback)->getTable();
        return $this->hasMany('Feedback', 'store_id')->where($tblFeedback.'.user_id', $userId);        
    }
}
