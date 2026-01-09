@extends('layouts.admin.app')
<?php
$type = $dm->delivery_type;
if($type == 'agent'){
?>
@section('title', 'Delivery Agent Preview')

<?php } else { ?>

@section('title', 'Delivery Man Preview')

<?php } ?>


@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ translate('messages.dashboard') }}</a></li>
            <?php
            if($type == 'agent'){

            ?>


            <li class="breadcrumb-item" aria-current="page">{{ translate('messages.delivery agent') }} {{ translate('messages.view') }}</li>
            <?php } else { ?>
                <li class="breadcrumb-item" aria-current="page">{{ translate('messages.delivery') }} {{ translate('messages.view') }}</li>
        <?php } ?>
        </ol>
    </nav>
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-6">
                 <?php
            if($type == 'agent'){

            ?>
                <h1>{{ translate('messages.delivery agent_preview') }}</h1>
            <?php } else { ?>
 <h1>{{ translate('messages.deliveryman_preview') }}</h1>
            <?php } ?>
            </div>
            @if($dm->application_status == 'approved')
            <div class="col-6">
                <a href="{{ url()->previous() }}" class="btn btn-primary float-right">
                    <i class="tio-back-ui"></i> {{ translate('messages.back') }}
                </a>
            </div>

            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <!-- Nav -->
                <ul class="nav nav-tabs page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.delivery-man.preview', ['id'=>$dm->id, 'tab'=> 'info']) }}" aria-disabled="true">{{ translate('messages.info') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.delivery-man.preview', ['id'=>$dm->id, 'tab'=> 'transaction']) }}" aria-disabled="true">{{ translate('messages.transaction') }}</a>
                    </li>
                </ul>
                <!-- End Nav -->
            </div>
            @else
            <div class="col-md-6">
                <div class="hs-unfold float-right">
                    <a class="btn btn-primary text-capitalize font-weight-bold" onclick="request_alert('{{ route('admin.delivery-man.application',[$dm['id'],'approved']) }}','{{ translate('messages.you_want_to_approve_this_application') }}')" href="javascript:">{{ translate('messages.approve') }}</a>
                    @if($dm->application_status != 'denied')
                    <a class="btn btn-danger text-capitalize font-weight-bold" onclick="request_alert('{{ route('admin.delivery-man.application',[$dm['id'],'denied']) }}','{{ translate('messages.you_want_to_deny_this_application') }}')" href="javascript:">{{ translate('messages.deny') }}</a>
                    @endif
                </div>
            </div>

            @endif
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Card -->
    <div class="card mb-3 mb-lg-5 mt-2">
        <div class="card-header">
            <h4 class="page-header-title">
                {{$dm['f_name'].' '.$dm['l_name']}}

                (@if($dm->zone)
                {{$dm->zone->name}}
                @else {{ translate('messages.zone') . ' ' . translate('messages.deleted') }}
                @endif )
                @if($dm->application_status=='approved')
                @if($dm['status'])
                @if($dm['active'])
                <label class="badge badge-soft-primary">{{ translate('messages.online') }}</label>
                @else
                <label class="badge badge-soft-danger">{{ translate('messages.offline') }}</label>
                @endif
                @else
                <span class="badge badge-danger">{{ translate('messages.suspended') }}</span>
                @endif

                @else
                <label class="badge badge-soft-{{$dm->application_status=='pending'?'info':'danger'}}">{{ translate('messages.' . $dm->application_status) }}</label>
                @endif
            </h4>
            <!-- <a href="javascript:"  onclick="request_alert('{{route('admin.delivery-man.earning',[$dm['id'],$dm->earning?0:1])}}','{{$dm->earning?translate('messages.want_to_disable_earnings'):translate('messages.want_to_enable_earnings')}}')" class="btn {{$dm->earning?'btn-danger':'btn-success'}}">
                 {{$dm->earning?translate('messages.disable_earning'):translate('messages.enable_earning')}}
            </a> -->
            @if($dm->application_status=='approved')
            <a href="javascript:" onclick="request_alert('{{ route('admin.delivery-man.status',[$dm['id'],$dm->status?0:1]) }}','{{$dm->status?translate('messages.you_want_to_suspend_this_deliveryman'):translate('messages.you_want_to_unsuspend_this_deliveryman')}}')" class="btn {{$dm->status?'btn-danger':'btn-success'}}">
                {{$dm->status?translate('messages.suspend_this_delivery_man'):translate('messages.unsuspend_this_delivery_man')}}
            </a>
            @endif
            <div class="hs-unfold float-right">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ translate('messages.type') }} ({{$dm->earning?translate('messages.freelancer'):translate('messages.salary_based')}})
                    </button>
                    <div class="dropdown-menu text-capitalize" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item {{$dm->earning?'active':''}}" onclick="request_alert('{{ route('admin.delivery-man.earning',[$dm['id'],1]) }}','{{ translate('messages.want_to_enable_earnings') }}')" href="javascript:">{{ translate('messages.freelancer') }}</a>
                        <a class="dropdown-item {{$dm->earning?'':'active'}}" onclick="request_alert('{{ route('admin.delivery-man.earning',[$dm['id'],0]) }}','{{ translate('messages.want_to_disable_earnings') }}')" href="javascript:">{{ translate('messages.salary_based') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Body -->
        <div class="card-body">
            <div class="row align-items-md-center gx-md-5">
                <div class="col-md-auto mb-3 mb-md-0">
                    <div class="d-flex align-items-center">
                        <img class="avatar avatar-xxl avatar-4by3 mr-4" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img1.jpg') }}'" src="{{ asset('storage/app/public/delivery-man') }}/{{$dm['image']}}" alt="Image Description">
                        <div class="d-block">
                            <h4 class="display-2 text-dark mb-0">{{ count($dm->rating) > 0 ? number_format($dm->rating[0]->average, 2, '.', ' ') : 0 }}</h4>
                            <p>{{ translate('messages.reviews') }} of {{$dm->reviews->count()}} <span class="badge badge-soft-dark badge-pill ml-1"></span></p>
                        </div>
                    </div>
                </div>

                <div class="col-md">
                    <ul class="list-unstyled list-unstyled-py-2 mb-0">

                        @php($total=$dm->reviews->count())
                        <!-- Review Ratings -->
                        <li class="d-flex align-items-center font-size-sm">
                            @php($five=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],5))
                            <span class="mr-3">5 star</span>
                            <div class="progress flex-grow-1">
                                <div class="progress-bar" role="progressbar" style="width: {{$total==0?0:($five/$total)*100}}%;" aria-valuenow="{{$total==0?0:($five/$total)*100}}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="ml-3">{{$five}}</span>
                        </li>
                        <!-- End Review Ratings -->

                        <!-- Review Ratings -->
                        <li class="d-flex align-items-center font-size-sm">
                            @php($four=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],4))
                            <span class="mr-3">4 star</span>
                            <div class="progress flex-grow-1">
                                <div class="progress-bar" role="progressbar" style="width: {{$total==0?0:($four/$total)*100}}%;" aria-valuenow="{{$total==0?0:($four/$total)*100}}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="ml-3">{{$four}}</span>
                        </li>
                        <!-- End Review Ratings -->

                        <!-- Review Ratings -->
                        <li class="d-flex align-items-center font-size-sm">
                            @php($three=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],3))
                            <span class="mr-3">3 star</span>
                            <div class="progress flex-grow-1">
                                <div class="progress-bar" role="progressbar" style="width: {{$total==0?0:($three/$total)*100}}%;" aria-valuenow="{{$total==0?0:($three/$total)*100}}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="ml-3">{{$three}}</span>
                        </li>
                        <!-- End Review Ratings -->

                        <!-- Review Ratings -->
                        <li class="d-flex align-items-center font-size-sm">
                            @php($two=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],2))
                            <span class="mr-3">2 star</span>
                            <div class="progress flex-grow-1">
                                <div class="progress-bar" role="progressbar" style="width: {{$total==0?0:($two/$total)*100}}%;" aria-valuenow="{{$total==0?0:($two/$total)*100}}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="ml-3">{{$two}}</span>
                        </li>
                        <!-- End Review Ratings -->

                        <!-- Review Ratings -->
                        <li class="d-flex align-items-center font-size-sm">
                            @php($one=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],1))
                            <span class="mr-3">1 star</span>
                            <div class="progress flex-grow-1">
                                <div class="progress-bar" role="progressbar" style="width: {{$total==0?0:($one/$total)*100}}%;" aria-valuenow="{{$total==0?0:($one/$total)*100}}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="ml-3">{{$one}}</span>
                        </li>
                        <!-- End Review Ratings -->
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Body -->
    </div>
    <!-- End Card -->

    <div class="row my-3">
        <!-- Earnings (Monthly) Card Example -->
        <div class="for-card col-sm-4 col-6 mb-2">
            <div class="card for-card-body-2 shadow h-100  badge-primary ">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="font-weight-bold  text-uppercase for-card-text mb-1">{{ translate('messages.total') }} {{ translate('messages.delivered') }} {{ translate('messages.orders') }}</div>
                            <div class="for-card-count">{{$dm->orders->count()}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Collected Cash Card Example -->
        <div class="for-card col-sm-4 col-6 mb-2">
            <div class="card r shadow h-100 for-card-body-4  badge-dark">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class=" for-card-text font-weight-bold  text-uppercase mb-1">{{ translate('messages.cash_in_hand') }}</div>
                            <div class="for-card-count">{{ \App\CentralLogics\Helpers::format_currency($dm->wallet?$dm->wallet->collected_cash:0.0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Earning Card Example -->
        <div class="for-card col-sm-4 col-6 mb-2">
            <div class="card r shadow h-100 for-card-body-4  badge-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class=" for-card-text font-weight-bold  text-uppercase mb-1">{{ translate('messages.total_earning') }}</div>
                            <div class="for-card-count">{{ \App\CentralLogics\Helpers::format_currency($dm->wallet?$dm->wallet->total_earning:0.00) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card -->
    <div class="card">
        <!-- Table -->
        <div class="table-responsive datatable-custom">
            <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap card-table" data-hs-datatables-options='{"columnDefs": [{"targets": [0, 3, 6],"orderable": false}],"order": [],"info": {"totalQty": "#datatableWithPaginationInfoTotalQty"},"search": "#datatableSearch","entries": "#datatableEntries","pageLength": 25,"isResponsive": false,"isShowPaging": false,"pagination": "datatablePagination"}'>
                <thead class="thead-light">
                <tr>
                    <th>{{ translate('messages.reviewer') }}</th>
                    <th>{{ translate('messages.review') }}</th>
                    <th>{{ translate('messages.attachment') }}</th>
                    <th>{{ translate('messages.date') }}</th>
                </tr>
                </thead>

                <tbody>

                @foreach($reviews as $review)
                    <tr>
                        <td>
                            @if ($review->customer)
                                <a class="d-flex align-items-center" href="{{ route('admin.customer.view', [$review['user_id']]) }}">
                                    <div class="avatar avatar-circle">
                                        <img class="avatar-img" width="75" height="75" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img1.jpg') }}'" src="{{ asset('storage/app/public/profile/'.$review->customer?$review->customer->image:'') }}" alt="Image Description">
                                    </div>
                                    <div class="ml-3">
                                        <span class="d-block h5 text-hover-primary mb-0">{{ $review->customer ? $review->customer['f_name']." ".$review->customer['l_name'] : '' }} <i class="tio-verified text-primary" data-toggle="tooltip" data-placement="top" title="Verified Customer"></i></span>
                                        <span class="d-block font-size-sm text-body">{{ $review->customer ? $review->customer->email : '' }}</span>
                                    </div>
                                </a>
                            @else
                                {{ translate('messages.customer_not_found') }}
                            @endif
                        </td>
                        <td>
                            <div class="text-wrap" style="width: 18rem;">
                                <div class="d-flex mb-2">
                                    <label class="badge badge-soft-info">{{ $review->rating }} <i class="tio-star"></i></label>
                                </div>

                                <p>{{ $review['comment'] }}</p>
                            </div>
                        </td>
                        <td>
                            @foreach(json_decode($review['attachment'], true) as $attachment)
                                <img width="100" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public'). '/' . $attachment }}">
                            @endforeach
                        </td>
                        <td>{{ date('d M Y '.config('timeformat'), strtotime($review['created_at'])) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- End Table -->

        <!-- Footer -->
        <div class="card-footer">
            <!-- Pagination -->
            <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                <div class="col-12">
                    {!! $reviews->links() !!}
                </div>
            </div>

            <!-- End Pagination -->
        </div>

        <!-- End Footer -->

    </div>
    <!-- End Card -->
  
    <div class="row pt-2">
        <div class="col-md-12">
            <div class="card h-100">
                <div class="card-header">
                    {{ translate('messages.documents') }}
                </div>
                <div class="card-body">
                 <?php

                  if($dm['delivery_type'] == 'agent'){
                ?>
                
                    <div class="row">
                        <div class="col-xl-4 col-md-4 col-sm-6 col-6">
                            <h5>GST Certificate </h5><br>
                            @php($gst_certificate = $dm['gst_certificate'] ?? '')
                            <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/documents/' . $gst_certificate) }}" title="View large image" class="document" />
                        </div>
                        <div class="col-xl-4 col-md-4 col-sm-6 col-6">
                            <h5> CIN Certificate </h5><br>
                            @php($cin_certificate = $dm['cin_certificate'] ?? '')
                            <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/documents/' . $cin_certificate) }}" title="View large image" class="document" />
                        </div>

                        <div class="col-xl-4 col-md-4 col-sm-6 col-6">
                            <h5> PAN Card </h5><br>
                            @php($pan_card = $dm['pan_card'] ?? '')
                            <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/documents/' . $pan_card) }}" title="View large image" class="document"  />
                        </div>

                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xl-4 col-md-4 col-sm-6 col-6">
                            <h5> TFN Certificate </h5><br>
                            @php($tfn_certificate = $dm['tfn_certificate'] ?? '')
                            <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/documents/' . $tfn_certificate) }}" title="View large image" class="document" />
                        </div>

                        <div class="col-xl-4 col-md-4 col-sm-6 col-6">
                            <h5> ACN Certificate </h5><br>
                            @php($acn_certificate = $dm['acn_certificate'] ?? '')
                            <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/documents/' . $acn_certificate) }}" title="View large image" class="document" />
                        </div>
                        <div class="col-xl-4 col-md-4 col-sm-6 col-6">
                            <h5> ABN Certificate </h5><br>
                            @php($abn_certificate = $dm['abn_certificate'] ?? '')
                            <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/documents/' . $abn_certificate) }}" title="View large image" class="document" />
                        </div>
                        

                    </div>
                    <br>
                    <div class="row">
                    
                     <div class="col-xl-4 col-md-4 col-sm-6 col-6">
                            <h5> Passbook Image </h5><br>
                            @php($passbook_image = $dm['passbook_image'] ?? '')
                            <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/documents/' . $passbook_image) }}" title="View large image" class="document" />
                        </div>
                        
                         <div class="col-xl-4 col-md-4 col-sm-6 col-6">
                            <h5> Driver License Image </h5><br>
                            @php($driver_license_image = $dm['driver_license_image'] ?? '')
                            <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/driver-license-image/' . $driver_license_image) }}" title="View large image" class="document" />
                        </div>
                        
                        <div class="col-xl-4 col-md-4 col-sm-6 col-6">
                            <h5> Vehicle Registration Image </h5><br>
                            @php($vehicle_registration_image = $dm['vehicle_registration_image'] ?? '')
                            <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/vehicle-registration-image/' . $vehicle_registration_image) }}" title="View large image" class="document" />
                        </div>
                    </div>
                    <?php } ?>
                    <br>
                    <div class="row">
                    
                           <?php

                  if($dm['delivery_type'] == 'individual'){
                ?>
                        <div class="col-xl-4 col-md-4 col-sm-6 col-6">
                            <h5> Driver License Image </h5><br>
                            @php($driver_license_image = $dm['driver_license_image'] ?? '')
                            <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/driver-license-image/' . $driver_license_image) }}" title="View large image" class="document" />
                        </div>
                        
                        <div class="col-xl-4 col-md-4 col-sm-6 col-6">
                            <h5> Vehicle Registration Image </h5><br>
                            @php($vehicle_registration_image = $dm['vehicle_registration_image'] ?? '')
                            <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/vehicle-registration-image/' . $vehicle_registration_image) }}" title="View large image" class="document" />
                        </div>
                        
                           <?php } ?>
                        
                         <div class="col-xl-4 col-md-4 col-sm-6 col-6">
                            <h5> Vehicle Insurance Image </h5><br>
                            @php($vehicle_insurance_image = $dm['vehicle_insurance_image'] ?? '')
                            <img style="height: 200px;border: 1px solid; border-radius: 10px;cursor:pointer;" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/vehicle-insurance-image/' . $vehicle_insurance_image) }}" title="View large image" class="document" />
                        </div>
                    
                    </div>
                        
                </div>
            </div>
        </div>
    </div>
  <div class="row pt-2">
        <div class="col-md-12">
            <div class="card h-100">
                <div class="card-header">
                    {{ translate('messages.account_details') }}
                </div>
                <div class="card-body">
               
                
                    <div class="row">
                        <div class="col-xl-3 col-md-3 col-sm-3 col-6">
                            <h5>Holder Name </h5><br>
                            @php($holder_name = $dm['holder_name'] ?? '')
                            <?=$holder_name?>
                         </div>
                         <div class="col-xl-3 col-md-3 col-sm-3 col-6">
                            <h5>Account Number </h5><br>
                            @php($account_number = $dm['account_number'] ?? '')
                             <?=$account_number?>
                         </div>

                         <div class="col-xl-3 col-md-3 col-sm-3 col-6">
                            <h5>Bank Name </h5><br>
                            @php($bank_name = $dm['bank_name'] ?? '')
                             <?=$bank_name?>
                         </div>
                         
                          <div class="col-xl-3 col-md-3 col-sm-3 col-6">
                            <h5>Branch Name </h5><br>
                            @php($branch = $dm['branch'] ?? '')
                              <?=$branch?>
                         </div>
                         <br> <br> <br>
                         <div class="col-xl-3 col-md-3 col-sm-3 col-6">
                           <br> <h5>IFSC Code </h5><br>
                            @php($ifsc_code = $dm['ifsc_code'] ?? '')
                             <?=$ifsc_code?>
                         </div>

                    </div>
                    <br>
                    
                 
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>
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


