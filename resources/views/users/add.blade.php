@extends('layouts.app')
@push('breadcrumb-plugins')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Users</a></li>
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
                            <h4 class="card-title">Add User</h4>
                        </div>
                    </div>
                    <form class="forms-sample" method="post" action="{{route('admin.users.addUser')}}">
                        @csrf
                        <input type="hidden" name="ref" value="{{ Auth::guard('admin')->user()->referal }}"  />
                        <div class="form-group">
                          <label for="username">Username</label>
                          <input type="text" class="form-control" name="username" value="{{old('username')}}" id="username" placeholder="Username">
                        </div>
                        <div class="form-group">
                          <label for="password">Password</label>
                          <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                        </div>
                        <div class="form-group">
                          <label for="ConfirmPassword">Confirm Password</label>
                          <input type="password" class="form-control" name="ConfirmPassword" id="ConfirmPassword" placeholder="Password">
                        </div>
                        <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
