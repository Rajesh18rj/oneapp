@extends('layouts.admin.app')

@section('title', 'Logsitic Type List')

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-4 col-12">
                    <h1 class="page-header-title"> {{ translate('messages.logsitic_Type') }}
                        {{ translate('messages.list') }}<span class="badge badge-soft-dark ml-2"
                            id="foodCount">{{ $logisitic_types->total() }}</span></h1>
                </div>

                {{-- <div class="col-sm-4 col-6">
                    <select name="module_id" class="form-control js-select2-custom"
                        onchange="set_filter('{{ url()->full() }}',this.value,'module_id')"
                        title="{{ translate('messages.select') }} {{ translate('messages.modules') }}">
                        <option value="" {{ !request('module_id') ? 'selected' : '' }}>{{ translate('messages.all') }}
                            {{ translate('messages.modules') }}</option>
                        @foreach (\App\Models\Module::notParcel()->get() as $module)
                            <option value="{{ $module->id }}"
                                {{ request('module_id') == $module->id ? 'selected' : '' }}>
                                {{ $module['module_name'] }}
                            </option>
                        @endforeach
                    </select>
                </div> --}}



            </div>
            <!-- End Page Header -->
            <div class="row gx-2 gx-lg-3">
                <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                    <!-- Card -->
                    <div class="card">
                        <!-- Header -->
                        <div class="card-header p-1">
                            <div class="row justify-content-between align-items-center flex-grow-1">
                                {{-- <div class="col-md-4 mb-3 mb-md-0">
                                    <form id="search-form">
                                        @csrf
                                        <!-- Search -->
                                        <div class="input-group input-group-merge input-group-flush">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="tio-search"></i>
                                                </div>
                                            </div>
                                            <input id="datatableSearch" name="search" type="search" class="form-control"
                                                placeholder="{{ translate('messages.search_here') }}"
                                                aria-label="{{ translate('messages.search_here') }}">
                                            <button type="submit"
                                                class="btn btn-light">{{ translate('messages.search') }}</button>
                                        </div>
                                        <!-- End Search -->
                                    </form>
                                </div> --}}


                            </div>
                            <!-- End Row -->
                        </div>
                        <!-- End Header -->

                        <!-- Table -->
                        <div class="table-responsive datatable-custom" id="table-div" style="margin-top:20px;">
                            <button id="bulkDelete" class="btn btn-danger">Bulk Delete</button>

                            <table id="datatable"
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                data-hs-datatables-options='{
                                "columnDefs": [{
                                    "targets": [],
                                    "width": "5%",
                                    "orderable": false
                                }],
                                "order": [],
                                "info": {
                                "totalQty": "#datatableWithPaginationInfoTotalQty"
                                },

                                "entries": "#datatableEntries",
     
                                "isResponsive": false,
                                "isShowPaging": false,
                                "paging":false
                            }'
                                style="margin-top:20px;">
                                <thead class="thead-light">
                                    <tr>
                                        <th><input type="checkbox" id="checkAll"></th>
                                        <th>{{ translate('messages.#') }}</th>
                                        <th style="width: 20%">{{ translate('messages.name') }}</th>
                                        <th style="width: 20%">{{ translate('messages.module_type') }}</th>
                                        <th>{{ translate('messages.action') }}</th>
                                    </tr>
                                </thead>

                                <tbody id="set-rows">
                                    @foreach ($logisitic_types as $key => $item)
                                        <tr data-id="{{ $item->id }}" class="table_row">
                                            <td>
                                                <input type="checkbox" class="row-checkbox">
                                            </td>
                                            <td>{{ $key + $logisitic_types->firstItem() }}</td>
                                            <td>
                                                {{ $item->name }}
                                            </td>
                                            
                                           <td>
						    <?php
						    $module_type_id = explode(",", $item->module_type_id);
						    $name = '';
						    foreach($module_type_id as $key => $value){
							$moduleData = \App\Models\Module::find($value);
							$moduleName = $moduleData ? $moduleData->module_name : '';
							$name .= "," . $moduleName; 
						    }
						    $name = trim($name, ","); // Remove leading and trailing commas
						    ?>
						    {{ $name }}
						</td>

                                            <td>
                                                <a class="btn btn-sm btn-white"
                                                    href="{{ route('admin.logistic_type.edit', [$item['id']]) }}"
                                                    title="{{ translate('messages.edit') }} {{ translate('messages.logistic type') }}"><i
                                                        class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-sm btn-white" href="javascript:"
                                                    onclick="form_alert('food-{{ $item['id'] }}','{{ translate('messages.Want_to_delete_this_logistic_type?') }}')"
                                                    title="{{ translate('messages.delete') }} {{ translate('messages.logistic type') }}"><i
                                                        class="tio-delete-outlined"></i>
                                                </a>
                                                <form action="{{ route('admin.logistic_type.delete', [$item['id']]) }}"
                                                    method="post" id="food-{{ $item['id'] }}">
                                                    @csrf @method('delete')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <hr>
                            <div class="page-area">
                                <table>
                                    <tfoot class="border-top">
                                        {!! $logisitic_types->withQueryString()->links() !!}
                                    </tfoot>
                                </table>
                            </div>
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
            $(document).on('ready', function() {
                // INITIALIZATION OF DATATABLES
                // =======================================================
                var datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
                    select: {
                        style: 'multi',
                        classMap: {
                            checkAll: '#datatableCheckAll',
                            counter: '#datatableCounter',
                            counterInfo: '#datatableCounterInfo'
                        }
                    },
                    language: {
                        zeroRecords: '<div class="text-center p-4">' +
                            '<img class="mb-3" src="{{ asset('public/assets/admin/svg/illustrations/sorry.svg') }}" alt="Image Description" style="width: 7rem;">' +
                            '<p class="mb-0">No data to show</p>' +
                            '</div>'
                    }
                });

                $('#datatableSearch').on('mouseup', function(e) {
                    var $input = $(this),
                        oldValue = $input.val();

                    if (oldValue == "") return;

                    setTimeout(function() {
                        var newValue = $input.val();

                        if (newValue == "") {
                            // Gotcha
                            datatable.search('').draw();
                        }
                    }, 1);
                });

                // Individual row deletion
                $('#datatable tbody').on('click', '.delete-row', function() {
                    var row = $(this).closest('tr');
                    var itemId = row.data('id');
                    datatable.row(row).remove().draw();
                    // Perform delete action using itemId
                });

                // Bulk delete
                $('#bulkDelete').on('click', function() {
                    var selectedRows = $("tr.table_row");
                    var logisticIds = [];
                    $(selectedRows).each(function() {
                        var checkbox = $(this).find('.row-checkbox');
                        if (checkbox.prop('checked')) {
                            var logisticId = $(this).data('id');
                            logisticIds.push(logisticId);
                        }
                    });
                    if (logisticIds.length > 0) {
                        // Show a confirmation dialog before deleting

                        Swal.fire({
                            title: '{{ translate('messages.are_you_sure') }}',
                            text: "Want to delete this logistic types ?",
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
                                    url: '{{ url('/') }}/admin/logistic-type/bulk-item-delete',
                                    type: 'DELETE',
                                    data: {
                                        logistic_ids: logisticIds,
                                        "_token": "{{ csrf_token() }}"
                                    },
                                    success: function(data) {
                                        var json_data = JSON.parse(data);
                                        toastr.success(json_data.message);
                                        location.reload();
                                    }
                                });
                            }
                        })





                    } else {
                        alert("Please select at least one logistic type to delete.");
                    }
                    console.log("logisticIds", logisticIds);
                    // Perform bulk delete action using itemIds
                });

                // Select/Deselect all rows
                $('#checkAll').on('change', function() {
                    var isChecked = $(this).prop('checked');
                    $('.row-checkbox').prop('checked', isChecked);
                    // Handle check all action
                });

                $('#toggleColumn_index').change(function(e) {
                    datatable.columns(0).visible(e.target.checked)
                })
                $('#toggleColumn_name').change(function(e) {
                    datatable.columns(1).visible(e.target.checked)
                })

                $('#toggleColumn_type').change(function(e) {
                    datatable.columns(2).visible(e.target.checked)
                })

                $('#toggleColumn_vendor').change(function(e) {
                    datatable.columns(3).visible(e.target.checked)
                })

                $('#toggleColumn_status').change(function(e) {
                    datatable.columns(5).visible(e.target.checked)
                })
                $('#toggleColumn_price').change(function(e) {
                    datatable.columns(4).visible(e.target.checked)
                })
                $('#toggleColumn_action').change(function(e) {
                    datatable.columns(6).visible(e.target.checked)
                })

                // INITIALIZATION OF SELECT2
                // =======================================================
                $('.js-select2-custom').each(function() {
                    var select2 = $.HSCore.components.HSSelect2.init($(this));
                });
            });

            $('#store').select2({
                ajax: {
                    url: '{{ url('/') }}/admin/vendor/get-stores',
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            all: true,
                            page: params.page
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    __port: function(params, success, failure) {
                        var $request = $.ajax(params);

                        $request.then(success);
                        $request.fail(failure);

                        return $request;
                    }
                }
            });

            $('#category').select2({
                ajax: {
                    url: '{{ route('admin.category.get-all') }}',
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            all: true,
                            page: params.page
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    __port: function(params, success, failure) {
                        var $request = $.ajax(params);

                        $request.then(success);
                        $request.fail(failure);

                        return $request;
                    }
                }
            });

            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{ route('admin.item.search') }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#loading').show();
                    },
                    success: function(data) {
                        $('#set-rows').html(data.view);
                        $('.page-area').hide();
                        $('#foodCount').html(data.count);
                    },
                    complete: function() {
                        $('#loading').hide();
                    },
                });
            });
        </script>
    @endpush
