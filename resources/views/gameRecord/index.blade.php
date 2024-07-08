@extends('layouts.app')
@push('breadcrumb-plugins')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Game Records List</li>
    </ol>
</nav>
@endpush
@section('panel')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{$pagetitle}}</h4>
                </p>
                <form action="{{route('admin.gameRecord.index')}}" method="get">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">User Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="username" value="{{request()->username ?? '' }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Category</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="type">
                                        <option @if(request()->type == 0) selected
                                            @endif value="0">Transection All</option>
                                        <option @if(request()->type == 1) selected
                                            @endif value="1">Fastparity</option>
                                        <option @if(request()->type == 2) selected
                                            @endif value="2">Parity</option>
                                        <option @if(request()->type == 4) selected
                                            @endif value="4">Circle</option>
                                        <option @if(request()->type == 3) selected
                                            @endif value="3">Jet</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary mb-2">Filter</button>
                    <a href="{{route('admin.gameRecord.index')}}" class="btn btn-gradient-danger mb-2">Clear</a>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                @if(request()->has('type') )
                                @if(request()->type == 1 || request()->type == 2 || request()->type == 4 )
                                <th> # </th>
                                <th> Username </th>
                                <th> Type </th>
                                <th> Amount </th>
                                <th> date </th>
                                @elseif(request()->type == 3)
                                <th> # </th>
                                <th> Username </th>
                                <th> Period </th>
                                <th> Type </th>
                                <th> Amount </th>
                                <th> date </th>
                                @else
                                <th> # </th>
                                <th> Username </th>
                                <th> Type </th>
                                <th> TNX ID </th>
                                <th> Amount </th>
                                <th> Source </th>
                                <th> date </th>
                                @endif
                                @else
                                <th> # </th>
                                <th> Username </th>
                                <th> Type </th>
                                <th> TNX ID </th>
                                <th> Amount </th>
                                <th> Source </th>
                                <th> date </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $record)
                            <tr>
                                @if(request()->has('type'))
                                @if(request()->type == 1 || request()->type == 2 || request()->type == 4)
                                <td> # </td>
                                <td> {{$record->username}} </td>
                                <td>@if(request()->type == 1 ) FastParity Betting @elseif(request()->type == 2) Parity Betting @else Circle Betting @endif</td>
                                @if($record->res == 'fail')
                                <td class="text-danger">-{{$record->amount}}</td>
                                @elseif($record->res == 'success')
                                <td class="text-success">+{{$record->amount}}</td>
                                @else
                                <td class="text-warning">-{{$record->amount}}</td>
                                @endif
                                <td> {{ CostumDateFormet($record->time) }}</td>
                                @elseif(request()->type == 3)
                                <td> # </td>
                                <td>  {{$record->username}} </td>
                                <td>  {{$record->period}} </td>
                                <td> Jet Game </td>
                                @if($record->status == 'fail')
                                <td class="text-danger">-{{$record->amount}}</td>
                                @elseif($record->status == 'success')
                                <td class="text-success">+{{$record->amount}}</td>
                                @else
                                <td class="text-warning">-{{$record->amount}}</td>
                                @endif
                                <td> {{ CostumDateFormet($record->time) }}</td>
                                @else
                                <td>#</td>
                                <td>{{$record->username}}</td>
                                <td>
                                    Transection Records
                                </td>
                                <td>{{$record->id}}</td>
                                @if($record->status == 1)
                                <td class="text-danger">-{{$record->amount}}</td>
                                @else
                                <td class="text-success">+{{$record->amount}}</td>
                                @endif
                                <td>{{$record->reason}}</td>
                                <td> {{ CostumDateFormet($record->time) }}</td>
                                @endif
                                @else
                                <td>#</td>
                                <td>{{$record->username}}</td>
                                <td>
                                    Transection Records
                                </td>
                                <td>{{$record->id}}</td>
                                @if($record->status == 1)
                                <td class="text-danger">-{{$record->amount}}</td>
                                @else
                                <td class="text-success">+{{$record->amount}}</td>
                                @endif
                                <td>{{$record->reason}}</td>
                                <td> {{ CostumDateFormet($record->time) }}</td>
                                @endif


                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                    <div class="d-flex justify-content-center mt-2">
                        {{ $records->appends(['type' => request()->query('type'),'username' => request()->query('username')])->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
    @section('scripts')
    @endsection