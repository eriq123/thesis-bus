@extends('layouts.admin')
@section('contents')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('buses.bookings.submit.step.two') }}" method="POST">
                        @csrf
                        <input type="hidden" name="time_departure" id="time_departure">
                        <input type="hidden" name="time_arrival" id="time_arrival">

                        <h1 class="text-center">Book a seat</h1>
                        <div class="card outlined">
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <h3>{{ $from->name }} - {{ $to->name }}</h3>
                                    <p>{{ $date }}</p>
                                </div>
                                <div class="mb-3">
                                    <p class="mb-0 ml-1 text-left">Enter quantity :</p>
                                    <input type="text" name="quantity" id="quantity" class="form-control"
                                        value="{{ old('quantity') }}" placeholder="Enter quantity" required>
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
                            <div class="card" data-time_departure="{{ $item->time_departure }}"
                                data-time_arrival="{{ $item->time_arrival }}">
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
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-primary">
                                Confirm Booking
                            </button>
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
        border: 1px solid #eee;
    }

    #step-two-wrapper {
        max-height: 500px;
        border: 1px solid #eee;
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
    $('#step-two-wrapper .card').click(function(){
        $('#step-two-wrapper .card').removeClass('active');
        $(this).toggleClass('active');
        $('#time_departure').val($(this).data('time_departure'));
        $('#time_arrival').val($(this).data('time_arrival'));
    });
</script>
@endsection
@stop
