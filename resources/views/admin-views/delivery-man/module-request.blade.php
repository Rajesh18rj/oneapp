@extends('layouts.admin.app')


@section('title','Manage Module Request')




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

  <h1 class="page-header-title"><i class="tio-edit"></i> 

                    {{translate('Manage Module Request')}} 




                  
                </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
	    
          <div class="row">
          	<form action="{{route('admin.delivery-man.approve-delivery-agent-module-request',[$delivery_man['id']])}}" method="post">
                <div class="m-3 p-3 col-12">
                    
                        @csrf



                        <br>
                        <div class="row mt-3">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">System Module <small
                                        style="color:red;font-size:18px;">*</small></label>
                                    <select class="form-control select2" name="module_id[]" multiple>
                                    <option value="">Select System Module</option>
                                    
                                    	@foreach($modules as $key => $value)
                                    	  <option value="<?=$value->id?>"><?=$value->module_name?></option>
                                    	
                                    	@endforeach
                                    </select>
                                </div>
                            </div>
                       <br>
 		 </div>
                  <button type="submit" class="btn btn-primary">{{translate('messages.Approve')}}</button>

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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>


    <script src="{{asset('public/assets/admin/js/spartan-multi-image-picker.js')}}"></script>
    <script type="text/javascript">
        $(function () {
        
$('.select2').select2({ width: '100%', placeholder: "Select module", allowClear: true });

        });
        
       
    </script>
@endpush
