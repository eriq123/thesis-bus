@extends('layouts.admin')
@section('contents')

@section('content_header')
<div class="text-center">
    <button class="btn btn-outline-success" type="button" id="openAddModal">
        <i class="fa fa-bus mr-1"></i>
        Add a bus
    </button>
    <x-modal>
        <x-slot name="modalBody">
            <input type="hidden" name="id" id="id">
            <div class="input-group mb-3">
                <input type="text" name="plate_number" id="plate_number" class="form-control"
                    value="{{ old('plate_number') }}" placeholder="Plate Number" autofocus>
                @if($errors->has('plate_number'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('plate_number') }}</strong>
                </div>
                @endif
            </div>
            <div class="input-group mb-3">
                <input type="text" name="type" id="type" class="form-control" value="{{ old('type') }}"
                    placeholder="Bus Type">
                @if($errors->has('type'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('type') }}</strong>
                </div>
                @endif
            </div>
            <div class="input-group mb-3">
                <input type="number" name="capacity" id="capacity" class="form-control" value="{{ old('capacity') }}"
                    placeholder="Bus Capacity">
                @if($errors->has('capacity'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('capacity') }}</strong>
                </div>
                @endif
            </div>
        </x-slot>
        <x-slot name="modalFooter">
            <button class="btn btn-success" id="footerButton">Save</button>
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
                    @forelse ($buses as $item)
                    <tr>
                        <td>{{ $item->id}}</td>
                        <td>{{ $item->plate_number}}</td>
                        <td>{{ $item->type}}</td>
                        <td>{{ $item->capacity}}</td>
                        <td>
                            <form action="{{ route('buses.destroy', ['id'=> $item->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-primary" type="button" id="openUpdateModal"
                                    data-id="{{ $item->id }}" data-plate_number="{{ $item->plate_number }}"
                                    data-type="{{ $item->type }}" data-capacity="{{ $item->capacity }}">Update</button>
                                <button class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            No data available.
                        </td>
                    </tr>
                    @endforelse
                </x-slot>
            </x-table>
        </div>
    </div>
</div>
@stop
@section('js')
<script>
    $(document).ready(function() {
        $('#openUpdateModal').click(function(){
            fillUpAndOpenModal(
                "{{ route('buses.update') }}",
                'Update Bus',
                'Update',
                'btn-success',
                'btn-primary',
                $(this).data('id'),
                $(this).data('plate_number'),
                $(this).data('type'),
                $(this).data('capacity')
            );
        });

        $('#openAddModal').click(function(){
            fillUpAndOpenModal(
                "{{ route('buses.store') }}",
                'Add a bus',
                'Save',
                'btn-primary',
                'btn-success',
            );
        });

        function fillUpAndOpenModal(
            formRoute,
            modalTitle,
            modalFooter,
            removeClass,
            addClass,
            id = null,
            plate_number = null,
            type = null,
            capacity = null
        ){
            $('#id').val(id);
            $('#plate_number').val(plate_number);
            $('#type').val(type);
            $('#capacity').val(capacity);

            $('#crudModalForm').attr('action',formRoute);
            $('#modalTitle').text(modalTitle);
            $('#footerButton').text(modalFooter);
            $('#footerButton').removeClass(removeClass).addClass(addClass);
            $('#crud-modal').modal('show');
        }
    });
</script>
@endsection
@stop
