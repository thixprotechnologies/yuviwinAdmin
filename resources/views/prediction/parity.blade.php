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
                            <p>Answer : {{ $nextPre->nxt == 11 ? 'Not Set' : $nextPre->nxt }}</p>
                            <p>Total Amount: &#8377; {{$bettingTotal ?? 0}}</p>
                        </div>
                        <div class="template-demo">
                            <a  href="{{route('admin.prediction')}}" class="btn btn-gradient-success btn-rounded btn-sm">Fast Parity</a>
                            <a href="{{route('admin.prediction.parity')}}" class="btn btn-gradient-primary btn-rounded btn-sm">Parity</a>
                            <a href="{{route('admin.prediction.jet')}}"  class="btn btn-gradient-success btn-rounded btn-sm">Jet</a>
                            <a href="{{route('admin.prediction.circle')}}"  class="btn btn-gradient-success btn-rounded btn-sm">Circle</a>
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
                        <div class="col-3  text-center mt-2">
                            <div style="border-radius: 2rem;background: #C13BF2;padding: 0.5rem;">Big</div>
                        </div>
                        <div class="col-3  text-center mt-2">&#8377; {{ isset($betting->Big) ? $betting->Big : 0 }}</div>
                        <div class="col-3  text-center mt-2">
                            <div style="border-radius: 2rem;background: #3977CD;padding: 0.5rem;"> Small</div>
                        </div>
                        <div class="col-3  text-center mt-2">&#8377; {{ isset($betting->Small) ? $betting->Small : 0 }}
                        </div>
                        <div class="col-3  text-center mt-2">
                            <div style="border-radius: 2rem;background: red;padding: 0.5rem;">Red</div>
                        </div>
                        <div class="col-3  text-center mt-2">&#8377; {{ isset($betting->red) ? $betting->red : 0 }}</div>
                        <div class="col-3  text-center mt-2">
                            <div style="border-radius: 2rem;background: #9400d3;padding: 0.5rem;"> Violet</div>
                        </div>
                        <div class="col-3  text-center mt-2">&#8377; {{ isset($betting->violet) ? $betting->violet : 0 }}
                        </div>

                        <div class="col-3  text-center mt-2">
                            <div style="border-radius: 2rem;background: Green;padding: 0.5rem;">Green</div>
                        </div>
                        <div class="col-3  text-center mt-2">&#8377; {{ isset($betting->green) ? $betting->green : 0 }}</div>
                        <div class="col-3  text-center mt-2">
                            <div style="border-radius: 2rem;background: #aba5a5;padding: 0.5rem;">0</div>
                        </div>
                        <div class="col-3  text-center mt-2">&#8377; {{ isset($betting->option0) ? $betting->option0 : 0 }}</div>
                        <div class="col-3  text-center mt-2">
                            <div style="border-radius: 2rem;background: #aba5a5;padding: 0.5rem;">1</div>
                        </div>
                        <div class="col-3  text-center mt-2">&#8377; {{ isset($betting->option1) ? $betting->option1 : 0 }}</div>
                        <div class="col-3  text-center mt-2">
                            <div style="border-radius: 2rem;background: #aba5a5;padding: 0.5rem;">2</div>
                        </div>
                        <div class="col-3  text-center mt-2">&#8377; {{ isset($betting->option2) ? $betting->option2 : 0 }}</div>
                        <div class="col-3  text-center mt-2">
                            <div style="border-radius: 2rem;background: #aba5a5;padding: 0.5rem;">3</div>
                        </div>
                        <div class="col-3  text-center mt-2">&#8377; {{ isset($betting->option3) ? $betting->option3 : 0 }}</div>
                        <div class="col-3  text-center mt-2">
                            <div style="border-radius: 2rem;background: #aba5a5;padding: 0.5rem;">4</div>
                        </div>
                        <div class="col-3  text-center mt-2">&#8377; {{ isset($betting->option4) ? $betting->option4 : 0 }}</div>
                        <div class="col-3  text-center mt-2">
                            <div style="border-radius: 2rem;background: #aba5a5;padding: 0.5rem;">5</div>
                        </div>
                        <div class="col-3  text-center mt-2">&#8377; {{ isset($betting->option5) ? $betting->option5 : 0 }}</div>
                        <div class="col-3  text-center mt-2">
                            <div style="border-radius: 2rem;background: #aba5a5;padding: 0.5rem;">6</div>
                        </div>
                        <div class="col-3  text-center mt-2">&#8377; {{ isset($betting->option6) ? $betting->option6 : 0 }}</div>
                        <div class="col-3  text-center mt-2">
                            <div style="border-radius: 2rem;background: #aba5a5;padding: 0.5rem;">7</div>
                        </div>
                        <div class="col-3  text-center mt-2">&#8377; {{ isset($betting->option7) ? $betting->option7 : 0 }}</div>
                        <div class="col-3  text-center mt-2">
                            <div style="border-radius: 2rem;background: #aba5a5;padding: 0.5rem;">8</div>
                        </div>
                        <div class="col-3  text-center mt-2">&#8377; {{ isset($betting->option8) ? $betting->option8 : 0 }}</div>
                        <div class="col-3  text-center mt-2">
                            <div style="border-radius: 2rem;background: #aba5a5;padding: 0.5rem;">9</div>
                        </div>
                        <div class="col-3  text-center mt-2">&#8377; {{ isset($betting->green) ? $betting->option9 : 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form class="forms-sample" action="{{ route('admin.prediction.ans') }}" method="POST">
                        @csrf
                        <input type="hidden" name="betting" value="Parity">
                        <div class="form-group">
                            <label for="answer">Next Answer</label>
                            <input type="text" class="form-control" id="answer" name="answer"
                                placeholder="Enter a number from 0-9">
                                <small id="answerHelp" class="form-text text-muted">
                                    0 - Green + Voilet + 0<br>
                                    5 - Red + Voilet + 5<br>
                                    1/3/7/9 - Red + 1/3/7/9<br>
                                    2/4/6/8 - Green +  2/4/6/8<br>
                                     0-4 - Small<br>
                                    5-9 - Big<br>
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
            var distance = 180 - countDownDate % 180;
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
            if (distance == 180) {
                location.reload();
            }
        }

        func();
        var interval = setInterval(func, 1000);
    </script>
@endsection
