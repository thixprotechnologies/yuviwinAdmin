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
                    <form action="{{route('admin.upi.add')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="phonepayUpi">PhonePay Upi</label>
                            <input type="text" class="form-control" name="phonepayUpi" value="{{ old('phonepayUpi') ?? $settings->upi }}"
                                id="phonepayUpi" placeholder="PhonePay Upi">
                        </div>
                        <div class="form-group">
                            <label for="PaytmUpi">Paytm Upi</label>
                            <input type="text" class="form-control" name="PaytmUpi" value="{{ old('PaytmUpi') ?? $settings->upi1 }}"
                                id="PaytmUpi" placeholder="Paytm Upi">
                        </div>
                        <div class="form-group">
                            <label for="PaytmUpi">Gpay</label>
                            <input type="text" class="form-control" name="gpayupi" value="{{ old('gpayupi') ?? $settings->upi2 }}"
                                id="PaytmUpi" placeholder="Gpay Upi">
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
