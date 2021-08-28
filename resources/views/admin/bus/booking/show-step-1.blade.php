@extends('layouts.admin')
@section('contents')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('buses.bookings.submit.step.one') }}" method="POST">
                        @csrf
                        <h1 class="text-center">Book a seat</h1>

                        <div class="mt-3">
                            <div class="card">
                                <div class="card-body">
                                    <p class="mb-0 ml-1 text-left">Choose a passenger :</p>
                                    <input type="text" name="search_user" id="search_user" class="form-control mb-3"
                                        value="{{ old('search_user') }}" placeholder="Search a passenger">
                                    <select class="form-control form-select" name="user_id" id="user_id" required>
                                        @foreach ($passengers as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="">
                                    <p class="mb-0 ml-1 text-left">From :</p>
                                    <select class="form-select form-control" id="starting_point_id"
                                        name="starting_point_id" required>
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
                                    <select class="form-select form-control" id="destination_id" name="destination_id"
                                        required>
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
                                    <p class="mb-0 ml-1 text-left">Date :</p>
                                    <input type="date" name="schedule_date" id="schedule_date" class="form-control"
                                        value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" placeholder="Date" required>
                                    @if($errors->has('schedule_date'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('schedule_date') }}</strong>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <button class="btn btn-primary">
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
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
</script>
@endsection

@stop
