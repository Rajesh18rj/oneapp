<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\CategoryLogic;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class CategoryController extends Controller
{
    public function get_categories(Request $request)
    {
        try {
            $categories = Category::where(['position'=>0,'status'=>1])
            ->when(config('module.current_module_data'), function($query){
                $query->module(config('module.current_module_data')['id']);
            })
            ->orderBy('priority','desc')->get();
            
            $languageName = $request->header('X-Localization');
            
            // $languageName = 'ta';
            if(isset($categories) && !empty($categories)){
               foreach($categories as $key => $value){
		       if($languageName == 'ta'){
		    	   $translatedData = DB::table('translations')->where(array('translationable_type' =>  "App\\Models\\Category", "translationable_id" => $value['id'] , "locale" => "ta"))->first();
		           $categories[$key]['name'] = (isset($translatedData) && !empty($translatedData)) ? $translatedData->value : ''; 
		       }
		       else if($languageName == 'hi'){
		    	   $translatedData = DB::table('translations')->where(array('translationable_type' =>  "App\\Models\\Category", "translationable_id" => $value['id'] , "locale" => "hi"))->first();
		    	   $categories[$key]['name'] = (isset($translatedData) && !empty($translatedData)) ? $translatedData->value : '';
		       } else {
		            $translatedData = DB::table('categories')->where(array("id" => $value['id']))->first();
		            $categories[$key]['name'] = (isset($translatedData) && !empty($translatedData)) ? $translatedData->name : '';
		       }
               
           	}
            
            }
           
            
   
            return response()->json(Helpers::category_data_formatting($categories, true), 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    public function get_childes($id,Request $request)
    {
        try {
            $categories = Category::where(['parent_id' => $id,'status'=>1])->orderBy('priority','desc')->get();
          
              
             
            $languageName = $request->header('X-Localization');
            
            //$languageName = 'ta';

          
       

             foreach($categories as $key => $value){
          
         
		       if($languageName == 'ta'){
		    	   $translatedData = DB::table('translations')->where(array('translationable_type' =>  "App\\Models\\Category", "translationable_id" => $value['id'] , "locale" => "ta"))->first();
		           $categories[$key]['name'] = (isset($translatedData) && !empty($translatedData)) ? $translatedData->value : ''; 
		       }
		       else if($languageName == 'hi'){
		    	   $translatedData = DB::table('translations')->where(array('translationable_type' =>  "App\\Models\\Category", "translationable_id" => $value['id'] , "locale" => "hi"))->first();
		    	   $categories[$key]['name'] = (isset($translatedData) && !empty($translatedData)) ? $translatedData->value : '';
		       } else {
		            $translatedData = DB::table('categories')->where(array("id" => $value['id']))->first();
		            $categories[$key]['name'] = (isset($translatedData) && !empty($translatedData)) ? $translatedData->name : '';
		       }
               
           	}
           
            
            
            return response()->json(Helpers::category_data_formatting($categories, true), 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    public function get_products($id, Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $zone_id= $request->header('zoneId');

        $type = $request->query('type', 'all');

        $data = CategoryLogic::products($id, $zone_id, $request['limit'], $request['offset'], $type);
        $data['products'] = Helpers::product_data_formatting($data['products'] , true, false, app()->getLocale());
        return response()->json($data, 200);
    }


    public function get_stores($id, Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $zone_id= $request->header('zoneId');

        $type = $request->query('type', 'all');

        $data = CategoryLogic::stores($id, $zone_id, $request['limit'], $request['offset'], $type);
        $data['stores'] = Helpers::store_data_formatting($data['stores'] , true);
        return response()->json($data, 200);
    }



    public function get_all_products($id,Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $zone_id= $request->header('zoneId');

        try {
            return response()->json(Helpers::product_data_formatting(CategoryLogic::all_products($id, $zone_id), true, false, app()->getLocale()), 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }
}
