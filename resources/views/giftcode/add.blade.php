@extends('layouts.app')
@push('breadcrumb-plugins')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Giftcode</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add</li>
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
                            <h4 class="card-title">Add Giftcode</h4>
                        </div>
                    </div>
                    <form class="forms-sample" method="post" action="{{route('admin.giftcode.addcodegift')}}">
                        @csrf
                        <input type="hidden" name="username" value="{{ old('email') ?? $admin->email }}"  />
                        <div class="form-group">
                          <label for="username">Amount</label>
                          <input type="text" class="form-control" name="amount" placeholder="Amount">
                        </div>
                        <div class="form-group">
                          <label for="username">User Limit</label>
                          <input type="text" class="form-control" name="limit" placeholder="User Limit">
                        </div>
                        <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between my-2">
                    <div class="text-left">
                        <h4 class="card-title">GiftRecord</h4>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th> Code</th>
                                <th> Amount </th>
                                <th> Used </th>
                                <th> User Limit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gifts as $gifts)
                            <tr>
                                <td>{{ $gifts->code }}</td>
                                <td>{{ $gifts->amount }}</td>
                                <td>{{ $gifts->used }}</td>
                               <td>{{ $gifts->userlimit }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                   
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('scripts')
@endsection
