<?php

namespace App\Http\Controllers\DeliveryAgent;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\Order;
use App\Models\DeliveryMan;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{

    public function store_data()
    {
        $new_order = Order::StoreOrder()->where(['checked' => 0])->where('payment_status', 'paid')->count();
        $new_parcel_order = Order::ParcelOrder()->where(['checked' => 0])->where('payment_status', 'paid')->count();
        return response()->json([
            'success' => 1,

            'data' => ['new_order' => $new_order > 0 ? $new_order : $new_parcel_order, 'type' => $new_order > 0 ? 'store_order' : 'parcel_order',]
        ]);
    }

    public function settings()
    {
        return view('delivery-agent-views.settings');
    }

    public function settings_update(Request $request)
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required|unique:delivery_men,email,' . auth('delivery_men')->id(),
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:delivery_men,phone,' . auth('delivery_men')->id(),
        ], [
            'f_name.required' => translate('messages.first_name_is_required'),
            'l_name.required' => translate('messages.Last name is required!'),
        ]);

        $delivery_men = DeliveryMan::find(auth('delivery_men')->id());

        if ($request->has('image')) {
            $image_name = Helpers::update('delivery-man/', $delivery_men->image, 'png', $request->file('image'));
        } else {
            $image_name = $delivery_men['image'];
        }


        $delivery_men->f_name = $request->f_name;
        $delivery_men->l_name = $request->l_name;
        $delivery_men->email = $request->email;
        $delivery_men->phone = $request->phone;
        $delivery_men->image = $image_name;
        $delivery_men->save();
        Toastr::success(translate('messages.profile_updated_successfully'));
        return back();
    }

    public function settings_password_update(Request $request)
    {
        $request->validate([
            'password' => 'required|same:confirm_password',
            'confirm_password' => 'required',
        ]);

        $delivery_men = DeliveryMan::find(auth('delivery_men')->id());
        $delivery_men->password = bcrypt($request['password']);
        $delivery_men->save();
        Toastr::success(translate('messages.password_updated_successfully'));
        return back();
    }

    public function maintenance_mode()
    {
        $maintenance_mode = BusinessSetting::where('key', 'maintenance_mode')->first();
        if (isset($maintenance_mode) == false) {
            DB::table('business_settings')->insert([
                'key' => 'maintenance_mode',
                'value' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('business_settings')->where(['key' => 'maintenance_mode'])->update([
                'key' => 'maintenance_mode',
                'value' => $maintenance_mode->value == 1 ? 0 : 1,
                'updated_at' => now(),
            ]);
        }

        if (isset($maintenance_mode) && $maintenance_mode->value) {
            return response()->json(['message' => 'Maintenance is off.']);
        }
        return response()->json(['message' => 'Maintenance is on.']);
    }
}
