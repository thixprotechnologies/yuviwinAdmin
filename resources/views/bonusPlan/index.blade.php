@extends('layouts.app')
@push('breadcrumb-plugins')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $pagetitle }}</li>
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
                            <h4 class="card-title">{{ $pagetitle }}</h4>
                        </div>
                        <div>
                            <a href="{{ route('admin.bonusplans.create') }}" class="btn btn-gradient-success btn-sm">Add
                                Plan</a>
                        </div>
                    </div>
                    </p>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 15%"> # </th>
                                    <th class="text-center" style="width: 15%"> Plan ID </th>
                                    <th class="text-center" style="width: 15%"> Type </th>
                                    <th class="text-center" style="width: 15%"> Amount </th>
                                    <th class="text-center" style="width:20%"> Details </th>
                                    <th class="text-center" style="width: 20%"> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($plans->count() > 0)
                                    @foreach ($plans as $plan)
                                        <tr>
                                            <td class="text-center">{{ $loop->index + 1 }}</td>
                                            <td class="text-center">{{ $plan->bonus_plan_id }}</td>
                                            <td class="text-center">
                                                @if ($plan->type == 1)
                                                    New Joining
                                                @elseif($plan->type == 2)
                                                    Game Play
                                                @elseif($plan->type == 3)
                                                    Recharege
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $plan->amount }}</td>
                                            <td class="text-center">
                                                @if ($plan->type == 1)
                                                    Create Account
                                                @elseif($plan->type == 2)
                                                    {{ $plan->game_count }} GamePlay Daily
                                                @elseif($plan->type == 3)
                                                    {{ $plan->rechare_value }} Recharge Amount
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.bonusplans.edit', $plan->id) }}"
                                                    class="btn btn-primary mb-2">Edit</a>
                                                <button type="button" class="btn btn-danger mb-2 deleteplan" data-bs-toggle="modal"
                                                    data-bs-target="#deletePlan"
                                                    data-planid="{{ $plan->id }}">Delete</button>

                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            No Plans Found
                                        </td>
                                    </tr>
                                @endif
                            </tbody>

                        </table>
                        <div class="d-flex justify-content-center mt-2">
                            {{ $plans->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="deletePlan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Plan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form  id="deletePlanForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <input type="hidden" id="deletePlanId">
                            <p>Are you sure you want to delete this plan?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @endsection
    @section('scripts')
        <script>
            $(document).ready(function() {
                $('.deleteplan').click(function() {
                    var planId = $(this).data('planid');
                    $('#deletePlanId').val(planId); // Set the plan_id value in the hidden input field
                    var actionUrl = "{{ route('admin.bonusplans.destroy', ':planId') }}";
                    actionUrl = actionUrl.replace(':planId', planId);
                    $('#deletePlanForm').attr('action', actionUrl);
                });
            });
        </script>

        {{-- <script>
            $(document).ready(function() {
                $('.updateComplainBtn').click(function() {
                    var id = $('#complainID').val();
                    var response = $("#response").val();
                    var status = $("#status").val();
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: "{{ route('admin.complains.update') }}",
                        type: 'POST',
                        data: {
                            id: id,
                            status: status,
                            response: response,
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
                $('.respondeComplain').click(function() {
                    var data = $(this).data('data');
                    $('#complainID').val(data.id);
                });
                $('.viewComplain').click(function() {
                    var data = $(this).data('data');
                    var srcNew = $(this).data('image');
                    $('#image').attr('src', srcNew);
                    // $('#complainID').val(data.id);
                });
            });
        </script> --}}
    @endsection
