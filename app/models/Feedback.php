<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Feedback extends Eloquent {
    
    protected $table = 'feedback';
    
    public function ratings()
    {
        return $this->hasMany('Rating', 'feedback_id');
    }
    
    public function scoreRatings()
    {
        $tblRatingType =with(new RatingType)->getTable();
        $tblRating =with(new Rating)->getTable();
        return $this->hasMany('Rating', 'feedback_id')
                ->leftJoin($tblRatingType, $tblRatingType.'.id', '=', $tblRating.'.type_id')
                ->where($tblRatingType.'.is_score', TRUE)
                ->where($tblRatingType.'.is_visible', TRUE);
    }    
    
    public function messages()
    {
        return $this->hasMany('\DirectoryService\Models\Message', 'feedback_id')
                    ->orderBy('id', 'DESC');
    }    
    
    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }

    public function store()
    {
        return $this->belongsTo('Store', 'store_id');
    }
    
    public function getTypeScore($typeId) {
        $prefix = DB::getTablePrefix();
        
        $tblRating =with(new Rating)->getTable();
        $tblRatingType =with(new RatingType)->getTable();

        $feedbackId = $this->id;
        
        $sql = "SELECT IFNULL(AVG(answer), -1) AS avgScore
                  FROM ".$prefix.$tblRating."
                 WHERE type_id = $typeId
                   AND feedback_id = $feedbackId";
        $result = DB::select($sql);
        $score = $result[0]->avgScore;
        
        $sql = "SELECT is_score
                  FROM ".$prefix.$tblRatingType."
                 WHERE id = $typeId";
        $resultType = DB::select($sql);
        if ($resultType[0]->is_score && $score == -1) {
            $score = 0;
        }
        return $score;
    }
    
    public function scopeAvgReview($query) {
        $tblRating =with(new Rating)->getTable();
        $tblRatingType =with(new RatingType)->getTable();

        $result =  $query->select($this->table.'.*', DB::raw("AVG(answer) as avgReview"))
                          ->join($tblRating, $tblRating.'.feedback_id', '=', $this->table.'.id')
                          ->join($tblRatingType, function($join) use ($tblRatingType, $tblRating) {
                              $join->on($tblRatingType.'.id', '=', $tblRating.'.type_id')
                                   ->where($tblRatingType.'.is_score', '=', TRUE)
                                   ->where($tblRatingType.'.is_visible', '=', TRUE);
                          })
                          ->groupBy($this->table.'.user_id', $this->table.'.store_id');
        return $result;
    }
}
