@extends('layouts.dashboard')

@section('main-content')

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
                    {!! $satisfaction_by_scores->container() !!}
                </div>
            </h1>
        </div>
        <div class="col-md-6">
            <div class="bg-white p-2 shadow-sm rounded">
                {{-- grafik classified --}}
            </div>
        </div>
    </div>

    <script src="{{ LarapexChart::cdn() }}"></script>
        {{ $satisfaction_by_scores->script() }}
    
@endsection