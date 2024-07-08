@extends('layouts.master')

@section('content')
    <div class="container-scroller">
        @include('partials.topnav')
        <div class="container-fluid page-body-wrapper">
            @include('partials.sidenav')
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        @include('partials.breadcrumb')
                    </div>
                    @yield('panel')
                </div>
            </div>
        </div>
    </div>
@endsection
