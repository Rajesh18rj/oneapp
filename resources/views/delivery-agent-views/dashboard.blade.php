@extends('layouts.delivery-agent.app')

@section('title', \App\Models\BusinessSetting::where(['key' => 'business_name'])->first()->value ?? 'Dashboard')

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .grid-card {
            border: 2px solid #00000012;
            border-radius: 10px;
            padding: 10px;
        }

        .label_1 {
            position: absolute;
            font-size: 10px;
            background: #396786;
            color: #fff;
            width: 60px;
            padding: 3px 5px;
            font-weight: bold;
            border-radius: 6px;
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        {{-- @if (auth('delivery_men')->user()->role_id == 1) --}}
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">{{ translate('messages.welcome') }}, {{ auth('delivery_men')->user()->f_name }}.
                    </h1>
                    <p class="page-header-text">{{ translate('Hello, here you can manage your delivery men by zone.') }} </p>
                </div>

                <div class="col-sm-auto" style="width: 306px;">
                    <label class="badge badge-soft-primary float-right">
                        {{ translate('messages.software_version') }} : {{ env('SOFTWARE_VERSION') }}
                    </label>
                    <select name="zone_id" class="form-control js-select2-custom"
                        onchange="fetch_data_zone_wise(this.value)">
                        <option value="all">All Zones</option>
                        @foreach (\App\Models\Zone::orderBy('name')->get() as $zone)
                            <option value="{{ $zone['id'] }}" {{ $params['zone_id'] == $zone['id'] ? 'selected' : '' }}>
                                {{ $zone['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <!-- End Page Header -->


        <!-- Stats -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="row gx-2 gx-lg-3 mb-2">
                    <div class="col-6">
				  <!-- Card -->
					<a class="card card-hover-shadow h-100" href="{{ route('delivery-agent.delivery-man.list') }}"
					    style="background: #0468A6;">
					    <div class="card-body">
						<h6 class="card-subtitle" style="color: white!important;">
						    {{ translate('delivery_man_earnings') }}</h6>
						<div class="row align-items-center gx-2 mb-1">
						    <div class="col-6">
							<span class="card-title h2" style="color: white!important;">
							<?php
							     $login_agent_id = auth('delivery_men')->user()->id;
        
         $all_delivery_mens =  DB::table('delivery_men')->where(array('added_by' => $login_agent_id))->get();
         $all_deliivery_man_earning_amount = 0;
							 if(isset($all_delivery_mens) && !empty($all_delivery_mens)){         
	 	  foreach($all_delivery_mens as $key => $value){	 	  	
		 	  $all_deliivery_man_earning_data = DB::table('orders')->where(array('delivery_man_id' => $value->id,'parcel_payment_status' => 'paid'))->get();			 
			   if(isset($all_deliivery_man_earning_data) && !empty($all_deliivery_man_earning_data)){		   
	 	  		foreach($all_deliivery_man_earning_data as $key => $value){
			   		$all_deliivery_man_earning_amount = $all_deliivery_man_earning_amount + $value->amount;
			   	
			   	}
			   	
			   }
	 	  
	 	  }	
         	 
         
         }
							
							?>
							 Rs. <?=$all_deliivery_man_earning_amount?>
							</span>
						    </div>
						    <div class="col-6 mt-2">
							<i class="tio-man" style="font-size: 30px;color: white"></i>

						    </div>
						</div>
						<!-- End Row -->
					    </div>
					</a>
                    </div>
                    <div class="col-6">
                     	<a class="card card-hover-shadow h-100" href="#"
					    style="background: #0468A6;">
					    <div class="card-body">
						<h6 class="card-subtitle" style="color: white!important;">
						    {{ translate('admin_comission') }}</h6>
						<div class="row align-items-center gx-2 mb-1">
						    <div class="col-6">
							<span class="card-title h2" style="color: white!important;">
							  <?php
							  $admin_commission = \App\Models\BusinessSetting::where('key', 'admin_commission')->first();
							   $admin_commission =  $admin_commission ? $admin_commission->value : 0;
							    
							    ?>
							    {{ $admin_commission }} %
							</span>
						    </div>
						    <div class="col-6 mt-2">
							<i class="tio-man" style="font-size: 30px;color: white"></i>

						    </div>
						</div>
						<!-- End Row -->
					    </div>
					</a>
                    </div>
                </div>
                
            </div>
        </div>

        <!-- End Stats -->


          

        <!-- End Row -->


           

              
    </div>
@endsection

@push('script')
    <script src="{{ asset('public/assets/admin') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('public/assets/admin') }}/vendor/chart.js.extensions/chartjs-extensions.js"></script>
    <script
        src="{{ asset('public/assets/admin') }}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js">
    </script>
@endpush


@push('script_2')
    <script>
        // INITIALIZATION OF CHARTJS
        // =======================================================
        Chart.plugins.unregister(ChartDataLabels);

        $('.js-chart').each(function() {
            $.HSCore.components.HSChartJS.init($(this));
        });

        var updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));
    </script>

    <script>
        var ctx = document.getElementById('user-overview');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    '{{ translate('messages.customer') }}',
                    '{{ translate('messages.store') }}',
                    '{{ translate('messages.Delivery Man') }}'
                ],
                datasets: [{
                    label: 'User',
                    data: ['{{ $data['customer'] }}', '{{ $data['stores'] }}',
                        '{{ $data['delivery_man'] }}'
                    ],
                    backgroundColor: [
                        '#628395',
                        '#055052',
                        '#53B8BB'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script>
        function order_stats_update(type) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('delivery-agent.dashboard-stats.order') }}',
                data: {
                    statistics_type: type
                },
                beforeSend: function() {
                    $('#loading').show()
                },
                success: function(data) {
                    insert_param('statistics_type', type);
                    $('#order_stats').html(data.view)
                },
                complete: function() {
                    $('#loading').hide()
                }
            });
        }

        function fetch_data_zone_wise(zone_id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('delivery-agent.dashboard-stats.zone') }}',
                data: {
                    zone_id: zone_id
                },
                beforeSend: function() {
                    $('#loading').show()
                },
                success: function(data) {
                    insert_param('zone_id', zone_id);
                    $('#order_stats').html(data.order_stats);
                    $('#user-overview-board').html(data.user_overview);
                    $('#monthly-earning-graph').html(data.monthly_graph);
                    $('#popular-restaurants-view').html(data.popular_restaurants);
                    $('#top-deliveryman-view').html(data.top_deliveryman);
                    $('#top-rated-foods-view').html(data.top_rated_foods);
                    $('#top-restaurants-view').html(data.top_restaurants);
                    $('#top-selling-foods-view').html(data.top_selling_foods);
                },
                complete: function() {
                    $('#loading').hide()
                }
            });
        }

        function user_overview_stats_update(type) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('delivery-agent.dashboard-stats.user-overview') }}',
                data: {
                    user_overview: type
                },
                beforeSend: function() {
                    $('#loading').show()
                },
                success: function(data) {
                    insert_param('user_overview', type);
                    $('#user-overview-board').html(data.view)
                },
                complete: function() {
                    $('#loading').hide()
                }
            });
        }
    </script>

    <script>
        function insert_param(key, value) {
            key = encodeURIComponent(key);
            value = encodeURIComponent(value);
            // kvp looks like ['key1=value1', 'key2=value2', ...]
            var kvp = document.location.search.substr(1).split('&');
            let i = 0;

            for (; i < kvp.length; i++) {
                if (kvp[i].startsWith(key + '=')) {
                    let pair = kvp[i].split('=');
                    pair[1] = value;
                    kvp[i] = pair.join('=');
                    break;
                }
            }
            if (i >= kvp.length) {
                kvp[kvp.length] = [key, value].join('=');
            }
            // can return this or...
            let params = kvp.join('&');
            // change url page with new params
            window.history.pushState('page2', 'Title', '{{ url()->current() }}?' + params);
        }
    </script>
@endpush
