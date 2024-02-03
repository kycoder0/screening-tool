@extends('layouts.base')

@section('body')
    @yield('content')

    @isset($slot)
        {{ $slot }}
        @livewireScripts
    @endisset
@endsection
