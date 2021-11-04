@extends('layouts.dashboard')

@php
    $current_year = date("Y");
    $begin_year = date("Y") - 20;

    $year = \Request::get('year');

@endphp

@section('main-content')
    <div class="row mb-5">
        <div class="col-md-12">
            <h1>Room Selection</h1>
            <form class="form-inline" action="{{ \Request::url() }}" method="get">
                <select name="year" class="form-control mr-2" id="">
                    <option value="" {{ NULL == $year ? "selected" : "" }}>All time</option>
                    @for($yr = $current_year; $yr >= $begin_year; $yr--)
                        <option value="{{ $yr }}" {{ $yr == $year ? "selected" : "" }}> {{ $yr }}</option>
                    @endfor
                </select>
                <button class="btn btn-primary">Go</button>
            </form>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-6">
            <div class="bg-white rounded p-2">
                {{-- Grafik Rooms Selection Overview --}}
                {!! $rooms_overview->container() !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="bg-white rounded p-2">
                {{-- Grafik Rooms Selection Origin --}}
                {!! $rooms_by_guest_origin->container() !!}

            </div>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-md-6">
            <div class="bg-white rounded p-2">
                {{-- Grafik Rooms Selection by Age Range --}}
                {!! $rooms_by_age->container() !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="bg-white rounded p-2">
                {{-- Grafik Rooms Selection by Guest Type --}}
                {!! $rooms_by_guest_type->container() !!}
            </div>
        </div>
    </div>

    <script src="{{ LarapexChart::cdn() }}"></script>
    {{ $rooms_overview->script() }}
    {{ $rooms_by_guest_origin->script() }}
    {{ $rooms_by_age->script() }}
    {{ $rooms_by_guest_type->script() }}

@endsection