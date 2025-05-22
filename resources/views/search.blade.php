@extends('layouts.main')

@section('styles')
    <style>
        body {
            height: 100vh;
        }
    </style>
@endsection

@section('content')
    @livewire('search')
@endsection
