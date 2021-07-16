@extends('layouts.main')
@section('contents')



<div id="wrapper">
    @extends('layouts.sidebar')

    <div id="page-wrapper">
        <div class="header">
            <h1 class="page-header">
                Employees Information
            </h1>
            @include('layouts.errormsg')
            <ol class="breadcrumb">
                <li><a href="dashboard">Home</a></li>
                <li><a href="/users">Employees Information</a></li>

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
                                                <th>Name</th>
                                                <th>Email Address</th>
                                                <th>Role</th>

                                                <th>Actions</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($users as $item)
                                            <tr class="odd gradeX">
                                                <td> {{ $item->name }} </td>
                                                <td> {{ $item->email }}</td>
                                                <td class="center"> {{ $item->role_name }}</td>

                                                <td>
                                                    <form action="{{route('users.destroy', $item->id)}}" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <!-- <a href="#" class="btn btn-outline-info py-0">
                                                <i class="fas fa-eye"></i>
                                                </a> -->
                                                        <a href="{{route('users.edit',$item->id)}}"
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
                                                            <h4 class="modal-title" id="myModalLabel">Add New Employee
                                                            </h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post" action="/users"
                                                                enctype="multipart/form-data">
                                                                @csrf

                                                        </div>
                                                        <div class="row clearfix">
                                                            <div
                                                                class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                                <label>Name</label>
                                                            </div>
                                                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                                <div class="form-group">
                                                                    <div class="form-line">
                                                                        <input type="text" id="name" name="name"
                                                                            class="form-control" required autofocus>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row clearfix">
                                                            <div
                                                                class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                                <label>Email Address</label>
                                                            </div>
                                                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                                <div class="form-group">
                                                                    <div class="form-line">
                                                                        <input type="email" id="email" name="email"
                                                                            class="form-control" required autofocus>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row clearfix">
                                                            <div
                                                                class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                                <label>Password</label>
                                                            </div>
                                                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                                <div class="form-group">
                                                                    <div class="form-line">
                                                                        <input type="password" id="password"
                                                                            name="password" class="form-control"
                                                                            required autofocus>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row clearfix">
                                                            <div
                                                                class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                                <label>Confirm Password</label>
                                                            </div>
                                                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                                <div class="form-group">
                                                                    <div class="form-line">
                                                                        <input type="password"
                                                                            id="password_confirmation"
                                                                            name="password_confirmation"
                                                                            class="form-control" required autofocus>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row clearfix">
                                                            <div
                                                                class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                                <label>Position</label>
                                                            </div>
                                                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="role_name" id="role_name"
                                                                        value="Bus Driver" checked>

                                                                    <label class="form-check-label"
                                                                        for="exampleRadios1">
                                                                        Bus Driver
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="role_name" id="role_name"
                                                                        value="Conductor">

                                                                    <label class="form-check-label"
                                                                        for="exampleRadios2">
                                                                        Conductor
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="role_name" id="role_name" value="Admin">

                                                                    <label class="form-check-label"
                                                                        for="exampleRadios2">
                                                                        Admin
                                                                    </label>
                                                                </div>

                                                            </div>

                                                        </div>




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
