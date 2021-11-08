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

            <div class="row">
                <div class="col-md-12">
                    <div class="text-center pb-5">
                        @foreach ($rooms_overview_data as $item)
                            <div class="d-inline-block bg-white m-2 rounded text-center py-2 px-5">
                                <div class="h6">
                                    {{ $item->room_category }}
                                </div>
                                <div class="h3">
                                    {{ $item->percentage }}%
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

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