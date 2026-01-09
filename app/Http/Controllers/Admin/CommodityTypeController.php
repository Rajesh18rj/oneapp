<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Models\Store;
use App\Models\Review;
use App\Models\CommodityType;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
use App\CentralLogics\Helpers;
use App\CentralLogics\ProductLogic;
use App\Models\ItemCampaign;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;
use App\Scopes\StoreScope;
use App\Models\Translation;

class CommodityTypeController extends Controller
{
    public function index()
    {
        $commodity_types = CommodityType::get();
        return view('admin-views.commodity-type.index', compact('commodity_types'));
    }

    public function store(Request $request)
    {
    
      $request->validate([
            'name' => 'required|unique:commodity_types'
        ]);
    
    
    	


        $item = new CommodityType;
        $item->name = $request->name;
        $item->save();

        Toastr::success(translate('messages.commodity_type_added_successfully'));

        return redirect()->route('admin.commodity_type.list');
    }


    public function edit($id)
    {
        $item = CommodityType::find($id);

        return view('admin-views.commodity-type.edit', compact('item'));
    }

    public function status(Request $request)
    {
        $product = CommodityType::withoutGlobalScope(StoreScope::class)->findOrFail($request->id);
        $product->status = $request->status;
        $product->save();
        Toastr::success(translate('messages.item_status_updated'));
        return back();
    }

    public function update(Request $request, $id)
    {
   
        $request->validate([
             'name' => 'required|unique:commodity_types,name,'.$id,

        ]);

        $item = CommodityType::find($id);
        $item->name = $request->name;
        $item->save();
        
     

        Toastr::success(translate('messages.commodity_type_updated_successfully'));
        return redirect()->route('admin.commodity_type.list');
    }

    public function delete(Request $request)
    {
        $item = CommodityType::find($request->id);
        $item->delete();
        Toastr::success(translate('messages.commodity_type_deleted_successfully'));
        return back();
    }

    public function bulk_delete(Request $request)
    {
        // Get commodity IDs from the request
        $commodityIds = $request->commodity_ids;
        
      /*  echo "<pre>";
        print_r($commodityIds);
        die;*/

        // Check if commodityIds is set and not empty
        if (isset($commodityIds) && !empty($commodityIds)) {
            // Loop through each ID
            foreach ($commodityIds as $key => $value) {

                $lType = CommodityType::find($value);

                // Delete the lType itself
                $lType->delete();
            }
        }

        // Display success message using Toastr (adjust based on your implementation)
        Toastr::success(translate('messages.commodity_type_deleted_successfully'));

        return json_encode(array('status' => 'true', 'message' => translate('messages.commodity_type_deleted_successfully')));

        // Redirect back to the previous page
        return back();
    }




    public function list(Request $request)
    {
        $commodity_types = CommodityType::latest()->paginate(config('default_pagination'));
        return view('admin-views.commodity-type.list', compact('commodity_types'));
    }
}
