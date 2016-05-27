<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Category extends Eloquent implements SluggableInterface {
    use SluggableTrait;
    
    protected $table = 'category';
    
    protected $sluggable = array(
        'build_from' => 'name',
        'save_to'    => 'slug',
    );

    public function subCategories() {
        return $this->hasMany('SubCategory', 'category_id');
    }

    public function companies() {
        return $this->hasMany('CompanySubCategory', 'category_id');
    }

    public function stores() {
        return $this->hasMany('StoreSubCategory', 'category_id');
    }    
    
}
