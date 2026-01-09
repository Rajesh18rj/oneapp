<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\CommodityType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommodityTypeController extends Controller
{


    public function list(Request $request)
    {
        

        $commodity_types = CommodityType::orderBy('id', 'desc')->get();

        return response()->json($commodity_types, 200);
    }
}
