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
            <div class="alert alert-dark" role="alert">
                The default password is <code>123456</code>
            </div>
            <div class="mb-3">
                <p class="mb-0 ml-1 text-left">Role :</p>
                <select class="form-select form-control" id="role_id" name="role_id" required>
                    @forelse ($roles as $item)
                    <option value="{{ $item->id }}" data-name="{{ $item->name }}">
                        {{ $item->name }}
                    </option>
                    @empty
                    <option selected>No roles available</option>
                    @endforelse
                </select>
            </div>
            <div class="input-group mb-3">
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                    placeholder="Name" autofocus required>
                @if($errors->has('name'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('name') }}</strong>
                </div>
                @endif
            </div>
            <div class="input-group mb-3">
                <input type="text" name="email" id="email" class="form-control" value="{{ old('email') }}"
                    placeholder="Email" required>
                @if($errors->has('email'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </div>
                @endif
            </div>
        </x-slot>
        <x-slot name="modalFooter">
            <button class="btn btn-default mr-auto" type="button" id="changePasswordButton">Change Password</button>
            <button class="btn btn-success" id="footerButton">Save</button>
        </x-slot>
    </x-modal>

    <x-modal id="change-password-modal" formID="changePasswordForm">
        <x-slot name="modalBody">
            <input type="hidden" name="id" id="changePasswordID">
            <div class="alert alert-dark" role="alert">
                The default password is <code>123456</code>
            </div>
            <div class="input-group mb-3">
                <input type="password" name="old_password" id="old_password" class="form-control"
                    value="{{ old('old_password') }}" placeholder="Old Password" required>
                @if($errors->has('old_password'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('old_password') }}</strong>
                </div>
                @endif
            </div>
            <div class="input-group mb-3">
                <input type="password" name="new_password" id="new_password" class="form-control"
                    value="{{ old('new_password') }}" placeholder="New Password" required>
                @if($errors->has('new_password'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('new_password') }}</strong>
                </div>
                @endif
            </div>
            <div class="input-group mb-3">
                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                    class="form-control" value="{{ old('new_password_confirmation') }}" placeholder="Confirm Password"
                    required>
                @if($errors->has('new_password_confirmation'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('new_password_confirmation') }}</strong>
                </div>
                @endif
            </div>
        </x-slot>
        <x-slot name="modalFooter">
            <button class="btn btn-primary">Update</button>
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
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($users as $item)
                    <tr>
                        <td>{{ $item->name}}</td>
                        <td>{{ $item->email}}</td>
                        <td>{{ $item->role->name}}</td>
                        <td>
                            <form action="{{ route('users.destroy', ['id'=> $item->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-primary openUpdateModal" type="button" data-id="{{ $item->id }}"
                                    data-name="{{ $item->name }}" data-email="{{ $item->email }}"
                                    data-role_id="{{ $item->role_id }}">Update</button>
                                <button class="btn btn-danger">Delete</button>
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
                </x-slot>
            </x-table>
        </div>
    </div>
</div>
@stop
@section('js')
<script>
    $(document).ready(function() {
        $('.openUpdateModal[data-id]').on('click',function(){
            fillUpAndOpenModal(
                "{{ route('users.update') }}",
                'Update a User',
                'Update',
                'btn-success',
                'btn-primary',
                $(this).data('id'),
                $(this).data('name'),
                $(this).data('email'),
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

        $('#changePasswordButton').click(function(){
            $('#crud-modal').modal('hide');
            $('.modal-title').text('Change Password');
            var id = $(this).data('id');
            $('#changePasswordID').val(id);
            $('#changePasswordForm').attr('action', "{{ route('users.changePassword') }}");
            $('#change-password-modal').modal('show');
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
            role_id = null,
        ){
            $('#id').val(id);
            $('#name').val(name);
            $('#email').val(email);

            $('#changePasswordButton').hide();
            if(role_id){
                $('#role_id').val(role_id);
                $('#changePasswordButton').data('id', id);
                $('#changePasswordButton').show();
            }
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
