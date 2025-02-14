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
         'resources/assets/js/customs/activities/list.js',
         ])
@endsection

@section('content')
    <div class="nav-align-top">
        <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-2 gap-lg-0">
            <li class="nav-item"><a class="nav-link @if(request()->route()->getName() === 'app-profiles-index') active @endif" href="{{ route('app-profiles-index') }}"><i
                        class="ti-sm ti ti-users me-1_5"></i> {{ __('account') }}</a></li>
            <li class="nav-item"><a class="nav-link @if(request()->route()->getName() === 'app-log-activities-index') active @endif" href="{{ route('app-log-activities-index') }}"><i
                        class="ti-sm ti ti-lock me-1_5"></i> {{ __('activity') }}</a></li>
        </ul>
    </div>
    <!-- Users List Table -->
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">{{ __('log_activity') }}</h5>
        </div>
        <div class="card-datatable text-nowrap">
            <table class="datatables-activity table">
                <thead class="border-top">
                <tr>
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
