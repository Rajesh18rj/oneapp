@extends('layouts.admin.app')

@section('title',$store->name."'s Settings")

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/admin/css/croppie.css')}}" rel="stylesheet">

@endpush

@section('content')
<div class="content container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{translate('messages.dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{translate('messages.vendor_view')}}</li>
        </ol>
    </nav>

    @include('admin-views.vendor.view.partials._header',['store'=>$store])
    <!-- Page Heading -->
    <div class="tab-content">
        <div class="tab-pane fade show active" id="vendor">
            <div class="row pt-2" >
                <div class="col-md-12">
                    <div class="card h-100">
                        <div class="card-header">
                             {{translate('messages.documents')}}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-4 col-md-4 col-sm-6 col-6">
                                   <h5>GST Certificate </h5><br>
                                     @php($gst_certificate = $store->gst_certificate ?? '')
                                   <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/store/' . $gst_certificate) }}" title="View large image" class="document" />                                                   
                                                  
                                </div>
                                <div class="col-xl-4 col-md-4 col-sm-6 col-6">
                                   <h5> FSSAI Certificate </h5><br>
                                       @php($fssai_certificate = $store->fssai_certificate ?? '')
                                             <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/store/' . $fssai_certificate) }}" title="View large image" class="document" />     
                           
                                </div>

                                <div class="col-xl-4 col-md-4 col-sm-6 col-6">
                                  <h5> PAN Card </h5><br>
                                     @php($pan_card = $store->pan_card ?? '')
                                   <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/store/' . $pan_card) }}" title="View large image" class="document"  />    
                                </div>
                                
                             </div>
                             <br>
			  <div class="row">
                                <div class="col-xl-4 col-md-4 col-sm-6 col-6">
                                  <h5> TFN Certificate </h5><br>
                                    @php($tfn_certificate = $store->tfn_certificate ?? '')
                                   <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/store/' . $tfn_certificate) }}" title="View large image" class="document" />    
                                </div>

                                <div class="col-xl-4 col-md-4 col-sm-6 col-6">
                                 <h5> ACN Certificate </h5><br>
                                   @php($acn_certificate = $store->acn_certificate ?? '')
                                   <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/store/' . $acn_certificate) }}" title="View large image" />    
                                </div>
                                <div class="col-xl-4 col-md-4 col-sm-6 col-6">
                                   <h5> ABN Certificate </h5><br>
                                    @php($abn_certificate = $store->abn_certificate ?? '')
                                   <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/store/' . $abn_certificate) }}" title="View large image" class="document" />    
                                </div>
                           </div>
                           <br>
                           <div class="row">
                                <div class="col-xl-4 col-md-4 col-sm-6 col-6">
                                   <h5> EIN Certificate </h5><br>
                                     @php($ein_certificate = $store->ein_certificate ?? '')
                                   <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/store/' . $ein_certificate) }}" title="View large image" class="document" />    
                                </div>
                                <div class="col-xl-4 col-md-4 col-sm-6 col-6">
				  <h5> CIN Certificate </h5><br>
				   @php($cin_certificate = $store->cin_certificate ?? '')
                                   <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/store/' . $cin_certificate) }}" title="View large image" class="document"  />    
				 </div>
				 
				 <div class="col-xl-4 col-md-4 col-sm-6 col-6">
				  <h5> Bank Cheque </h5><br>
				   @php($bank_cheque = $store->bank_cheque ?? '')
                                   <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/store/' . $bank_cheque) }}" title="View large image" class="document"  />    
				 </div>
                           </div>
                           
                            <br>
                           <div class="row" >
                                <div class="col-xl-4 col-md-4 col-sm-6 col-6">
                                   <h5> Passbook Image </h5><br>
                                     @php($pasbook_image = $store->pasbook_image ?? '')
                                   <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/store/' . $pasbook_image) }}" title="View large image" class="document" />    
                                </div>
                                
                           </div>
			</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create schedule modal -->


@endsection

@push('script_2')
    <!-- Page level plugins -->
    <script>
    $(document).ready(function(){
	    $('.document').on('click', function (e) {
	       $('#document-modal').modal('show');
	       $img = $(this).attr("src");
	       $('#document-modal img').attr('src', $img);
	    });
    });
    
    </script>
@endpush
