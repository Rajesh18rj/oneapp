<?php

namespace App\Http\Controllers\DeliveryAgent;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Models\Store;
use App\Models\Review;
use App\Models\LogisticType;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
use App\CentralLogics\Helpers;
use App\CentralLogics\ProductLogic;
use App\Models\ItemCampaign;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;
use App\Scopes\StoreScope;
use App\Models\Translation;

class LogisticTypeController extends Controller
{
    public function index()
    {
        $logisitic_types = LogisticType::get();
        return view('admin-views.logistic-type.index', compact('logisitic_types'));
    }

    public function store(Request $request)
    {
    
      $request->validate([
            'name' => 'required|unique:logistic_types'
        ]);
    
    
    	


        $item = new LogisticType;
        $item->name = $request->name;
        $item->save();

        Toastr::success(translate('messages.logistic_type_added_successfully'));

        return redirect()->route('admin.logistic_type.list');
    }


    public function edit($id)
    {
        $item = LogisticType::find($id);

        return view('admin-views.logistic-type.edit', compact('item'));
    }

    public function status(Request $request)
    {
        $product = LogisticType::withoutGlobalScope(StoreScope::class)->findOrFail($request->id);
        $product->status = $request->status;
        $product->save();
        Toastr::success(translate('messages.item_status_updated'));
        return back();
    }

    public function update(Request $request, $id)
    {
   
        $request->validate([
             'name' => 'required|unique:logistic_types,name,'.$id,

        ]);

        $item = LogisticType::find($id);
        $item->name = $request->name;
        $item->save();
        
     

        Toastr::success(translate('messages.logistic_type_updated_successfully'));
        return redirect()->route('admin.logistic_type.list');
    }

    public function delete(Request $request)
    {
        $item = LogisticType::find($request->id);
        $item->delete();
        Toastr::success(translate('messages.logistic_type_deleted_successfully'));
        return back();
    }

    public function bulk_delete(Request $request)
    {
        // Get logistic IDs from the request
        $logisticIds = $request->logistic_ids;
        
      /*  echo "<pre>";
        print_r($logisticIds);
        die;*/

        // Check if logisticIds is set and not empty
        if (isset($logisticIds) && !empty($logisticIds)) {
            // Loop through each ID
            foreach ($logisticIds as $key => $value) {

                $lType = LogisticType::find($value);

                // Delete the lType itself
                $lType->delete();
            }
        }

        // Display success message using Toastr (adjust based on your implementation)
        Toastr::success(translate('messages.logistic_type_deleted_successfully'));

        return json_encode(array('status' => 'true', 'message' => translate('messages.logistic_type_deleted_successfully')));

        // Redirect back to the previous page
        return back();
    }




    public function list(Request $request)
    {
        $logisitic_types = LogisticType::latest()->paginate(config('default_pagination'));
        return view('admin-views.logistic-type.list', compact('logisitic_types'));
    }
}
