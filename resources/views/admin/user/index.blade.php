@extends('layouts.admin')
@section('contents')

@section('content_header')
<div class="text-center">
    <button class="btn btn-outline-success" type="button" id="openAddModal">
        <i class="fa fa-bus mr-1"></i>
        Add a user
    </button>
    <x-modal>
        <x-slot name="modalBody">
            <input type="hidden" name="id" id="id">
            <div class="input-group mb-3">
                <input type="text" name="name" id="name" class="form-control"
                    value="{{ old('name') }}" placeholder="Name" autofocus required>
                @if($errors->has('name'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('name') }}</strong>
                </div>
                @endif
            </div>
            <div class="input-group mb-3">
                <input type="text" name="email" id="email" class="form-control"
                    value="{{ old('email') }}" placeholder="Email" required>
                @if($errors->has('email'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </div>
                @endif
            </div>
            <div class="input-group mb-3">
                <input type="password" name="password" id="password" class="form-control" value="{{ old('password') }}"
                    placeholder="Password" required>
                @if($errors->has('password'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </div>
                @endif
            </div>
            <div class="input-group mb-3">
                <input type="number" name="role_id" id="role_id" class="form-control" value="{{ old('role_id') }}"
                    placeholder="Role ID" required>
                @if($errors->has('role_id'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('role_id') }}</strong>
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
                    <th>Name</th>
                        <th>Email</th>
                        <th>Role ID</th>
                        <th>Actions</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($users as $item)
                    <tr>
                        <td>{{ $item->name}}</td>
                        <td>{{ $item->email}}</td>
                        <td>{{ $item->role_id}}</td>
                        <td>
                            <form action="{{ route('users.destroy', ['id'=> $item->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-primary" type="button" id="openUpdateModal"
                                    data-id="{{ $item->id }}" 
                                    data-name="{{ $item->name }}"
                                    data-email="{{ $item->email }}" 
                                    data-password="{{ $item->password }}" 
                                    data-role_id="{{ $item->role_id }}">Update</button>
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
                "{{ route('users.update') }}",
                'Update a User',
                'Update',
                'btn-success',
                'btn-primary',
                $(this).data('id'),
                $(this).data('name'),
                $(this).data('email'),
                $(this).data('password'),
                $(this).data('role_id'),
            );
        });

        $('#openAddModal').click(function(){
            fillUpAndOpenModal(
                "{{ route('users.store') }}",
                'Add a User',
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
            email = null,
            password = null,
            role_id = null,
        ){
            $('#id').val(id);
            $('#name').val(name);
            $('#email').val(email);
            $('#password').val(password);
            $('#role_id').val(role_id);

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
