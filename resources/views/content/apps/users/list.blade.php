@php use App\Enums\BaseEnum; $user = auth()->user(); @endphp
@extends('layouts.layoutMaster')

@section('title', __('user_list'))

@section('vendor-style')
    @vite([
      'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
      'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
      'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
      'resources/assets/vendor/libs/select2/select2.scss',
      'resources/assets/vendor/libs/@form-validation/form-validation.scss',
      'resources/assets/vendor/libs/animate-css/animate.scss',
      'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
       'resources/assets/vendor/libs/jstree/jstree.scss',
    ])
@endsection

@section('vendor-script')
    @vite([
      'resources/assets/vendor/libs/moment/moment.js',
      'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
      'resources/assets/vendor/libs/select2/select2.js',
      'resources/assets/vendor/libs/@form-validation/popular.js',
      'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
      'resources/assets/vendor/libs/@form-validation/auto-focus.js',
      'resources/assets/vendor/libs/cleavejs/cleave.js',
      'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
      'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
    'resources/assets/vendor/libs/jstree/jstree.js',
    ])
@endsection

@section('page-script')
    @vite([
         'resources/assets/js/customs/users/list.js',
         'resources/assets/js/customs/users/store.js',
         'resources/assets/js/customs/users/update.js'
    ])
    <script>
        window.userPermission = {
            Access: false,
            Create: false,
            Update: false,
            Delete: false,
            Export: false,
            Import: false,
        }
        window.hasAdmin = false;
        window.menuData = @json($menus);
        @if($user->hasRole('Admin')) window.hasAdmin = true;
        @endif
            @foreach(BaseEnum::ROLE_ACTION as $role)
            @if($user->hasOrganizationPermission("$role Users", session('organization_id')))
            window.userPermission['{{$role}}'] = true
        @endif
        @endforeach
    </script>
@endsection

@section('content')
<section id="user-list">
    <div class="row g-6 mb-6">
        @if ($user->hasRole('Admin'))
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">{{ __('users') }}</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $totalUser }}</h4>
                                </div>
                                <small class="mb-0">{{ __('total_users') }}</small>
                            </div>
                            <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                              <i class="ti ti-user ti-26px"></i>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">{{ __('admin') }}</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $totalAdmin }}</h4>
                                </div>
                                <small class="mb-0">{{ __('total_admin') }}</small>
                            </div>
                            <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                              <i class="ti ti-user ti-26px"></i>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">{{ __('active_users') }}</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $totalActive }}</h4>
                                </div>
                                <small class="mb-0">{{ __('active_users') }}</small>
                            </div>
                            <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                              <i class="ti ti-user ti-26px"></i>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">{{ __('inactive_users') }}</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $totalInactive }}</h4>
                                </div>
                                <small class="mb-0">{{ __('inactive_users') }}</small>
                            </div>
                            <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                              <i class="ti ti-user ti-26px"></i>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <!-- Users List Table -->
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">{{ __('users') }}</h5>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-users table">
                <thead class="border-top">
                <tr>
                    <th></th>
                    <th></th>
                    <th>{{ __('name') }}</th>
                    <th>{{ __('organization_name') }}</th>
                    <th>{{ __('status') }}</th>
                    <th>{{ __('created_at') }}</th>
                    <th>{{ __('actions') }}</th>
                </tr>
                </thead>
            </table>
        </div>
        <!-- Custom -->
        @include('customs.users.canvas-create-user')
        @include('customs.users.canvas-edit-user')
        @include('customs.users.form-delete-user')
        <!-- /Custom -->
    </div>
</section>

@endsection
