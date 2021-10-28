@extends('layouts.admin')
@section('contents')

@section('content_header')

<style type="text/css">
        #map {
            width: 100%;
            height: 500px;
            border: 2px solid Black;
        }
</style>
<div class="text-center">
    <a href="#" class="btn btn-outline-success" type="button">
        <!-- <i class="fas fa-book-open"></i> -->
         Live Bus Location
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
                        <th>Booking ID</th>
                        <th>Status</th>
                        <th>Bus#</th>
                        <th>Passenger Name</th>
                        <th>Route</th>
                        <th>Schedule</th>
                        <th>Time</th>
                        <th>Quantity</th>
                       
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                   
                   
                    <tr>
                        <td>{{ $booking->id }}</td>
                        <td>@foreach ($status as $statusItem )
                                @if ( $booking->status_id == $statusItem->id)
                                <button class="btn btn-sm btn-{{ $statusItem->class }}" >
                                {{ $statusItem->title }}     
                                </button>
                                @endif
                       
                            @endforeach


                           
                        </td>
                        <td> @foreach ($bus as $item )
                                    @if ( $booking->bus_id == $item->id)
                                   {{     $item->plate_number}}                          
                                    <input type="hidden" id="lat" name="lat" value="{{$item->lat}}">       
                                    <input type="hidden" id="long" name="long" value="{{$item->long}}">
                                   <input type="hidden" name="busId" id="busId" value="{{$item->id}}">
                                    @endif
                                       
                                            
                            @endforeach
                        </td>
                        <td>{{ $booking->user_name }}</td>
                        <td>{{ $booking->schedule->starting_point->name }} - {{ $booking->schedule->destination->name }}</td>
                        <td>{{ $booking->schedule->schedule_date }} </td>
                        <td>{{ $booking->schedule->time_departure }} - {{ $booking->schedule->time_arrival }}</td>
                        <td>{{ $booking->quantity}}</td>

                    
                    </tr>
                   
                </x-slot>
            </x-table>
        </div>
        <div class="col-12">
            <div id="map"></div>       
        </div>

<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript">

function initMap() 
{
    
      var long = parseFloat( $("#long").val());
      var lat = parseFloat($("#lat").val());
 console.log(long);
 console.log(lat);

     
    var myLatLng = {lat: lat, lng: long};
    
    var map = new google.maps.Map(document.getElementById('map'), {
      center: myLatLng,
      zoom: 18
    });
  
    var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          title: 'Google Maps',
          draggable: true
        });
  
     google.maps.event.addListener(marker, 'dragend', function(marker) {
        var latLng = marker.latLng;

        document.getElementById('lat-span').innerHTML = latLng.lat();
        document.getElementById('lon-span').innerHTML = latLng.lng();
     });
}


function hitLocation()
{
    var busId = $("#busId").val();
    let _token ='<?php echo csrf_token() ?>'; 
     $.ajax({
               type:'POST',
               url:'/ajax/location',
               data:{
                    bus_id   : busId,
                    _token : _token
               },
               success:function(data) 
               {

                   var long = parseFloat(data.success['long']);
                   var lat = parseFloat(data.success['lat']);
                 
                   var myLatLng = {lat: lat, lng: long};
                   var map = new google.maps.Map(document.getElementById('map'), {
                      center: myLatLng,
                      zoom: 18
                    });
                 
              
                    var marker = new google.maps.Marker({
                      position: myLatLng,
                      map: map,
                      title: 'Bus Location on Map',
                      draggable: true
                    });
                    google.maps.event.addListener(marker, 'dragend', function(marker) {
                              
                        var latLng = marker.latLng;
                        document.getElementById('lat-span').innerHTML = latLng.lat();
                        document.getElementById('lon-span').innerHTML = latLng.lng();
                    });
                 
                }

            });
}

// hitLocation();
 setInterval(hitLocation, 15000);
</script>
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initMap" async defer></script>
<!--   <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbLjZE7C1lRqge20MpcrXQBIo6LI2S_tw&callback=initMap&v=weekly"
      async defer
    ></script> -->
    </div>
    
     

</div>
@section('scripts')
@parent
@stop
@stop
