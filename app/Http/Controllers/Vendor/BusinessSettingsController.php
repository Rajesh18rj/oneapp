<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\StoreSchedule;
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\Validator;
use App\Models\BusinessSetting;

class BusinessSettingsController extends Controller
{

    private $store;

    public function store_index()
    {
        $store = Helpers::get_store_data();
        return view('vendor-views.business-settings.restaurant-index', compact('store'));
    }

    public function store_setup(Store $store, Request $request)
    {
        $request->validate([
            'gst' => 'required_if:gst_status,1',
        ], [
            'gst.required_if' => translate('messages.gst_can_not_be_empty'),
        ]);


        $store->minimum_order = $request->minimum_order;
        $store->gst = json_encode(['status' => $request->gst_status, 'code' => $request->gst]);
        $store->delivery_charge = $store->self_delivery_system ? $request->delivery_charge ?? 0 : $store->delivery_charge;
        $store->order_place_to_schedule_interval = $request->order_place_to_schedule_interval;
        $store->delivery_time = $request->minimum_delivery_time . '-' . $request->maximum_delivery_time . ' ' . $request->delivery_time_type;
        $storeId =  $store->id;
        $storeData = Store::where(['id' => $storeId])->first();

        $is_gst_certificate_upload_enabled = $request->is_gst_certificate_upload_enabled;
        
        $is_gst_certificate_enabled_for_vendor = BusinessSetting::where('key', 'is_gst_certificate_enabled_for_vendor')->first();
        $is_gst_certificate_enabled_for_vendor = $is_gst_certificate_enabled_for_vendor ? $is_gst_certificate_enabled_for_vendor->value : 0;
        
        

        if ($request->has('gst_certificate')) {
            if ($is_gst_certificate_enabled_for_vendor == '1') {
                $gst_certificate =  Helpers::upload('store/', 'png', $request->file('gst_certificate'));
            } else {
                $gst_certificate = ($storeData->gst_certificate) ?? '';
            }
        } else {
            $gst_certificate = ($storeData->gst_certificate) ?? '';
        }

        $store->gst_certificate = $gst_certificate;


	$is_fssai_certificate_enabled_for_vendor = BusinessSetting::where('key', 'is_fssai_certificate_enabled_for_vendor')->first();
        $is_fssai_certificate_enabled_for_vendor = $is_fssai_certificate_enabled_for_vendor ? $is_fssai_certificate_enabled_for_vendor->value : 0;

        if ($request->has('fssai_certificate')) {
             if ($is_fssai_certificate_enabled_for_vendor == '1') {
               $fssai_certificate =  Helpers::upload('store/', 'png', $request->file('fssai_certificate'));
             } else {
                $fssai_certificate = ($storeData->fssai_certificate) ?? '';
              }	
            
        } else {
            $fssai_certificate = ($storeData->fssai_certificate) ?? '';
        }

        $store->fssai_certificate = $fssai_certificate;
        
        
        $is_pan_card_enabled_for_vendor = BusinessSetting::where('key', 'is_pan_card_enabled_for_vendor')->first();
        $is_pan_card_enabled_for_vendor = $is_pan_card_enabled_for_vendor ? $is_pan_card_enabled_for_vendor->value : 0;


        if ($request->has('pan_card')) {
              if ($is_pan_card_enabled_for_vendor == '1') {
                  $pan_card =  Helpers::upload('store/', 'png', $request->file('pan_card'));
              } else {
                  $pan_card = ($storeData->pan_card) ?? '';
              }
          
        } else {
            $pan_card = ($storeData->pan_card) ?? '';
        }

        $store->pan_card = $pan_card;
        
        
        $is_tfn_certificate_enabled_for_vendor = BusinessSetting::where('key', 'is_tfn_certificate_enabled_for_vendor')->first();
        $is_tfn_certificate_enabled_for_vendor = $is_tfn_certificate_enabled_for_vendor ? $is_tfn_certificate_enabled_for_vendor->value : 0;


        if ($request->has('tfn_certificate')) {
        
             if ($is_tfn_certificate_enabled_for_vendor == '1') {
                    $tfn_certificate =  Helpers::upload('store/', 'png', $request->file('tfn_certificate'));
             } else {
             	$tfn_certificate = ($storeData->tfn_certificate) ?? '';
             }
	} else {
            $tfn_certificate = ($storeData->tfn_certificate) ?? '';
        }
        
         $store->tfn_certificate = $tfn_certificate;

        /*$store->is_gst_certificate_upload_enabled = $request->manage_gst_certificate == 'enabled' ? 1 : 0;
        $store->is_fssai_certificate_upload_enabled = $request->manage_fssai_certificate == 'enabled' ? 1 : 0;
        $store->is_pan_card_upload_enabled = $request->manage_pan_card == 'enabled' ? 1 : 0;
        $store->is_tfn_certificate_upload_enabled = $request->manage_tfn_certificate == 'enabled' ? 1 : 0;
        $store->is_acn_certificate_upload_enabled = $request->manage_acn_certificate == 'enabled' ? 1 : 0;
        $store->is_abn_certificate_upload_enabled = $request->manage_abn_certificate == 'enabled' ? 1 : 0;
        $store->is_ein_certificate_upload_enabled = $request->manage_ein_certificate == 'enabled' ? 1 : 0;
        $store->is_cin_certificate_upload_enabled = $request->manage_cin_certificate == 'enabled' ? 1 : 0;*/



	$is_acn_certificate_enabled_for_vendor = BusinessSetting::where('key', 'is_acn_certificate_enabled_for_vendor')->first();
        $is_acn_certificate_enabled_for_vendor = $is_acn_certificate_enabled_for_vendor ? $is_acn_certificate_enabled_for_vendor->value : 0;


        if ($request->has('acn_certificate')) {
                if ($is_acn_certificate_enabled_for_vendor == '1') {
                    $acn_certificate =  Helpers::upload('store/', 'png', $request->file('acn_certificate'));
                } else {
                    $acn_certificate =  ($storeData->acn_certificate) ?? '';
                }
           
        } else {
            $acn_certificate = ($storeData->acn_certificate) ?? '';
        }

        $store->acn_certificate = $acn_certificate;
        
        
        $is_abn_certificate_enabled_for_vendor = BusinessSetting::where('key', 'is_abn_certificate_enabled_for_vendor')->first();
        $is_abn_certificate_enabled_for_vendor = $is_abn_certificate_enabled_for_vendor ? $is_abn_certificate_enabled_for_vendor->value : 0;


        if ($request->has('abn_certificate')) {
         if ($is_abn_certificate_enabled_for_vendor == '1') {
         	 $abn_certificate =  Helpers::upload('store/', 'png', $request->file('abn_certificate'));
         } else {
         	 $abn_certificate = ($storeData->abn_certificate) ?? '';
         }
           
        } else {
            $abn_certificate = ($storeData->abn_certificate) ?? '';
        }

        $store->abn_certificate = $abn_certificate;
        
        
        $is_ein_certificate_enabled_for_vendor = BusinessSetting::where('key', 'is_ein_certificate_enabled_for_vendor')->first();
        $is_ein_certificate_enabled_for_vendor = $is_ein_certificate_enabled_for_vendor ? $is_ein_certificate_enabled_for_vendor->value : 0;



        if ($request->has('ein_certificate')) {
            if ($is_ein_certificate_enabled_for_vendor == '1') {
            	  $ein_certificate =  Helpers::upload('store/', 'png', $request->file('ein_certificate'));
            
            } else {
                 $ein_certificate = ($storeData->ein_certificate) ?? '';
            }
          
        } else {
            $ein_certificate = ($storeData->ein_certificate) ?? '';
        }

        $store->ein_certificate = $ein_certificate;
        
        $is_cin_certificate_enabled_for_vendor = BusinessSetting::where('key', 'is_cin_certificate_enabled_for_vendor')->first();
        $is_cin_certificate_enabled_for_vendor = $is_cin_certificate_enabled_for_vendor ? $is_cin_certificate_enabled_for_vendor->value : 0;


        if ($request->has('cin_certificate')) {
              if ($is_ein_certificate_enabled_for_vendor == '1') {
              	 $cin_certificate =  Helpers::upload('store/', 'png', $request->file('cin_certificate'));
              } else {
                 $cin_certificate = ($storeData->cin_certificate) ?? '';
              }
           
        } else {
            $cin_certificate = ($storeData->cin_certificate) ?? '';
        }
        
        
        
   

        $store->cin_certificate = $cin_certificate;
        
        
        $is_bank_cheque_enabled_for_vendor = BusinessSetting::where('key', 'is_bank_cheque_enabled_for_vendor')->first();
        $is_bank_cheque_enabled_for_vendor = $is_bank_cheque_enabled_for_vendor ? $is_bank_cheque_enabled_for_vendor->value : 0;


        if ($request->has('bank_cheque')) {
              if ($is_bank_cheque_enabled_for_vendor == '1') {
                  $bank_cheque =  Helpers::upload('store/', 'png', $request->file('bank_cheque'));
              } else {
                  $bank_cheque = ($storeData->bank_cheque) ?? '';
              }
          
        } else {
            $bank_cheque = ($storeData->bank_cheque) ?? '';
        }

        $store->bank_cheque = $bank_cheque;
        
        $is_pasbook_image_enabled_for_vendor = BusinessSetting::where('key', 'is_pasbook_image_enabled_for_vendor')->first();
        $is_pasbook_image_enabled_for_vendor = $is_pasbook_image_enabled_for_vendor ? $is_pasbook_image_enabled_for_vendor->value : 0;


        if ($request->has('pasbook_image')) {
              if ($is_pasbook_image_enabled_for_vendor == '1') {
                  $pasbook_image =  Helpers::upload('store/', 'png', $request->file('pasbook_image'));
              } else {
                  $pasbook_image = ($storeData->pasbook_image) ?? '';
              }
          
        } else {
            $pasbook_image = ($storeData->pasbook_image) ?? '';
        }

        $store->pasbook_image = $pasbook_image;


        $store->save();
        Toastr::success(translate('messages.store_settings_updated'));
        return back();
    }

    public function store_status(Store $store, Request $request)
    {
        if ($request->menu == "schedule_order" && !Helpers::schedule_order()) {
            Toastr::warning(translate('messages.schedule_order_disabled_warning'));
            return back();
        }

        if ((($request->menu == "delivery" && $store->take_away == 0) || ($request->menu == "take_away" && $store->delivery == 0)) &&  $request->status == 0) {
            Toastr::warning(translate('messages.can_not_disable_both_take_away_and_delivery'));
            return back();
        }

        if ((($request->menu == "veg" && $store->non_veg == 0) || ($request->menu == "non_veg" && $store->veg == 0)) &&  $request->status == 0) {
            Toastr::warning(translate('messages.veg_non_veg_disable_warning'));
            return back();
        }

        $store[$request->menu] = $request->status;
        $store->save();
        Toastr::success(translate('messages.store settings updated!'));
        return back();
    }

    public function active_status(Request $request)
    {
        $store = Helpers::get_store_data();
        $store->active = $store->active ? 0 : 1;
        $store->save();
        return response()->json(['message' => $store->active ? translate('messages.store_opened') : translate('messages.store_temporarily_closed')], 200);
    }

    public function add_schedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ], [
            'end_time.after' => translate('messages.End time must be after the start time')
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }
        $temp = StoreSchedule::where('day', $request->day)->where('store_id', Helpers::get_store_id())
            ->where(function ($q) use ($request) {
                return $q->where(function ($query) use ($request) {
                    return $query->where('opening_time', '<=', $request->start_time)->where('closing_time', '>=', $request->start_time);
                })->orWhere(function ($query) use ($request) {
                    return $query->where('opening_time', '<=', $request->end_time)->where('closing_time', '>=', $request->end_time);
                });
            })
            ->first();

        if (isset($temp)) {
            return response()->json(['errors' => [
                ['code' => 'time', 'message' => translate('messages.schedule_overlapping_warning'), 'dd' => $request->all()]
            ]]);
        }

        $store = Helpers::get_store_data();
        $store_schedule = StoreSchedule::insert(['store_id' => Helpers::get_store_id(), 'day' => $request->day, 'opening_time' => $request->start_time, 'closing_time' => $request->end_time]);
        return response()->json([
            'view' => view('vendor-views.business-settings.partials._schedule', compact('store'))->render(),
        ]);
    }

    public function remove_schedule($store_schedule)
    {
        $store = Helpers::get_store_data();
        $schedule = StoreSchedule::where('store_id', $store->id)->find($store_schedule);
        if (!$schedule) {
            return response()->json([], 404);
        }
        $schedule->delete();
        return response()->json([
            'view' => view('vendor-views.business-settings.partials._schedule', compact('store'))->render(),
        ]);
    }
}
