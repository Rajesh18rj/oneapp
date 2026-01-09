@extends('layouts.delivery-agent.app')

@section('title',translate('messages.modules'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">{{translate('messages.module')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="card mt-3">
            <div class="card-header pb-0">
                <h5>{{translate('messages.module')}} {{translate('messages.list')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$modules->total()}}</span></h5>
                {{--<form id="dataSearch">
                    @csrf
                    <!-- Search -->
                    <div class="input-group input-group-merge input-group-flush">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="tio-search"></i>
                            </div>
                        </div>
                        <input type="search" name="search" class="form-control" placeholder="{{translate('messages.search_categories')}}" aria-label="{{translate('messages.search_categories')}}">
                        <button type="submit" class="btn btn-light">{{translate('messages.search')}}</button>
                    </div>
                    <!-- End Search -->
                </form>--}}
            </div>
            <div class="card-body">
                <div class="table-responsive datatable-custom">
                <button id="bulkSendAssignRequest" class="btn btn-danger">Bulk Send Assign Request</button>
                    <table id="columnSearchDatatable"
                        class="table table-borderless table-thead-bordered table-align-middle" style="width:100%;"
                        data-hs-datatables-options='{
                            "isResponsive": false,
                            "isShowPaging": false,
                            "paging":false,
                        }'>
                        <thead class="thead-light">
                            <tr>
                                <th><input type="checkbox" id="checkAll"></th>
                                <th style="width: 5%">{{translate('messages.#')}}</th>
                                <th style="width: 10%">{{translate('messages.id')}}</th>
                                <th style="width: 20%">{{translate('messages.name')}}</th>
                                <th style="width: 20%">{{translate('messages.module_type')}}</th>
                                <th style="width: 10%">{{translate('messages.request')}} {{translate('messages.status')}}</th>
                                <th style="width: 20%">{{translate('messages.store_count')}}</th>

                            </tr>
                        </thead>

                        <tbody id="table-div">
                        @foreach($modules as $key=>$module)
                            <?php
                              $request_by = auth('delivery_men')->user()->id;
                               $request_status_data = \DB::table('module_request_status')->where('module_id', $module['id'])->where('request_by', $request_by)->first();
                               if ($request_status_data) {
                               	 $is_approved = $request_status_data->is_approve;
                               } else {

                               	  $is_approved = 0;
                               }
                            
                            ?>
                        	
                            <tr data-id="{{ $module->id }}" data-request-status="1" class="table_row"> 
                               <td><input type="checkbox" class="row-checkbox"></td>
                                <td>{{$key+$modules->firstItem()}}</td>
                                <td>{{$module->id}}</td>
                                <td>
                                    <span class="d-block font-size-sm text-body">
                                        {{Str::limit($module['module_name'], 20,'...')}}
                                    </span>
                                </td>
                                <td>
                                    <span class="d-block font-size-sm text-body text-capitalize">
                                        {{Str::limit($module['module_type'], 20,'...')}}
                                    </span>
                                </td>
                                <td>
                                    <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$module->id}}">
                                    <input type="checkbox" onclick="location.href='{{route('delivery-agent.module.send-assign-request',[$module['id'],1])}}'"class="toggle-switch-input" id="stocksCheckbox{{$module->id}}" {{$is_approved?'checked':''}}>
                                        <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </td>
                                <td>
                                 @php
                                  $module_id = $module['id'];
                                 @endphp
                                <a href="javascript:;" style="cursor:none;"> {{$module->stores_count}}</a>
                                
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer page-area">
                <!-- Pagination -->
                <div class="row justify-content-center justify-content-sm-between align-items-sm-center"> 
                    <div class="col-sm-auto">
                        <div class="d-flex justify-content-center justify-content-sm-end">
                            <!-- Pagination -->
                            {!! $modules->links() !!}
                        </div>
                    </div>
                </div>
                <!-- End Pagination -->
            </div>
        </div>

    </div>

@endsection

@push('script_2')
    <script>
        $(document).on('ready', function () {
        
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
                $(selectedRows).each(function() {
                    var checkbox = $(this).find('.row-checkbox');
                    if (checkbox.prop('checked')) {
                        var moduleId = $(this).data('id');
                        var moduleRequestStatus = $(this).data('request-status');
                        itemIds.push(moduleId);
                        itemStatuses.push(moduleRequestStatus);
                    }
                });
                if (itemIds.length > 0) {


                    Swal.fire({
                        title: '{{ translate('messages.are_you_sure') }}',
                        text: "Want to send assign request to this modules ?",
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
                                url: '{{ url('/') }}/delivery-agent/module/bulk-send-assign-module-request',
                                type: 'DELETE',
                                data: {
                                    item_ids: itemIds,
                                    itemStatuses: itemStatuses,
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
                    alert("Please select at least one module to send assign request .");
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
            

            {{--
                $('#dataSearch').on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{route('delivery-agent.module.search')}}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#loading').show();
                    },
                    success: function (data) {
                        $('#table-div').html(data.view);
                        $('#itemCount').html(data.count);
                        $('.page-area').hide();
                    },
                    complete: function () {
                        $('#loading').hide();
                    },
                });
            });
            --}}


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
@endpush
