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
                      @if($item->status == 'booked')
                        <option value="{{ $item->id }}" disabled>
                            {{ $item->plate_number }} - {{ $item->type }} ({{ $item->capacity }})
                        </option>
                        @elseif($item->status == 'free')
                         <option value="{{ $item->id }}" >
                            {{ $item->plate_number }} - {{ $item->type }} ({{ $item->capacity }})
                        </option>
                        @endif
                    @empty
                    <option selected>No buses available</option>
                    @endforelse
                </select>
            </div>
            <div class="mt-3">
                <p class="mb-0 ml-1 text-left">Bus Drivers :</p>
                <select class="form-select form-control" id="driver_id" name="driver_id" required>
                    @forelse ($drivers as $item)
                      @if($item->status == 'open')
                        <option value="{{ $item->id }}">
                            {{ $item->name }}
                        </option>
                      @elseif($item->status == 'booked')
                        <option value="{{ $item->id }}" disabled>
                            {{ $item->name }}
                        </option>
                       @endif 
                    @empty
                    <option selected>No drivers available</option>
                    @endforelse
                </select>
            </div>
            <div class="mt-3">
                <p class="mb-0 ml-1 text-left">Bus Conductor :</p>
                <select class="form-select form-control" id="conductor_id" name="conductor_id" required>
                    @forelse ($conductors as $item)
                        @if($item->status == 'open')
                            <option value="{{ $item->id }}">
                                {{ $item->name }}
                            </option>
                        @elseif($item->status == 'booked')
                            <option value="{{ $item->id }}" disabled>
                                {{ $item->name }}
                            </option>
                       @endif 
                    @empty
                    <option selected>No conductors available</option>
                    @endforelse
                </select>
            </div>
            <div class="mt-3">
                <p class="mb-0 ml-1 text-left">From :</p>
                <select class="form-select form-control" id="starting_point_id" name="starting_point_id" required>
                    @forelse ($bus_routes as $item)
                    <option value="{{ $item->id }}">
                        {{ $item->name }}
                    </option>
                    @empty
                    <option selected>No bus routes available</option>
                    @endforelse
                </select>
            </div>
            <div class="mt-3">
                <p class="mb-0 ml-1 text-left">To :</p>
                <select class="form-select form-control" id="destination_id" name="destination_id" required>
                    @forelse ($bus_routes as $item)
                    <option value="{{ $item->id }}">
                        {{ $item->name }}
                    </option>
                    @empty
                    <option selected>No bus routes available</option>
                    @endforelse
                </select>
            </div>
            <div class="my-3">
                <p class="mb-0 ml-1 text-left">Fare :</p>
                <input type="text" name="fare" id="fare" class="form-control" value="{{ old('fare') }}"
                    placeholder="Fare" required>
                @if($errors->has('fare'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('fare') }}</strong>
                </div>
                @endif
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
                        <th>Bus</th>
                        <th>Driver</th>
                        <th>Conductor</th>
                        <th>Routes</th>
                        <th>Schedule Date</th>
                        <th>Departure Time</th>
                        <th>Arrival Time</th>
                        <th>Fare</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($schedules as $item)
                    <tr>
                        <td>{{ $item->bus->plate_number}}</td>
                        <td>{{ $item->driver->name}}</td>
                        <td>{{ $item->conductor->name}}</td>
                        <td>{{ $item->starting_point->name}} - {{ $item->destination->name}}</td>
                        <td>{{ $item->schedule_date}}</td>
                        <td>{{ $item->time_departure}}</td>
                        <td>{{ $item->time_arrival}}</td>
                        <td>{{ $item->fare}}</td>
                        <td>{{ ucfirst($item->status)}}</td>
                        <td>
                            <form action="{{ route('buses.schedules.destroy', ['id'=> $item->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                @if($item->status == 'done')
                                <button class="btn btn-primary openUpdateModal" type="button" data-id="{{ $item->id }}"
                                    data-bus_id="{{ $item->bus_id }}" data-driver_id="{{ $item->driver_id }}"
                                    data-conductor_id="{{ $item->conductor_id }}"
                                    data-starting_point_id="{{ $item->starting_point_id }}"
                                    data-destination_id="{{ $item->destination_id }}" data-fare="{{ $item->fare }}"
                                    data-schedule_date="{{ $item->schedule_date }}"
                                    data-time_departure="{{ $item->time_departure }}"
                                    data-time_arrival="{{ $item->time_arrival }}"
                                    data-bus_status="{{ $item->time_arrival }}">Update</button>
                                @endif    
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
                $(this).data('driver_id'),
                $(this).data('conductor_id'),
                $(this).data('starting_point_id'),
                $(this).data('destination_id'),
                $(this).data('fare'),
                $(this).data('schedule_date'),
                $(this).data('time_departure'),
                $(this).data('time_arrival'),
                $(this).data('bus_status'),
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
            driver_id = null,
            conductor_id = null,
            starting_point_id = null,
            destination_id = null,
            fare = null,
            schedule_date = null,
            time_departure = null,
            time_arrival = null,
            bus_status ,
        ){
            $('#id').val(id);
           
            if(modalFooter == 'Update'){
                $('#bus_id').val(bus_id);
                $('#driver_id').val(driver_id);
                $('#conductor_id').val(conductor_id);
                $('#starting_point_id').val(starting_point_id);
                $('#destination_id').val(destination_id);
                $('#bus_id').attr('disabled', 'disabled');
                $('#driver_id').attr('disabled', 'disabled');
                $('#conductor_id').attr('disabled', 'disabled');
                $('#id')

            }else if(modalFooter =='Save'){
                $('#bus_id').removeAttr("disabled");
                $('#driver_id').removeAttr("disabled");
                $('#conductor_id').removeAttr("disabled");
            }
          
            $('#fare').val(fare);
            $('#schedule_date').val(schedule_date);
            $('#time_departure').val(time_departure);
            $('#time_arrival').val(time_arrival);

            $('#crudModalForm').attr('action',formRoute);
            $('#modalTitle').text(modalTitle);
            $('#footerButton').text(modalFooter);
            $('#footerButton').removeClass(removeClass).addClass(addClass);
            $('#crud-modal').modal('show');
        }

        var dtToday = new Date();
    
        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();
        if(month < 10)
            month = '0' + month.toString();
        if(day < 10)
            day = '0' + day.toString();
        
        var maxDate = year + '-' + month + '-' + day;

        // or instead:
        // var maxDate = dtToday.toISOString().substr(0, 10);

       var maxDate = year + '-' + month + '-' + day;
       
        $('#schedule_date').attr('min', maxDate);
      
        });
</script>
@endsection
@stop
