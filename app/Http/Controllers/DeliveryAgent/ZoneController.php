<?php

namespace App\Http\Controllers\DeliveryAgent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Zone;
use Brian2694\Toastr\Facades\Toastr;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Grimzy\LaravelMysqlSpatial\Types\Polygon;
use Grimzy\LaravelMysqlSpatial\Types\LineString;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\DB;

class ZoneController extends Controller
{
    public function index()
    {
        $zones = Zone::withCount(['stores','deliverymen'])->latest()->paginate(config('default_pagination'));
        return view('delivery-agent-views.zone.index', compact('zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:191',
            'sub_zone' => 'required|max:191',
            'coordinates' => 'required',
        ]);

        $value = $request->coordinates; 
        foreach(explode('),(',trim($value,'()')) as $index=>$single_array){
            if($index == 0)
            {
                $lastcord = explode(',',$single_array);
            }
            $coords = explode(',',$single_array);
            $polygon[] = new Point($coords[0], $coords[1]);
        }
        $zone_id=Zone::all()->count() + 1;
        $polygon[] = new Point($lastcord[0], $lastcord[1]);
        $zone = new Zone();
        $zone->name = $request->name;
        $zone->sub_zone = $request->sub_zone;        
        $sub_zone_distances = $request->sub_zone_distance;        
        if(isset($sub_zone_distances) && !empty($sub_zone_distances)){
            $sub_zone_distances = implode(",",$sub_zone_distances);
       	} else {
            $sub_zone_distances = '';
       	}
        
        $zone->sub_zone_distance = $sub_zone_distances;
        $zone->coordinates = new Polygon([new LineString($polygon)]);
        $zone->store_wise_topic =  'zone_'.$zone_id.'_store';
        $zone->customer_wise_topic = 'zone_'.$zone_id.'_customer';
        $zone->deliveryman_wise_topic = 'zone_'.$zone_id.'_delivery_man';
        $zone->save();

        Toastr::success(translate('messages.zone_added_successfully'));
        return back();
    }

    public function edit($id)
    {
        if(env('APP_MODE')=='demo' && $id == 1)
        {
            Toastr::warning(translate('messages.you_can_not_edit_this_zone_please_add_a_new_zone_to_edit'));
            return back();
        }
        $zone=Zone::selectRaw("*,ST_AsText(ST_Centroid(`coordinates`)) as center")->findOrFail($id);
        // dd($zone->coordinates);
        return view('delivery-agent-views.zone.edit', compact('zone'));
    }
    
    
    

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:191',
            'sub_zone' => 'required|max:191',
            'coordinates' => 'required',
        ]);
        $value = $request->coordinates; 
        foreach(explode('),(',trim($value,'()')) as $index=>$single_array){
            if($index == 0)
            {
                $lastcord = explode(',',$single_array);
            }
            $coords = explode(',',$single_array);
            $polygon[] = new Point($coords[0], $coords[1]);
        }
        $polygon[] = new Point($lastcord[0], $lastcord[1]);
        $zone=Zone::findOrFail($id);
        $zone->name = $request->name;
        $zone->sub_zone = $request->sub_zone;        
        $sub_zone_distances = $request->sub_zone_distance;        
        if(isset($sub_zone_distances) && !empty($sub_zone_distances)){
            $sub_zone_distances = implode(",",$sub_zone_distances);
       	} else {
            $sub_zone_distances = '';
       	}
        
        $zone->sub_zone_distance = $sub_zone_distances;
        $zone->coordinates = new Polygon([new LineString($polygon)]);
        $zone->store_wise_topic =  'zone_'.$id.'_store';
        $zone->customer_wise_topic = 'zone_'.$id.'_customer';
        $zone->deliveryman_wise_topic = 'zone_'.$id.'_delivery_man';
        $zone->save();
        Toastr::success(translate('messages.zone_updated_successfully'));
        return redirect()->route('delivery-agent.zone.home');
    }

    public function destroy(Zone $zone)
    {
        if(env('APP_MODE')=='demo' && $zone->id == 1)
        {
            Toastr::warning(translate('messages.you_can_not_delete_this_zone_please_add_a_new_zone_to_delete'));
            return back();
        }
        $zone->delete();
        Toastr::success(translate('messages.zone_deleted_successfully'));
        return back();
    }

    public function status(Request $request)
    {
        if(env('APP_MODE')=='demo' && $request->id == 1)
        {
            Toastr::warning('Sorry!You can not inactive this zone!');
            return back();
        }
        $zone = Zone::findOrFail($request->id);
        $zone->status = $request->status;
        $zone->save();
        Toastr::success(translate('messages.zone_status_updated'));
        return back();
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $zones=Zone::withCount(['stores','deliverymen'])->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json([
            'view'=>view('delivery-agent-views.zone.partials._table',compact('zones'))->render(),
            'total'=>$zones->count()
        ]);
    }

    public function get_coordinates($id){
        $zone=Zone::withoutGlobalScopes()->selectRaw("*,ST_AsText(ST_Centroid(`coordinates`)) as center")->findOrFail($id);
        $data = Helpers::format_coordiantes($zone->coordinates[0]);
        $center = (object)['lat'=>(float)trim(explode(' ',$zone->center)[1], 'POINT()'), 'lng'=>(float)trim(explode(' ',$zone->center)[0], 'POINT()')];
        return response()->json(['coordinates'=>$data, 'center'=>$center]);
    }
    
     public function get_sub_zones($zoneName){
        $Zones = Zone::withoutGlobalScopes()->where('name',$zoneName)->get()->toArray();
        $allData = array();
       /* echo "<pre>";
        
        print_r($Zones);*/
        if(isset($Zones) && !empty($Zones)){
           foreach($Zones as $key => $val){
           	array_push($allData,array('sub_zone_name' => $val['sub_zone'] ?? '','zone_id' =>  $val['id']));
           
           }
           return response()->json(['status' => true,'data'=> $allData ?? [] ]);
        } else {
        
         return response()->json(['status' => false,'data'=> []]);	
        }
     }

    public function zone_filter($id)
    {
        if($id == 'all')
        {
            if(session()->has('zone_id')){
                session()->forget('zone_id');
            }
        }
        else{
            session()->put('zone_id', $id);
        }
        
        return back();
    }

    public function get_all_zone_cordinates($id = 0)
    {
        $zones = Zone::where('id', '<>', $id)->active()->get();
        $data = [];
        foreach($zones as $zone)
        {
            $data[] = Helpers::format_coordiantes($zone->coordinates[0]);
        }
        return response()->json($data,200);
    }
    
    
    public function sendZoneAssignRequest($id, $status, $name)
    {
       
        // Get the authenticated delivery men's user ID
	$request_by = auth('delivery_men')->user()->id;
	$request_id = $id;
	$request_status = $status;
	$sub_zone_name = $name;

	
	
	// Check if the record already exists
	$existingRecord = DB::table('zone_request_status')
	    ->where('zone_id', $request_id)->where('sub_zone', $sub_zone_name)->where('request_status', $request_status)->where('request_by', $request_by)
	    ->first();

	// If the record doesn't exist, insert a new one
	if (!$existingRecord) {
		DB::table('zone_request_status')->insert([
		    'zone_id' => $request_id,
		    'request_status' => $request_status,
		    'request_by' => $request_by,
		    'sub_zone' => $sub_zone_name,
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
    
     public function sendBulkZoneAssignRequest(Request $request){
	    // Get item IDs from the request
	    $itemIds = $request->item_ids;
	    $itemStatuses = $request->itemStatuses;
	    $sub_zone_names = $request->sub_zone;
	  

	    // Check if itemIds is set and not empty
	    if (isset($itemIds) && !empty($itemIds)) {
	    	// Loop through each item ID
		foreach ($itemIds as $key => $value) {
			$request_by = auth('delivery_men')->user()->id;
			$request_id = $value;
		
			$request_status = $itemStatuses[$key];
			
			$sub_zone = $sub_zone_names[$key];
			
			
			$existingRecord = DB::table('zone_request_status')->where('zone_id', $request_id)->where('sub_zone', $sub_zone)->where('request_status', $request_status)->where('request_by', $request_by)->first();

			// If the record doesn't exist, insert a new one
			if (!$existingRecord) {
				DB::table('zone_request_status')->insert([
				    'zone_id' => $request_id,
				    'request_status' => $request_status,
				    'request_by' => $request_by,
				     'sub_zone' => $sub_zone,
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
     
}
