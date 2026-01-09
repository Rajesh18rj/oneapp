@extends('layouts.landing.app')
@section('title', translate('messages.courier_Signup'))
@push('css_or_js')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css" />
@endpush

@section('content')
    <section class="m-0">
        <div class="container">
            <!-- Page Header -->

            @php($deliveryman_deposit_amount = \App\Models\BusinessSetting::where('key', 'deliveryman_deposit_amount')->first())
            @php($deliveryman_deposit = \App\Models\BusinessSetting::where('key', 'deliveryman_deposit')->first())

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm mb-2 mb-sm-0">
                        <h1 class="page-header-title text-center"><i class="tio-add-circle-outlined"></i>
                            {{ translate('messages.courier_application') }}</h1>
                    </div>
                </div>
            </div>


            @if ($deliveryman_deposit->value)
                <div class="alert alert-info">
                    Please note: A deposit of
                    {{ \App\CentralLogics\Helpers::format_currency($deliveryman_deposit_amount->value) }} is required for
                    delivery men or agencies
                    to finalize registration. Kindly transfer
                    the initial deposit to the following bank account to proceed with your sign-up. Verification will be
                    done
                    manually upon receipt of payment. Simply upload a screenshot of the receipt and the transferred amount.
                    Thank you

                    <br />
                    <br />
                    <ul style="font-weight: bold">
                        <li> Account Name: [Your Name or Company Name]</li>
                        <li>Account Number: [Your Account Number]</li>
                        <li>Bank Name: [Your Bank Name]</li>
                        <li> Branch: [Your Bank Branch]</li>
                        <li>IFSC Code: [IFSC Code]</li>
                    </ul>

                    <a href="#initial-deposit-section">Click here to upload screenshot</a>

                </div>
            @endIf



            <!-- End Page Header -->
            <div class="row">
                <div class="card shadow-sm col-12">
                    <form class="card-body" action="{{ route('deliveryman.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <small class="nav-subtitle">{{ translate('messages.deliveryman') }}

                            {{ translate('messages.info') }}</small>
                        <br>

                        <small class="nav-subtitle text-capitalize">{{ translate('messages.register') }}</small> -
                        <br>
                        <div class="row mt-3">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.first') }}
                                        {{ translate('messages.name') }}</label><span
                                        style="color:red;font-size:18px;"><strong>*</strong></span>
                                    <input type="text" name="f_name" class="form-control"
                                        placeholder="{{ translate('messages.first') }} {{ translate('messages.name') }}"
                                        required value="{{ old('f_name') }}">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.last') }}
                                        {{ translate('messages.name') }}</label><span
                                        style="color:red;font-size:18px;"><strong>*</strong></span>
                                    <input type="text" name="l_name" class="form-control"
                                        placeholder="{{ translate('messages.last') }} {{ translate('messages.name') }}"
                                        value="{{ old('l_name') }}" required>
                                </div>
                            </div>

                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">Type of Courier <span
                                            style="color:red;font-size:18px;"><strong>*</strong></span></label>
                                    <select name="delivery_type" class="form-control" id="delivery_type">
                                        <option value="">Select Type</option>
                                        <option value="individual">Individual</option>
                                        <option value="agent">Agent</option>
                                    </select>
                                </div>
                            </div>



                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="phone">{{ translate('messages.phone') }}</label><span
                                        style="color:red;font-size:18px;"><strong>*</strong></span>
                                    <div class="input-group">
                                        <input type="tel" name="tel" id="phone" placeholder="Ex : 017********"
                                            class="form-control" value="{{ old('tel') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.verification_code') }}</label><span
                                        style="color:red;font-size:18px;"><strong>*</strong></span>
                                    <input type="text" name="verification_code" class="form-control"
                                        placeholder="Ex : Verification Code" value="{{ old('verification_code') }}"
                                        required>
                                </div>
                                <div class="col-md-4 col-12">
                                    <button type="button" class="btn btn-primary float-right"
                                        onclick="sendVerificationCode()">Send SMS</button>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.password') }}</label><span
                                        style="color:red;font-size:18px;"><strong>*</strong></span>
                                    <input type="password" name="password" class="form-control" placeholder="Ex : Password"
                                        value="{{ old('password') }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.email') }}</label><span
                                        style="color:red;font-size:18px;"><strong>*</strong></span>
                                    <input type="email" name="email" class="form-control"
                                        placeholder="Ex : ex@example.com" value="{{ old('email') }}" required>
                                </div>
                            </div>

                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="phone">{{ translate('messages.city') }}</label><span
                                        style="color:red;font-size:18px;"><strong>*</strong></span>
                                    <div class="input-group">
                                        <input type="text" name="city" id="city" placeholder="Enter City"
                                            class="form-control" value="{{ old('city') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="toggle-switch toggle-switch-sm" for="toggleColumn_index"><span
                                            style="color:red;font-size:18px;"><strong>*</strong></span>
                                        <input type="checkbox" id="terms" name="terms" class="toggle-switch-input"
                                            required="">

                                    </label>
                                    you have read and agree to comply with the One app plus Terms and Conditions.Courier
                                    Agreement and Requriements. A copy of these documents well be available to you via the
                                    One app plus Driver platfrom.
                                </div>
                            </div>
                        </div>

                        <small class="nav-subtitle text-capitalize">{{ translate('messages.basic') }}
                            {{ translate('messages.information') }}</small> -
                        <div class="row">


                            <div class="col-sm-3 col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.zone') }}<span
                                            style="color:red;font-size:18px;"><strong>*</strong></span></label>
                                    <select name="zone_id" id="zone_id" class="form-control" required
                                        data-placeholder="{{ translate('messages.select') }} {{ translate('messages.zone') }}">
                                        <option value="" readonly="true" hidden="true">
                                            {{ translate('messages.select') }}
                                            {{ translate('messages.zone') }}</option>
                                        @foreach (\App\Models\Zone::active()->get() as $zone)
                                            @if (isset(auth('admin')->user()->zone_id))
                                                @if (auth('admin')->user()->zone_id == $zone->id)
                                                    <option value="{{ $zone->name }}" selected>{{ $zone->name }}
                                                    </option>
                                                @endif
                                            @else
                                                <option value="{{ $zone->name }}">{{ $zone->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-3 col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.sub_zone') }}<span
                                            style="color:red;font-size:18px;"><strong>*</strong></span></label>
                                    <select name="sub_zone_id" id="sub_zone_id" class="form-control" required
                                        data-placeholder="{{ translate('messages.select') }} {{ translate('messages.sub_zone') }}">
                                        <option value="" readonly="true" hidden="true">
                                            {{ translate('messages.select') }}
                                            {{ translate('messages.sub_zone') }}</option>

                                    </select>
                                </div>
                            </div>




                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.vehicle_plate_number') }}</label><span
                                        style="color:red;font-size:18px;"><strong>*</strong></span>
                                    <input type="text" name="vehicle_plate_number" class="form-control"
                                        value="{{ old('vehicle_plate_number') }}" required>
                                </div>
                            </div>

                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.vehicle_insurance') }}</label><span
                                        style="color:red;font-size:18px;"><strong>*</strong></span>
                                    <input type="text" name="vehicle_insurance" class="form-control"
                                        value="{{ old('vehicle_insurance') }}" required>
                                </div>
                            </div>

                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.license') }}</label><span
                                        style="color:red;font-size:18px;"><strong>*</strong></span>
                                    <input type="text" name="license" class="form-control"
                                        value="{{ old('license') }}" required>
                                </div>
                            </div>



                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.vehicle') }}
                                        {{ translate('messages.type') }}<span
                                            style="color:red;font-size:18px;"><strong>*</strong></span></label>
                                    <select name="vehicle_type_id" class="form-control">
                                        <option value="">Select Vehicle</option>



                                        @foreach (\App\Models\VehicleType::all() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>



                            <div class="col-sm-6 col-12" id="logistic_type">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">Logistics type <span
                                            style="color:red;font-size:18px;"><strong>*</strong></span></label>
                                    <select name="logistics_type" class="form-control">
                                        <option value="">Logistics type</option>
                                        <option value="bike">Bike</option>
                                        <option value="car">Cart</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-sm-6 col-12" id="gst">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">GST (%)<span
                                            style="color:red;font-size:18px;"><strong>*</strong></span></label>
                                    <select name="gst" class="form-control">
                                        <option value="">GST</option>
                                        <option value="5">5%</option>
                                        <option value="7">7%</option>
                                    </select>
                                </div>
                            </div>


                        </div>

                        <br />



                        <div class="row d-flex">
                            <div class="col-md-6 col-12" id="gst_certificate">
                                <div class="form-group">
                                    <center class="pt-4">
                                        <img style="height: 200px;border: 1px solid; border-radius: 10px;"
                                            id="gst_certificate_viewer"
                                            src="{{ asset('public/assets/admin/img/400x400/img2.jpg') }}"
                                            alt="delivery-man image" />
                                    </center>
                                    <label class="input-label">{{ translate('messages.GST') }}
                                        {{ translate('messages.Certificate') }}<small style="color: red">* (
                                            {{ translate('messages.ratio') }} 1:1 )</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="gst_certificate" class="form-control customFileEg"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12" id="pan_card">
                                <div class="form-group">
                                    <center class="pt-4">
                                        <img style="height: 200px;border: 1px solid; border-radius: 10px;"
                                            id="pan_card_viewer"
                                            src="{{ asset('public/assets/admin/img/400x400/img2.jpg') }}"
                                            alt="delivery-man image" />
                                    </center>
                                    <label class="input-label">{{ translate('messages.PAN') }}
                                        {{ translate('messages.Card') }}</label>
                                    <div class="custom-file">
                                        <input type="file" name="pan_card" class="form-control customFileEg"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    </div>
                                </div>
                            </div>



                            <div class="col-md-6 col-12" id="cin_certificate">
                                <div class="form-group">
                                    <center class="pt-4">
                                        <img style="height: 200px;border: 1px solid; border-radius: 10px;"
                                            id="cin_certificate_viewer"
                                            src="{{ asset('public/assets/admin/img/400x400/img2.jpg') }}"
                                            alt="delivery-man image" />
                                    </center>
                                    <label class="input-label">{{ translate('messages.CIN') }}
                                        {{ translate('messages.Certificate') }}</label>
                                    <div class="custom-file">
                                        <input type="file" name="cin_certificate" class="form-control customFileEg"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6 col-12" id="passbook_image">
                                <div class="form-group">
                                    <center class="pt-4">
                                        <img style="height: 200px;border: 1px solid; border-radius: 10px;"
                                            id="passbook_image_viewer"
                                            src="{{ asset('public/assets/admin/img/400x400/img2.jpg') }}"
                                            alt="delivery-man image" />
                                    </center>
                                    <label class="input-label">{{ translate('messages.Passbook') }}
                                        {{ translate('messages.Image') }}</label>
                                    <div class="custom-file">
                                        <input type="file" name="passbook_image" class="form-control customFileEg"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    </div>
                                </div>
                            </div>

                        </div>




                        <br>

                        <div class="row d-flex">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <center class="pt-4">
                                        <img style="height: 200px;border: 1px solid; border-radius: 10px;"
                                            id="image_viewer"
                                            src="{{ asset('public/assets/admin/img/400x400/img2.jpg') }}"
                                            alt="delivery-man image" />
                                    </center>
                                    <label class="input-label">{{ translate('messages.deliveryman') }}
                                        {{ translate('messages.image') }}<small style="color: red">* (
                                            {{ translate('messages.ratio') }} 1:1 )</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="image" class="form-control customFileEg"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <center class="pt-4">
                                        <img style="height: 200px;border: 1px solid; border-radius: 10px;"
                                            id="driver_license_image_viewer"
                                            src="{{ asset('public/assets/admin/img/400x400/img2.jpg') }}"
                                            alt="delivery-man image" />
                                    </center>
                                    <label class="input-label">{{ translate('messages.driver') }}
                                        {{ translate('messages.License') }}</label>
                                    <div class="custom-file">
                                        <input type="file" name="driver_license_image"
                                            class="form-control customFileEg"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row d-flex">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <center class="pt-4">
                                        <img style="height: 200px;border: 1px solid; border-radius: 10px;"
                                            id="vehicle_registration_image_viewer"
                                            src="{{ asset('public/assets/admin/img/400x400/img2.jpg') }}"
                                            alt="delivery-man image" />
                                    </center>
                                    <label class="input-label">{{ translate('messages.vehicle') }}
                                        {{ translate('messages.registration') }}</label>
                                    <div class="custom-file">
                                        <input type="file" name="vehicle_registration_image"
                                            class="form-control customFileEg"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <center class="pt-4">
                                        <img style="height: 200px;border: 1px solid; border-radius: 10px;"
                                            id="vehicle_insurance_image_viewer"
                                            src="{{ asset('public/assets/admin/img/400x400/img2.jpg') }}"
                                            alt="delivery-man image" />
                                    </center>
                                    <label class="input-label">{{ translate('messages.vehicle') }}
                                        {{ translate('messages.insurance') }}</label>
                                    <div class="custom-file">
                                        <input type="file" name="vehicle_insurance_image"
                                            class="form-control customFileEg"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.identity') }}
                                        {{ translate('messages.type') }}</label>
                                    <select name="identity_type" class="form-control">
                                        <option value="passport">{{ translate('messages.passport') }}</option>
                                        <option value="driving_license">{{ translate('messages.driving') }}
                                            {{ translate('messages.license') }}</option>
                                        <option value="nid">{{ translate('messages.nid') }}</option>
                                        <option value="restaurant_id">{{ translate('messages.store') }}
                                            {{ translate('messages.id') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.identity') }}
                                        {{ translate('messages.number') }}</label>
                                    <input type="text" name="identity_number" class="form-control"
                                        value="{{ old('identity_number') }}" placeholder="Ex : DH-23434-LS" required>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.driver_license_expiry_date') }}
                                        {{ translate('messages.type') }}</label>
                                    <input name="driver_license_expiry_date" class="form-control" type="date">

                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.deliveryman') }}
                                        {{ translate('messages.type') }}</label>
                                    <select name="earning" class="form-control">
                                        <option value="1">{{ translate('messages.freelancer') }}</option>
                                        <option value="0">{{ translate('messages.salary_based') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.identity') }}
                                        {{ translate('messages.image') }}</label>
                                    <div>
                                        <div class="row" id="coba"></div>
                                    </div>
                                </div>
                            </div>
                        </div>




                        @if ($deliveryman_deposit->value)
                            <small class="nav-subtitle text-capitalize">{{ translate('messages.Payment') }}
                                {{ translate('messages.information') }}</small> -
                            <br><br />

                            <div class="row d-flex" id="initial-deposit-section">

                                <div class="col-md-6 col-12 d-flex align-items-end">
                                    <div class="form-group w-100">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.Initial_Deposit_Amount') }}
                                        </label>
                                        <input type="text" name="deposit_amount" class="form-control"
                                            value="{{ old('deposit_amount') }}"
                                            placeholder="{{ translate('messages.Initial_Deposit_Amount') }}" required>
                                    </div>
                                </div>


                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <center class="pt-4">
                                            <img style="height: 200px;border: 1px solid; border-radius: 10px;"
                                                id="initial_deposit_receipt_viewer"
                                                src="{{ asset('public/assets/admin/img/400x400/img2.jpg') }}"
                                                alt="delivery-man image" />
                                        </center>
                                        <label
                                            class="input-label">{{ translate('messages.Initial_Deposit_Receipt') }}</label>
                                        <div class="custom-file">
                                            <input type="file" name="initial_deposit_receipt"
                                                class="form-control customFileEg"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        </div>
                                    </div>
                                </div>




                            </div>
                        @endIf






                        <small class="nav-subtitle text-capitalize">{{ translate('messages.deposit') }}
                            {{ translate('messages.information') }}</small> -
                        <br><br />

                        <div class="row d-flex">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.holder_name') }}
                                    </label>
                                    <input type="text" name="holder_name" class="form-control"
                                        value="{{ old('holder_name') }}" placeholder="Holder Name" required>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.bsb') }}
                                        {{ translate('messages.number') }}</label>
                                    <input type="text" name="bsb_number" class="form-control"
                                        value="{{ old('bsb_number') }}" placeholder="BSB Number" required>
                                </div>
                            </div>
                        </div>



                        <div class="row d-flex">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.account_number') }}
                                    </label>
                                    <input type="text" name="account_number" class="form-control"
                                        value="{{ old('holder_name') }}" placeholder="Account Number" required>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.indian_business') }}
                                        {{ translate('messages.number') }}</label>
                                    <input type="text" name="business_number" class="form-control"
                                        value="{{ old('business_number') }}" placeholder="Indian Business Number"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="row d-flex">
                            <div class="col-md-6 col-12">
                                <div class="form-group mb-12">

                                    <label class="input-label" for="exampleFormControlInput1">Do you have Insulated
                                        Courier Bag? <span
                                            style="color:red;font-size:18px;"><strong>*</strong></span></label>
                                    <br>

                                    <label class="toggle-switch toggle-switch-sm" for="toggleColumn_index"><span
                                            style="color:red;font-size:18px;"></span>
                                        <input type="radio" class="toggle-switch-input courier_bag" id="courier_bag"
                                            name="courier_bag" value="1">

                                    </label>
                                    Yes
                                    <label class="toggle-switch toggle-switch-sm" for="toggleColumn_index"><span
                                            style="color:red;font-size:18px;"></span>
                                        <input type="radio" class="toggle-switch-input courier_bag"
                                            id="toggleColumn_index" name="courier_bag" value="0">

                                    </label>
                                    No

                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">Order delivery location
                                        limit(In Km)</label>
                                    <input type="number" name="business_number" class="form-control"
                                        value="{{ old('order_delivery_location_limit') }}"
                                        placeholder="Order delivery location limit" required>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group mb-12">

                                    <label class="input-label" for="exampleFormControlInput1">Do You Have High-Visibility
                                        Personal Protective Equipment (PPE)? <span
                                            style="color:red;font-size:18px;"><strong>*</strong></span></label>
                                    <br>

                                    <label class="toggle-switch toggle-switch-sm" for="toggleColumn_index"><span
                                            style="color:red;font-size:18px;"></span>
                                        <input type="radio" class="toggle-switch-input courier_bag" id="ppe"
                                            name="ppe" value="1">

                                    </label>
                                    Yes
                                    <label class="toggle-switch toggle-switch-sm" for="toggleColumn_index"><span
                                            style="color:red;font-size:18px;"></span>
                                        <input type="radio" class="toggle-switch-input courier_bag"
                                            id="toggleColumn_index" name="ppe" value="0">

                                    </label>
                                    No

                                </div>

                            </div>

                            <div class="col-sm-6">
                                <div class="form-group mb-3">

                                    <label class="toggle-switch toggle-switch-sm" for="toggleColumn_index"><span
                                            style="color:red;font-size:18px;"></span>
                                        <input type="checkbox" class="toggle-switch-input" id="bank_term"
                                            name="bank_term">

                                    </label>
                                    Please confirm that your banking information has been entered correctly.
                                </div>
                            </div>

                            <div class="col-sm-12" style="margin-bottom:20px">

                                <span style="color:red;">To become a courier on the OneAppPlus Platfrom, you’re required to
                                    complete a background check. A background check usually takes 3 to 5 days to process,
                                    but can take up to 14 days.
                                    <br>If you've recently completed a background check, i.e. within 90 days of your signup,
                                    you can simply email it to us at <a
                                        href="mailto:courier@oneappplus.com">courier@oneappplus.com</a>

                                </span>

                            </div>
                        </div>
                        <button type="submit"
                            class="btn btn-primary float-right">{{ translate('messages.submit') }}</button>
                    </form>
                </div>
            </div>
        </div>

    </section>

@endsection

@push('script_2')
    <script src="{{ asset('public/assets/admin') }}/js/toastr.js"></script>
    {!! Toastr::message() !!}

    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}', Error, {
                    CloseButton: true,
                    ProgressBar: true
                });
            @endforeach
        </script>
    @endif
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput.min.js"
        integrity="sha512-QMUqEPmhXq1f3DnAVdXvu40C8nbTgxvBGvNruP6RFacy3zWKbNTmx7rdQVVM2gkd2auCWhlPYtcW2tHwzso4SA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"
        integrity="sha512-hkmipUFWbNGcKnR0nayU95TV/6YhJ7J9YUAkx4WLoIgrVr7w1NYz28YkdNFMtPyPeX1FrQzbfs3gl+y94uZpSw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/utils.min.js"
        integrity="sha512-lv6g7RcY/5b9GMtFgw1qpTrznYu1U4Fm2z5PfDTG1puaaA+6F+aunX+GlMotukUFkxhDrvli/AgjAu128n2sXw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <link rel="shortcut icon" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/img/flags.png"
        type="image/x-icon">
    <link rel="shortcut icon" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/img/flags@2x.png"
        type="image/x-icon">
    <script>
        function readURL(input, previewer) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#' + previewer + '_viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }


        function sendVerificationCode() {
            var phone = $("#phone").val();

            if (phone == '') {

                alert("Please enter phone number");
                return false;

            }

            phone = "+91" + phone;

            $.post({
                url: '{{ route('deliveryman.send-verification-code') }}',
                data: {
                    "phone": phone,
                    "_token": "{{ csrf_token() }}"
                },

                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {

                },
                complete: function() {
                    $('#loading').hide();
                },
            });

        }

        $(".customFileEg").change(function() {

            var name = $(this).attr("name");
            readURL(this, name);
        });
        @php($country = \App\Models\BusinessSetting::where('key', 'country')->first())
        var phone = $("#phone").intlTelInput({
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/js/utils.js",
            autoHideDialCode: true,
            autoPlaceholder: "ON",
            dropdownContainer: document.body,
            formatOnDisplay: true,
            hiddenInput: "phone",
            initialCountry: "{{ $country ? $country->value : auto }}",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        // $("#phone").on('change', function(){
        //     $(this).val(phone.getNumber());
        // })
    </script>
    <script src="{{ asset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script type="text/javascript">
        $(function() {

            $('#delivery_type').on('change', function() {
                // Check if the selected value is 'agent'
                if ($(this).val() === 'agent') {
                    // If 'agent' is selected, show the fields
                    $('#logistic_type').show();
                    $('#gst').show();
                    $('#gst_certificate').show();
                    $('#pan_card').show();
                    $('#cin_certificate').show();
                    $('#passbook_image').show();


                } else {
                    // If any other value is selected, hide the fields
                    $('#logistic_type').hide();
                    $('#gst').hide();
                    $('#gst_certificate').hide();
                    $('#pan_card').hide();
                    $('#cin_certificate').hide();
                    $('#passbook_image').hide();
                }
            });

            // Trigger the change event initially to set the initial state
            $('#delivery_type').trigger('change');


            $("#zone_id").on("change", function() {
                var zone_name = $(this).val();



                $.get({
                    url: "{{ route('deliveryman.get-sub-zones') }}",
                    data: {
                        "zone_name": zone_name,
                        "_token": "{{ csrf_token() }}"
                    },

                    beforeSend: function() {
                        $('#loading').show();
                    },
                    success: function(data) {
                        var htmlData = '';
                        var data = JSON.parse(data);
                        var sub_zone_data = data.sub_zone_data;
                        if (sub_zone_data.length > 0) {
                            $.each(sub_zone_data, function(k, val) {
                                htmlData = htmlData + "<option value=" + val.id + ">" +
                                    val.sub_zone + "</option>";
                            });

                            console.log("htmlData", htmlData);
                            $("#sub_zone_id").html(htmlData);

                        }
                    },
                    complete: function() {
                        $('#loading').hide();
                    },
                });

            });

            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: '120px',
                groupClassName: 'col-lg-2 col-md-4 col-sm-4 col-6',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{ asset('public/assets/admin/img/400x400/img2.jpg') }}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function(index, file) {

                },
                onRenderedPreview: function(index) {

                },
                onRemoveRow: function(index) {

                },
                onExtensionErr: function(index, file) {
                    toastr.error(
                        '{{ translate('messages.please_only_input_png_or_jpg_type_file') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                },
                onSizeErr: function(index, file) {
                    toastr.error('{{ translate('messages.file_size_too_big') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>
@endpush
