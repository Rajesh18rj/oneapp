@extends('layouts.delivery-agent.app')

@section('title', 'Zone List')

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">
                         {{ translate('messages.delivery') }} {{ translate('messages.zone') }} 
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">


            <div class="col-sm-12 col-lg-12 mb-3 my-lg-2">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ translate('messages.zone') }} {{ translate('messages.list') }}<span
                                class="badge badge-soft-dark ml-2" id="itemCount">{{ $zones->total() }}</span></h5>
                        <form action="javascript:" id="search-form">
                            <!-- Search -->
                            @csrf
                            <div class="input-group input-group-merge input-group-flush">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                    placeholder="{{ translate('messages.search') }}"
                                    aria-label="{{ translate('messages.search') }}" required>
                                <button type="submit" class="btn btn-light">{{ translate('messages.search') }}</button>

                            </div>
                            <!-- End Search -->
                        </form>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                                    <button id="bulkSendAssignRequest" class="btn btn-danger">Bulk Send Assign Request</button>
                        <table id="columnSearchDatatable"
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                            data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging":false
                               }'>
                            <thead class="thead-light">
                                <tr>
                                <th><input type="checkbox" id="checkAll"></th>
                                    <th>{{ translate('messages.#') }}</th>
                                    <th>{{ translate('messages.id') }}</th>
                                    <th>{{ translate('messages.name') }}</th>
                                    <th>{{ translate('messages.sub_zone_name') }}</th>
                                    <th>{{ translate('messages.stores') }}</th>
                                    <th>{{ translate('messages.deliverymen') }}</th>
                                    <th>{{ translate('messages.status') }}</th>

                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($zones as $key => $zone)
                                 <?php
                              $request_by = auth('delivery_men')->user()->id;
                               $request_status_data = \DB::table('zone_request_status')->where('zone_id', $zone->id)->where('request_by', $request_by)->first();
                               if ($request_status_data) {
                               	 $is_approved = $request_status_data->is_approve;
                               } else {

                               	  $is_approved = 0;
                               	  
                               }
                               
                            
                            ?>
                                     <tr data-id="{{ $zone->id }}" data-request-status="1" data-sub-zone="<?=$zone['sub_zone'] ?? 'N/A'?>" class="table_row"> 
                               <td><input type="checkbox" class="row-checkbox"></td>
                                        <td>{{ $key + $zones->firstItem() }}</td>
                                        <td>{{ $zone->id }}</td>
                                        <td>
                                            <span class="d-block font-size-sm text-body">
                                                {{ $zone['name'] }}
                                            </span>
                                        </td>
                                        
                                        <td>
                                            <span class="d-block font-size-sm text-body">
                                                {{ $zone['sub_zone'] ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>{{ $zone->stores_count }}</td>
                                        <td>{{ $zone->deliverymen_count }}</td>
                                      
                                        
                                         <td>
                                    <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$zone->id}}">
                          


<input type="checkbox" onclick="window.location.href = '{{ route('delivery-agent.zone.send-assign-request', ['id' => $zone->id, 'status' => 1, 'name' => $zone['sub_zone'] ?? 'N/A']) }}'" class="toggle-switch-input" id="stocksCheckbox{{$zone->id}}" {{$is_approved ? 'checked' : ''}}>

                                        <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </td>
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <hr>
                        <div class="page-area">
                            <table>
                                <tfoot>
                                    {!! $zones->withQueryString()->links() !!}
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
    <script>
       
    </script>
    <script>
        $(document).on('ready', function() {
         // Individual row deletion
            $('#datatable tbody').on('click', '.send-assign-request-row', function() {
                var row = $(this).closest('tr');
                var itemId = row.data('id');
                datatable.row(row).remove().draw();
                // Perform delete action using itemId
            });
        
         // Bulk send assign request
            $('#bulkSendAssignRequest').on('click', function() {
                var selectedRows = $("tr.table_row");
                var itemIds = [];
                var itemStatuses = [];
                var subZones = [];
                $(selectedRows).each(function() {
                    var checkbox = $(this).find('.row-checkbox');
                    if (checkbox.prop('checked')) {
                        var moduleId = $(this).data('id');
                        var moduleRequestStatus = $(this).data('request-status');
                         var subZone = $(this).data('sub-zone');
                        itemIds.push(moduleId);
                        itemStatuses.push(moduleRequestStatus);
                        subZones.push(subZone);
                    }
                });
                if (itemIds.length > 0) {


                    Swal.fire({
                        title: '{{ translate('messages.are_you_sure') }}',
                        text: "Want to send assign request to this zones ?",
                        type: 'warning',
                        showCancelButton: true,
                        cancelButtonColor: 'default',
                        confirmButtonColor: '#FC6A57',
                        cancelButtonText: 'No',
                        confirmButtonText: 'Yes',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: '{{ url('/') }}/delivery-agent/zone/bulk-send-assign-zone-request',
                                type: 'DELETE',
                                data: {
                                    item_ids: itemIds,
                                    itemStatuses: itemStatuses,
                                     subZones: subZones,
                                    "_token": "{{ csrf_token() }}"
                                },
                                success: function(data) {
                                    var json_data = JSON.parse(data);
                                    if(json_data.status == 'true'){
                                     toastr.success(json_data.message);
                                    } else {
                                     toastr.error(json_data.message);
                                    }
                                    
                                    //location.reload();
                                }
                            });
                        }
                    })
		} else {
                    alert("Please select at least one zone to send assign request .");
                }


            });
            
             // Select/Deselect all rows
            $('#checkAll').on('change', function() {
                var isChecked = $(this).prop('checked');
                $('.row-checkbox').prop('checked', isChecked);
                // Handle check all action
            });	
        
        
        



            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function() {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });


            $('#column3_search').on('change', function() {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });

            $("#zone_form").on('keydown', function(e) {
                if (e.keyCode === 13) {
                    e.preventDefault();
                }
            })
        });
    </script>



    <script>
        
        // initialize();

    </script>
    <script>
        $('#search-form').on('submit', function() {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('delivery-agent.zone.search') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#set-rows').html(data.view);
                    $('#itemCount').html(data.total);
                    $('.page-area').hide();
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
