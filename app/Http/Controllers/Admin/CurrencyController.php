<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Currency;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;


class CurrencyController extends Controller
{
    function index()
    {
        $currencies = Currency::orderBy('status', 'desc')->paginate(config('default_pagination'));
        return view('admin-views.currency.index', compact('currencies'));
    }

    public function edit($id)
    {
        $currency = Currency::findOrFail($id);
        return view('admin-views.currency.edit', compact('currency'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'currency_code' => 'required|max:191',
            'country_code' => 'required|unique:currencies,country_code',
            'currency_symbol' => 'required|max:1000',
            'exchange_rate' => 'required',
        ]);

        $currency = Currency::findOrFail($id);
        $currency->exchange_rate = $request->exchange_rate;
        $currency->currency_symbol = $request->currency_symbol;
        $currency->currency_code= $request->currency_code;
        $currency->country_code= $request->country_code;
        $currency->save();

    
        Toastr::success(translate('messages.currency').' '.translate('messages.updated_successfully'));
        return back();
    }

    public function status(Request $request)
    {
        $currency = Currency::findOrFail($request->id);
        $currency->status = $request->status;
        $currency->save();
        Toastr::success(translate('Currency Status updated'));
        return back();
    }

    public function delete(Request $request)
    {
        $currency = Currency::findOrFail($request->id);
        $currency->delete();
        Toastr::success(translate('Currency Deleted Successfully'));
        return back();
    }
}