@extends('layouts.app')
@push('breadcrumb-plugins')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Users</li>
    </ol>
</nav>
@endpush
@section('panel')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between my-2">
                    <div class="text-left">
                        <h4 class="card-title">Active Users</h4>
                    </div>
                    <form action="{{route('admin.users')}}" method="get">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">User Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="username" value="{{request()->username ?? '' }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">

                                <button type="submit" class="btn btn-gradient-primary mb-2">Filter</button>
                                <a href="{{route('admin.users')}}" class="btn btn-gradient-danger mb-2">Clear</a>
                            </div>
                        </div>

                    </form>
                    <div>
                        {{-- <button class="btn btn-gradient-danger btn-sm">Blocked Users</button> --}}
                        <button class="btn btn-gradient-primary btn-sm">Recharge</button>
                        <button class="btn btn-gradient-success btn-sm">Withdrow</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th> User ID</th>
                                <th> First </th>
                                <th> Amount </th>
                                <th> Bonus </th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->username }}</td>
                                <td>
                                    @if ($user->name != '')
                                    {{ $user->name }}
                                    @else
                                    {{ $user->nickname }}
                                    @endif
                                </td>
                                <td> &#8377; {{ round_to_2dp($user->balance) }} </td>
                                <td> &#8377; {{ round_to_2dp($user->bonus) }} </td>
                                <td>
                                    <a href="{{ route('admin.users.info', $user->id) }}" class="btn btn-sm badge badge-success">Information</a>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" data-bonus="{{ round_to_2dp($user->bonus) }}" data-balance="{{ round_to_2dp($user->balance) }}" data-username="{{ $user->username }}" class="btn btn-sm btn-primary editUser" data-bs-toggle="modal" data-bs-target="#editbalance">Edit</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-2">
                        {{ $users->appends(['username' => request()->query('username')])->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editbalance" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" t>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="username">
                    <div class="form-group">
                        <label for="balance">Balance</label>
                        <input type="number" class="form-control balance" step="0.01" id="balance" placeholder="balance">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Update Option</label>
                        <select class="form-control" id="reason">
                            <option value="Bombwin Bonus">Bonus</option>
                            <option value="Bombwin Recharge">Recharge</option>
                        </select>
                    </div>
                    <!--<div class="form-group">-->
                    <!--    <label for="bonus">Bonus</label>-->
                    <!--    <input type="number" class="form-control" step="0.01" id="bonus" placeholder="bonus">-->
                    <!--</div>-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary updateAmountBtn">Save changes</button>

                </div>
            </div>
        </div>
    </div>
    @endsection
    @section('scripts')
    <script>
        $(document).ready(function() {
            $('.updateAmountBtn').click(function() {
                var username = $('#username').val();
                var balance = $('#balance').val();
                // var bonus = $('#bonus').val();
                var reason = $('#reason').val();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "{{ route('admin.users.update') }}",
                    type: 'POST',
                    data: {
                        username: username,
                        balance: balance,
                        // bonus: bonus,
                        reason: reason,
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
            $('.editUser').click(function() {
                // var balance = $(this).data('balance');
                var username = $(this).data('username');
                if (typeof balance === 'string') {
                    var balanceWithoutCommas = balance.replace(/,/g, '');
                    balance = parseFloat(balanceWithoutCommas);
                }
                // var bonus = $(this).data('bonus');
                // $('#bonus').val(bonus);
                // $('#balance').val(balance);
                $('#username').val(username);
            });
        });
    </script>
    @endsection