<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Models\Store;
use App\Models\Review;
use App\Models\VehicleType;
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

class VehicleTypeController extends Controller
{
    public function index()
    {
        $categories = Category::where(['position' => 0])->get();
        $logistic_types = LogisticType::all();
        return view('admin-views.vehicle-type.index', compact('categories','logistic_types'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'distance' => 'required',
            'weight' => 'required',
            'price' => 'required',
        ]);



        $item = new VehicleType;
        $item->name = $request->name;
        $item->logistic_type_id = $request->logistic_type_id;
        $item->distance = $request->distance;
        $item->weight = $request->weight;
        $item->price = $request->price;
        $item->save();

        Toastr::success(translate('messages.vehicle_type_deleted_successfully'));

        return redirect()->route('admin.vehicle_type.list');
    }


    public function edit($id)
    {
        $item = VehicleType::find($id);
         $logistic_types = LogisticType::all();

        return view('admin-views.vehicle-type.edit', compact('item','logistic_types'));
    }

    public function status(Request $request)
    {
        $product = Item::withoutGlobalScope(StoreScope::class)->findOrFail($request->id);
        $product->status = $request->status;
        $product->save();
        Toastr::success(translate('messages.item_status_updated'));
        return back();
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'distance' => 'required',
            'weight' => 'required',
        ], []);

        $item = VehicleType::find($id);
        $item->name = $request->name;
        $item->logistic_type_id = $request->logistic_type_id;
        $item->distance = $request->distance;
        $item->weight = $request->weight;
        $item->price = $request->price;
        $item->save();

        Toastr::success(translate('messages.vehicle_type_updated_successfully'));
        return redirect()->route('admin.vehicle_type.list');
    }

    public function delete(Request $request)
    {
        $item = VehicleType::find($request->id);
        $item->delete();
        Toastr::success(translate('messages.vehicle_type_deleted_successfully'));
        return back();
    }

    public function bulk_delete(Request $request)
    {
        // Get item IDs from the request
        $itemIds = $request->item_ids;

        // Check if itemIds is set and not empty
        if (isset($itemIds) && !empty($itemIds)) {
            // Loop through each item ID
            foreach ($itemIds as $key => $value) {
                // Output the item ID (you may remove this line)


                // Find the product using the item ID
                $product = VehicleType::find($value);

                // Delete the product itself
                $product->delete();
            }
        }

        // Display success message using Toastr (adjust based on your implementation)
        Toastr::success(translate('messages.item_deleted_successfully'));

        return json_encode(array('status' => 'true', 'message' => translate('messages.item_deleted_successfully')));

        // Redirect back to the previous page
        return back();
    }




    public function list(Request $request)
    {
        $items = VehicleType::latest()->paginate(config('default_pagination'));
        return view('admin-views.vehicle-type.list', compact('items'));
    }
}
