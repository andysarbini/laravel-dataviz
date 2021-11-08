@extends('layouts.dashboard')

@php
        $current_year = date("Y");
        $begin_year = date("Y") - 20;

        $year = \Request::get('year');
        $start_year = \Request::get('start');
        $end_year = \Request::get('end');

        if($start_year == NULL) $start_year = \Carbon\Carbon::now()->subYear(5)->year;
        if($end_year == NULL) $end_year = date("Y");

    @endphp

@section('main-content')

    <div class="row mb-5">
        <div class="col-md-12">
            <form action="{{ \Request::url() }}" class="form-inline" method="GET">
                <input type="hidden" name="start" value="{{ $start_year }}">
                <input type="hidden" name="end" value="{{ $end_year }}">

                <select name="year" class="form-control mr-2" id="">
                    <option value="" {{ NULL == $year ? "selected" : "" }}>All time</option>
                    @for($yr = $current_year; $yr >= $begin_year; $yr--)
                        <option value="{{ $yr }}" {{ $yr == $year ? "selected" : "" }}>{{ $yr }}</option>
                    @endfor
                </select>
                <button class="btn btn-primary">Go</button>
            </form>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-6">
            <h1>
                <div class="bg-white p-2 shadow-sm text-center rounded">
                    <div class="my-5">
                        <div>
                            Guest Satisfaction
                        </div>
                        {{-- satisfaction average --}}
                        {{ $satisfaction_avg }} /10
                    </div>

                    {{-- satisfaction by score --}}
                    <br>
                    {!! $satisfaction_by_scores->container() !!}
                </div>
            </h1>
        </div>
        <div class="col-md-6">
            <div class="bg-white p-2 shadow-sm rounded">
                {{-- grafik classified --}}
                <div class="text-center">
                    @foreach ($satisfaction_classified_data as $item)
                        <div class="d-inline-block my-5 text-center p-2">
                            {{ $item->class }} <br>
                            <h4>{{ $item->percentage }}%</h4>
                        </div>
                    @endforeach
                </div>
                {!! $satisfaction_classified->container() !!}
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-7">
            <div class="bg-white p-2 rounded shadow-sm">
                {{-- by months --}}
                {!! $satisfaction_by_months->container() !!}
            </div>
        </div>
        <div class="col-md-5">
            <div class="bg-white p-2 rounded shadow-sm">
                {{-- by services --}}
                {!! $satisfaction_by_services->container() !!}
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-12">
            <div class="bg-white rounded p-2 shadow-sm">
                <form action="{{ Request::url() }}" class="form-inline">

                    <input type="hidden" nam="year" value="{{ $year }}">

                    <div class="form-group">
                        <select name="start" id="" class="form-control">
                            @for($year = $current_year; $year >= $begin_year; $year--)
                                <option value="{{ $year }}" {{ $year == $start_year ? "selected" : ""}}> {{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="px-3">s.d</div>
                    <div class="form-group">
                        <select name="end" id="" class="form-control">
                            @for($year = $current_year; $year >= $begin_year; $year--)
                                <option value="{{ $year }}" {{ $year == $end_year ? "selected" : ""}}> {{ $year }}</option>
                            @endfor
                        </select>
                    </div>

                    <button class="ml-3 btn btn-primary"> Go </button>
                </form>
            </div>
            <div class="bg-white rounded p-2 shadow-sm">
                {{-- grafik yoy --}}
                {!! $satisfaction_yoy->container() !!}
            </div>
        </div>
    </div>

    <script src="{{ LarapexChart::cdn() }}"></script>
        {{ $satisfaction_by_scores->script() }}
        {{ $satisfaction_classified->script() }}
        {{ $satisfaction_by_months->script() }}
        {{ $satisfaction_by_services->script() }}
        {{ $satisfaction_yoy->script() }}
    
@endsection