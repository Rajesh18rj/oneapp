@extends('layouts.admin.app')

@section('title', 'Review List')

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">{{ translate('messages.User') }}
                        {{ translate('messages.reviews') }}<span class="badge badge-soft-dark ml-2"
                            id="itemCount">{{ $reviews->total() }}</span></h1>
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
                                    <th>{{ translate('messages.#') }}</th>
                                    {{-- <th style="width: 30%">{{ translate('messages.deliveryman') }}</th> --}}
                                    <th style="width: 25%">{{ translate('messages.customer') }}/Vendor</th>
                                    <th>{{ translate('messages.review') }}</th>
                                    <th>{{ translate('messages.rating') }}</th>
                                    <th>{{ translate('messages.status') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($reviews as $key => $review)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        {{-- <td>
                                                <span class="d-block font-size-sm text-body">
                                                    <a
                                                        href="{{ route('admin.delivery-man.preview', [$review['delivery_man_id']]) }}">
                                                        {{ $review->delivery_man->f_name . ' ' . $review->delivery_man->l_name }}
                                                    </a>
                                                </span>
                                            </td> --}}
                                        <td>
                                            @if ($review->reviewable)
                                                @if ($review->reviewable_type == 'App\Models\Vendor')
                                                    <a href="{{ route('admin.vendor.view', [$review->reviewable->id]) }}">
                                                        {{ $review->reviewable ? $review->reviewable->f_name : '' }}
                                                        {{ $review->reviewable ? $review->reviewable->l_name : '' }}
                                                        (Vendor)
                                                    </a>
                                                @elseif ($review->reviewable_type == 'App\Models\User')
                                                    <a
                                                        href="{{ route('admin.customer.view', [$review->reviewable->id]) }}">
                                                        {{ $review->reviewable ? $review->reviewable->f_name : '' }}
                                                        {{ $review->reviewable ? $review->reviewable->l_name : '' }}
                                                        (Customer)
                                                    </a>
                                                @endIf
                                            @else
                                                {{ translate('messages.customer_not_found') }}
                                            @endif

                                        </td>
                                        <td>
                                            {{ $review->comment }}
                                        </td>
                                        <td>
                                            <label class="badge badge-soft-info">
                                                {{ $review->rating }} <i class="tio-star"></i>
                                            </label>
                                        </td>
                                        <td class="d-flex align-items-center">

                                            @if (in_array($review->status, ['approved', 'rejected']))
                                                <span
                                                    class="font-weight-bold  {{ $review->status === 'approved' ? 'text-success' : 'text-danger' }}">{{ $review->status }}</span>
                                            @else
                                                <a onclick="return confirmRedirect()"
                                                    href="{{ route('admin.user_reviews.status', ['status' => 'approved', 'id' => $review->id]) }}"
                                                    class="btn btn-success btn-sm">Accept</a> &nbsp;

                                                <a onclick="return confirmRedirect()"
                                                    href="{{ route('admin.user_reviews.status', ['status' => 'rejected', 'id' => $review->id]) }}"
                                                    class="btn btn-danger btn-sm">Reject</a>
                                            @endIf
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
        $(document).on('ready', function() {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

        });

        function status_form_alert(id, message, e) {
            e.preventDefault();
            Swal.fire({
                title: '{{ translate('messages.are_you_sure') }}',
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
                    $('#' + id).submit()
                }
            })
        }


        function confirmRedirect() {
            // Display a confirmation dialog
            var confirmation = confirm("Are you sure you want to go to perform this action?");

            // If the user clicks OK, allow the redirection
            if (confirmation) {
                return true;
            }

            // If the user clicks Cancel, prevent the redirection
            return false;
        }
    </script>
@endpush
