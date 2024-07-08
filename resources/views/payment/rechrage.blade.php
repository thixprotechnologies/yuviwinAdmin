@extends('layouts.app')
@push('breadcrumb-plugins')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Recharge</li>
    </ol>
</nav>
@endpush
@section('panel')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Recharge Records</h4>
                <form action="{{route('admin.recharge')}}" method="get">
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
                        <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Bombwin Tnx_Id </label>
                                <div class="col-sm-9">
                                    <input type="text" name="tnx_id" value="{{request()->tnx_id ?? '' }}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary mb-2">Filter</button>
                    <a href="{{route('admin.recharge')}}" class="btn btn-gradient-danger mb-2">Clear</a>
                </form>
                </p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Username </th>
                                <th> Amount </th>
                                <th> Bombwin Tnx_Id </th>
                                <th>Utr </th>
                                <th> Status </th>
                                <th> date </th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rechargeRecord as $recharge)
                            <tr>
                                <td> {{ $loop->iteration }}</td>
                                <td>
                                    {{$recharge->username}}
                                </td>
                                <td> &#8377; {{round_to_2dp($recharge->recharge)}} </td>
                                <td>{{$recharge->rand}}</td>
                                <td> {{$recharge->utr}}
                                    
                                </td>
                                <td>
                                    {!! getRecharegStatus($recharge->status) !!}
                                </td>
                                <td> {{CostumDateFormet($recharge->created_at) }} </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        @if ($recharge->status == 0 || $recharge->status == 1 )
                                        <button type="button" data-id="{{$recharge->id}}" class="btn btn-sm btn-primary approvePayment">Approve</button>
                                        <button type="button" data-id="{{$recharge->id}}" class="btn btn-sm btn-danger rejectPayment">Reject</button>
                                        @endif
                                        @if ($recharge->type == 0)
                                        <button type="button" class="btn btn-sm btn-success viewRecharge" data-bs-toggle="modal" data-bs-target="#rechageImage" data-image="{{env('PAYMENT_URL').$recharge->img}}">View</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                    <div class="d-flex justify-content-center mt-2">
                        {{ $rechargeRecord->appends(['tnx_id' => request()->query('tnx_id'),'username' => request()->query('username')])->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="rechageImage" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" t>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img id="rechageImageATR" style="width: 100%;height: auto;" alt="Rechage Imgae">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                    url: "{{ route('admin.recharge.update') }}",
                    type: 'POST',
                    data: {
                        id: id,
                        status: 2,
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
                    url: "{{ route('admin.recharge.update') }}",
                    type: 'POST',
                    data: {
                        id: id,
                        status: 4,
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
            $('.viewRecharge').click(function() {
                var srcNew = $(this).data('image');
                $('#rechageImageATR').attr('src', srcNew);
            });
        });
    </script>
    @endsection