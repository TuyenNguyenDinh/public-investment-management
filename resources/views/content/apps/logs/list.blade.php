@extends('layouts.layoutMaster')

@section('title', __('activity'))

@section('vendor-style')
    @vite([
      'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
      'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
      'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
      'resources/assets/vendor/libs/select2/select2.scss',
      'resources/assets/vendor/libs/@form-validation/form-validation.scss',
      'resources/assets/vendor/libs/animate-css/animate.scss',
      'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
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
      'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
    ])
@endsection

@section('page-script')
    @vite([
         'resources/assets/js/customs/logs/list.js',
         ])
@endsection

@section('content')
    <!-- Users List Table -->
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">{{ __('log_activity') }}</h5>
        </div>
        <div class="card-datatable text-nowrap">
            <table class="datatables-activity table">
                <thead class="border-top">
                <tr>
                    <th>{{ __('user_name') }}</th>
                    <th>{{ __('organization_name') }}</th>
                    <th>{{ __('description') }}</th>
                    <th>{{ __('route') }}</th>
                    <th>{{ __('method') }}</th>
                    <th>{{ __('ip_address') }}</th>
                    <th>{{ __('user_agent') }}</th>
                    <th>{{ __('country') }}</th>
                    <th>{{ __('access_date') }}</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
