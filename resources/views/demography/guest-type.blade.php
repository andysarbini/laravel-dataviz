@extends('layouts.dashboard')

@php
 $current_year = date("Y");
 $begin_year = date("Y") - 20;

 $start_year = \Request::get('start');
 $end_year = \Request::get('end');
 $quarter_year = \Request::get('quarter_year');
 $month_year = \Request::get('month_year');

 if(NULL == $end_year) $end_year = date("Y");
 if(NULL == $start_year) $start_year = $end_year - 5;
 if(NULL == $quarter_year) $quarter_year = $end_year;
 if(NULL == $month_year) $month_year = $end_year;
 
@endphp

@section('main-content')
    <div class="row">
        <div class="col-md-6">
            <div class="bg-white rounded p-2">
                <h2 class="p-2">All Time</h2>
                {{-- grafik all time horizontal bar chart --}}
                {!! $dg_by_type->container() !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="bg-white rounded p-2">
                <h2 class="p-2">All time</h2>
                {{-- grafik all time donut chart --}}
                {!! $dg_by_type_donut->container() !!}
            </div>
        </div>
    </div>
    <script src = "{{ LarapexChart::cdn() }}"></script>
    {{ $dg_by_type->script() }}
    {{ $dg_by_type_donut->script() }}
@endsection