@extends('layouts.app')
@push('breadcrumb-plugins')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Next Prediction</li>
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
                            <h4 class="card-title">PERIOD : {{ $nextPre->period }}</h4>
                            <p id="time">Time : </p>
                            @php
                                if ($nextPre['nxt'] == '0') {
                                    $pre = 'Not set';
                                } elseif ($nextPre['nxt'] == '1') {
                                    $pre = 'gold';
                                } elseif ($nextPre['nxt'] == '2') {
                                    $pre = 'red';
                                } elseif ($nextPre['nxt'] == '3') {
                                    $pre = 'violet';
                                } elseif ($nextPre['nxt'] == '4') {
                                    $pre = 'green';
                                }
                            @endphp
                            <p>Answer : {{ $nextPre->nxt == 11 ? 'Not Set' : $pre }}</p>
                            <p>Total Amount: &#8377; {{$bettingTotal ?? 0}}</p>

                        </div>
                        <div class="template-demo">
                            <a href="{{ route('admin.prediction') }}"
                                class="btn btn-gradient-success btn-rounded btn-sm">Fast Parity</a>
                            <a href="{{ route('admin.prediction.parity') }}"
                                class="btn btn-gradient-success btn-rounded btn-sm">Parity</a>
                            <a href="{{ route('admin.prediction.jet') }}"
                                class="btn btn-gradient-success btn-rounded btn-sm">Jet</a>
                            <a href="{{ route('admin.prediction.circle') }}"
                                class="btn btn-gradient-primary btn-rounded btn-sm">Circle</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Batting Amount</h4>
                    <div class="row">
                        <div class="col-6 text-center mt-2">
                            <div
                                style="
                            border-radius: 2rem;
                            background: red;
                            padding: 0.5rem;">
                                Red</div>
                        </div>
                        <div class="col-6  text-center mt-2">&#8377; {{(isset($betting->red)) ? $betting->red: 0}}</div>

                        <div class="col-6  text-center mt-2">
                            <div
                                style="
                            border-radius: 2rem;
                            background: #f9ab13;
                            padding: 0.5rem;">
                                Gold</div>
                        </div>
                        <div class="col-6  text-center mt-2">&#8377; {{(isset($betting->gold))? $betting->gold: 0}}</div>

                        <div class="col-6  text-center mt-2">
                            <div
                                style="
                            border-radius: 2rem;
                            background: #9400d3;
                            padding: 0.5rem;">
                                Violet</div>
                        </div>
                        <div class="col-6  text-center mt-2">&#8377; {{(isset($betting->violet))? $betting->violet : 0}}</div>

                        <div class="col-6  text-center mt-2">
                            <div
                                style="
                            border-radius: 2rem;
                            background: Green;
                            padding: 0.5rem;">
                                Green</div>
                        </div>
                        <div class="col-6  text-center mt-2">&#8377; {{(isset($betting->green))? $betting->green:0}}</div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form class="forms-sample" action="{{ route('admin.prediction.ans') }}" method="POST">
                        @csrf
                        <input type="hidden" name="betting" value="circle">
                        <div class="form-group">
                            <label for="answer">Next Answer</label>
                            <input type="text" class="form-control" id="answer" name="answer"
                                placeholder="Enter a number from 0-9">
                            <small id="answerHelp" class="form-text text-muted">
                                1 - Gold<br>
                                2 - Red<br>
                                3 - Violet<br>
                                4 - Green<br>
                            </small>
                        </div>
                        <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function func() {
            var countDownDate = Date.parse(new Date()) / 1e3;
            var now = new Date().getTime();
            var distance = 30 - countDownDate % 30;
            var i = distance / 60,
                n = distance % 60,
                o = n / 10,
                s = n % 10;
            var minutes = Math.floor(i);
            var seconds = ("0" + Math.floor(n)).slice(-2);
            var sec1 = ((seconds % 100) - (seconds % 10)) / 10;
            var sec2 = seconds % 10;
            document.getElementById("time").innerHTML =
                "TIME : 0 " + Math.floor(minutes) + " : " + sec1 + " " + sec2 + " ";
            if (distance == 30) {
                location.reload();
            }
        }

        func();
        var interval = setInterval(func, 1000);
    </script>
@endsection
