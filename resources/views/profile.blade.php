@extends('layouts.app')
@push('breadcrumb-plugins')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Profile Settings</li>
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
                            <h4 class="card-title">Profile Settings</h4>
                        </div>
                    </div>
                    <form action="{{route('admin.profile.settings')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') ?? $admin->name }}"
                                id="username" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') ?? $admin->email }}"
                                id="username" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <label for="NewPassword">New Password</label>
                            <input type="password" class="form-control" name="NewPassword"
                                id="NewPassword" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="ConfirmNewPassword">Confirm New Password</label>
                            <input type="password" class="form-control" name="ConfirmNewPassword"
                                id="ConfirmNewPassword" placeholder="Confirm Password">
                        </div>
                        <button type="submit" id="checkrecords" class="btn btn-gradient-primary me-2">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')

@endsection
