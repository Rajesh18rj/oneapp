<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = Unit::paginate(config('default_pagination'));
        return view('admin-views.unit.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'unit' => 'required|unique:units,unit'
        ]);

        $unit = new Unit;
        $unit->unit = $request->unit;
        $unit->save();

        Toastr::success(translate('messages.unit_added_successfully'));
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $unit = Unit::findOrFail($id);

        return view('admin-views.unit.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'unit' => 'required|unique:units,unit,' . $id,
        ]);
        $unit = Unit::findOrFail($id);
        $unit->unit = $request->unit;
        $unit->save();

        Toastr::success(translate('messages.unit_updated_successfully'));
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


                // Find the item using the item ID
                $item = Unit::find($value);

                // Delete the item itself
                $item->delete();
            }
        }

        // Display success message using Toastr (adjust based on your implementation)
        Toastr::success(translate('messages.item_deleted_successfully'));

        return json_encode(array('status' => 'true', 'message' => translate('messages.item_deleted_successfully')));

        // Redirect back to the previous page
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        Toastr::success(translate('messages.unit_deleted_successfully'));
        return back();
    }
}
