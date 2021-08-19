@extends('layouts.admin')
@section('contents')

@section('content_header')
<div class="text-center">
    <button class="btn btn-outline-success" type="button" id="openAddModal">
        <i class="fa fa-bus mr-1"></i>
        Schedule a bus
    </button>
    <x-modal>
        <x-slot name="modalBody">
            <input type="hidden" name="id" id="id">
            <div>
                <p class="mb-0 ml-1 text-left">Buses :</p>
                <select class="form-select form-control" id="bus_id" name="bus_id" required>
                    @forelse ($buses as $item)
                    <option value="{{ $item->id }}">
                        {{ $item->plate_number }} - {{ $item->type }} ({{ $item->capacity }})
                    </option>
                    @empty
                    <option selected>No buses available</option>
                    @endforelse
                </select>
            </div>
            <div class="mt-3">
                <p class="mb-0 ml-1 text-left">Bus Drivers :</p>
                <select class="form-select form-control" id="user_id" name="user_id" required>
                    @forelse ($drivers as $item)
                    <option value="{{ $item->id }}">
                        {{ $item->name }}
                    </option>
                    @empty
                    <option selected>No drivers available</option>
                    @endforelse
                </select>
            </div>
            <div class="mt-3">
                <p class="mb-0 ml-1 text-left">Bus Routes :</p>
                <select class="form-select form-control" id="bus_route_id" name="bus_route_id" required>
                    @forelse ($bus_routes as $item)
                    <option value="{{ $item->id }}">
                        PHP {{ $item->fare }} ({{ $item->starting_point }} - {{ $item->destination }})
                    </option>
                    @empty
                    <option selected>No bus routes available</option>
                    @endforelse
                </select>
            </div>
            <div class="my-3">
                <p class="mb-0 ml-1 text-left">Date :</p>
                <input type="date" name="schedule_date" id="schedule_date" class="form-control"
                    value="{{ old('schedule_date') }}" placeholder="Date" required>
                @if($errors->has('schedule_date'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('schedule_date') }}</strong>
                </div>
                @endif
            </div>
            <div class="my-3">
                <p class="mb-0 ml-1 text-left">Departure Time :</p>
                <input type="text" name="time_departure" id="time_departure" class="form-control"
                    value="{{ old('time_departure') }}" placeholder="Departure time e.g. 3:30 PM" required>
                @if($errors->has('time_departure'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('time_departure') }}</strong>
                </div>
                @endif
            </div>
            <div class="my-3">
                <p class="mb-0 ml-1 text-left">Arrival Time :</p>
                <input type="text" name="time_arrival" id="time_arrival" class="form-control"
                    value="{{ old('time_arrival') }}" placeholder="Arrival Time e.g. 3:30 PM" required>
                @if($errors->has('time_arrival'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('time_arrival') }}</strong>
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
                        <th>Bus</th>
                        <th>Driver</th>
                        <th>Bus Route</th>
                        <th>Schedule Date</th>
                        <th>Departure Time</th>
                        <th>Arrival Time</th>
                        <th>Actions</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($schedules as $item)
                    <tr>
                        <td>{{ $item->id}}</td>
                        <td>{{ $item->bus->plate_number}}</td>
                        <td>{{ $item->user->name}}</td>
                        <td>{{ $item->bus_route->starting_point}}</td>
                        <td>{{ $item->schedule_date}}</td>
                        <td>{{ $item->time_departure}}</td>
                        <td>{{ $item->time_arrival}}</td>
                        <td>
                            <form action="{{ route('buses.schedules.destroy', ['id'=> $item->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-primary openUpdateModal" type="button" data-id="{{ $item->id }}"
                                    data-bus_id="{{ $item->bus_id }}" data-user_id="{{ $item->user_id }}"
                                    data-bus_route_id="{{ $item->bus_route_id }}"
                                    data-schedule_date="{{ $item->schedule_date }}"
                                    data-time_departure="{{ $item->time_departure }}"
                                    data-time_arrival="{{ $item->time_arrival }}">Update</button>
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
@section('js')
<script>
    $(document).ready(function() {
        $('.openUpdateModal[data-id]').on('click',function(){
            fillUpAndOpenModal(
                "{{ route('buses.schedules.update') }}",
                'Update a schedule',
                'Update',
                'btn-success',
                'btn-primary',
                $(this).data('id'),
                $(this).data('bus_id'),
                $(this).data('user_id'),
                $(this).data('bus_route_id'),
                $(this).data('schedule_date'),
                $(this).data('time_departure'),
                $(this).data('time_arrival'),
            );
        });

        $('#openAddModal').click(function(){
            fillUpAndOpenModal(
                "{{ route('buses.schedules.store') }}",
                'Schedule a bus',
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
            bus_id = null,
            user_id = null,
            bus_route_id = null,
            schedule_date = null,
            time_departure = null,
            time_arrival = null,
        ){
            $('#id').val(id);
            if(modalFooter == 'Update'){
                $('#bus_id').val(bus_id);
                $('#user_id').val(user_id);
                $('#bus_route_id').val(bus_route_id);
            }
            $('#schedule_date').val(schedule_date);
            $('#time_departure').val(time_departure);
            $('#time_arrival').val(time_arrival);

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
