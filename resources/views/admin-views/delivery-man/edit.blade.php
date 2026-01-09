@extends('layouts.admin.app')
 <?php
 $type = $delivery_man['delivery_type'];
if($type == 'agent'){
?>
@section('title','Update delivery agent')

<?php } else { ?>

@section('title','Update delivery man')

<?php } ?>


@push('css_or_js')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css" integrity="sha512-gxWow8Mo6q6pLa1XH/CcH8JyiSDEtiwJV78E+D+QP0EVasFs8wKXq16G8CLD4CJ2SnonHr4Lm/yY2fSI2+cbmw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .iti{
            width:100%;
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
<?php
                    if($type == 'agent'){
?>
  <h1 class="page-header-title"><i class="tio-edit"></i> 

                    {{translate('messages.update')}} {{translate('messages.delivery agent')}}

<?php } else { ?>

 <h1 class="page-header-title"><i class="tio-edit"></i> 

                    {{translate('messages.update')}} {{translate('messages.deliveryman')}}
<?php } ?>
                  
                </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
	    
          <div class="row">
          
          <?php
        
          
          ?>
     @php($deliveryman_deposit_amount = \App\Models\BusinessSetting::where('key', 'deliveryman_deposit_amount')->first())
            @php($deliveryman_deposit = \App\Models\BusinessSetting::where('key', 'deliveryman_deposit')->first())
            
              @php($deposit_note = \App\Models\BusinessSetting::where('key', 'deposit_note')->first())
                @php($deposit_note = $deposit_note->value ?? '')
                            
                        
                <div class="alert alert-info">
                    <?php
                  $deposit_note = str_replace('{deliveryman_deposit_amount}', $deliveryman_deposit_amount->value, $deposit_note);

                    ?>
                    {!! $deposit_note !!}
               </div>
            
            
                            <form action="{{route('admin.delivery-man.update',[$delivery_man['id']])}}" method="post" enctype="multipart/form-data">
                <div class="border m-3 p-3 col-12">
                    
                        @csrf
                       <?php
                          if($type == 'agent'){
                        ?>
                        <small class="nav-subtitle">{{ translate('messages.delivery agent') }}
                        
                        <?php } else { ?>
                          
                          <small class="nav-subtitle">{{ translate('messages.deliveryman') }}
                        <?php } ?>

                            {{ translate('messages.info') }}</small>
                        <br>


                        <br>
                        <div class="row mt-3">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.first') }}
                                        {{ translate('messages.name') }} <small
                                        style="color:red;font-size:18px;">*</small></label>
                                    <input type="text" name="f_name" class="form-control"
                                        placeholder="{{ translate('messages.first') }} {{ translate('messages.name') }}"
                                        required value="{{$delivery_man['f_name']}}">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.last') }}
                                        {{ translate('messages.name') }} <small
                                        style="color:red;font-size:18px;">*</small></label>
                                    <input type="text" name="l_name" class="form-control"
                                        placeholder="{{ translate('messages.last') }} {{ translate('messages.name') }}"
                                      value="{{$delivery_man['l_name']}}" required>
                                </div>
                            </div>
                            
                              <input type="hidden" name="delivery_type" class="form-control" id="delivery_type" value="{{ $delivery_man->delivery_type }}">

                         



                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="phone">{{ translate('messages.phone') }}<small
                                        style="color:red;font-size:18px;">*</small></label>
                                    <div class="input-group">
                                        <input type="tel" name="tel" id="phone" placeholder="Ex : 017********"
                                            class="form-control" value="{{$delivery_man['phone']}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.verification_code') }} <small
                                        style="color:red;font-size:18px;">*</small></label>
                                    <input type="text" name="verification_code" class="form-control"
                                        placeholder="Ex : Verification Code" value="{{ old('verification_code') }}"
                                        >
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
                                        for="exampleFormControlInput1">{{ translate('messages.password') }} <small
                                        style="color:red;font-size:18px;">*</small></label>
                                    <input type="password" name="password" class="form-control" placeholder="Ex : Password"
                                        value="{{ old('password') }}">
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.email') }} <small
                                        style="color:red;font-size:18px;">*</small></label>
                                    <input type="email" name="email" class="form-control"
                                        placeholder="Ex : ex@example.com" value="{{$delivery_man['email']}}" required>
                                </div>
                            </div>

                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="phone">{{ translate('messages.city') }} <small
                                        style="color:red;font-size:18px;">*</small></label>
                                    <div class="input-group">
                                        <input type="text" name="city" id="city" placeholder="Enter City"
                                            class="form-control" value="{{$delivery_man['city']}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="" for="toggleColumn_index"><small
                                        style="color:red;font-size:18px;">*</small>
                                        <input type="checkbox" id="terms" name="terms" class=""
                                            required="" checked>

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
                                        for="exampleFormControlInput1">{{ translate('messages.zone') }}<small
                                        style="color:red;font-size:18px;">*</small></label>
                                        
                                 
                                        
                                       
                                        
                                    <select name="zone_id" id="zone_id" class="form-control" required
                                        data-placeholder="{{ translate('messages.select') }} {{ translate('messages.zone') }}">
                                        <option value="" readonly="true" hidden="true">
                                            {{ translate('messages.select') }}
                                            {{ translate('messages.zone') }}</option>
                                         @foreach(\App\Models\Zone::all() as $zone)
                                    @if(isset(auth('admin')->user()->zone_id))
                                        @if(auth('admin')->user()->zone_id == $zone->id)
                                            <option value="{{$zone->id}}" {{$zone->id == $delivery_man->zone_id?'selected':''}}>{{$zone->name}}</option>
                                        @endif
                                    @else
                                    <option value="{{$zone->id}}" {{$zone->id == $delivery_man->zone_id?'selected':''}}>{{$zone->name}}</option>
                                    @endif
                                @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-3 col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.sub_zone') }}<small
                                        style="color:red;font-size:18px;">*</small></label>
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
                                        for="exampleFormControlInput1">{{ translate('messages.vehicle_plate_number') }} <small
                                        style="color:red;font-size:18px;display:none;" id="vehicle_plate_number_required_section">*</small> </label>
                                    <input type="text" name="vehicle_plate_number" class="form-control"
                                         value="{{$delivery_man['vehicle_plate_number']}}" id="vehicle_plate_number">
                                </div>
                            </div>

                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.vehicle_insurance') }}<small
                                        style="color:red;font-size:18px;">*</small> </label>
                                    <input type="text" name="vehicle_insurance" class="form-control"
                                        value="{{$delivery_man['vehicle_insurance']}}"  required>
                                </div>
                            </div>

                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.license') }} <small
                                        style="color:red;font-size:18px;">*</small>  </label>
                                    <input type="text" name="license" class="form-control"
                                         value="{{$delivery_man['license']}}"  required>
                                </div>
                            </div>



                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.vehicle') }}
                                        {{ translate('messages.type') }} <small
                                        style="color:red;font-size:18px;">*</small>  </label>
                                    <select name="vehicle_type_id" class="form-control">
                                        <option value="">Select Vehicle</option>



                                         @foreach (\App\Models\VehicleType::orderBy('id', 'desc')->get() as $item)
                                            <option value="{{ $item->id }}" {{$delivery_man->vehicle_type_id == $item->id ?'selected':''}} >{{ $item->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>



                            <div class="col-sm-6 col-12" id="logistic_type">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">Logistics type <small
                                        style="color:red;font-size:18px;">*</small> </label>
                                    <select name="logistics_type" class="form-control">
                                        <option value="">Logistics type</option>
                                        @foreach (\App\Models\LogisticType::orderBy('id', 'desc')->get() as $item)
                                           <option value="{{ $item->id }}" {{$delivery_man->logistics_type == $item->id ?'selected':''}} >{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-sm-6 col-12" id="gst">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">GST (%) <small
                                        style="color:red;font-size:18px;">*</small> </label>
                                    <select name="gst" class="form-control">
                                        <option value="">GST</option>
                                        <option value="5" {{$delivery_man->gst == '5' ?'selected':''}} >5%</option>
                                        <option value="7" {{$delivery_man->gst == '7' ?'selected':''}}>7%</option>
                                    </select>
                                </div>
                            </div>


                        </div>

                        <br />



                        <div class="row d-flex">
                            @php($is_gst_certificate_enabled = \App\Models\BusinessSetting::where('key', 'is_gst_certificate_enabled')->first())
                            @php($is_gst_certificate_enabled = $is_gst_certificate_enabled ? $is_gst_certificate_enabled->value : 0)
                                <input type="hidden" value="{{ $is_gst_certificate_enabled }}" name="is_gst_certificate_enabled" id="is_gst_certificate_enabled">
                            <div class="col-md-6 col-12" id="gst_certificate">
                                <div class="form-group">
                                    <center class="pt-4">
                                    @php($gst_certificate = $delivery_man['gst_certificate'] ?? '')
                                    <input type="hidden" id="gst_certificate_value" value="{{$gst_certificate}}">
                                                    <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/documents/' . $gst_certificate) }}" title="View large image" id="gst_certificate_viewer" />
                                    
                                     
                                    </center>
                                    <label class="input-label">{{ translate('messages.GST') }}
                                        {{ translate('messages.Certificate') }}<small style="color: red">* (
                                            {{ translate('messages.ratio') }} 1:1 )</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="gst_certificate" class="form-control customFileEg"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12" id="pan_card">
                              @php($is_pan_card_enabled = \App\Models\BusinessSetting::where('key', 'is_pan_card_enabled')->first())
                               @php($is_pan_card_enabled = $is_pan_card_enabled ? $is_pan_card_enabled->value : 0)
                              
                                <div class="form-group">
                                    <center class="pt-4">
                                    
                                              @php($pan_card = $delivery_man['pan_card'] ?? '')
                                            <input type="hidden" id="pan_card_value" value="{{$pan_card}}">   
                                        <img style="height: 200px;border: 1px solid; border-radius: 10px;"
                                            id="pan_card_viewer" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"
                                           src="{{ asset('storage/app/public/documents/' . $pan_card) }}" 
                                            alt="delivery-man image" />
                                    </center>
                                    <label class="input-label">{{ translate('messages.PAN') }}
                                        {{ translate('messages.Card') }} <small style="color: red">* (
                                            {{ translate('messages.ratio') }} 1:1 )</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="pan_card" class="form-control customFileEg"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    </div>
                                </div>
                            </div>



                            <div class="col-md-6 col-12" id="cin_certificate">
                              @php($is_cin_certificate_enabled = \App\Models\BusinessSetting::where('key', 'is_cin_certificate_enabled')->first())
                            @php($is_cin_certificate_enabled = $is_cin_certificate_enabled ? $is_cin_certificate_enabled->value : 0)
                         
                             <input type="hidden" value="{{ $is_cin_certificate_enabled }}" name="is_cin_certificate_enabled" id="is_cin_certificate_enabled">
                                <div class="form-group">
                                    <center class="pt-4">
                                    
                                     @php($cin_certificate = $delivery_man['cin_certificate'] ?? '')
                                         <input type="hidden" id="cin_certificate_value" value="{{$cin_certificate}}">
                                        <img style="height: 200px;border: 1px solid; border-radius: 10px;"
                                            id="cin_certificate_viewer"
                                             src="{{ asset('storage/app/public/documents/' . $cin_certificate) }}" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"
                                           />
                                    </center>
                                    <label class="input-label">{{ translate('messages.CIN') }}
                                        {{ translate('messages.Certificate') }}</label>
                                    <div class="custom-file">
                                        <input type="file" name="cin_certificate" class="form-control customFileEg"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    </div>
                                </div>
                            </div>
                            
                                <div class="col-md-6 col-12" id="tfn_certificate">
                              @php($is_tfn_certificate_enabled = \App\Models\BusinessSetting::where('key', 'is_tfn_certificate_enabled')->first())
                              @php($is_tfn_certificate_enabled = $is_tfn_certificate_enabled ? $is_tfn_certificate_enabled->value : 0)
                             <input type="hidden" value="{{ $is_tfn_certificate_enabled }}" name="is_tfn_certificate_enabled" id="is_tfn_certificate_enabled">
                                         
                                         
                                           @php($tfn_certificate = $delivery_man['tfn_certificate'] ?? '')
                                         <input type="hidden" id="tfn_certificate_value" value="{{$tfn_certificate}}">
                             
                             
                                <div class="form-group">
                                    <center class="pt-4">
                                        <img style="height: 200px;border: 1px solid; border-radius: 10px;"
                                            id="tfn_certificate_viewer"
                                            src="{{ asset('storage/app/public/documents/' . $tfn_certificate) }}"  onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"
                                             />
                                    </center>
                                    <label class="input-label">{{ translate('messages.TFN') }}
                                        {{ translate('messages.Certificate') }}<small style="color: red" id="tfn_certificate_required">* </small></label>
                                    <div class="custom-file">
                                        <input type="file" name="tfn_certificate" class="form-control customFileEg"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    </div>
                                </div>
                            </div>
                            
                             <div class="col-md-6 col-12" id="acn_certificate">
                              @php($is_acn_certificate_enabled = \App\Models\BusinessSetting::where('key', 'is_acn_certificate_enabled')->first())
                            @php($is_acn_certificate_enabled = $is_cin_certificate_enabled ? $is_acn_certificate_enabled->value : 0)
                            <input type="hidden" value="{{ $is_acn_certificate_enabled }}" name="is_acn_certificate_enabled" id="is_acn_certificate_enabled">
                                
                                      @php($acn_certificate = $delivery_man['acn_certificate'] ?? '')
                                         <input type="hidden" id="acn_certificate_value" value="{{$acn_certificate}}">
                                <div class="form-group">
                                    <center class="pt-4">
                                        <img style="height: 200px;border: 1px solid; border-radius: 10px;"
                                            id="acn_certificate_viewer"
                                            src="{{ asset('storage/app/public/documents/' . $acn_certificate) }}"  onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"
                                           />
                                    </center>
                                    <label class="input-label">{{ translate('messages.ACN') }}
                                        {{ translate('messages.Certificate') }}<small style="color: red" id="acn_certificate_required">* </small></label>
                                    <div class="custom-file">
                                        <input type="file" name="acn_certificate" class="form-control customFileEg"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    </div>
                                </div>
                            </div>
                            
                           
                            
                                 <div class="col-md-6 col-12" id="abn_certificate">
                               @php($is_abn_certificate_enabled = \App\Models\BusinessSetting::where('key', 'is_abn_certificate_enabled')->first())
                            @php($is_abn_certificate_enabled = $is_cin_certificate_enabled ? $is_abn_certificate_enabled->value : 0)
                              <input type="hidden" value="{{ $is_abn_certificate_enabled }}" name="is_abn_certificate_enabled" id="is_abn_certificate_enabled">
                                <div class="form-group">
                                
                                 @php($acn_certificate = $delivery_man['acn_certificate'] ?? '')
                                         <input type="hidden" id="acn_certificate_value" value="{{$acn_certificate}}">
                                    <center class="pt-4">
                                    
                                        <img style="height: 200px;border: 1px solid; border-radius: 10px;"
                                            id="abn_certificate_viewer"
                                             src="{{ asset('storage/app/public/documents/' . $acn_certificate) }}"  onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"
                                          />
                                    </center>
                                    <label class="input-label">{{ translate('messages.ABN') }}
                                        {{ translate('messages.Certificate') }}<small style="color: red" id="abn_certificate_required">* </small></label>
                                    <div class="custom-file">
                                        <input type="file" name="abn_certificate" class="form-control customFileEg"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="col-md-6 col-12" id="ein_certificate">
                            @php($is_ein_certificate_enabled = \App\Models\BusinessSetting::where('key', 'is_ein_certificate_enabled')->first())
                            @php($is_ein_certificate_enabled = $is_cin_certificate_enabled ? $is_ein_certificate_enabled->value : 0)
                             <input type="hidden" value="{{ $is_ein_certificate_enabled }}" name="is_ein_certificate_enabled" id="is_ein_certificate_enabled">
                                <div class="form-group">
                                    <center class="pt-4">
                                     @php($ein_certificate = $delivery_man['ein_certificate'] ?? '')
                                         <input type="hidden" id="ein_certificate_value" value="{{$ein_certificate}}">
                                        <img style="height: 200px;border: 1px solid; border-radius: 10px;"
                                            id="ein_certificate_viewer"
                                          src="{{ asset('storage/app/public/documents/' . $ein_certificate) }}"  onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"
                                            alt="delivery-man image" />
                                    </center>
                                    <label class="input-label">{{ translate('messages.EIN') }}
                                        {{ translate('messages.Certificate') }}<small style="color: red" id="ein_certificate_required">* </small></label>
                                    <div class="custom-file">
                                        <input type="file" name="ein_certificate" class="form-control customFileEg"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6 col-12" id="passbook_image">
                                <div class="form-group">
                                    <center class="pt-4">
                                    @php($passbook_image = $delivery_man['passbook_image'] ?? '')
                                        <img style="height: 200px;border: 1px solid; border-radius: 10px;"
                                            id="passbook_image_viewer"
                                           src="{{ asset('storage/app/public/documents/' . $passbook_image) }}" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"
                                            />
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
                                        @php($delivery_man_image = $delivery_man['image'] ?? '')
                                        
                                        <img style="height: 200px;border: 1px solid; border-radius: 10px;"
                                            id="image_viewer"
                                               src="{{ asset('storage/app/public/delivery-man/'.$delivery_man_image) }}"
                                            alt="delivery-man image" />
                                    </center>
                                    <label class="input-label">
                                        <?php
                                        if($type == 'agent'){
                                            ?>
                                        {{ translate('messages.delivery_agent') }}

                                    <?php } else { ?>
                                         {{ translate('messages.deliveryman') }}
                                    <?php } ?>
                                        {{ translate('messages.image') }}<small style="color: red">* (
                                            {{ translate('messages.ratio') }} 1:1 )</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="image" class="form-control customFileEg"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <center class="pt-4">
                                     @php($driver_license_image = $delivery_man['driver_license_image'] ?? '')
                                        <img style="height: 200px;border: 1px solid; border-radius: 10px;"
                                            id="driver_license_image_viewer"
                                            src="{{ asset('storage/app/public/driver-license-image/' . $driver_license_image) }}" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"
                                             />
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
                                  @php($vehicle_registration_image = $dm['vehicle_registration_image'] ?? '')
                                        <img style="height: 200px;border: 1px solid; border-radius: 10px;"
                                            id="vehicle_registration_image_viewer"
                                           src="{{ asset('storage/app/public/vehicle-registration-image/' . $vehicle_registration_image) }}" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"
                                             />
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
                                           @php($vehicle_insurance_image = $dm['vehicle_insurance_image'] ?? '')
                                        <img style="height: 200px;border: 1px solid; border-radius: 10px;"
                                            id="vehicle_insurance_image_viewer"
                                        src="{{ asset('storage/app/public/vehicle-insurance-image/' . $vehicle_insurance_image) }}" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"
                                            />
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
                                    <option
                                        value="passport" {{$delivery_man['identity_type']=='passport'?'selected':''}}>
                                        {{translate('messages.passport')}}
                                    </option>
                                    <option
                                        value="driving_license" {{$delivery_man['identity_type']=='driving_license'?'selected':''}}>
                                        {{translate('messages.driving')}} {{translate('messages.license')}}
                                    </option>
                                    <option value="nid" {{$delivery_man['identity_type']=='nid'?'selected':''}}>{{translate('messages.nid')}}
                                    </option>
                                    <option
                                        value="store_id" {{$delivery_man['identity_type']=='store_id'?'selected':''}}>
                                        {{translate('messages.store')}} {{translate('messages.id')}}
                                    </option>
                                </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.identity') }}
                                        {{ translate('messages.number') }}</label><span
                                        style="color:red;font-size:18px;"></span>
                                    <input type="text" name="identity_number" class="form-control"
                                       value="{{$delivery_man['identity_number']}}"  placeholder="Ex : DH-23434-LS" required>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.driver_license_expiry_date') }}
                                        {{ translate('messages.type') }}</label>
                                    <input name="driver_license_expiry_date" class="form-control" type="date" value="{{$delivery_man['driver_license_expiry_date']}}">

                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.deliveryman') }}
                                        {{ translate('messages.type') }}</label>
                                    <select name="earning" class="form-control">
                                             <option value="1" {{$delivery_man->earning?'selected':''}}>{{translate('messages.freelancer')}}</option>
                                    <option value="0" {{$delivery_man->earning?'':'selected'}}>{{translate('messages.salary_based')}}</option>
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




                        @if ($deliveryman_deposit->value && $deliveryman_deposit_amount->value > 0 && $deliveryman_deposit->value == "1")
                            <small class="nav-subtitle text-capitalize">{{ translate('messages.Payment') }}
                                {{ translate('messages.information') }}</small> -
                            <br><br />

                            <div class="row d-flex" id="initial-deposit-section">

                                <div class="col-md-6 col-12 d-flex align-items-end">
                                    <div class="form-group w-100">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.Initial_Deposit_Amount') }} <small
                                        style="color:red;font-size:18px;">*</small> 
                                        </label>
                                        <input type="text" name="deposit_amount" class="form-control"
                                           value="{{$delivery_man['deposit_amount']}}"  
                                            placeholder="{{ translate('messages.Initial_Deposit_Amount') }}" required>
                                    </div>
                                </div>


                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <center class="pt-4">
                                        @php($initial_deposit_receipt = $delivery_man['initial_deposit_receipt'] ?? '')
                                        
                                            <img style="height: 200px;border: 1px solid; border-radius: 10px;"
                                                id="initial_deposit_receipt_viewer"src="{{ asset('storage/app/public/deposit-receipt/' . $initial_deposit_receipt) }}" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"
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
                                           value="{{$delivery_man['holder_name']}}"  placeholder="Holder Name">
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.bsb') }}
                                        {{ translate('messages.number') }}</label>
                                    <input type="text" name="bsb_number" class="form-control"
                                        value="{{$delivery_man['bsb_number']}}"  placeholder="BSB Number">
                                </div>
                            </div>
                        </div>



                        <div class="row d-flex">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.account_Number') }}
                                    </label>
                                    <input type="text" name="account_number" class="form-control"
                                         value="{{$delivery_man['account_number']}}" placeholder="Account Number">
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.indian_business') }}
                                        {{ translate('messages.number') }}</label>
                                    <input type="text" name="business_number" class="form-control"
                                        value="{{$delivery_man['business_number']}}" placeholder="Indian Business Number"
                                        >
                                </div>
                            </div>
                        </div>
                        
                        <div class="row d-flex">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.bank_name') }}
                                    </label>
                                    <input type="text" name="bank_name" class="form-control"
                                           value="{{$delivery_man['bank_name']}}"  placeholder="Bank Name">
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.branch') }}</label>
                                    <input type="text" name="branch" class="form-control"
                                     value="{{$delivery_man['branch']}}"  placeholder="Branch"
                                       >
                                </div>
                            </div>
                        </div>
                        
                         <div class="row d-flex">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">IFSC Code
                                    </label>
                                    <input type="text" name="ifsc_code" class="form-control"
                                        value="{{$delivery_man['ifsc_code']}}" placeholder="IFSC Code">
                                </div>
                            </div>
                            
                              <div class="col-md-6 col-12">
                                <div class="form-group mb-12">

                                    <label class="input-label" for="exampleFormControlInput1">Do you have Insulated
                                        Courier Bag? <small
                                        style="color:red;font-size:18px;">*</small> </label>
                                    <br>

                                    <label class="" for="toggleColumn_index"><span
                                            style="color:red;font-size:18px;"></span>
                                        <input type="radio" class="courier_bag" id="courier_bag"
                                            name="courier_bag" value="1"  {{$delivery_man->courier_bag == '1' ?'checked':''}}>

                                    </label>
                                    Yes
                                    <label class="" for="toggleColumn_index"><span
                                            style="color:red;font-size:18px;"></span>
                                        <input type="radio" class="courier_bag"
                                            id="toggleColumn_index" name="courier_bag" value="0" {{$delivery_man->courier_bag == '0' ?'checked':''}}>

                                    </label>
                                    No

                                </div>
                            </div>

                           
                        </div>

                        <div class="row d-flex">
                          

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">Order delivery location
                                        limit(In Km) <small
                                        style="color:red;font-size:18px;">*</small> </label> 
                                    <input type="number" name="order_delivery_location_limit" class="form-control"
                                     value="{{$delivery_man['order_delivery_location_limit']}}" min="0"
                                        placeholder="Order delivery location limit" required>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group mb-12">

                                    <label class="input-label" for="exampleFormControlInput1">Do You Have High-Visibility
                                        Personal Protective Equipment (PPE)? <small
                                        style="color:red;font-size:18px;">*</small> </label>
                                    <br>

                                    <label class="" for="toggleColumn_index"><span
                                            style="color:red;font-size:18px;"></span>
                                        <input type="radio" class="ppe" 
                                            name="ppe" value="1" {{$delivery_man->ppe == '1' ?'checked':''}}>

                                    </label>
                                    Yes
                                    <label class="" for="toggleColumn_index"><span
                                            style="color:red;font-size:18px;"></span>
                                        <input type="radio" class="ppe"
                                            id="toggleColumn_index" name="ppe" value="0" {{$delivery_man->ppe == '0' ?'checked':''}} >

                                    </label>
                                    No

                                </div>

                            </div>

                            <div class="col-sm-12">
                                <div class="form-group mb-3">

                                    <label class="" for="toggleColumn_index"><small
                                        style="color:red;font-size:18px;">*</small> 
                                        <input type="checkbox" class="" id="bank_term"
                                            name="bank_term">

                                    </label>
                                    Please confirm that your banking information has been entered correctly.
                                </div>
                            </div>

                            <div class="col-sm-12" style="margin-bottom:20px">

                               @php($courier_note = \App\Models\BusinessSetting::where('key', 'courier_note')->first())
				@php($courier_note = $courier_note->value ?? '')
				<span style="color:red;">{!! $courier_note !!}</span>

                            </div>
                        </div>
                         @endIf
                                            <button type="submit" class="btn btn-primary">{{translate('messages.submit')}}</button>

                    </form>
                </div>
            </div>
        </div>

@endsection

@push('script_2')
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput.min.js" integrity="sha512-QMUqEPmhXq1f3DnAVdXvu40C8nbTgxvBGvNruP6RFacy3zWKbNTmx7rdQVVM2gkd2auCWhlPYtcW2tHwzso4SA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js" integrity="sha512-hkmipUFWbNGcKnR0nayU95TV/6YhJ7J9YUAkx4WLoIgrVr7w1NYz28YkdNFMtPyPeX1FrQzbfs3gl+y94uZpSw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/utils.min.js" integrity="sha512-lv6g7RcY/5b9GMtFgw1qpTrznYu1U4Fm2z5PfDTG1puaaA+6F+aunX+GlMotukUFkxhDrvli/AgjAu128n2sXw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <link rel="shortcut icon" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/img/flags.png" type="image/x-icon">
    <link rel="shortcut icon" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/img/flags@2x.png" type="image/x-icon">
    <script>
     document.addEventListener('DOMContentLoaded', function() {
        // Selecting radio buttons
        const radioButtons = document.querySelectorAll('.courier_bag');

        // Removing required attribute from all radio buttons
        radioButtons.forEach(function(radioButton) {
            radioButton.removeAttribute('required');
        });

        // Adding required attribute to the first radio button
        radioButtons[0].setAttribute('required', true);
        
        
        // Selecting radio buttons
        const radioButtons1 = document.querySelectorAll('.ppe');

        // Removing required attribute from all radio buttons
        radioButtons1.forEach(function(radioButton) {
            radioButtons1.removeAttribute('required');
        });

        // Adding required attribute to the first radio button
        radioButtons1[0].setAttribute('required', true);
    });
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
        
           function readURL1(input, previewer) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#' + previewer + '_viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        
         $(".customFileEg").change(function() {

            var name = $(this).attr("name");
            readURL1(this, name);
        });

        @php($country=\App\Models\BusinessSetting::where('key','country')->first())
        var phone = $("#phone").intlTelInput({
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/js/utils.js",
            nationalMode: true,
            autoHideDialCode: true,
            autoPlaceholder: "ON",
            dropdownContainer: document.body,
            formatOnDisplay: true,
            hiddenInput: "phone",
            initialCountry: "{{$country?$country->value:auto}}",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
    </script>

    <script src="{{asset('public/assets/admin/js/spartan-multi-image-picker.js')}}"></script>
    <script type="text/javascript">
        $(function () {
        
            $('#delivery_type').on('change', function() {
                // Check if the selected value is 'agent'
                
              

                if ($(this).val() === 'agent') {

                    // If 'agent' is selected, show the fields
                    $('#logistic_type').show();
                    $('#gst').show();
                    $('#gst_certificate').show();
                    var is_gst_certificate_enabled = $("#is_gst_certificate_enabled").val();
                    if(is_gst_certificate_enabled == '1'){
                      $("#gst_certificate_required").show();
                      if($("#gst_certificate_value").val() ==''){
                        $("input[name='gst_certificate']").prop("required", true);
                      }
                      
                    } else {
                    $("#gst_certificate_required").hide();
                      $("input[name='gst_certificate']").prop("required", false);
                    }
                    
                    $('#pan_card').show();
                    var is_pan_card_enabled = $("#is_pan_card_enabled").val();
                    if(is_pan_card_enabled == '1'){
                      $("#pan_card_required").show();
                      if($("#pan_card_value").val() ==''){
                        $("input[name='pan_card']").prop("required", true);
                      }
                    } else {
                    $("#pan_card_required").hide();
                      $("input[name='pan_card']").prop("required", false);
                    }
                    

                    $('#cin_certificate').show();
                    var is_cin_certificate_enabled = $("#is_cin_certificate_enabled").val();
                    if(is_cin_certificate_enabled == '1'){
                    $("#cin_certificate_required").show();
                     if($("#cin_certificate_value").val() ==''){
                       $("input[name='cin_certificate']").prop("required", true);
                     }
                    } else {
                     $("#cin_certificate_required").hide();
                      $("input[name='cin_certificate']").prop("required", false);
                    }
                    

                    $('#tfn_certificate').show();
                    var is_tfn_certificate_enabled = $("#is_tfn_certificate_enabled").val();
                    if(is_tfn_certificate_enabled == '1'){
                     $("#tfn_certificate_required").show();
                     if($("#tfn_certificate_value").val() ==''){
                       $("input[name='tfn_certificate']").prop("required", true);
                     }
                     

                    } else {
                      $("#tfn_certificate_required").hide();
                      $("input[name='tfn_certificate']").prop("required", false);
                    }
                    

                    
                    $('#acn_certificate').show();
                    var is_acn_certificate_enabled = $("#is_acn_certificate_enabled").val();
                    if(is_acn_certificate_enabled == '1'){
                      $("#acn_certificate_required").show();
                      if($("#acn_certificate_value").val() ==''){
                       $("input[name='acn_certificate']").prop("required", true);
                     }
                     

                    } else {
                      $("#acn_certificate_required").hide();
                      $("input[name='acn_certificate']").prop("required", false);
                    }
                    
                    

                    $('#abn_certificate').show();
                    
                    var is_abn_certificate_enabled = $("#is_abn_certificate_enabled").val();
                    if(is_abn_certificate_enabled == '1'){
                       $("#abn_certificate_required").show();
                     if($("#abn_certificate_value").val() ==''){
                       $("input[name='abn_certificate']").prop("required", true);
                     }
                     

                    } else {
                      $("#abn_certificate_required").hide();
                      $("input[name='abn_certificate']").prop("required", false);
                    }
                    

                    $('#ein_certificate').show();
                     var is_ein_certificate_enabled = $("#is_ein_certificate_enabled").val();
                    if(is_ein_certificate_enabled == '1'){
                     $("#ein_certificate_required").show();
                     if($("#ein_certificate_value").val() ==''){
                       $("input[name='ein_certificate']").prop("required", true);
                     }
                     
                    } else {
                     $("#ein_certificate_required").hide();
                      $("input[name='ein_certificate']").prop("required", false);
                    }
                    
                    

                    $('#tfn_certificate').show();
                      var is_tfn_certificate_enabled = $("#is_tfn_certificate_enabled").val();
                    if(is_tfn_certificate_enabled == '1'){
                      $("#tfn_certificate_required").show();
                       if($("#tfn_certificate_value").val() ==''){
                       $("input[name='tfn_certificate']").prop("required", true);
                     }
                      

                    } else {
                    $("#tfn_certificate_required").hide();
                      $("input[name='tfn_certificate']").prop("required", false);
                    }
                    
                    

                    $("#vehicle_plate_number_required_section").hide();
                    $("#vehicle_plate_number").prop("required", false);


                } 
                
                else {
                	
                    if($(this).val() === 'individual'){
                       $("#vehicle_plate_number_required_section").show();
                       $("#vehicle_plate_number").prop("required", true);

                    } else {
                       $("#vehicle_plate_number_required_section").hide();
                       $("#vehicle_plate_number").prop("required", false);

                    }                  	
                    // If any other value is selected, hide the fields
                    $('#logistic_type').hide();
                    $('#gst').hide();
                    $('#gst_certificate').hide();
                    $("input[name='gst_certificate']").prop("required", false);
                    $('#pan_card').hide();
                    $("input[name='pan_card']").prop("required", false);
                    $('#cin_certificate').hide();                    
                    $("input[name='cin_certificate']").prop("required", false);
                    $('#acn_certificate').hide();
                    $("input[name='acn_certificate']").prop("required", false);
                    $('#abn_certificate').hide();
                    $("input[name='abn_certificate']").prop("required", false);
                    $('#ein_certificate').hide();
                    $("input[name='ein_certificate']").prop("required", false);
                    $('#tfn_certificate').hide();
                    $("input[name='tfn_certificate']").prop("required", false);


                   
                }
            });

            // Trigger the change event initially to set the initial state
            $('#delivery_type').trigger('change');
            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: '120px',
                groupClassName: 'col-2',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset('public/assets/admin/img/400x400/img2.jpg')}}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('Please only input png or jpg type file', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('File size too big', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
            
            
         
            
             $("#zone_id").on("change", function() {
                var zone_id = $(this).val();



                $.get({
                    url: "{{ route('deliveryman.get-sub-zones') }}",
                    data: {
                        "zone_id": zone_id,
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
                                if(val.sub_zone == null){
                                  val.sub_zone = '';
                                }
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
            
               $('#zone_id').trigger('change');
        });
        
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
                     if (data.status == 'error') {
                        toastr.error(data.message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                    } else {
                        toastr.success(data.message, {
                            CloseButton: true,
                            ProgressBar: true
                        });

                    }
                },
                complete: function() {
                    $('#loading').hide();
                },
            });

        }
    </script>
@endpush
