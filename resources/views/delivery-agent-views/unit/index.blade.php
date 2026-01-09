@extends('layouts.admin.app')

@section('title', translate('messages.units'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i> {{ translate('messages.add') }}
                        {{ translate('messages.new') }} {{ translate('messages.unit') }}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{ route('admin.unit.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{ translate('messages.name') }}</label>
                                <input type="text" name="unit" class="form-control"
                                    placeholder="{{ translate('messages.new_unit') }}" maxlength="191" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ translate('messages.submit') }}</button>
                </form>
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <hr>
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-capitalize">{{ translate('messages.unit') }} {{ translate('messages.list') }}<span
                                class="badge badge-soft-dark ml-2" id="itemCount">{{ $units->total() }}</span></h5>
                        {{-- <form action="javascript:" id="search-form" >
                            <!-- Search -->
                            @csrf
                            <div class="input-group input-group-merge input-group-flush">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                        placeholder="{{translate('messages.search')}}" aria-label="Search" required>
                                <button type="submit" class="btn btn-light">{{translate('messages.search')}}</button>
                            </div>
                            <!-- End Search -->
                        </form> --}}
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <button id="bulkDelete" class="btn btn-danger">Bulk Delete</button>

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
                                    <th style="width: 50%">{{ translate('messages.unit') }}</th>
                                    <th style="width: 10%">{{ translate('messages.action') }}</th>
                                </tr>

                            </thead>

                            <tbody id="set-rows">
                                @foreach ($units as $key => $unit)
                                    <tr class="table_row" data-id="{{ $unit->id }}">
                                        <td>
                                            <input type="checkbox" class="row-checkbox">
                                        </td>
                                        <td>{{ $key + $units->firstItem() }}</td>
                                        <td>
                                            <span class="d-block font-size-sm text-body">
                                                {{ Str::limit($unit['unit'], 20, '...') }}
                                            </span>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-white"
                                                href="{{ route('admin.unit.edit', [$unit['id']]) }}"
                                                title="{{ translate('messages.edit') }}"><i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-sm btn-white" href="javascript:"
                                                onclick="form_alert('unit-{{ $unit['id'] }}','Want to delete this unit ?')"
                                                title="{{ translate('messages.delete') }}"><i
                                                    class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{ route('admin.unit.destroy', [$unit['id']]) }}" method="post"
                                                id="unit-{{ $unit['id'] }}">
                                                @csrf @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <hr>
                        <table class="page-area">
                            <tfoot>
                                {!! $units->links() !!}
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $(document).on('ready', function() {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function() {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });


            // Bulk delete
            $('#bulkDelete').on('click', function() {
                var selectedRows = $("tr.table_row");
                var itemIds = [];
                $(selectedRows).each(function() {
                    var checkbox = $(this).find('.row-checkbox');
                    if (checkbox.prop('checked')) {
                        var itemId = $(this).data('id');
                        itemIds.push(itemId);
                    }
                });
                if (itemIds.length > 0) {
                    // Show a confirmation dialog before deleting

                    Swal.fire({
                        title: '{{ translate('messages.are_you_sure') }}',
                        text: "Want to delete this items ?",
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
                                url: '{{ url('/') }}/admin/unit/bulk-item-delete',
                                type: 'DELETE',
                                data: {
                                    item_ids: itemIds,
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
                    alert("Please select at least one item to delete.");
                }
                console.log("itemIds", itemIds);
                // Perform bulk delete action using itemIds
            });

            // Select/Deselect all rows
            $('#checkAll').on('change', function() {
                var isChecked = $(this).prop('checked');
                $('.row-checkbox').prop('checked', isChecked);
                // Handle check all action
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
        });
    </script>
    <script>
        {{-- $('#search-form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.unit.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }); --}}
    </script>
@endpush
