@extends('layouts.app')
@push('breadcrumb-plugins')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Invites Records</li>
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
                            <h4 class="card-title">Invites Records</h4>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" value="{{ old('username') }}"
                            id="username" placeholder="Username">
                    </div>
                    <button type="submit" id="checkrecords" class="btn btn-gradient-primary me-2">Search</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="recordData" style="display: none">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                              <tr>
                                <th>Username</th>
                                <th>Total Recharge</th>
                                <th>First Recharge</th>
                                <th>Created Date</th>
                              </tr>
                            </thead>
                            <tbody id="addData">

                            </tbody>
                          </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('#checkrecords').click(function() {
            var username = $('input[name="username"]').val();

            if (username.trim() === '') {
                $('input[name="username"]').focus();
            } else {
                $.ajax({
                    url: "{{ route('admin.inviteRecords') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Assuming your CSRF token is in a meta tag with the name csrf-token
                    },
                    data: {
                        username: username
                    },
                    success: function(response) {
                        $('#recordData').css('display', 'flex');
                        if (!response || response.length === 0) {
                            $('#addData').html('<td class="text-center" colspan="4">No Results Found for Referal</td>')
                        }else{
                            $('#addData').html(response)

                        }
                    },
                    error: function(xhr, status, error) {
                        notify("error","something went wrong");
                    }
                });
            }
        });
    </script>
@endsection
