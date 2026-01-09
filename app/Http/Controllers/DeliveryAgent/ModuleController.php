<?php

namespace App\Http\Controllers\DeliveryAgent;

use App\CentralLogics\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modules = Module::withCount('stores')->latest()->paginate(config('default_pagination'));
        
        return view('delivery-agent-views.module.index',compact('modules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('delivery-agent-views.module.create');
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
            'module_name' => 'required|unique:modules|max:100',
            'module_type'=>'required',
            'theme'=>'required_unless:module_type,parcel',
        ], [
            'module_name.required' => translate('messages.Name is required!'),
        ]);
        
        
        $uploadedThumbnail = $request->file('thumbnail');       
	$thumbnailHash =  $request->has('thumbnail') ? md5(file_get_contents($uploadedThumbnail->getRealPath())) : 'thumbh_hash';

	$uploadedIcon = $request->file('icon');       
	$iconHash = $request->has('icon') ? md5(file_get_contents($uploadedIcon->getRealPath())) : 'icon_hash';

	// Check if the hash values are the same
	if ($thumbnailHash == $iconHash) {
	    Toastr::error(translate('Icon and thumbnail should not be allowed to upload the same image.'));
	    return back();
	}

        $module = new Module();
        $module->module_name = $request->module_name[array_search('en', $request->lang)];
        $module->icon = Helpers::upload('module/', 'png', $request->file('icon'));
        $module->thumbnail = Helpers::upload('module/', 'png', $request->file('thumbnail'));
        $module->module_type= $request->module_type;
        $module->theme_id= $request->theme??1;
        $module->thumbnail_hash = $thumbnailHash;
        $module->icon_hash = $iconHash;
        $module->description= $request->description[array_search('en', $request->lang)];
        $module->all_zone_service = $request->all_zone_service??false;
        $module->save();

        $data = [];
        foreach($request->lang as $index=>$key)
        {
            if($request->module_name[$index] && $key != 'en')
            {
                array_push($data, Array(
                    'translationable_type'  => 'App\Models\Module',
                    'translationable_id'    => $module->id,
                    'locale'                => $key,
                    'key'                   => 'module_name',
                    'value'                 => $request->module_name[$index],
                ));
            }
            if ($request->description[$index] && $key != 'en') {
                array_push($data, array(
                    'translationable_type' => 'App\Models\Module',
                    'translationable_id' => $module->id,
                    'locale' => $key,
                    'key' => 'description',
                    'value' => $request->description[$index],
                ));
            }
        }
        if(count($data))
        {
            Translation::insert($data);
        }

        Toastr::success(translate('messages.module_updated_successfully'));
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
        $module = Module::findOrFail($id);
        return response()->json(['data'=>config('module.'.$module->module_type)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(env('APP_MODE')=='demo' && in_array($id, [1,2,3,4,5]))
        {
            Toastr::warning(translate('messages.you_can_not_edit_this_module_please_add_a_new_module_to_edit'));
            return back();
        }

        $module = Module::withoutGlobalScope('translate')->findOrFail($id);
        return view('delivery-agent-views.module.edit', compact('module'));
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
        if(env('APP_MODE')=='demo' && in_array($id, [1,2,3,4,5]))
        {
            Toastr::warning(translate('messages.you_can_not_edit_this_module_please_add_a_new_module_to_edit'));
            return back();
        }

        $request->validate([
            'module_name' => 'required|max:100|unique:modules,module_name,'.$id,
            'theme'=>'required_unless:module_type,parcel',
        ], [
            'module_name.required' => translate('messages.Name is required!'),
        ]);
        $module = Module::withoutGlobalScope('translate')->findOrFail($id);
        
        $uploadedThumbnail = $request->file('thumbnail');       
	$thumbnailHash =  $request->has('thumbnail') ? md5(file_get_contents($uploadedThumbnail->getRealPath())) : $module->thumbnail_hash;

	$uploadedIcon = $request->file('icon');       
	$iconHash = $request->has('icon') ? md5(file_get_contents($uploadedIcon->getRealPath())) : $module->icon_hash;

	// Check if the hash values are the same
	if ($thumbnailHash == $iconHash) {
	    Toastr::error(translate('Icon and thumbnail should not be allowed to upload the same image.'));
	    return back();
	}

        $module->module_name = $request->module_name[array_search('en', $request->lang)];
        $module->icon = $request->has('icon') ? Helpers::update('module/', $module->icon, 'png', $request->file('icon')) : $module->icon;
        $module->thumbnail = $request->has('thumbnail') ? Helpers::update('module/', $module->thumbnail, 'png', $request->file('thumbnail')) : $module->thumbnail;
        $module->theme_id= $request->theme??1;        
        $module->thumbnail_hash = $thumbnailHash;
        $module->icon_hash = $iconHash;       
        $module->description =  $request->description[array_search('en', $request->lang)];
        $module->all_zone_service = $request->all_zone_service ?? false;
        $module->save();
        foreach($request->lang as $index=>$key)
        {
            if($request->module_name[$index] && $key != 'en')
            {
                Translation::updateOrInsert(
                    ['translationable_type'  => 'App\Models\Module',
                        'translationable_id'    => $module->id,
                        'locale'                => $key,
                        'key'                   => 'module_name'],
                    ['value'                 => $request->module_name[$index]]
                );
            }
            if ($request->description[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Models\Module',
                        'translationable_id' => $module->id,
                        'locale' => $key,
                        'key' => 'description'],
                    ['value' => $request->description[$index]]
                );
            }
        }
        Toastr::success(translate('messages.module_updated_successfully'));
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
        $module = Module::withoutGlobalScope('translate')->findOrFail($id);
        if($module->thumbnail)
        {
            if (Storage::disk('public')->exists('module/' . $module['thumbnail'])) {
                Storage::disk('public')->delete('module/' . $module['thumbnail']);
            }
        }
        $module->translations()->delete();
        $module->delete();
        Toastr::success(translate('messages.module_deleted_successfully'));
        return back();
    }

    public function status(Request $request)
    {
        if(env('APP_MODE')=='demo' && in_array($request->id, [1,2,3,4,5]))
        {
            Toastr::warning(translate('messages.you_can_not_edit_this_module_please_add_a_new_module_to_edit'));
            return back();
        }
        $module = Module::find($request->id);
        $module->status = $request->status;
        $module->save();
        Toastr::success(translate('messages.module_status_updated'));
        return back();
    }
    
    
    public function sendModuleAssignRequest(Request $request)
    {
        if(env('APP_MODE')=='demo' && in_array($request->id, [1,2,3,4,5]))
        {
            Toastr::warning(translate('messages.you_can_not_edit_this_module_please_add_a_new_module_to_edit'));
            return back();
        }
        // Get the authenticated delivery men's user ID
	$request_by = auth('delivery_men')->user()->id;
	$request_id = $request->id;
	$request_status = $request->status;
	
	
	// Check if the record already exists
	$existingRecord = DB::table('module_request_status')
	    ->where('module_id', $request_id)->where('request_status', $request_status)->where('request_by', $request_by)
	    ->first();

	// If the record doesn't exist, insert a new one
	if (!$existingRecord) {
		DB::table('module_request_status')->insert([
		    'module_id' => $request_id,
		    'request_status' => $request_status,
		    'request_by' => $request_by
		]);
		
		Toastr::success(translate('Request has been send to admin succesfully'));

	} else {
	
	
	
		if($existingRecord->is_approve == '1'){

		   Toastr::error(translate('Request is already approved by admin'));
		} else {

		     Toastr::error(translate('Request is already send to admin'));
		}
				
		
	}

        

        return back();
    }
    
     public function sendBulkModuleAssignRequest(Request $request){
	    // Get item IDs from the request
	    $itemIds = $request->item_ids;
	    $itemStatuses = $request->itemStatuses;
	     
	  

	    // Check if itemIds is set and not empty
	    if (isset($itemIds) && !empty($itemIds)) {
	    	// Loop through each item ID
		foreach ($itemIds as $key => $value) {
			$request_by = auth('delivery_men')->user()->id;
			$request_id = $value;
		
			$request_status = $itemStatuses[$key];
			
			
			$existingRecord = DB::table('module_request_status')->where('module_id', $request_id)->where('request_status', $request_status)->where('request_by', $request_by)->first();

			// If the record doesn't exist, insert a new one
			if (!$existingRecord) {
				DB::table('module_request_status')->insert([
				    'module_id' => $request_id,
				    'request_status' => $request_status,
				    'request_by' => $request_by
				]);
				
				$message = 'Request has been send to admin succesfully';
				$status = 'true';

			} else {
			
				
				if($existingRecord->is_approve == '1'){
				  $message = 'Request is already approved by admin';
				} else {
				  $message = 'Request is already send to admin';
				}
				
				$status = 'false';
			}
		}
	   }
	   
	   return json_encode(array('status' => $status,'message' => $message));


     }

    public function type(Request $request)
    {
        return response()->json(['data'=>config('module.'.$request->module_type)]);
    }
}
