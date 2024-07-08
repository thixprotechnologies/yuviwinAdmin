@extends('layouts.app')
@push('breadcrumb-plugins')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ranks Details</li>
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
                            <h4 class="card-title">Ranks Details</h4>
                        </div>
                        <div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input autoRank"
                                        @if ($autorank == 1) checked @endif> AutoRank <i
                                        class="input-helper"></i></label>
                            </div>
                            <button class="btn btn-gradient-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#addRankModel">Add Rank</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th> User ID</th>
                                    <th> rank </th>
                                    <th> Amount </th>
                                    <th> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ranks as $user)
                                    <tr>
                                        <td>{{ $user->username }}</td>
                                        <td> {{ $user->ranks }} </td>
                                        <td> &#8377; {{ round_to_2dp($user->amount) }} </td>
                                        <td>
                                            <a href="{{ route('admin.users.info', $user->id) }}"
                                                class="btn btn-sm badge badge-success">Information</a>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <button type="button" data-data="{{ $user }}"
                                                    class="btn btn-sm btn-primary editRank" data-bs-toggle="modal"
                                                    data-bs-target="#editRank">Edit</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-2">
                            {{ $ranks->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addRankModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Rank</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" placeholder="Rank">
                    </div>
                    <div class="form-group">
                        <label for="rank">Rank</label>
                        <input type="number" class="form-control" id="rank" placeholder="Rank">
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" step="0.01" class="form-control" id="amount" placeholder="Amount">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary addRankbtn">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editRank" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Rank</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idEdit">
                    <div class="form-group">
                        <label for="usernameEdit">Username</label>
                        <input type="text" class="form-control" id="usernameEdit" placeholder="Rank">
                    </div>
                    <div class="form-group">
                        <label for="rankEdit">Rank</label>
                        <input type="number" class="form-control" id="rankEdit" placeholder="Rank">
                    </div>
                    <div class="form-group">
                        <label for="amountEdit">Amount</label>
                        <input type="number" step="0.01" class="form-control" id="amountEdit" placeholder="Amount">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary updateRank">Update Rank</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {{-- autoRank --}}
    {{-- addRankbtn  --}}
    <script>
        $(document).ready(function() {
            $('.autoRank').change(function() {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "{{ route('admin.ranks.auto') }}",
                    type: 'POST',
                    data: {
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
            $('.editRank').click(function() {
                var data = $(this).data('data');
                console.log(data);
                $('#usernameEdit').val(data.username);
                $('#rankEdit').val(data.ranks);
                $('#amountEdit').val(data.amount);
                $('#idEdit').val(data.id);
            });
            $('.addRankbtn').click(function() {
               var username = $('#username').val();
               var rank = $('#rank').val();
               var amount = $('#amount').val();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "{{ route('admin.ranks.add') }}",
                    type: 'POST',
                    data: {
                        username: username,
                        rank: rank,
                        amount:amount,
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
            $('.updateRank').click(function(){
                var username = $('#usernameEdit').val();
               var rank = $('#rankEdit').val();
               var amount = $('#amountEdit').val();
               var id = $('#idEdit').val();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "{{ route('admin.ranks.update') }}",
                    type: 'POST',
                    data: {
                        id:id,
                        username: username,
                        rank: rank,
                        amount:amount,
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
