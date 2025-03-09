@php use App\Enums\BaseEnum; $user = auth()->user(); @endphp
@extends('layouts.layoutMaster')

@section('title', __('permission_title'))

@section('vendor-style')
    @vite([
      'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
      'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
      'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
      'resources/assets/vendor/libs/@form-validation/form-validation.scss',
      'resources/assets/vendor/libs/animate-css/animate.scss',
      'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
       ])
@endsection

@section('vendor-script')
    @vite([
      'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
      'resources/assets/vendor/libs/select2/select2.js',
      'resources/assets/vendor/libs/@form-validation/popular.js',
      'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
      'resources/assets/vendor/libs/@form-validation/auto-focus.js',
      'resources/assets/vendor/libs/cleavejs/cleave.js',
      'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
      'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
         ])
@endsection

@section('page-script')
    @vite([
      'resources/assets/js/customs/permissions/list-permission.js',
      'resources/assets/js/customs/permissions/store-permission.js',
      'resources/assets/js/customs/permissions/store-child-permission.js',
      'resources/assets/js/customs/permissions/update-permission.js',
      ])
    <script>
        window.permissions = {
            Access: false,
            Create: false,
            Update: false,
            Delete: false,
        }
        @foreach(BaseEnum::ROLE_ACTION as $role)
            @if($user->checkHasOrganizationPermission("$role Permissions"))
            window.permissions['{{$role}}'] = true
        @endif
        @endforeach
    </script>
@endsection

@section('content')

    <!-- Permission Table -->
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">{{ __('permissions') }}</h5>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-permissions table border-top">
                <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th>{{ __('name') }}</th>
                    <th>{{ __('created_date') }}</th>
                    <th>{{ __('actions') }}</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <!--/ Permission Table -->

    <!-- Modal -->
    @include('customs.permissions.modal-add-permission')
    @include('customs.permissions.modal-add-child-permission')
    @include('customs.permissions.modal-edit-permission')
    <!-- /Modal -->

    <!-- Custom -->
    @include('customs.permissions.form-delete-permission')
    <!-- /Custom -->
@endsection
