@extends('layouts.admin')
@section('contents')

@if (Auth::user()->role_id == 1)
@section('content_header')
<div class="text-center">
    <a href="{{ route('buses.bookings.add') }}" class="btn btn-outline-success" type="button">
        <i class="fas fa-book-open"></i>
        Book a seat
    </a>
</div>
@stop
@endif
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <x-table>
                <x-slot name="thead">
                    <tr>
                        <th>ID</th>
                        <th>Status</th>
                        <th>Passenger Name</th>
                        <th>Route</th>
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
                        <td>
                            <button class="btn btn-sm btn-{{ $item->status->class }}">
                                {{ $item->status->title }}
                            </button>
                        </td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->schedule->starting_point->name }} - {{ $item->schedule->destination->name }}</td>
                        <td>{{ $item->schedule->schedule_date }} ({{ $item->schedule->time_departure }} -
                            {{ $item->schedule->time_arrival }})</td>
                        <td>{{ $item->fare_amount}}</td>
                        <td>{{ $item->quantity}}</td>
                        <td>{{ $item->grand_total}}</td>
                        <td>
                            @if (Auth::user()->role_id == 3)
                            <div class="d-flex">
                                <form action="{{ route('buses.bookings.update.status') }}" class="mr-1" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <input type="hidden" name="status" value="Verified">
                                    <button class="btn btn-primary">Verify</button>
                                </form>
                                <form action="{{ route('buses.bookings.update.status') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <input type="hidden" name="status" value="Closed">
                                    <button class="btn btn-danger">Leave</button>
                                </form>
                            </div>
                            @else
                            <form action="{{ route('buses.bookings.destroy', ['id'=> $item->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <a href="{{ route('buses.bookings.edit', ['id' => $item->id]) }}" role="button"
                                    class="btn btn-primary">Update</a>
                                <button class="btn btn-danger">Delete</button>
                            </form>
                            @endif
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
