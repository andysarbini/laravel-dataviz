@extends('layouts.dashboard')

@php
    $current_year = date("Y");
    $begin_year = date("Y") - 20;

    $year = \Request::get('year');

@endphp

@section('main-content')

    <div class="row mb-5">
        <div class="com-md-12">
            <h1 class="p-2">Stay Duration</h1>

            <div class="row">
                <div class="col-md-12">
                    <div class="text-center pb-5">
                        @foreach ($duration_overview_data as $item)
                            <div class="d-inline-block bg-white m-2 rounded text-center py-2 px-5">
                                <div class="h6">
                                    {{ $item->duration }} night(s)
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
                {{-- grafik duration overview --}}
                {!! $duration_overview->container() !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="bg-white rounded p-2">
                {{-- grafik duration by guest origin --}}
                {!! $duration_by_guest_origin->container() !!}
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-6">
            <div class="bg-white rounded p-2">
                {{-- grafik duration by age --}}
                {!! $duration_by_age->container() !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="bg-white rounded p-2">
                {{-- grafik duration by guest type --}}
                {!! $duration_by_guest_type->container() !!}
            </div>
        </div>
    </div>

<script src="{{ LarapexChart::cdn() }}"></script>
    {{ $duration_overview->script() }}
    {{ $duration_by_guest_origin->script() }}
    {{ $duration_by_age->script() }}
    {{ $duration_by_guest_type->script() }}
    
@endsection