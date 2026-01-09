@extends('layouts.delivery-agent.app')

@section('title', translate('messages.deliverymen'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-filter-list"></i>Delivery Agent</h1>
                </div>
                {{-- <a href="{{route('delivery-agent.delivery-man.add')}}" class="btn btn-primary pull-right"><i
                                class="tio-add-circle"></i> {{translate('messages.add')}} {{translate('messages.deliveryman')}}</a> --}}

                @if (!isset(auth('admin')->user()->zone_id))
                    <div class="col-sm-auto" style="width: 306px;">
                        <select name="zone_id" class="form-control js-select2-custom"
                            onchange="set_zone_filter('{{ route('delivery-agent.delivery-man.list') }}', this.value)">
                            <option value="all">All Zones</option>
                            @foreach (\App\Models\Zone::orderBy('name')->get() as $z)
                                <option value="{{ $z['id'] }}"
                                    {{ isset($zone) && $zone->id == $z['id'] ? 'selected' : '' }}>
                                    {{ $z['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header p-1">
                        <h5>Delivery Agent {{ translate('messages.list') }}<span class="badge badge-soft-dark ml-2"
                                id="itemCount">{{ $delivery_men->total() }}</span></h5>
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
                                    placeholder="{{ translate('messages.search') }}" aria-label="Search" required>
                                <button type="submit" class="btn btn-light">{{ translate('messages.search') }}</button>

                            </div>
                            <!-- End Search -->
                        </form>
                    </div>
                    <!-- End Header -->

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
                                    <th class="text-capitalize">{{ translate('messages.#') }}</th>
                                    <th class="text-capitalize">{{ translate('messages.name') }}</th>
                                    <th class="text-capitalize">{{ translate('messages.zone') }}</th>
                                    <th class="text-capitalize">{{ translate('messages.availability') }}
                                        {{ translate('messages.status') }}</th>
                                    <th class="text-capitalize">{{ translate('messages.phone') }}</th>
                                    <th class="text-capitalize">{{ translate('messages.action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($delivery_men as $key => $dm)
                                    <tr class="table_row" data-id="{{ $dm->id }}">
                                        <td>
                                            <input type="checkbox" class="row-checkbox">
                                        </td>
                                        <td>{{ $key + $delivery_men->firstItem() }}</td>
                                        <td>
                                            <a class="media align-items-center"
                                                href="{{ route('delivery-agent.delivery-man.preview', [$dm['id']]) }}">
                                                <img class="avatar avatar-lg mr-3"
                                                    onerror="this.src='{{ asset('public/assets/admin/img/160x160/img1.jpg') }}'"
                                                    src="{{ asset('storage/app/public/delivery-man') }}/{{ $dm['image'] }}"
                                                    alt="{{ $dm['f_name'] }} {{ $dm['l_name'] }}">
                                                <div class="media-body">
                                                    <h5 class="text-hover-primary mb-0">
                                                        {{ $dm['f_name'] . ' ' . $dm['l_name'] }}</h5>
                                                </div>
                                            </a>
                                        </td>
                                        <td>
                                            @if ($dm->zone)
                                                <label class="badge badge-soft-info">{{ $dm->zone->name }}</label>
                                            @else
                                                <label
                                                    class="badge badge-soft-warning">{{ translate('messages.zone') . ' ' . translate('messages.deleted') }}</label>
                                            @endif
                                            {{-- <span class="d-block font-size-sm">{{$banner['image']}}</span> --}}
                                        </td>
                                        <td class="text-center">
                                            @if ($dm->application_status == 'approved')
                                                @if ($dm->active)
                                                    <label
                                                        class="badge badge-soft-primary">{{ translate('messages.online') }}</label>
                                                @else
                                                    <label
                                                        class="badge badge-soft-secondary">{{ translate('messages.offline') }}</label>
                                                @endif
                                            @elseif ($dm->application_status == 'denied')
                                                <label
                                                    class="badge badge-soft-danger">{{ translate('messages.denied') }}</label>
                                            @else
                                                <label
                                                    class="badge badge-soft-info">{{ translate('messages.pending') }}</label>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="deco-none" href="tel:{{ $dm['phone'] }}">{{ $dm['phone'] }}</a>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-white"
                                                href="{{ route('delivery-agent.delivery-man.edit', [$dm['id']]) }}"
                                                title="{{ translate('messages.edit') }}"><i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-sm btn-white text-danger" href="javascript:"
                                                onclick="form_alert('delivery-man-{{ $dm['id'] }}','Want to remove this deliveryman ?')"
                                                title="{{ translate('messages.delete') }}"><i
                                                    class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{ route('delivery-agent.delivery-man.delete', [$dm['id']]) }}"
                                                method="post" id="delivery-man-{{ $dm['id'] }}">
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
                                <tfoot>
                                    {!! $delivery_men->links() !!}
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
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function() {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function() {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('keyup', function() {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function() {
                datatable
                    .columns(4)
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
                                url: '{{ url('/') }}/admin/delivery-man/bulk-item-delete',
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






            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <script>
        $('#search-form').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('delivery-agent.delivery-man.search') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#set-rows').html(data.view);
                    $('#itemCount').html(data.count);
                    $('.page-area').hide();
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
