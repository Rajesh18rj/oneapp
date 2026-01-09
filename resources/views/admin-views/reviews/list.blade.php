@extends('layouts.admin.app')

@section('title','Review List')

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"> {{translate('messages.reviews')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$reviews->total()}}</span></h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header">
                        <h5 class="card-header-title"></h5>
                    </div>
                    <!-- End Header -->

                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging": false
                               }'>
                            <thead class="thead-light">
                            <tr>
                                <th>{{translate('messages.#')}}</th>
                                <th style="width: 10%">{{translate('messages.review to')}}</th>
                                <th style="width: 20%">{{translate('messages.review from')}}</th>
                                <th style="width: 30%">{{translate('messages.review')}}</th>
                                <th>{{translate('messages.rating')}}</th>
                                 <th>{{translate('messages.status')}}</th>
                                <th>{{translate('messages.action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($reviews as $key=>$review)
                            
                             <?php
                             $review_to_id = $review['item']['id'] ?? '';
                             $type = 'item';
                                    	
                                    	if (isset($review['delivery_man_id'])){
				           $type = 'delivery_man';
				           $review_to_id = $review['delivery_man_id'];	                                    	
                                    	}
                                    	
                                    	if (isset($review['item'])){
                                            $type = 'item';
                                            $review_to_id = $review['item']['id'];		
                                    	}
                                    	$user_id = $review['user_id'];
                                    $reviewData = DB::table('review')
				    ->where('review_to_id', $review_to_id)
				    ->where('review_id', $review['id'])
				    ->where('user_id', $user_id)
				    ->where('type', $type)
				    ->first();
				  
                                    ?>
                                <tr>
                                    <td>{{$key+$reviews->firstItem()}}</td>
                                    <td>
                                        <?php
                                        
                                        if (isset($review['item'])){
                                        ?>
                                            <a class="media align-items-center" href="{{route('admin.item.view',[$review['item']['id']])}}">
                                                <img class="avatar avatar-lg mr-3" src="{{asset('storage/app/public/product')}}/{{$review['item']['image']}}" 
                                                    onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'" alt="{{$review['item']['name']}} image">
                                                <div class="media-body">
                                                    <h5 class="text-hover-primary mb-0">{{Str::limit($review['item']['name'],20,'...')}}</h5>
                                                </div>
                                            </a>
                                        <?php 
                                        } else {
                                     
                                        
                                         if (isset($review['delivery_man_id'])){
                                        ?>
                                        
                                        <span class="d-block font-size-sm text-body">
                                                    <a
                                                        href="{{ route('admin.delivery-man.preview', [$review['delivery_man_id']]) }}">
                                                        {{ $review['delivery_man']['f_name'] . ' ' . $review['delivery_man']['f_name'] }}
                                                    </a>
                                                </span>
                                       
                                         <?php } else {
                                         
                                         ?>
                                         
                                             {{translate('messages.Item deleted!')}}
                                             
                                             <?php }  } ?>

                                    </td>
                                    <td>
                                    	<?php
                                    	if (isset($review['user_id'])){
                                    	
                                    	?>
                                        <a href="{{route('admin.customer.view',[$review['user_id']])}}">
                                            {{$review['customer']?$review['customer']['f_name']:""}} {{$review['customer']?$review['customer']['l_name']:""}}
                                        </a>
                                        <?php } ?>
                                    </td>
                                    <td>
                                   
                                        <p class="text-wrap">{{$review['comment']}}</p>
                                    </td>
                                    <td>
                                        <label class="badge badge-soft-info">
                                            {{$review['rating']}} <i class="tio-star"></i>
                                        </label>
                                    </td>
                                    <td>
                                    	<?php
                                    	if(isset($reviewData) && !empty($reviewData)){
                                    		$admin_status = $reviewData->admin_status;
                                    	} else {
                                    	   $admin_status =  'pending';
                                    	}
                                    	  
                                    	?>
                                        {{$admin_status}}
                                    </td>
                                    <td>
                                   
                                        <label class="toggle-switch toggle-switch-sm" for="reviewCheckbox{{$review['id']}}-{{$review_to_id}}-{{$type}}">
                                            <input type="checkbox" onclick="status_form_alert('status-{{$review['id']}}-{{$review_to_id}}-{{$type}}','{{$admin_status == 'approve'?translate('messages.you_want_to_reject_this_review'):translate('messages.you_want_to_approve_this_review')}}', event)" class="toggle-switch-input" id="reviewCheckbox{{$review['id']}}-{{$review_to_id}}-{{$type}}" {{$admin_status == 'approve' ?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
     <form action="{{ route('admin.review.update-review-status', ['id' => $review['id'], 'admin_status' => ($admin_status == 'pending' || $admin_status == 'reject') ? 'approve' : 'reject', 'type' => $type, 'user_id' => $review['user_id'], 'review_to_id' => $review_to_id]) }}" method="get" id="status-{{$review['id']}}-{{$review_to_id}}-{{$type}}">







                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <hr>
                        <table>
                            <tfoot>
                            {!! $reviews->links() !!}
                            </tfoot>
                        </table>
                    </div>
                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

        });

        function status_form_alert(id, message, e) {
      
            e.preventDefault();
            Swal.fire({
                title: '{{translate('messages.are_you_sure')}}',   
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $('#'+id).submit()
                }
            })
        }
    </script>
@endpush
