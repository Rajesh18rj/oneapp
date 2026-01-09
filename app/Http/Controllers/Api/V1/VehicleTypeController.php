<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleTypeController extends Controller
{


    public function list(Request $request)
    {
        

        $vehicle_types = VehicleType::orderBy('id', 'desc')->get();
        return response()->json($vehicle_types, 200);
    }
    
    public function getVehicleTypesByLogisiticTypeId($logistic_type_id,Request $request)
    {
      

        $vehicle_types = VehicleType::where('logistic_type_id',$logistic_type_id)->orderBy('id', 'desc')->get();

        return response()->json($vehicle_types, 200);
    }
}
