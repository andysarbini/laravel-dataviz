@php
    $path = \Request::path();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- script -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito">

    <!-- styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
    <body>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 p-4 bg-white shadow-sm"> <!-- warna background putih shadow padding 4px utk semua sisi -->
        
                    <div class="rounded bg-light px-2 py-3 mb-5">
                        <span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 4H21V10H15V4Z" fill="currentColor" /><path d="M3 12C3 16.9706 7.02944 21 12 21C16.9706 21 21 16.9706 21 12H17C17 14.7614 14.7614 17 12 17C9.23858 17 7 14.7614 7 12H3Z" fill="currentColor" /><path d="M6 10C7.65685 10 9 8.65685 9 7C9 5.34315 7.65685 4 6 4C4.34315 4 3 5.34315 3 7C3 8.65685 4.34315 10 6 10Z" fill="currentColor" /></svg>
                        </span>
                        <span class="h5">
                            User
                        </span>
                    </div>

                    <div class="mb-3">
                        <div class="text-white px-1 py-2 rounded text-xl bg-primary mb-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.2426 6.34319C16.6331 5.95266 17.2663 5.95266 17.6568 6.34319C18.0474 6.73371 18.0474 7.36688 17.6568 7.7574L7.75734 17.6569C7.36681 18.0474 6.73365 18.0474 6.34313 17.6569C5.9526 17.2664 5.9526 16.6332 6.34313 16.2427L16.2426 6.34319Z" fill="currentColor" /><path d="M9.87866 9.87872C9.09761 10.6598 7.83128 10.6598 7.05023 9.87872C6.26918 9.09767 6.26918 7.83134 7.05023 7.05029C7.83128 6.26924 9.09761 6.26924 9.87866 7.05029C10.6597 7.83134 10.6597 9.09767 9.87866 9.87872Z" fill="currentColor" /><path d="M14.1213 16.9498C14.9023 17.7308 16.1687 17.7308 16.9497 16.9498C17.7308 16.1687 17.7308 14.9024 16.9497 14.1214C16.1687 13.3403 14.9023 13.3403 14.1213 14.1214C13.3403 14.9024 13.3403 16.1687 14.1213 16.9498Z" fill="currentColor" /></svg>  
                            Occupancy Rate <br>
                        </div>
                        <div class="ml-4 px-2 py-1 rounded {{ $path == 'occupancy-rate' ? 'bg-dark' : '' }}">
                            <a class="{{ $path == 'occupancy-rate' ? 'text-white' : 'text-dark' }}" href="{{ url('/occupancy-rate') }}">Overview</a>
                        </div>
                        <div class="ml-4 px-2 py-1 rounded {{ $path == 'occupancy-rate/room' ? 'bg-dark' : '' }}">
                            <a class="{{ $path == 'occupancy-rate/room' ? 'text-white' : 'text-dark' }}" href="{{ url('/occupancy-rate/room') }}">By room category</a>
                        </div>
                        <div class="ml-4 px-2 py-1 rounded {{ $path == 'occupancy-rate/bed' ? 'bg-dark' : '' }}">
                            <a class="{{ $path == 'occupancy-rate/bed' ? 'text-white' : 'text-dark' }}" href="{{ url('/occupancy-rate/bed') }}">By bed type</a>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="text-white px-1 py-2 rounded text-xl bg-primary mb-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M8 11C10.2091 11 12 9.20914 12 7C12 4.79086 10.2091 3 8 3C5.79086 3 4 4.79086 4 7C4 9.20914 5.79086 11 8 11ZM8 9C9.10457 9 10 8.10457 10 7C10 5.89543 9.10457 5 8 5C6.89543 5 6 5.89543 6 7C6 8.10457 6.89543 9 8 9Z" fill="currentColor" /><path d="M11 14C11.5523 14 12 14.4477 12 15V21H14V15C14 13.3431 12.6569 12 11 12H5C3.34315 12 2 13.3431 2 15V21H4V15C4 14.4477 4.44772 14 5 14H11Z" fill="currentColor" /><path d="M22 11H16V13H22V11Z" fill="currentColor" /><path d="M16 15H22V17H16V15Z" fill="currentColor" /><path d="M22 7H16V9H22V7Z" fill="currentColor" /></svg>
                            Demography <br>
                        </div>
                        <div class="ml-4 px-2 py-1 rounded {{ $path == 'demography' ? 'bg-dark' : '' }}">
                            <a href="{{ url('/demography') }}">By Age By Range</a>
                        </div>
                        <div class="ml-4 px-2 py-1 rounded {{ $path == 'demography/guest-type' ? 'bg-dark' : '' }}">
                            <a href="{{ url('/demography/guest-type') }}">By Guest Type</a>
                        </div>
                        <div class="ml-4 px-2 py-1 rounded {{ $path == 'demography/origin' ? 'bg-dark' : '' }}">
                            <a href="{{ url('/demography/origin') }}">By Origin</a>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="text-white px-1 py-2 rounded text-xl bg-primary mb-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 6V4H20V20H12V18H8V16H4V8H8V6H12ZM14 6H18V18H14V6ZM12 8H10V16H12V8ZM8 10V14H6V10H8Z" fill="currentColor" /></svg>
                            Behaviour
                        </div>
                        <div class="ml-4 px-2 py-1 rounded {{ $path == 'behaviour/rooms' ? 'bg-dark' : '' }}">
                            <a href="{{ url('/behaviour/rooms') }}">Room Selection</a>
                        </div>
                        <div class="ml-4 px-2 py-1 rounded {{ $path == 'behaviour/duration' ? 'bg-dark' : '' }}">
                            <a href="{{ url('/behaviour/duration') }}">Stay Duration</a>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="text-white px-1 py-2 rounded text-xl bg-primary mb-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M19 9C19 11.3787 17.8135 13.4804 16 14.7453V22H13.4142L12 20.5858L10.5858 22H8V14.7453C6.18652 13.4804 5 11.3787 5 9C5 5.13401 8.13401 2 12 2C15.866 2 19 5.13401 19 9ZM17 9C17 11.7614 14.7614 14 12 14C9.23858 14 7 11.7614 7 9C7 6.23858 9.23858 4 12 4C14.7614 4 17 6.23858 17 9ZM10 19.7573L12 17.7573L14 19.7574V15.7101C13.3663 15.8987 12.695 16 12 16C11.305 16 10.6337 15.8987 10 15.7101V19.7573Z" fill="currentColor" /></svg>
                            Satisfaction
                        </div>
                        <div class="ml-4 px-2 py-1 rounded {{ $path == 'satisfactions' ? 'bg-dark' : '' }}">
                            <a href="{{ url('/satisfactions') }}">Satisfaction</a>
                        </div>                       
                    </div>
        
                </div>
        
                <div class="col-md-10 p-4"> <!-- padding 4px di setiap sisi dari konten utama dashboard -->
                    @yield('main-content')
                </div>
            </div>
        </div>

    </body>
</html>
    
