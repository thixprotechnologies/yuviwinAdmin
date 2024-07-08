@extends('layouts.app')
@push('breadcrumb-plugins')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$pagetitle}}</li>
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
                            <h4 class="card-title">{{$pagetitle}}</h4>
                        </div>
                        <div>
                            <a href="{{route('admin.bonusplans.index')}}" class="btn btn-gradient-success btn-sm">Back</a>
                        </div>
                    </div>
                    <form action="{{route('admin.bonusplans.store')}}" method="POST"  class="forms-sample">
                        @csrf
                        <div class="form-group">
                          <label for="amount">Amount</label>
                          <input type="number" step="0.01" min="1" value="{{old('amount')}}" required class="form-control" id="amount" name="amount" placeholder="Amount">
                        </div>
                        <div class="form-group">
                            <label for="plan_type">Plan Type</label>
                            <select class="form-control" id="plan_type" name="plan_type" required>
                              <option value="">Select Plan Type</option>
                              <option @if(old('plan_type') == 1) selected @endif value="1">New Joining</option>
                              <option @if(old('plan_type') == 2) selected @endif value="2">Game Play</option>
                              <option @if(old('plan_type') == 3) selected @endif value="3">Recharege</option>
                            </select>
                          </div>
                        <div class="form-group">
                          <label for="game_count">Game Count</label>
                          <input type="number" min="1" value="{{old('game_count')}}" class="form-control" name="game_count" id="game_count" placeholder="Game Count">
                        </div>
                        <div class="form-group">
                          <label for="recharge_amount">Recharege Amount</label>
                          <input type="number" class="form-control" id="recharge_amount" name="recharge_amount" placeholder="recharge amount">
                        </div>
                        <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                        <a href="{{route('admin.bonusplans.index')}}" class="btn btn-light">Cancel</a>
                      </form>                    
                </div>
            </div>
        </div>
      
       
    @endsection
    @section('scripts')
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
