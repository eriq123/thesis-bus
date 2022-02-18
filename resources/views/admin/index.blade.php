@extends('adminlte::page')

@section('content_header')
<h1>Welcome {{ Auth::user()->role->name }}, {{ Auth::user()->name }}!</h1>
@stop

@section('content')

@if (Auth::user()->role_id == 1)
<div class="container-fluid">
	
		<div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 id="totalBooking">Fetching</h3>

                <p>Total Bookings</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3 id="unPaid">Fetching</h3>

                <p>unPaid Bookings</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 id="busOnTravel">Fetching</h3>

                <p>Buses on Travel</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 id="totalPassenger">Fetching</h3>

                <p>Total Passengers</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">User Categories</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 765px;" width="765" height="250" class="chartjs-render-monitor"></canvas>
              </div>
              <!-- /.card-body -->
            </div>
            
          </div>
          <div class="col-md-6">
              <div class="card card-secondary">
              <div class="card-header">
                <h3 class="card-title">Monthly Income</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body" style="display: block;">
                <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                  <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 765px;" width="765" height="250" class="chartjs-render-monitor"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
          </div>
        
          
        </div>

	
</div>
<script src="https://adminlte.io/themes/v3/plugins/jquery/jquery.min.js"></script>
<script src="https://adminlte.io/themes/v3/plugins/chart.js/Chart.min.js"></script>
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

<script>
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.
    // var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

    var areaChartData = {
      labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
        {
          label               : 'Digital Goods',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [28, 48, 40, 19, 86, 27, 90]
        },
        {
          label               : 'Electronics',
          backgroundColor     : 'rgba(210, 214, 222, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [65, 59, 80, 81, 56, 55, 40]
        },
      ]
    }

    var areaChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines : {
            display : false,
          }
        }],
        yAxes: [{
          gridLines : {
            display : false,
          }
        }]
      }
    }

    // This will get the first returned node in the jQuery collection.
    // new Chart(areaChartCanvas, {
    //   type: 'line',
    //   data: areaChartData,
    //   options: areaChartOptions
    // })

    //-------------
    //- LINE CHART -
    //--------------
    // var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
    // var lineChartOptions = $.extend(true, {}, areaChartOptions)
    // var lineChartData = $.extend(true, {}, areaChartData)
    // lineChartData.datasets[0].fill = false;
    // lineChartData.datasets[1].fill = false;
    // lineChartOptions.datasetFill = false

    // var lineChart = new Chart(lineChartCanvas, {
    //   type: 'line',
    //   data: lineChartData,
    //   options: lineChartOptions
    // })

    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    


    //-------------
    //- BAR CHART -
    //-------------



  })
  
  function totalBooking(){

    $.ajax({
             type:'GET',
             url:'/ajax/totalBooking',
             success:function(data) 
             {
                 console.log(data.success);    
                  
                  $("#totalBooking").text(data.success['totalBooking']);
                  $("#unPaid").text(data.success['unPaidBooking']);
                  $("#totalPassenger").text(data.success['totalPassengers']);
                  $("#busOnTravel").text(data.success['busOnTravel']);

                  const person = [parseInt(data.success['totalAdmin']),parseInt(data.success['totalConductor']),parseInt(data.success['totalDriver']),parseInt(data.success['totalPassengers'])];
                    var donutChartCanvas = $('#donutChart').get(0)
                              var donutData        = {
                                labels: [
                                    'Admin',
                                    'Conductor',
                                    'Driver',
                                    'Passenger',
                                ],
                                datasets: [
                                  {
                                    data: person,
                                    backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
                                  }
                                ]
                              }
                              var donutOptions     = {
                                maintainAspectRatio : false,
                                responsive : true,
                              }
                              //Create pie or douhnut chart
                              // You can switch between pie and douhnut using the method below.
                              new Chart(donutChartCanvas, {
                                type: 'doughnut',
                                data: donutData,
                                options: donutOptions
                              })
                               }
            });

  setTimeout(function(){ totalBooking(); }, 10000);
  
monthlyProfit();
  }

function monthlyProfit() {
     $.ajax({
             type:'GET',
             url:'/ajax/monthlyProfit',
             success:function(data) 
             {
                 console.log(data.success);    
                  
                  // $("#totalBooking").text(data.success['totalBooking']);
                  // $("#unPaid").text(data.success['unPaidBooking']);
                  // $("#totalPassenger").text(data.success['totalPassengers']);
                  // $("#busOnTravel").text(data.success['busOnTravel']);

                   const months = [parseInt(data.success['january']),parseInt(data.success['february']),parseInt(data.success['march']),parseInt(data.success['april']),parseInt(data.success['may']),parseInt(data.success['june']),parseInt(data.success['july']),parseInt(data.success['august']),parseInt(data.success['september']),parseInt(data.success['october']),parseInt(data.success['november']),parseInt(data.success['december'])];


                       var areaChartDataNew = {
      labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July','August','September','October','November','December'],
      datasets: [
        {
          label               : 'Monthly Income PHP:',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : months
        }
      ]
    }
    var barChartCanvas = $('#barChart')
    var barChartData = $.extend(true, {}, areaChartDataNew)
    var temp0 = areaChartDataNew.datasets[0]
    // var temp1 = areaChartData.datasets[1]
    // barChartData.datasets[0] = temp1
    barChartData.datasets[0] = temp0

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions
    })
              } 
            });
}
monthlyProfit();
totalBooking();

              
</script>
@endif
@stop
