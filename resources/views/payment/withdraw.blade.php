@extends('layouts.app')
@push('breadcrumb-plugins')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Withdrawal</li>
        </ol>
    </nav>
@endpush
@section('panel')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Withdrawal Records</h4>
                    </p>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th> User name </th>
                                    <th> Amount </th>
                                    <th> Type </th>
                                    <th> Withdraw TO </th>
                                    <th> Status </th>
                                    <th> date </th>
                                    <th> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($withdrawRecord as $withdraw)
                                    <tr>
                                        <td> {{ $loop->iteration }}</td>
                                        <td> {{ $withdraw->username }} </td>
                                        <td>&#8377; {{ round_to_2dp($withdraw->withdraw) }} </td>
                                        <td> {{ $withdraw->type }} </td>
                                        <td>@if ($withdraw->bankinfo->type == 1 )
                                          UPI :  {{$withdraw->bankinfo->upi_id}}
                                        @else
                                        Bank : {{$withdraw->bankinfo->bank_name }} <br>
                                        Account. : {{$withdraw->bankinfo->acc_number }} <br>
                                        IFSC : {{$withdraw->bankinfo->ifsc_code }}
                                        @endif  </td>
                                        <td>
                                            {{$withdraw->status}}
                                        </td>
                                        <td> {{ CostumDateFormet($withdraw->created_at) }} </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                @if ($withdraw->status == 'pending')
                                                    <button type="button" data-id="{{ $withdraw->id }}"
                                                        class="btn btn-sm btn-primary approvePayment">Approve</button>
                                                    <button type="button" data-id="{{ $withdraw->id }}"
                                                        class="btn btn-sm btn-danger rejectPayment">Reject</button>
                                                @else
                                                 NA
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-2">
                            {{ $withdrawRecord->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('.approvePayment').click(function() {
            var id = $(this).data('id');
            console.log(id);
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('admin.withdraw.update') }}",
                type: 'POST',
                data: {
                    id: id,
                    status:2,
                    _token: csrfToken
                }, // Pass the 'id' to your API
                success: function(response) {
                    alert(response.message);
                    if (response.status == true) {
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed:", error);
                }
            });
        });
        $('.rejectPayment').click(function() {
            var id = $(this).data('id');
            console.log(id);
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('admin.withdraw.update') }}",
                type: 'POST',
                data: {
                    id: id,
                    status:4,
                    _token: csrfToken
                }, // Pass the 'id' to your API
                success: function(response) {
                    alert(response.message);
                    if (response.status == true) {
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed:", error);
                }
            });
        });
    });
</script>
@endsection
