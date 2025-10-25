@extends('layouts.app')

@section('styles')
    <style>
        .stats-card {
            border-left: 4px solid;
            transition: transform 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-2px);
        }
        .bg-casse-primary { background-color: #2c3e50; }
        .text-casse-primary { color: #2c3e50; }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        @include('dashboard.casse-stats')

        <div class="row mt-4">
            <div class="col-12">
                @yield('casse-content')
            </div>
        </div>
    </div>
@endsection
