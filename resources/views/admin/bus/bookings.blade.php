@extends('layouts.admin')
@section('contents')

@section('content_header')
<div class="text-center">
    <a href="{{ route('buses.bookings.process') }}" class="btn btn-outline-success" type="button">
        <i class="fas fa-book-open"></i>
        Book a seat
    </a>
</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <x-table>
                <x-slot name="thead">
                    <tr>
                        <th>ID</th>
                        <th>Passenger Name</th>
                        <th>Schedule</th>
                        <th>Fare</th>
                        <th>Quantity</th>
                        <th>Grand Total</th>
                        <th>Actions</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($bookings as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->schedule->schedule_date }} ({{ $item->schedule->time_departure }} -
                            {{ $item->schedule->time_arrival }})</td>
                        <td>{{ $item->fare_amount}}</td>
                        <td>{{ $item->quantity}}</td>
                        <td>{{ $item->grand_total}}</td>
                        <td>
                            <form action="{{ route('buses.bookings.destroy', ['id'=> $item->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-primary openUpdateModal" type="button" data-id="{{ $item->id }}"
                                    data-user_id="{{ $item->user_id }}" data-schedule_id="{{ $item->schedule_id }}"
                                    data-quantity="{{ $item->quantity }}" data-grand_total="{{ $item->grand_total }}"
                                    data-status="{{ $item->status }}">Update</button>
                                <button class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="100%" class="text-center">
                            No data available.
                        </td>
                    </tr>
                    @endforelse
                </x-slot>
            </x-table>
        </div>
    </div>
</div>
@stop
@stop
