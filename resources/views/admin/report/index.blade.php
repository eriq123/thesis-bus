@extends('layouts.admin')
@section('contents')

@if (Auth::user()->role_id == 1)
@section('content_header')
<!-- <div class="text-center">
    <a href="{{ route('buses.bookings.add') }}" class="btn btn-outline-success" type="button">
        <i class="fas fa-book-open"></i>
        Book a seat
    </a>

</div> -->
<div class="row">
    <div class="col-md-5">
        <label>From:</label>
        <input type="date" class="form-control" id="from" name="from" placeholder="From" value="">
        <small id="fromerror" style="color:red;"> </small>
        
    </div>
    <div class="col-md-5">
        
        <label>To:</label>
        <input type="date" class="form-control" id="to" name="to" placeholder="To" value="">
        <small id="toerror" style="color:red;"></small>
    </div>
    <div class="col-md-2">
        <label>&nbsp;</label>
        <br>
        <input type="button" class="btn btn-primary" value="Search" onclick="getData()">
    </div>
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
                        <th>Date</th>
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
                <x-slot name="tbody" id="tbody1">
                            <?php $count = 0; ?>
                    @forelse ($bookings as $item)
                        <?php $count++;?>
                    <tr id="last-{{$count}}">
                        <td>{{ $item->updated_at }}</td>
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

                        <td><input type="hidden" id="fare-{{ $item->id }}" value="{{ $item->fare_amount }}">{{ $item->fare_amount}} ₱</td>
                        <td><input type="hidden" id="qty-{{ $item->id }}" value="{{ $item->quantity }}">{{ $item->quantity}}</td>

                        <td><input type="hidden" id="total-{{$count}}" value="{{$item->grand_total}}">
                            {{ $item->grand_total}} ₱</td>

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

                               <!--  <a href="{{ route('buses.bookings.edit', ['id' => $item->id]) }}" role="button"
                                    class="btn btn-primary">Update</a> -->
                                <input type="hidden" name="id-{{ $item->id}}" value="{{ $item->id }}">
    
                                 @if ($item->status_id == 1)
                                <a href="{{ route('buses.bookings.edit', ['id' => $item->id]) }}" role="button"
                                    class="btn btn-primary">Update</a>
                                <a href="#" role="button" class="btn btn-secondary" onclick="openModal('{{ $item->id }}')">Pay Now</a>
                                 @endif
                                <button class="btn btn-danger" disabled="">Delete</button>
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

       
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
      
<script type="text/javascript">

  setTimeout(function(){ updateTotalAmount(); }, 500);

        function updateTotalAmount(){

            const count = <?php echo $count;?>;
            var total= 0;
            
            for (var i =1; i<=count;  i++) {
                total = total + parseInt($("#total-"+i).val());
                
            }
            $("tbody").append("<tr style='background-color:#e8eaec;color:black;'> <td>-</td> <td>-</td> <td>-</td> <td>-</td> <td>-</td> <td>-</td> <td style='font-weight: bold;'>Total </td>-<td style='font-weight: bold;'>"+total+" ₱</td> <td></td> </tr>");

        }

         function getData() {

            let from = $("#from").val();
            let to = $("#to").val();
            let _token ='<?php echo csrf_token() ?>'; 
            if(from !="" && to != "")
            {
                 $("#fromerror").text("");
                 $("#toerror").text("");
                 var total = 0;

                $.ajax({
                           type:'POST',
                           url:'/ajax/fetch',
                           data:{
                                from   : from,
                                to     : to,
                                _token : _token
                           },
                           success:function(data) {
                              // $("#msg").html(data);
                              console.log(data.success);
                              if(data.success.length >0){

                                   var dataAppend = "";

                                      for (var i = 0; i < data.success.length; i++) 
                                      {
                                         var btn="";
                                         var actnBtn = "<button class='btn btn-danger' disabled>Delete</button>"
                                  
                                         if(data.success[i].status_id == 2){
                                            btn = "<button class='btn btn-sm btn-primary'>Paid</button>";
                                         }else if(data.success[i].status_id == 3){
                                           
                                            btn = "<button class='btn btn-sm btn-success'>Verified</button>";

                                         }else if(data.success[i].status_id == 6){
                                           
                                            btn = "<button class='btn btn-sm btn-success'>onBoard</button>";

                                         }
                                         total = total + parseInt(data.success[i].grand_total);



                                            dataAppend +="<tr id=last-"+(i+1)+"> <td>"+data.success[i].updated_at+"</td>"+
                                                           "<td>"+btn+"</td>"+
                                                           "<td>"+data.success[i].user_name+"</td>"+              
                                                          "<td>"+data.success[i].name+"</td>"+
                                                          "<td>"+data.success[i].schedule_date+" ( "+data.success[i].time_departure+"-"+data.success[i].time_arrival+" )</td>"+
                                                          "<td>"+data.success[i].fare_amount+"</td>"+
                                                          "<td>"+data.success[i].quantity+"</td>"+
                                                          "<td>"+data.success[i].grand_total+"</td>"+
                                                          "<td>"+actnBtn+"</td>";
                                      }
                                      $("tbody").empty();
                                      $("tbody").append(dataAppend);
                                      $("tbody").append("<tr style='background-color:#e8eaec;color:black;'> <td>-</td> <td>-</td> <td>-</td> <td>-</td> <td>-</td> <td>-</td> <td style='font-weight: bold;'>Total </td>-<td style='font-weight: bold;'>"+total+" ₱</td> <td></td> </tr>");


                                  

                              }else {
                                 $("tbody").empty();
                                $("tbody").append("<tr><td colspan='100%'' class='text-center'>No data available.</td></tr>");
                              }


                        }


                           
            });

            }else if(from == "" && to !=""){
                $("#fromerror").text("*Please select date");
                $("#toerror").text("");
            }else if(from !="" && to ==""){
                 $("#toerror").text("*Please select date");
                $("#fromerror").text("");
            }else if(from == "" && to==""){
                $("#fromerror").text("*Please select date");
                $("#toerror").text("*Please select date");
            }
    
          
         }
      </script>
        <!-- /Modal -->
    </div>
</div>
@section('scripts')
@parent
@stop
@stop
