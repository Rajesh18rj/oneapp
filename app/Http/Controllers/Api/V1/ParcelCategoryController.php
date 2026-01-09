<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\ParcelCategory;
use Illuminate\Http\Request;

class ParcelCategoryController extends Controller
{
    public function index(Request $request){
        try {
            $parcel_categories = ParcelCategory::
            when(config('module.current_module_data'), function($query){
                $query->module(config('module.current_module_data')['id']);
            })
            ->active()->get();
            $parcel_categories=Helpers::parcel_category_data_formatting($parcel_categories, true);
            return response()->json($parcel_categories, 200);
        } catch (\Exception $e) {
            info($e);
            return response()->json([], 200);
        }
    }
    
    
    public function getParcelCategoryDetail(Request $request){
        try {
           $parcel_category_id = $request->get('parcel_category_id');
           
        
           $detail = ParcelCategory::where('id',$parcel_category_id)->first();
           return response()->json($detail, 200);
        } catch (\Exception $e) {
            info($e);
            return response()->json([], 200);
        }
        
    }
}
