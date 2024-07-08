@extends('layouts.app')
@push('breadcrumb-plugins')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Complains List</li>
        </ol>
    </nav>
@endpush
@section('panel')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Recharge Records</h4>
                    </p>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th> Username </th>
                                    <th> Type </th>
                                    <th> Problem </th>
                                    <th> TNX ID </th>
                                    <th> Image </th>
                                    <th> Status </th>
                                    <th> Action </th>
                                    <th> date </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($complains as $complan)
                                    <tr>
                                        <td> {{ $loop->iteration }}</td>
                                        <td> {{ $complan->username }}</td>
                                        <td> {{ $complan->type }}</td>
                                        <td> {{ $complan->reason }}</td>
                                        <td> {{ $complan->tnx_id }}</td>
                                        <td> <img src="{{ env('COMPLAIN_IMG_URL') . $complan->image }}" alt=""></td>
                                        <td> {!! getComplainStatus($complan->status) !!}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                @if ($complan->status == 1)
                                                    <button type="button" data-data="{{ $complan }}"
                                                        class="btn btn-sm btn-primary respondeComplain"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#respondeComplain">Responde</button>
                                                @endif
                                                <button type="button"
                                                    data-image="{{ env('COMPLAIN_IMG_URL') . $complan->image }}"
                                                    class="btn btn-sm btn-danger viewComplain"data-bs-toggle="modal"
                                                    data-bs-target="#viewComplain">VIew</button>
                                            </div>
                                        </td>
                                        <td> {{ CostumDateFormet($complan->created_at) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                        <div class="d-flex justify-content-center mt-2">
                            {{ $complains->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="viewComplain" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" t>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">View Complain</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p></p>
                            <img id="image" style="width:100%;height:auto;">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="respondeComplain" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" t>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Responde To Complain</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p id="details"></p>
                            <input type="hidden" id="complainID">
                            <div class="form-group">
                                <label for="status">Change Status</label>
                                <select class="form-control" id="status">
                                    <option value="2">Complete</option>
                                    <option value="0">Reject</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="response">Response</label>
                                <textarea class="form-control" id="response" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary updateComplainBtn">Update</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('scripts')
        <script>
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
        </script>
    @endsection
