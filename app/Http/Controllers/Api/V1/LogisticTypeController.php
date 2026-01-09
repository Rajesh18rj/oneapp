<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\LogisticType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LogisticTypeController extends Controller
{


    public function list(Request $request)
    {
        
	$module_type_id = $request->module_type_id;
	
      $logistic_types = LogisticType::whereRaw("FIND_IN_SET('$module_type_id', module_type_id)")
    ->orderBy('id', 'desc')
    ->get();

        return response()->json($logistic_types, 200);
    }
    
    
  
}
