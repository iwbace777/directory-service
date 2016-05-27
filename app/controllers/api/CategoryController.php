<?php namespace Api;

use Illuminate\Routing\Controllers\Controller;
use Input, Response;
use Category as CategoryModel;

class CategoryController extends \BaseController {
    public function index() {
        $categories = CategoryModel::all();
        
        $categoryList = [];
        foreach ($categories as $key => $value) {
            $data['id'] = $value->id;
            $data['name'] = $value->name;
            $subCategoryList = [];
            foreach ($value->subCategories as $subKey => $subValue) {
                $subData['id'] = $subValue->id;
                $subData['name'] = $subValue->name;
                $subCategoryList[] = $subData;
            }
            $data['sub_categories'] = $subCategoryList;
            $categoryList[] = $data;
        }
        
        return Response::json(['result' => 'success', 'msg' => '', 'categories' => $categoryList, ], 200);
    }
}