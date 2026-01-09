@extends('layouts.admin.app')

@section('title',translate('messages.update').' '.translate('messages.currency'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-notifications"></i> {{translate('messages.currency')}} {{translate('messages.update')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.currency.update',[$currency['id']])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('Currency Code')}}</label>
                                <input type="text" value="{{$currency['currency_code']}}" name="currency_code" class="form-control" placeholder="{{translate('Currency Code')}}" required maxlength="191">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('Currency Symbol')}}</label>
                                <input type="text" value="{{$currency['currency_symbol']}}" name="currency_symbol" class="form-control"
                                    placeholder="{{translate('Currency Symbol')}}" required maxlength="191">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('Country Code')}}</label>
                                <input type="text" value="{{$currency['country_code']}}" name="country_code" class="form-control"
                                    placeholder="{{translate('Country Code')}}" required>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('Exchange Rate')}}</label>
                                <input type="text" value="{{$currency['exchange_rate']}}" name="exchange_rate" class="form-control"
                                    placeholder="{{translate('Currency Code')}}" required maxlength="191">
                            </div>
                        </div>
                     
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-primary">{{translate('messages.submit')}}</button>
                </form>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection