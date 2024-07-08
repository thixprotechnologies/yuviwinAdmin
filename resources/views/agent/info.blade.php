@extends('layouts.app')
@push('breadcrumb-plugins')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">users</a></li>
            <li class="breadcrumb-item active" aria-current="page">Information</li>
        </ol>
    </nav>
@endpush
@section('panel')
    <div class="row">
        <div class="col-md-7 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="clearfix">
                        <h4 class="card-title float-left">Details</h4>
                    </div>
                    <p>
                        Name : @if ($user->name != '')
                            {{ $user->name }}
                        @else
                            {{ $user->nickname }}
                        @endif
                    </p>
                    <p>{{ $user->amount }}</p>
                    <p>Balance : &#8377; {{ round_to_2dp($user->balance) }} </p>
                    <p>Bonus : &#8377; {{ round_to_2dp($user->bonus) }} </p>
                    <p>Mobile: {{$user->username}}</p>
                    <p>Total Battings: {{$battingCount}}</p>
                    <p>Total Withdraw: &#8377;  {{round_to_2dp($withdraw)}}</p>
                    <p>Total Deposit: &#8377;  {{round_to_2dp($deposit)}}</p>
                </div>
            </div>
        </div>
        <div class="col-md-5 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Game Played</h4>
                    <canvas id="doughnutChart" style="height:250px"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Last Recharge Records</h4>
                </p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th> # </th>
                            <th> Amount </th>
                            <th> Status </th>
                            <th> date </th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($rechargeRecord as $recharge)
                            <tr>
                                <td>  {{ $loop->iteration }}</td>
                                <td> &#8377;  {{round_to_2dp($recharge->recharge)}} </td>
                                <td>
                                    {!! getRecharegStatus($recharge->status) !!}
                                </td>
                                <td> {{CostumDateFormet($recharge->created_at) }} </td>
                              </tr>
                            @endforeach

                        </tbody>
                      </table>
                </div>
              </div>
            </div>
          </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Last Withdraw Records</h4>
                </p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th> # </th>
                            <th> Amount </th>
                            <th> Type </th>
                            <th> Status </th>
                            <th> date </th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($withdrawRecord as $withdraw)
                            <tr>
                                <td>  {{ $loop->iteration }}</td>
                                <td>&#8377;  {{round_to_2dp($withdraw->withdraw)}} </td>
                                <td> {{$withdraw->type}} </td>
                                <td>
                                    {{$withdraw->status}}
                                </td>
                                <td> {{CostumDateFormet($withdraw->created_at) }} </td>
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
    <script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>
    <script>
        $(function() {
            /* ChartJS
             * -------
             * Data and config for chartjs
             */
            'use strict';
            var doughnutPieData = {
                datasets: [{
                    data: [{{ $fast }}, {{ $parity }}, {{ $circle }},
                        {{ $jet }}
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 159, 64, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                }],

                // These labels appear in the legend and in the tooltips when hovering different arcs
                labels: [
                    'Fast Parity',
                    'Parity',
                    'Circle',
                    'Jet'
                ]
            };
            var doughnutPieOptions = {
                responsive: true,
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            };




            if ($("#doughnutChart").length) {
                var doughnutChartCanvas = $("#doughnutChart").get(0).getContext("2d");
                var doughnutChart = new Chart(doughnutChartCanvas, {
                    type: 'doughnut',
                    data: doughnutPieData,
                    options: doughnutPieOptions
                });
            }
        });
    </script>
@endsection
