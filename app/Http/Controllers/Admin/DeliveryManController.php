<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryMan;
use App\Models\DMReview;
use App\Models\Zone;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\DB;
use App\CentralLogics\SMS_module;
use App\Models\Module;

class DeliveryManController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->segment(4);
        return view('admin-views.delivery-man.index',array('type' => $type));
    }

    public function list(Request $request)
    {
        $zone_id = $request->query('zone_id', 'all');
        $delivery_men = DeliveryMan::Individual()->when(is_numeric($zone_id), function ($query) use ($zone_id) {
            return $query->where('zone_id', $zone_id);
        })->with('zone')->where('type', 'zone_wise')->latest()->paginate(config('default_pagination'));
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        return view('admin-views.delivery-man.list', compact('delivery_men', 'zone'));
    }


    public function agentList(Request $request)
    {
        $zone_id = $request->query('zone_id', 'all');
        $delivery_men = DeliveryMan::Agent()->when(is_numeric($zone_id), function ($query) use ($zone_id) {
            return $query->where('zone_id', $zone_id);
        })->with('zone')->where('type', 'zone_wise')->latest()->paginate(config('default_pagination'));
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        return view('admin-views.delivery-man.agent-list', compact('delivery_men', 'zone'));
    }

    public function search(Request $request)
    {
        $key = explode(' ', $request['search']);
        $delivery_type = $request['delivery_type'];
        $delivery_men = DeliveryMan::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('f_name', 'like', "%{$value}%")
                    ->orWhere('l_name', 'like', "%{$value}%")
                    ->orWhere('email', 'like', "%{$value}%")
                    ->orWhere('phone', 'like', "%{$value}%")
                    ->orWhere('identity_number', 'like', "%{$value}%");
            }
        })->where(array('type' =>  'zone_wise', 'delivery_type' => $delivery_type))->get();
        return response()->json([
            'view' => view('admin-views.delivery-man.partials._table', compact('delivery_men'))->render(),
            'count' => $delivery_men->count()
        ]);
    }

    public function reviews_list()
    {
        $reviews = DMReview::with(['delivery_man', 'customer'])->whereHas('delivery_man', function ($query) {
            $query->where('type', 'zone_wise');
        })->latest()->paginate(config('default_pagination'));
        return view('admin-views.delivery-man.reviews-list', compact('reviews'));
    }

    public function preview(Request $request, $id, $tab = 'info')
    {
        $dm = DeliveryMan::with(['reviews'])->where('type', 'zone_wise')->where(['id' => $id])->first();
        if ($tab == 'info') {
            $reviews = DMReview::where(['delivery_man_id' => $id])->latest()->paginate(config('default_pagination'));
            return view('admin-views.delivery-man.view.info', compact('dm', 'reviews'));
        } else if ($tab == 'transaction') {
            $date = $request->query('date');
            return view('admin-views.delivery-man.view.transaction', compact('dm', 'date'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'f_name' => 'required|max:100',
            'l_name' => 'nullable|max:100',
            'identity_number' => 'required|max:30',
            'email' => 'required|unique:delivery_men',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20|unique:delivery_men',
            'zone_id' => 'required',
            'earning' => 'required',
            'password' => 'required|min:9',
        ], [
            'f_name.required' => translate('messages.first_name_is_required'),
            'zone_id.required' => translate('messages.select_a_zone'),
            'earning.required' => translate('messages.select_dm_type')
        ]);

        if ($request->has('image')) {
            $image_name = Helpers::upload('delivery-man/', 'png', $request->file('image'));
        } else {
            $image_name = 'def.png';
        }

         $smsResponse = SMS_module::send($request->phone, $request->verification_code, '');
        $smsResponse = json_decode($smsResponse);
        
     
        if($smsResponse->type == 'error'){         

           Toastr::error($smsResponse->message);
           return back();
        }

        $id_img_names = [];
        if (!empty($request->file('identity_image'))) {
            foreach ($request->identity_image as $img) {
                $identity_image = Helpers::upload('delivery-man/', 'png', $img);
                array_push($id_img_names, $identity_image);
            }
            $identity_image = json_encode($id_img_names);
        } else {
            $identity_image = json_encode([]);
        }

         $dm = new DeliveryMan();
        $dm->f_name = $request->f_name;
        $dm->l_name = $request->l_name;
        $dm->phone = $request->phone;

        $dm->delivery_type = $request->delivery_type;
        $dm->vehicle_type_id = $request->vehicle_type_id;
        $dm->vehicle_plate_number = $request->vehicle_plate_number;
        $dm->vehicle_insurance = $request->vehicle_insurance;
        $dm->license = $request->license;

        $dm->verification_code = $request->verification_code;
        $dm->city = $request->city;

        $dm->email = $request->email;

        $dm->identity_number = $request->identity_number;
        $dm->identity_type = $request->identity_type;
        $dm->zone_id = $request->zone_id;
        $dm->sub_zone_id = $request->sub_zone_id;
        $dm->identity_image = $identity_image;
        $dm->image = $image_name;
        $dm->active = 0;
        $dm->earning = $request->earning;
        $dm->driver_license_expiry_date = $request->driver_license_expiry_date;
        $dm->terms = $request->terms;


        if ($request->has('driver_license_image')) {
            $driver_license_image_name = Helpers::upload('driver-license-image/', 'png', $request->file('driver_license_image'));
        } else {
            $driver_license_image_name = 'def.png';
        }

        if ($request->has('vehicle_registration_image')) {
            $vehicle_registration_image_name = Helpers::upload('vehicle-registration-image/', 'png', $request->file('vehicle_registration_image'));
        } else {
            $vehicle_registration_image_name = 'def.png';
        }

        if ($request->has('vehicle_insurance_image')) {
            $vehicle_insurance_image_name = Helpers::upload('vehicle-insurance-image/', 'png', $request->file('vehicle_insurance_image'));
        } else {
            $vehicle_insurance_image_name = 'def.png';
        }

        $deliveryman_deposit = \App\Models\BusinessSetting::where('key', 'deliveryman_deposit')->first();


        $dm->vehicle_type = $request->vehicle_type;
        $dm->driver_license_image = $driver_license_image_name;
        $dm->vehicle_license_image = $vehicle_insurance_image_name;
        $dm->vehicle_insurance_image = $vehicle_insurance_image_name;

        $dm->deposit_amount = $request->deposit_amount ?? 0;
        $dm->is_deposit_enabled = $deliveryman_deposit->value;


        if ($request->has('initial_deposit_receipt')) {
            $dm->initial_deposit_receipt = Helpers::upload('deposit-receipt/', 'png', $request->file('initial_deposit_receipt'));
        } else {
            $dm->initial_deposit_receipt = 'def.png';
        }


          $dm->order_delivery_location_limit = $request->order_delivery_location_limit;
        
        $dm->logistics_type = $request->logistics_type;



	 if ($request->has('gst_certificate')) {

                $gst_certificate =  Helpers::upload('documents/', 'png', $request->file('gst_certificate'));
            
        } else {
            $gst_certificate ='def.png';
        }

        $dm->gst_certificate = $gst_certificate;




        if ($request->has('fssai_certificate')) {
            $fssai_certificate =  Helpers::upload('documents/', 'png', $request->file('fssai_certificate'));
        } else {
            $fssai_certificate = 'def.png';
        }

        $dm->fssai_certificate = $fssai_certificate;


        if ($request->has('pan_card')) {
            $pan_card =  Helpers::upload('documents/', 'png', $request->file('pan_card'));
        } else {
            $pan_card = 'def.png';
        }

        $dm->pan_card = $pan_card;


        if ($request->has('tfn_certificate')) {

            $tfn_certificate =  Helpers::upload('documents/', 'png', $request->file('tfn_certificate'));
        } else {
            $tfn_certificate = 'def.png';
        }
	 $dm->tfn_certificate = $tfn_certificate;
	 
	if ($request->has('passbook_image')) {

            $passbook_image =  Helpers::upload('documents/', 'png', $request->file('passbook_image'));
        } else {
            $passbook_image = 'def.png';
        }
	 $dm->passbook_image = $passbook_image; 

        // deposit info

        $dm->holder_name = $request->holder_name;
        $dm->bsb_number = $request->bsb_number;
        $dm->account_number = $request->account_number;
        $dm->business_number = $request->business_number;
        $dm->courier_bag = $request->courier_bag;
        $dm->ppe = $request->ppe;
        $dm->bank_term = $request->bank_term;
        $dm->ifsc_code = $request->ifsc_code;
        $dm->branch = $request->branch;
        $dm->bank_name  = $request->bank_name;

        $dm->password = bcrypt($request->password);
        $dm->application_status = 'pending';
        $dm->added_by = auth('admin')->user()->id;
        
     
       
       
        
        
        
        
        
        
        
        
        $dm->save();
        
        if($request->delivery_type == 'agent'){
	  DB::table('admins')->insert([
	    'f_name' => $request->f_name,
	    'l_name' => $request->l_name,
	    'phone' => $request->phone,
	    'zone_id' => $request->zone_id,
	    'email' => $request->email,
	    'password' => bcrypt($request->password),
	    'image' => Helpers::upload('admin/', 'png', $request->file('image')),
	    'created_at' => now(),
	    'updated_at' => now(),
	]);
        
        }
        
        
        
        
        
        
        
        

        Toastr::success(translate('messages.deliveryman_added_successfully'));
        if($request->delivery_type != 'agent'){
          return redirect('admin/delivery-man/list');
        } else {
          return redirect('admin/delivery-man/agent-list');
        
        }
        
    }

    public function edit($id)
    {
        $delivery_man = DeliveryMan::find($id);
        return view('admin-views.delivery-man.edit', compact('delivery_man'));
    }

    public function status(Request $request)
    {
        $delivery_man = DeliveryMan::find($request->id);
        $delivery_man->status = $request->status;

        try {
            if ($request->status == 0) {
                $delivery_man->auth_token = null;
                if (isset($delivery_man->fcm_token)) {
                    $data = [
                        'title' => translate('messages.suspended'),
                        'description' => translate('messages.your_account_has_been_suspended'),
                        'order_id' => '',
                        'image' => '',
                        'type' => 'block'
                    ];
                    Helpers::send_push_notif_to_device($delivery_man->fcm_token, $data);

                    DB::table('user_notifications')->insert([
                        'data' => json_encode($data),
                        'delivery_man_id' => $delivery_man->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        } catch (\Exception $e) {
            Toastr::warning(translate('messages.push_notification_faild'));
        }

        $delivery_man->save();

        Toastr::success(translate('messages.deliveryman_status_updated'));
        return back();
    }

    public function reviews_status(Request $request)
    {
        $review = DMReview::find($request->id);
        $review->status = $request->status;
        $review->save();
        Toastr::success(translate('messages.review_visibility_updated'));
        return back();
    }

    public function earning(Request $request)
    {
        $delivery_man = DeliveryMan::find($request->id);
        $delivery_man->earning = $request->status;

        $delivery_man->save();

        Toastr::success(translate('messages.deliveryman_type_updated'));
        return back();
    }

    public function update_application(Request $request)
    {
        $delivery_man = DeliveryMan::findOrFail($request->id);
        $delivery_man->application_status = $request->status;
        if ($request->status == 'approved') $delivery_man->status = 1;
        $delivery_man->save();

        Toastr::success(translate('messages.application_status_updated_successfully'));
        return back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'f_name' => 'required|max:100',
            'l_name' => 'nullable|max:100',
            'identity_number' => 'required|max:30',
            'email' => 'required|unique:delivery_men,email,' . $id,
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:delivery_men,phone,' . $id,
            'earning' => 'required',
        ], [
            'f_name.required' => translate('messages.first_name_is_required'),
            'earning.required' => translate('messages.select_dm_type')
        ]);

        $delivery_man = DeliveryMan::find($id);
        
        /*  $smsResponse = SMS_module::send($request->phone,$request->verification_code,'');
          print_r($smsResponse);
          die;*/
        

               if ($request->has('image')) {
            $image_name = Helpers::update('delivery-man/', $delivery_man->image, 'png', $request->file('image'));
        } else {
            $image_name = $delivery_man['image'];
        }

        if ($request->has('identity_image')) {
            foreach (json_decode($delivery_man['identity_image'], true) as $img) {
                if (Storage::disk('public')->exists('delivery-man/' . $img)) {
                    Storage::disk('public')->delete('delivery-man/' . $img);
                }
            }
            $img_keeper = [];
            foreach ($request->identity_image as $img) {
                $identity_image = Helpers::upload('delivery-man/', 'png', $img);
                array_push($img_keeper, $identity_image);
            }
            $identity_image = json_encode($img_keeper);
        } else {
            $identity_image = $delivery_man['identity_image'];
        }

        if($delivery_man->phone != $request->phone){
            $smsResponse = SMS_module::send($request->phone, $request->verification_code, '');
            $smsResponse = json_decode($smsResponse);            
         
            if($smsResponse->type == 'error'){         
              Toastr::error($smsResponse->message);
               return back();
            }
        }
        

        $delivery_man->f_name = $request->f_name;
        $delivery_man->l_name = $request->l_name;
        $delivery_man->email = $request->email;
        $delivery_man->phone = $request->phone;
        $delivery_man->identity_number = $request->identity_number;
        $delivery_man->identity_type = $request->identity_type;
        $delivery_man->zone_id = $request->zone_id;
        $delivery_man->identity_image = $identity_image;
        $delivery_man->image = $image_name;
        $delivery_man->earning = $request->earning;
        $delivery_man->sub_zone_id = $request->sub_zone_id;
        $delivery_man->password = (strlen($request->password) > 1) ? bcrypt($request->password) : $delivery_man['password'];
        
     
        $delivery_man->order_delivery_location_limit = $request->order_delivery_location_limit;
        $delivery_man->logistics_type = $request->logistics_type;
        $delivery_man->driver_license_expiry_date = $request->driver_license_expiry_date;
        
        if ($request->has('driver_license_image')) {
            $driver_license_image_name = Helpers::upload('driver-license-image/', 'png', $request->file('driver_license_image'));
        } else {
            $driver_license_image_name = $delivery_man->driver_license_image;
        }

        if ($request->has('vehicle_registration_image')) {
            $vehicle_registration_image_name = Helpers::upload('vehicle-registration-image/', 'png', $request->file('vehicle_registration_image'));
        } else {
            $vehicle_registration_image_name = $delivery_man->vehicle_registration_image;
        }

        if ($request->has('vehicle_insurance_image')) {
            $vehicle_insurance_image_name = Helpers::upload('vehicle-insurance-image/', 'png', $request->file('vehicle_insurance_image'));
        } else {
            $vehicle_insurance_image_name = $delivery_man->vehicle_insurance_image;
        }

        $deliveryman_deposit = \App\Models\BusinessSetting::where('key', 'deliveryman_deposit')->first();
	

        $delivery_man->vehicle_type = $request->vehicle_type;
        $delivery_man->driver_license_image = $driver_license_image_name;
        $delivery_man->vehicle_license_image = $vehicle_insurance_image_name;
        $delivery_man->vehicle_insurance_image = $vehicle_insurance_image_name;
        $delivery_man->vehicle_registration_image = $vehicle_registration_image_name;
        
     

        $delivery_man->deposit_amount = $request->deposit_amount ?? 0;
        $delivery_man->is_deposit_enabled = $deliveryman_deposit->value;


        if ($request->has('initial_deposit_receipt')) {
            $delivery_man->initial_deposit_receipt = Helpers::upload('deposit-receipt/', 'png', $request->file('initial_deposit_receipt'));
        } else {
            $delivery_man->initial_deposit_receipt = $delivery_man->initial_deposit_receipt;
        }







	 if ($request->has('gst_certificate')) {

                $gst_certificate =  Helpers::upload('documents/', 'png', $request->file('gst_certificate'));
            
        } else {
            $gst_certificate = $delivery_man->gst_certificate;
        }

        $delivery_man->gst_certificate = $gst_certificate;




        if ($request->has('fssai_certificate')) {
            $fssai_certificate =  Helpers::upload('documents/', 'png', $request->file('fssai_certificate'));
        } else {
            $fssai_certificate = $delivery_man->fssai_certificate;
        }

        $delivery_man->fssai_certificate = $fssai_certificate;


        if ($request->has('pan_card')) {
            $pan_card =  Helpers::upload('documents/', 'png', $request->file('pan_card'));
        } else {
            $pan_card = $delivery_man->pan_card;
        }

        $delivery_man->pan_card = $pan_card;


        if ($request->has('tfn_certificate')) {

            $tfn_certificate =  Helpers::upload('documents/', 'png', $request->file('tfn_certificate'));
        } else {
            $tfn_certificate =  $delivery_man->tfn_certificate;
        }
	 $delivery_man->tfn_certificate = $tfn_certificate;
	 
	if ($request->has('passbook_image')) {

            $passbook_image =  Helpers::upload('documents/', 'png', $request->file('passbook_image'));
        } else {
            $passbook_image = $delivery_man->passbook_image;
        }
	 $delivery_man->passbook_image = $passbook_image; 
        
        
        // deposit info

        $delivery_man->holder_name = $request->holder_name;
        $delivery_man->bsb_number = $request->bsb_number;
        $delivery_man->account_number = $request->account_number;
        $delivery_man->business_number = $request->business_number;
        $delivery_man->courier_bag = $request->courier_bag;
        $delivery_man->ppe = $request->ppe;
        $delivery_man->bank_term = $request->bank_term;
        $delivery_man->ifsc_code = $request->ifsc_code;
        $delivery_man->branch = $request->branch;
        $delivery_man->bank_name  = $request->bank_name;
        
        
        $delivery_man->save();
       
        if($request->delivery_type != 'agent'){
          Toastr::success(translate('delivery man updated_successfully'));
          return redirect('admin/delivery-man/list');
        } else {
             Toastr::success(translate('delivery agent updated_successfully'));
          return redirect('admin/delivery-man/agent-list');
        
        }
    }

    public function delete(Request $request)
    {
        $delivery_man = DeliveryMan::find($request->id);
        if (Storage::disk('public')->exists('delivery-man/' . $delivery_man['image'])) {
            Storage::disk('public')->delete('delivery-man/' . $delivery_man['image']);
        }

        foreach (json_decode($delivery_man['identity_image'], true) as $img) {
            if (Storage::disk('public')->exists('delivery-man/' . $img)) {
                Storage::disk('public')->delete('delivery-man/' . $img);
            }
        }

        $delivery_man->delete();
        Toastr::success(translate('messages.deliveryman_deleted_successfully'));
        return back();
    }

    public function get_deliverymen(Request $request)
    {
        $key = explode(' ', $request->q);
        $zone_ids = isset($request->zone_ids) ? (count($request->zone_ids) > 0 ? $request->zone_ids : []) : 0;
        $data = DeliveryMan::when($zone_ids, function ($query) use ($zone_ids) {
            return $query->whereIn('zone_id', $zone_ids);
        })
            ->when($request->earning, function ($query) {
                return $query->earning();
            })
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('identity_number', 'like', "%{$value}%");
                }
            })->active()->limit(8)->get(['id', DB::raw('CONCAT(f_name, " ", l_name) as text')]);
        return response()->json($data);
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


                $delivery_man = DeliveryMan::find($value);
                if (Storage::disk('public')->exists('delivery-man/' . $delivery_man['image'])) {
                    Storage::disk('public')->delete('delivery-man/' . $delivery_man['image']);
                }

                // foreach (json_decode($delivery_man['identity_image'], true) as $img) {
                //     if (Storage::disk('public')->exists('delivery-man/' . $img)) {
                //         Storage::disk('public')->delete('delivery-man/' . $img);
                //     }
                // }

                $delivery_man->delete();
            }
        }

        // Display success message using Toastr (adjust based on your implementation)
        Toastr::success(translate('messages.item_deleted_successfully'));

        return json_encode(array('status' => 'true', 'message' => translate('messages.item_deleted_successfully')));

        // Redirect back to the previous page
        return back();
    }

    public function get_account_data(DeliveryMan $deliveryman)
    {
        $wallet = $deliveryman->wallet;
        $cash_in_hand = 0;
        $balance = 0;

        if ($wallet) {
            $cash_in_hand = $wallet->collected_cash;
            $balance = round($wallet->total_earning - $wallet->total_withdrawn - $wallet->pending_withdraw, config('round_up_to_digit'));
        }
        return response()->json(['cash_in_hand' => $cash_in_hand, 'earning_balance' => $balance], 200);
    }
    
    public function approveAgent(Request $request)
    {
        $delivery_man = DeliveryMan::find($request->id);
        if($delivery_man->approve_status == 'approve'){
          Toastr::error(translate('messages.already_approved'));
          return back();
        }
      	$delivery_man->approve_status = 'approve';

        $delivery_man->save();
        Toastr::success(translate('messages.approved_successfully'));
        return back();
    }
    
    public function rejectAgent(Request $request)
    {
        $delivery_man = DeliveryMan::find($request->id);
        if($delivery_man->approve_status == 'reject'){
          Toastr::error(translate('messages.already_rejected'));
          return back();
        }
      	$delivery_man->approve_status = 'reject';

        $delivery_man->save();
        Toastr::success(translate('messages.rejected_successfully'));
        return back();
    }
    
     public function approveEmployeeStatusOfAgent(Request $request)
    {
        $delivery_man = DeliveryMan::find($request->id);
        if($delivery_man->employee_status == 'approve'){
          Toastr::error(translate('messages.already_approved'));
          return back();
        }
      	$delivery_man->employee_status = 'approve';
      	
      	if($delivery_man->delivery_type == 'agent'){
	      	 $userData = DB::table('admins')->where('phone',$delivery_man->phone)->get();
	      	 $userCount = count($userData);
	      	 if($userCount == 0){
	      	 	DB::table('admins')->insert([
			    'f_name' => $delivery_man->f_name,
			    'l_name' => $delivery_man->l_name,
			    'phone' => $delivery_man->phone,
			    'zone_id' => $delivery_man->zone_id,
			    'email' => $delivery_man->email,
			    'password' => bcrypt($delivery_man->password),
			    'image' => $delivery_man->image,
			    'created_at' => now(),
			    'updated_at' => now(),
			]);
	      	 
	      	 }
      	}

        $delivery_man->save();
        Toastr::success(translate('messages.approved_successfully'));
        return back();
    }
    
    public function rejectEmployeeStatusOfAgent(Request $request)
    {
        $delivery_man = DeliveryMan::find($request->id);
        if($delivery_man->employee_status == 'reject'){
          Toastr::error(translate('messages.already_rejected'));
          return back();
        }
      	$delivery_man->employee_status = 'reject';
      	$userData = DB::table('admins')->where('phone',$delivery_man->phone)->get();
	$userCount = count($userData);
	if($userCount > 0){
	 DB::table('admins')->where('id', $userData[0]->id)->delete();
	}
	
	

	
	

        $delivery_man->save();
        Toastr::success(translate('messages.rejected_successfully'));
        return back();
    }
    
     public function manageModuleRequest($id)
    {
        $delivery_man = DeliveryMan::find($id);
        $modules = Module::withCount('stores')->latest()->get();

                                    
        return view('admin-views.delivery-man.module-request', compact('delivery_man','modules'));
    }
    
    
     public function approveModuleRequest(Request $request,$id)
    {
	
	
	$module_id = $request->module_id;
	  
           if(isset($module_id) && !empty($module_id)){
		   foreach($module_id as $key => $value){
		   	$requestData = DB::table('module_request_status')->where(array('request_by'=> $id,'module_id'=> $value,'request_status'=> '1'))->get();
		   	if(count($requestData) > 0){
		   	
		   	    DB::table('module_request_status')
			    ->where('id', $requestData[0]->id)
			    ->update(['is_approve' => 1]);	
		   	} else {
		   	     Toastr::error(translate('Module request is not sent by delivery agent'));
		   	     return back();
		   	}
		   
		   }
		      Toastr::success(translate('Module request is approved'));
           
           } else {
           	   Toastr::error(translate('Please select atleast one module id'));
           
           }  		
	 
	 
	   
	
	return back();
    }
    
    
      public function manageZoneRequest($id)
    {
        $delivery_man = DeliveryMan::find($id);
        $zones = Zone::latest()->get();

                                    
        return view('admin-views.delivery-man.zone-request', compact('delivery_man','zones'));
    }
    
    
     public function approveZoneRequest(Request $request,$id)
    {
	
	
	$zone_id = $request->zone_id;
	
	$sub_zone_names = $request->sub_zone;

	  
           if(isset($zone_id) && !empty($zone_id)){
		   foreach($zone_id as $key => $value){
		   $sub_zone = $sub_zone_names[$key] ?? '';
		   if(!empty($sub_zone)){
		      $requestData = DB::table('zone_request_status')->where(array('request_by'=> $id,'sub_zone'=> $sub_zone,'zone_id'=> $value,'request_status'=> '1'))->get();
		   } else {
		      $requestData = DB::table('zone_request_status')->where(array('request_by'=> $id,'zone_id'=> $value,'request_status'=> '1'))->get();
		    
		    
		    
		   }
		   	
		   	if(count($requestData) > 0){
		   	
		   	    DB::table('zone_request_status')
			    ->where('id', $requestData[0]->id)
			    ->update(['is_approve' => 1]);	
		   	} else {
		   	     Toastr::error(translate('Zone request is not sent by delivery agent'));
		   	     return back();
		   	}
		   
		   }
		      Toastr::success(translate('Zone request is approved'));
           
           } else {
           	   Toastr::error(translate('Please select atleast one zone id'));
           
           }  		
	 
	 
	   
	
	return back();
    }
}
