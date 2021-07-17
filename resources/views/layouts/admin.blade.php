@extends('adminlte::page')

@section('title', 'Admin')

@yield('contents')

@section('css')
@endsection

@section('js')
<script src="{{ mix('js/app.js') }}"></script>
@endsection
