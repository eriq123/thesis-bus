@extends('adminlte::page')

@section('title', 'Admin')

@section('content_header')
<h1>Welcome {{ Auth::user()->role->name }}, {{ Auth::user()->name }}!</h1>
@stop

@section('content')
@stop
