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
                        @unless(Auth::user()->role_id == 3 || Auth::user()->role_id == 2)
                        <th>Fare</th>
                        <th>Quantity</th>
                        <th>Grand Total</th>
                        @else
                        <th>Seats available</th>
                        @endunless
                        <th>Actions</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                   
                    @forelse ($bookings as $item)
                 
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td><input type="hidden" id="status-{{ $item->id }}" value="{{  $item->status->id }}">
                            <input type="hidden" id="userId-{{ $item->id }}" value=" {{ $item->user_id  }}">
                           
                            <button class="btn btn-sm btn-{{ $item->status->class }}" >
                                {{ $item->status->title }}
                            </button>
                        </td>
                        <td><input type="hidden" id="username-{{ $item->id }}" value="{{ $item->user_name }}">{{ $item->user_name }}</td>
                        <td><input type="hidden" id="route-{{ $item->id }}" value="{{ $item->schedule->starting_point->name}}- {{ $item->schedule->destination->name }}">{{ $item->schedule->starting_point->name }} - {{ $item->schedule->destination->name }}</td>
                        <td>{{ $item->schedule->schedule_date }} ({{ $item->schedule->time_departure }} -
                            {{ $item->schedule->time_arrival }})</td>

                        @unless(Auth::user()->role_id == 2 || Auth::user()->role_id == 3)

                        <td><input type="hidden" id="fare-{{ $item->id }}" value="{{ $item->fare_amount }}">{{ $item->fare_amount}}</td>
                        <td><input type="hidden" id="qty-{{ $item->id }}" value="{{ $item->quantity }}">{{ $item->quantity}}</td>

                        <td><input type="hidden" id="grand_total-{{ $item->id }}" value="{{ $item->grand_total }}">{{ $item->grand_total}}</td>
                        @else
                        <td>{{ $item->schedule->seats_available }}</td>
                        @endunless

                        <td>
                            @if (Auth::user()->role_id == 2 || Auth::user()->role_id == 3)
                            <div class="d-flex">
                                @if ($item->status_id == 1 || $item->status_id == 2)
                                <form action="{{ route('buses.bookings.update.status') }}" class="mr-1" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <input type="hidden" name="status_id" value="3">
                                    <button class="btn btn-success">Verify</button>
                                </form>
                                @elseif ($item->status_id == 3)
                                <form action="{{ route('buses.bookings.update.status') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">

                                    <input type="text" name="status_id" value="{{ $item->status->following_id }}">
                                    <button class="btn btn-secondary">Leave</button>
                                </form>
                                @endif
                            </div>
                            @else
                            <form action="{{ route('buses.bookings.destroy', ['id'=> $item->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <!-- <a href="{{ route('buses.bookings.edit', ['id' => $item->id]) }}" role="button"
                                    class="btn btn-primary">Update</a> -->
                                <input type="hidden" name="id-{{ $item->id}}" value="{{ $item->id }}">
    
                                 @if ($item->status_id == 1)
                                <a href="{{ route('buses.bookings.edit', ['id' => $item->id]) }}" role="button"
                                    class="btn btn-primary">Update</a>
                                <a href="#" role="button" class="btn btn-secondary" onclick="openModal('{{ $item->id }}')">Pay Now</a>
                                 @endif
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

        <!-- Modal -->
        <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModal">{{ __('Bus Payment') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{route('buses.bookings.paying.booking')}}" id="paymentForm">
                            @csrf

                            <div class="form-group row">
                                <label for="nameInput" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="nameInput" type="text" class="form-control" name="name" value="{{ old('name') }}"  autocomplete="name" autofocus readonly="">
                                    <input type="hidden" id="itemId" name="itemId" value="">
                                    <input type="hidden" id="userId" name="userId" value="">
                                    <span class="invalid-feedback" role="alert" id="nameError">
                                        <strong></strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="fareInput" class="col-md-4 col-form-label text-md-right">{{ __('Fare') }}</label>

                                <div class="col-md-6">
                                    <input id="fareInput" type="text" class="form-control" name="fare" value="{{ old('email') }}" required autocomplete="fare" readonly="">

                                    <span class="invalid-feedback" role="alert" id="emailError">
                                        <strong></strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="routeInput" class="col-md-4 col-form-label text-md-right">{{ __('Route') }}</label>

                                <div class="col-md-6">
                                    <input id="routeInput" type="text" class="form-control" name="routeInput" required autocomplete="route" readonly="">

                                    <span class="invalid-feedback" role="alert" id="passwordError">
                                        <strong></strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="qtyInput" class="col-md-4 col-form-label text-md-right">{{ __('Quantity ') }}</label>

                                <div class="col-md-6">
                                    <input id="qtyInput" type="text" class="form-control" name="qtyInput" autocomplete="quantity" readonly="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="GrandTotalInput" class="col-md-4 col-form-label text-md-right">{{ __('Grand Total') }}</label>

                                <div class="col-md-6">
                                    <input id="GrandTotalInput" type="text" class="form-control" name="grand_total" value="{{ old('email') }}" required autocomplete="fare" readonly="">

                                    <span class="invalid-feedback" role="alert" id="emailError">
                                        <strong></strong>
                                    </span>
                                </div>
                            </div>
                             <div class="form-group row">
                                <label for="statusInput" class="col-md-4 col-form-label text-md-right">{{ __('Status ') }}</label>

                                <div class="col-md-6">
                                    <input id="statusInput" type="text" class="form-control" name="statusInput" autocomplete="status" readonly="">
                                    <input id="statusInputId" type="hidden" class="form-control" name="statusInputId" autocomplete="status" readonly="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="gcashInput" class="col-md-4 col-form-label text-md-right">{{ __('Gcash Account') }}</label>

                                <div class="col-md-6">
                                    <input id="gcashInput" type="text" class="form-control" name="gcashInput" autocomplete="gcash" required="">
                                </div>
                            </div>


                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Proceed') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<script type="text/javascript">

    function openModal(itemId)
    {
   
        $("#itemId").val(itemId);
        var name  = $("#username-"+itemId).val();
        var fare  = $("#fare-"+itemId).val();
        var route = $("#route-"+itemId).val();
        var qty   = $("#qty-"+itemId).val();
        var status= $("#status-"+itemId).val();
        var userId= $("#userId-"+itemId).val();
        var GrandTotal= $("#grand_total-"+itemId).val();
       
        $("#nameInput").val(name);
        $("#fareInput").val(fare);
        $("#routeInput").val(route);
        $("#qtyInput").val(qty);
        $("#statusInputId").val(status);
        $("#userId").val(userId);
        $("#GrandTotalInput").val(GrandTotal);
        
        if(status == 1){
            
            $("#statusInput").val("Open");
        }
        $('#paymentModal').modal('show');

    }

</script>
        <!-- /Modal -->
    </div>
</div>
@section('scripts')
@parent
@stop
@stop
