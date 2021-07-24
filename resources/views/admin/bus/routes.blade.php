@extends('layouts.admin')
@section('contents')

@section('content_header')
<div class="text-center">
    <button class="btn btn-outline-success" type="button" id="openAddModal">
        <i class="fa fa-bus mr-1"></i>
        Add a bus route
    </button>
    <x-modal>
        <x-slot name="modalBody">
            <input type="hidden" name="id" id="id">
            <div class="input-group mb-3">
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                    placeholder="Bus Route Name" autofocus>
                @if($errors->has('name'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('name') }}</strong>
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
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($bus_routes as $item)
                    <tr>
                        <td>{{ $item->id}}</td>
                        <td>{{ $item->name}}</td>
                        <td>
                            <form action="{{ route('buses.routes.destroy', ['id'=> $item->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-primary" type="button" id="openUpdateModal"
                                    data-id="{{ $item->id }}" data-name="{{ $item->name }}">Update</button>
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
        $('#openUpdateModal[data-id]').on('click',function(){
            fillUpAndOpenModal(
                "{{ route('buses.routes.update') }}",
                'Update a bus route',
                'Update',
                'btn-success',
                'btn-primary',
                $(this).data('id'),
                $(this).data('name'),
            );
        });

        $('#openAddModal').click(function(){
            fillUpAndOpenModal(
                "{{ route('buses.routes.store') }}",
                'Add a bus route',
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
            name = null,
        ){
            $('#id').val(id);
            $('#name').val(name);

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
