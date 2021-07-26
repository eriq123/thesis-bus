@extends('layouts.admin')
@section('contents')

@section('content_header')
<div class="text-center">
    <button class="btn btn-outline-success" type="button" id="openAddModal">
        <i class="fa fa-bus mr-1"></i>
        Book a bus
    </button>
    <x-modal>
        <x-slot name="modalBody">
            <input type="hidden" name="id" id="id">
            <div class="">
                <p class="mb-0 ml-1 text-left">Bus Routes :</p>
                <select class="form-select form-control" id="bus_route_id" name="bus_route_id" required>
                    @forelse ($bus_routes as $item)
                    <option value="{{ $item->id }}" data-fare="{{ $item->fare }}">
                        PHP {{ $item->fare }} ({{ $item->starting_point }} - {{ $item->destination }})
                    </option>
                    @empty
                    <option selected>No bus routes available</option>
                    @endforelse
                </select>
            </div>
            <div class="mt-3">
                <p class="mb-0 ml-1 text-left">Bus Schedules :</p>
                <select class="form-select form-control" id="schedule_id" name="schedule_id" required>
                    <option selected>No bus schedules available</option>
                </select>
            </div>
            <div class="mt-3">
                <p class="mb-0 ml-1 text-left">Passenger :</p>
                <select class="form-select form-control" id="user_id" name="user_id" required>
                    @forelse ($passengers as $item)
                    <option value="{{ $item->id }}">
                        {{ $item->name }}
                    </option>
                    @empty
                    <option selected>No passengers available</option>
                    @endforelse
                </select>
            </div>

            <div class="my-3">
                <p class="mb-0 ml-1 text-left">Quantity :</p>
                <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}"
                    placeholder="Quantity" required autocomplete="off">
                @if($errors->has('quantity'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('quantity') }}</strong>
                </div>
                @endif
            </div>

            <div class="my-3">
                <p class="mb-0 ml-1 text-left">Fare :</p>
                <input type="text" name="fare_amount" id="fare_amount" class="form-control"
                    value="{{ old('fare_amount') }}" placeholder="Fare" required readonly>
                @if($errors->has('fare_amount'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('fare_amount') }}</strong>
                </div>
                @endif
            </div>
            <div class="my-3">
                <p class="mb-0 ml-1 text-left">Grand Total :</p>
                <input type="text" name="grand_total" id="grand_total" class="form-control"
                    value="{{ old('grand_total') }}" placeholder="Grand Total" required readonly>
                @if($errors->has('grand_total'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('grand_total') }}</strong>
                </div>
                @endif
            </div>
        </x-slot>
        <x-slot name="modalFooter">
            <button class="btn btn-success" id="footerButton">Save</button>
        </x-slot>
    </x-modal>
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
                                <button class="btn btn-primary" type="button" id="openUpdateModal"
                                    data-id="{{ $item->id }}" data-user_id="{{ $item->user_id }}"
                                    data-schedule_id="{{ $item->schedule_id }}" data-quantity="{{ $item->quantity }}"
                                    data-grand_total="{{ $item->grand_total }}"
                                    data-status="{{ $item->status }}">Update</button>
                                <button class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">
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
@section('js')
<script>
    $(document).ready(function() {
        var BUS_ROUTE_FARE;

        $('#bus_route_id').change(function() {
            appendScheduleByRoute($(this).val())
        });

        function appendScheduleByRoute(routeID) {
            $.ajax({
                type: 'POST',
                url: "{{ route('buses.bookings.scheduleByRouteID') }}",
                data: {
                    id: routeID,
                },
                success: function(data) {
                    $('#schedule_id').empty();
                    if(data.length > 0) {
                        data.forEach(function(value){
                            $('#schedule_id').append(`<option value="${value.id}">${value.schedule_date} (${value.time_departure} -
                                ${value.time_arrival})</option>`);
                        });
                    } else {
                        $('#schedule_id').append(`<option selected>No bus schedules available.</option>`);
                    }
                    BUS_ROUTE_FARE = $('#bus_route_id option:selected').data('fare');
                    $('#fare_amount').val(BUS_ROUTE_FARE);
                },
                errors: function(error) {
                    console.log(error);
                }
            });
        }

        $('#quantity').on('input',function(e) {
            var grand_total = $(this).val() * BUS_ROUTE_FARE;
            $('#grand_total').val(grand_total);
        });

        $('#openUpdateModal[data-id]').on('click',function(){
            fillUpAndOpenModal(
                "{{ route('buses.bookings.update') }}",
                'Book a bus',
                'Update',
                'btn-success',
                'btn-primary',
                $(this).data('id'),
                $(this).data('user_id'),
                $(this).data('schedule_id'),
                $(this).data('fare_amount'),
                $(this).data('quantity'),
                $(this).data('grand_total'),
                $(this).data('status'),
            );
        });

        $('#openAddModal').click(function(){
            fillUpAndOpenModal(
                "{{ route('buses.bookings.store') }}",
                'Book a bus',
                'Save',
                'btn-primary',
                'btn-success',
            );
        });

        function fillUpAndOpenModal(
            formRoute,
            modalTitle,
            modalFooter,
            removeClass,
            addClass,
            id = null,
            user_id = null,
            schedule_id = null,
            quantity = null,
            grand_total = null,
            status = null,
        ){
            $('#id').val(id);
            $('#user_id').val(user_id);
            $('#schedule_id').val(schedule_id);
            $('#quantity').val(quantity);
            $('#grand_total').val(grand_total);
            $('#status').val(status);

            appendScheduleByRoute($('#bus_route_id').val());
            BUS_ROUTE_FARE = $('#bus_route_id option:selected').data('fare');
            $('#fare_amount').val(BUS_ROUTE_FARE);

            $('#crudModalForm').attr('action',formRoute);
            $('#modalTitle').text(modalTitle);
            $('#footerButton').text(modalFooter);
            $('#footerButton').removeClass(removeClass).addClass(addClass);
            $('#crud-modal').modal('show');
        }
    });
</script>
@endsection
@stop
