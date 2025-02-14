@php
    use App\Enums\BaseEnum;
    $user = auth()->user();
@endphp

@extends('layouts.layoutMaster')

@section('title', __('roles'))

@section('vendor-style')
    @vite([
      'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
      'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
      'resources/assets/vendor/libs/@form-validation/form-validation.scss',
      'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
      'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
      'resources/assets/vendor/libs/animate-css/animate.scss',
      'resources/assets/vendor/libs/select2/select2.scss',
      ])
@endsection

@section('vendor-script')
    @vite([
      'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
      'resources/assets/vendor/libs/select2/select2.js',
      'resources/assets/vendor/libs/@form-validation/popular.js',
      'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
      'resources/assets/vendor/libs/@form-validation/auto-focus.js',
      'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
      ])
@endsection

@section('page-script')
    @vite([
      'resources/assets/js/customs/roles/list.js',
      'resources/assets/js/customs/roles/store.js',
      'resources/assets/js/customs/roles/update.js',
      ])
    <script>
        window.rolePermission = {
            Access: false,
            Create: false,
            Update: false,
            Delete: false,
        }
        @foreach(BaseEnum::ROLE_ACTION as $role)
            @if($user->hasOrganizationPermission("$role Roles", session('organization_id')))
            window.rolePermission['{{$role}}'] = true
        @endif
        @endforeach
    </script>
@endsection

@section('content')
<section id="role-list">
    <!-- Role cards -->
    <div class="row g-6">
        <div class="col-12">
            <!-- Role Table -->
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">{{ __('roles') }}</h5>
                </div>
                <div class="card-datatable table-responsive">
                    <table class="datatables-roles table border-top">
                        <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>{{ __('role_name') }}</th>
                            <th>{{ __('created_date') }}</th>
                            <th>{{ __('actions') }}</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!--/ Role Table -->
        </div>
    </div>
    <!--/ Role cards -->

    <!-- Add Role Modal -->
    @include('customs.roles.modal-add-role')
    <!-- / Add Role Modal -->
    <!-- Custom -->
    @include('customs.roles.modal-edit-role')
    @include('customs.roles.form-delete-role')
    <!-- / Custom -->
</section>
@endsection
