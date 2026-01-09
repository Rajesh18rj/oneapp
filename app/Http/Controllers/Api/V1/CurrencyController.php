<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Currency;
use App\CentralLogics\BannerLogic;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{    
    public function index(){
        return $this->successResponse("All Currencies", Currency::where('status', 1)->get());
    }

//     public function store(Request $request){

//         $currency = Currency::find($request->currency_id);

//         auth()->user()->update([

//         ]);

//         return
//     }
}