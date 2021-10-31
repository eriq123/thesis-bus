@extends('layouts.admin')
@section('contents')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('buses.bookings.submit.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="id" value="{{$booking->id ?? -1}}">
                        <input type="hidden" name="schedule_id" id="schedule_id"
                            value="{{$booking->schedule_id ?? ''}}">

                        <h1 class="text-center">Book a seat</h1>

                        @if(Auth::user()->role_id == 1)
                        <div class="mt-3">
                            <div class="card outlined">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-3">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-secondary active">
                                                <input type="radio" name="user_status" value="existing"
                                                    id="radioExisting">
                                                Existing
                                            </label>
                                            <label class="btn btn-secondary">
                                                <input type="radio" name="user_status" value="unregistered"
                                                    id="radioUnregistered">
                                                Unregistered
                                            </label>
                                        </div>
                                        <a class="btn btn-outline-success" role="button"
                                            href="{{route('users.index')}}">
                                            Add new passenger
                                        </a>
                                    </div>
                                    <div class="wrapper-existing">
                                        <input type="text" id="search_user" class="form-control mb-3"
                                            placeholder="Search a passenger">
                                        <select class="form-control form-select" name="user_id" id="user_id">
                                            @foreach ($passengers as $item)
                                            <option value="{{ $item->id }}"
                                                {{ ($booking->user_id ?? 0) == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="wrapper-unregistered d-none">
                                        <input type="text" name="name" class="form-control" placeholder="Name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif(Auth::user()->role_id == 4)
                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                        @endif

                        <div class="card outlined">
                            <div class="card-body">
                                <div class="">
                                    <p class="mb-0 ml-1 text-left">From :</p>
                                    <select class="form-select form-control" id="starting_point_id"
                                        name="starting_point_id" required>
                                        @forelse ($bus_routes as $item)
                                        <option value="{{ $item->id }}"
                                            {{ ($booking->schedule->starting_point_id ?? 0) == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                        @empty
                                        <option selected>No bus routes available</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <p class="mb-0 ml-1 text-left">To :</p>
                                    <select class="form-select form-control" id="destination_id" name="destination_id"
                                        required>
                                        @forelse ($bus_routes as $item)
                                        <option value="{{ $item->id }}"
                                            {{ ($booking->schedule->destination_id ?? 0) == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                        @empty
                                        <option selected>No bus routes available</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="my-3">
                                    <p class="mb-0 ml-1 text-left">
                                        Date :
                                        <span class="text-danger">*</span>
                                    </p>
                                    <input type="date" name="schedule_date" id="schedule_date" class="form-control"
                                        value="{{ $booking->schedule->schedule_date ?? Carbon\Carbon::now()->format('Y-m-d') }}"
                                        placeholder="Date" required >
                                    @if($errors->has('schedule_date'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('schedule_date') }}</strong>
                                    </div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <p class="mb-0 ml-1 text-left">
                                        Enter quantity :
                                        <span class="text-danger">*</span>
                                    </p>
                                    <input type="text" name="quantity" id="quantity" class="form-control"
                                        value="{{ $booking->quantity ?? 1 }}" placeholder="Enter quantity" required>
                                    @if($errors->has('quantity'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('quantity') }}</strong>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div id="step-two-wrapper">
                            @forelse ($schedules as $item)
                            <div class="card {{ $booking->schedule_id == $item->id ? 'active' : '' }}"
                                data-schedule_id="{{ $item->id }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h4>
                                            {{ $item->time_departure }} - {{ $item->time_arrival }}
                                        </h4>
                                        <h4 class="card-text">PHP {{ number_format($item->fare,2) }}</h4>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p class="mb-0">{{ $item->bus->plate_number }}</p>
                                        <p class="mb-0">{{ $item->bus->capacity }} seats</p>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="alert alert-info">
                                No schedules available.
                            </div>
                            @endforelse
                        </div>
                        <div class="text-right">
                            <button class="btn btn-primary">
                                Save
                            </button>
                            <a class="btn btn-secondary" href="/" role="button">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('css')
<style>
    .card.outlined {
        box-shadow: none;
        border: 1px solid #ccc;
    }

    #step-two-wrapper {
        max-height: 500px;
        border: 1px solid #ccc;
        overflow-y: auto;
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 5px;
    }

    #step-two-wrapper::-webkit-scrollbar {
        width: 10px;
        background-color: #fefefe;
        border-radius: 5px;
        box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
    }

    #step-two-wrapper::-webkit-scrollbar-thumb {
        background-color: #ddd;
        border-radius: 5px;
    }

    #step-two-wrapper .card.active {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
</style>
@endsection

@section('js')
<script>


    $( document ).ready(function() {
        var BOOKING_SCHEDULE_ID = '{{ $booking->schedule_id ?? 0 }}';
        var debounce;
        $('#search_user').on('input',function (e) {
            clearTimeout(debounce);
            debounce = setTimeout(function () {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('users.searchByName') }}",
                    data: {
                        name: e.target.value,
                    },
                    success: function(data) {
                        $('#user_id').empty();
                        if(data.length > 0) {
                            data.forEach(function(value){
                                $('#user_id').append(`<option value="${value.id}">${value.name}</option>`);
                            });
                        } else {
                            $('#user_id').append(`<option selected>No users available.</option>`);
                        }
                    },
                    errors: function(error) {
                        console.log(error);
                    }
                });
            }, 500);
        });

        $("#step-two-wrapper").on("click", '.card',function(){
            $('#step-two-wrapper .card').removeClass('active');
            $(this).toggleClass('active');
            $('#schedule_id').val($(this).data('schedule_id'));
        });

        function searchScheduleByRouteIDs(){
            var startingPointID = $('#starting_point_id').val();
            var destinationID = $('#destination_id').val();
            var scheduleDate = $('#schedule_date').val();
            $.ajax({
                type: "POST",
                url: "{{ route('buses.bookings.scheduleByBookingDetails') }}",
                data: {
                    starting_point_id: startingPointID,
                    destination_id: destinationID,
                    schedule_date: scheduleDate,
                },
                success: function(data) {
                    $('#step-two-wrapper').empty();
                    if(data.length > 0) {
                        data.forEach(function(item){
                            $('#step-two-wrapper').append(`
                            <div class="card ${BOOKING_SCHEDULE_ID == item.id ? 'active' : ''}" data-schedule_id="${item.id}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h4>
                                            ${item.time_departure} - ${item.time_arrival}
                                        </h4>
                                        <h4 class="card-text">PHP ${item.fare}</h4>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p class="mb-0">${item.bus.plate_number}</p>
                                        <p class="mb-0">${item.seats_available} seats available</p>
                                    </div>
                                </div>
                            </div>
                            `);
                        });
                    } else {
                        $('#step-two-wrapper').append(`<div class="alert alert-info">
                            No schedules available.
                        </div>`);
                    }
                },
                errors: function(error) {
                    console.log(error);
                }
            });

        }

        searchScheduleByRouteIDs()

        $('#starting_point_id').change(function() {
            searchScheduleByRouteIDs()
        });

        $('#destination_id').change(function() {
            searchScheduleByRouteIDs()
        });


        $('#schedule_date').change(function() {
            //alert("YahoooS");
             var scheduleDate = $('#schedule_date').val();
                 var now = new Date();
                      now.setHours(0,0,0,0);
                      if (scheduleDate < now) {
                        console.log("Selected date is in the past");
                      } else {
                        console.log("Selected date is NOT in the past");
                      }
             searchScheduleByRouteIDs()
        });

        $('#radioExisting').click(function(){
            if($('#radioExisting').is(':checked')) {
                $('.wrapper-existing').removeClass('d-none');
                $('.wrapper-unregistered').addClass('d-none');
            }
        });

        $('#radioUnregistered').click(function(){
            if($('#radioUnregistered').is(':checked')) {
                $('.wrapper-unregistered').removeClass('d-none');
                $('.wrapper-existing').addClass('d-none');
            }
        });


        if($('#id').val() == 0) {
            // unregistered
            $('#radioUnregistered').prop('checked', true);
            $('#radioExisting').prop('checked', false);
            $('.wrapper-unregistered').removeClass('d-none');
            $('.wrapper-existing').addClass('d-none');
        } else {
            // existing
            $('#radioExisting').prop('checked', true);
            $('#radioUnregistered').prop('checked', false);
            $('.wrapper-existing').removeClass('d-none');
            $('.wrapper-unregistered').addClass('d-none');
        }
    });


</script>
@endsection

@stop
