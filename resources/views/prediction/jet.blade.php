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
                        <h4 class="card-title">Betting Amount : &#8377; {{ $betting }} <br>Withdraw Bets Amount: &#8377; {{$bettingSuccess}} </h4>
                        <p>Period : {{ $nextPre->period }} at <span id="crashposition"></span></p>
                        <p>Answer : {{ $nextPre->nxt == 0 ? 'Not Set' : $nextPre->nxt . 'x' }}</p>
                        <button type="buttoon" id="btnCrash" class="btn btn-gradient-primary me-2">Instant Crash</button>
                    </div>
                    <div class="template-demo">
                        <a href="{{ route('admin.prediction') }}" class="btn btn-gradient-success btn-rounded btn-sm">Fast Parity</a>
                        <a href="{{ route('admin.prediction.parity') }}" class="btn btn-gradient-success btn-rounded btn-sm">Parity</a>
                        <a href="{{ route('admin.prediction.jet') }}" class="btn btn-gradient-primary btn-rounded btn-sm">Jet</a>
                        <a href="{{ route('admin.prediction.circle') }}" class="btn btn-gradient-success btn-rounded btn-sm">Circle</a>
                    </div>
                </div>
                <form class="forms-sample" action="{{ route('admin.prediction.ans') }}" method="POST">
                    @csrf
                    <input type="hidden" name="betting" value="jet">
                    <div class="form-group">
                        <label for="answer">Next Answer</label>
                        <input type="number" class="form-control" id="answer" name="answer" placeholder="Enter a number from 0 - 25">
                        <small id="answerHelp">Crash Point will be same for jet.</small>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.socket.io/4.7.4/socket.io.min.js" integrity="sha384-Gr6Lu2Ajx28mzwyVR8CFkULdCU7kMlZ9UthllibdOSo6qAiN+yXNHqtgdTvFXMT4" crossorigin="anonymous"></script>

<script>
    const socket = io('https://yuviwin.com:3000', {
        reconnect: true, // Allow reconnection
        transports: ['websocket'],
        upgrade: false,
    });
    var crashpoint = 1;
    socket.on('connect', function() {
        console.log('Connected to Socket.IO server');
    });

    socket.on('disconnect', function() {
        console.log('Disconnected from Socket.IO server');
    });
    socket.on('removecrash', function() {
        window.location.reload()
    })
    socket.on('crash-update', (data) => {
            document.getElementById('crashposition').innerText = data.crashPosition+ ' X';
            crashpoint = data.crashPosition;
        });
    
    $('#btnCrash').on('click',()=>{
        const username = 'BombwinAdmin@#123456';
        socket.emit('emitinstantCrash',username,crashpoint);
    });

    // Event listener for Socket.IO errors
    socket.on('error', function(error) {
        console.error('Socket.IO error:', error);
    });
</script>
@endsection