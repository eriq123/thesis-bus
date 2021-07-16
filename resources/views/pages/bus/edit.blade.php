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
                <li><a href="#">Edit Bus Information</a></li>

            </ol>
        </div>
        <div id="page-inner">
            <div class="row">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <h4>Edit Bus Information </h4>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="/buses/{{$bus->id}}" enctype="multipart/form-data">
                            @csrf
                            {{method_field('PATCH')}}
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label> Bus Route</label>
                        </div>
                        <input type="hidden" name="id" value="{{$bus->id}}">
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="bus_route" name="bus_route" class="form-control" required
                                        autofocus value="{{$bus->bus_route}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label> Bus Plate Number</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="bus_platenumber" name="bus_platenumber" class="form-control"
                                        required autofocus value="{{$bus->bus_platenumber}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label> Total Seats</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="total_seats" name="total_seats" class="form-control" required
                                        autofocus value="{{$bus->total_seats}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="is_fullybooked" name="is_fullybooked" value=0>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        </form>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>
        @endsection
    </div>
