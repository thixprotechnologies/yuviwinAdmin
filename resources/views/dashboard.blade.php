@extends('layouts.app')
@push('breadcrumb-plugins')
<nav aria-label="breadcrumb">
    <ul class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">
            <span></span>Overview <i
                class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
        </li>
    </ul>
</nav>
@endpush
@section('panel')
<div class="row">
    <div class="col-md-4 stretch-card grid-margin">
      <div class="card bg-gradient-danger card-img-holder text-white">
        <div class="card-body">
          <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
          <h4 class="font-weight-normal mb-3">Active Users <i class="mdi mdi-chart-line mdi-24px float-right"></i>
          </h4>
          <h2 class="mb-5">{{$Users}}</h2>
        </div>
      </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
      <div class="card bg-gradient-info card-img-holder text-white">
        <div class="card-body">
          <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
          <h4 class="font-weight-normal mb-3">TODAY Deposit<i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
          </h4>
          <h2 class="mb-5">&#8377; {{$Todaydeposit}}</h2>
        </div>
      </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
      <div class="card bg-gradient-info card-img-holder text-white">
        <div class="card-body">
          <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
          <h4 class="font-weight-normal mb-3">Total Deposit<i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
          </h4>
          <h2 class="mb-5">&#8377; {{$Totaldeposit}}</h2>
        </div>
      </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
      <div class="card bg-gradient-success card-img-holder text-white">
        <div class="card-body">
          <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
          <h4 class="font-weight-normal mb-3">Today Withdrawal <i class="mdi mdi-diamond mdi-24px float-right"></i>
          </h4>
          <h2 class="mb-5">&#8377; {{$Todaywithdraw}}</h2>
        </div>
      </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
      <div class="card bg-gradient-success card-img-holder text-white">
        <div class="card-body">
          <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
          <h4 class="font-weight-normal mb-3">Total Withdrawal <i class="mdi mdi-diamond mdi-24px float-right"></i>
          </h4>
          <h2 class="mb-5">&#8377; {{$Totalwithdraw}}</h2>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
    <script src="{{asset('assets/vendors/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('assets/js/dashboard.js')}}"></script>
@endsection
