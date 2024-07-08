@extends('layouts.app')
@push('breadcrumb-plugins')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page"> {{ $pagetitle }}</li>
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
                        <h4 class="card-title">Active  {{ $pagetitle }}</h4>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th> Name</th>
                                <th> Username </th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($agents as $agent)
                            <tr>
                                <td>{{ $agent->name }}</td>
                                <td>{{ $agent->email }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-sm btn-primary editUser" data="{{ $agent->id }}" data-bs-toggle="modal" data-bs-target="#editbalance">Edit</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                   
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
                        <label for="balance">Name</label>
                        <input type="text" class="form-control balance" id="name" placeholder="Name">
                    </div>
                  
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
                var name = $('#name').val();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                     url: "{{ route('admin.accountent.update') }}",
                    type: 'POST',
                    data: {
                        username: username,
                        name: name,
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
                var username = $(this).attr('data');
                $('#username').val(username);
            });
        });
    </script>
    @endsection