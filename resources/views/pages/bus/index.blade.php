@extends('layouts.main')
@section('contents')



<div id="wrapper">
    @extends('layouts.sidebar')

    <div id="page-wrapper">
        <div class="header">
            <h1 class="page-header">
                Bus Information
            </h1>
            @include('layouts.errormsg')
            <ol class="breadcrumb">
                <li><a href="dashboard">Home</a></li>
                <li><a href="/buses">Bus Information</a></li>

            </ol>
        </div>

        <div id="page-inner">

            <div class="row">
                <div class="col-md-12">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Bus Information
                            <button class="pull-right btn btn-primary btn-lg" data-toggle="modal"
                                data-target="#addNew"><i class="fas fa-plus">Add New </i></button>

                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover"
                                        id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Bus Route</th>
                                                <th>Bus Platenumber</th>
                                                <th>Total Seats</th>
                                                <th>Status</th>
                                                <th>Actions</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($buses as $bus)
                                            <tr class="odd gradeX">
                                                <td> {{ $bus->bus_route }} </td>
                                                <td> {{ $bus->bus_platenumber }}</td>
                                                <td class="center"> {{ $bus->total_seats }}</td>
                                                <td class="center">{{ $bus->is_fullybooked }}</td>
                                                <td>
                                                    <form action="{{route('buses.destroy', $bus->id)}}" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <!-- <a href="#" class="btn btn-outline-info py-0">
                                                <i class="fas fa-eye"></i>
                                                </a> -->
                                                        <a href="{{route('buses.edit',$bus->id)}}"
                                                            class="btn btn-outline-primary py-0">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                        <button class="btn btn-outline-danger py-0">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
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
                                            <!-- Start of Add New Modal -->
                                            <div class="modal fade" id="addNew" tabindex="-1" role="dialog"
                                                aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title" id="myModalLabel">Add New Bus Route
                                                            </h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post" action="/buses"
                                                                enctype="multipart/form-data">
                                                                @csrf

                                                        </div>
                                                        <div class="row clearfix">
                                                            <div
                                                                class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                                <label>Bus Route</label>
                                                            </div>
                                                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                                <div class="form-group">
                                                                    <div class="form-line">
                                                                        <input type="text" id="bus_route"
                                                                            name="bus_route" class="form-control"
                                                                            required autofocus>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row clearfix">
                                                            <div
                                                                class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                                <label>Bus Plate Number</label>
                                                            </div>
                                                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                                <div class="form-group">
                                                                    <div class="form-line">
                                                                        <input type="text" id="bus_platenumber"
                                                                            name="bus_platenumber" class="form-control"
                                                                            required autofocus>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row clearfix">
                                                            <div
                                                                class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                                <label>Total Seats</label>
                                                            </div>
                                                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                                <div class="form-group">
                                                                    <div class="form-line">
                                                                        <input type="text" id="total_seats"
                                                                            name="total_seats" class="form-control"
                                                                            required autofocus>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="is_fullybooked" name="is_fullybooked"
                                                            value=0>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Save
                                                                changes</button>
                                                            </form>
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Close</button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                </div>
                                <!-- End of Add modal -->



                                </tbody>
                                </table>
                            </div>


                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
        </div>
        @endsection
