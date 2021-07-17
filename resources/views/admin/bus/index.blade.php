@extends('layouts.admin')
@section('contents')

@section('content_header')
<div class="text-center">
    <button class="btn btn-outline-success" type="button" data-toggle="modal" data-target="#crud-modal">
        <i class="fa fa-bus mr-1"></i>
        Add a bus
    </button>
    <x-modal modalTitle="Add a bus" :formRoute="route('buses.store')">
        <x-slot name="modalBody">
            <div class="input-group mb-3">
                <input type="text" name="plate_number" class="form-control" value="{{ old('plate_number') }}"
                    placeholder="Plate Number" autofocus>
                @if($errors->has('plate_number'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('plate_number') }}</strong>
                </div>
                @endif
            </div>
            <div class="input-group mb-3">
                <input type="text" name="type" class="form-control" value="{{ old('type') }}" placeholder="Bus Type">
                @if($errors->has('type'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('type') }}</strong>
                </div>
                @endif
            </div>
            <div class="input-group mb-3">
                <input type="number" name="capacity" class="form-control" value="{{ old('capacity') }}"
                    placeholder="Bus Capacity">
                @if($errors->has('capacity'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('capacity') }}</strong>
                </div>
                @endif
            </div>
        </x-slot>
        <x-slot name="modalFooter">
            <button class="btn btn-success">Save</button>
        </x-slot>
    </x-modal>
</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <x-table>
                <x-slot name="thead">
                    <tr>
                        <th>ID</th>
                        <th>Plate Number</th>
                        <th>Type</th>
                        <th>Capacity</th>
                        <th>Actions</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    <tr>
                        <td colspan="5" class="text-center">
                            No data available.
                        </td>
                    </tr>
                </x-slot>
            </x-table>
        </div>
    </div>
</div>
@stop

@stop
