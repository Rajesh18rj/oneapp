<?php

namespace App\Http\Controllers;

use App\Models\DeliveryMan;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\CentralLogics\SMS_module;
use App\Models\Zone;
use Illuminate\Support\Facades\Validator;


class DeliveryManController extends Controller
{
    public function create()
    {
        $status = BusinessSetting::where('key', 'toggle_dm_registration')->first();
        if (!isset($status) || $status->value == '0') {
            Toastr::error(translate('messages.not_found'));
            return back();
        }

        return view('dm-registration');
    }

    public function send_verification_code(Request $request)
    {

       
  

         $validator = Validator::make($request->all(), [
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:delivery_men'

        ]);

        if ($validator->fails()) {
             return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);
        }
        
        $token = rand(1000, 9999);
         $config = null;
     $settingData = BusinessSetting::where(['key' => 'msg91_sms'])->first();
	if (isset($settingData)) {
	    $config = json_decode($settingData['value'], true);
	    if (is_null($config)) {
		$config = $settingData['value'];
	    }
	}
		
	    $templateId = $config['template_id'];
        $response = SMS_module::send($request['phone'], $token, $templateId);

        if ($response == 'success') {
            return response()->json(['message' => translate('messages.otp_sent_successfull')], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to send otp']);
        }
    }

    public function store(Request $request)
    {
        $status = BusinessSetting::where('key', 'toggle_dm_registration')->first();
        if (!isset($status) || $status->value == '0') {
            Toastr::error(translate('messages.not_found'));
            return back();
        }

        $request->validate([
            'f_name' => 'required|max:100',
            'delivery_type' => 'required|in:agent,individual',
            'l_name' => 'nullable|max:100',
            'identity_number' => 'required|max:30',
            'email' => 'required|unique:delivery_men',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:delivery_men',
            'verification_code' => 'nullable|max:100',
            'zone_id' => 'required',
            'earning' => 'required',
            'city' => 'required',
            'terms' => 'required',
            'vehicle_type_id' => 'required',
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
           return redirect()->route('deliveryman.create');


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
        if($request->delivery_type == 'agent'){
          $dm->employee_status = 'pending';
        }
        $dm->save();

        Toastr::success(translate('messages.application_placed_successfully'));
        return back();
    }

    public function get_sub_zones(Request $request)
    {
        $zone_id = $request->zone_id;
        $subZoneData = Zone::select('id', 'sub_zone')->where('id', $zone_id)->get();
        $resp = array('status' => true, 'sub_zone_data' => $subZoneData);
        return json_encode($resp);
    }
    
    public function get_all_sub_zones(Request $request)
    {
        $zone_id = $request->zone_id;
        $subZoneData = Zone::select('id', 'sub_zone')->whereIn('id', $zone_id)->get();
        $resp = array('status' => true, 'sub_zone_data' => $subZoneData);
        return json_encode($resp);
    }
}
