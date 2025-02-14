@extends('layouts/layoutMaster')

@section('title', __('configs_title'))

@section('vendor-style')
    @vite([
      'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
      'resources/assets/vendor/libs/select2/select2.scss',
    ])
@endsection

@section('page-script')
    @vite([
      'resources/assets/js/customs/configs/list.js',
      'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
      'resources/assets/vendor/libs/select2/select2.js',
    ])
    <script>
        localStorage.setItem('new_post_date', {{ $configs['new_post_date'] ?? 0 }});
    </script>
@endsection

@section('content')
    <div class="row g-6">

        <!-- Navigation -->
        <div class="col-12 col-lg-4">
            <div class="d-flex justify-content-between flex-column mb-4 mb-md-0">
                <h5 class="mb-4">{{ __('configs_header') }}</h5>
                @include('content.apps.configs.navigator')
            </div>
        </div>
        <!-- /Navigation -->

        <!-- Options -->
        <div class="col-12 col-lg-8 pt-6 pt-lg-0">
            <div class="tab-content p-0">
                <!-- General Configurations Tab -->
                @include('content.apps.configs.common')

                <!-- Email Configurations Tab -->
                @include('content.apps.configs.email')
            </div>
        </div>
    </div>
@endsection
