@php
use Illuminate\Support\Facades\Auth;
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
$configData = Helper::appClasses();
@endphp

@extends('layouts/blankLayout')

@section('title', __('select_organization'))

@section('vendor-style')
    @vite([
      'resources/assets/vendor/libs/select2/select2.scss',
    ])
@endsection


@section('vendor-script')
    @vite([
      'resources/assets/vendor/libs/select2/select2.js',
    ])
@endsection

@section('page-style')
<!-- Page -->
@vite('resources/assets/vendor/scss/pages/page-auth.scss')
@endsection

@section('page-script')
  <script>
    const organizations = @json($organizations);
  </script>
  @vite(['resources/assets/js/customs/auth/organization.js'])
@endsection

@section('content')
<div class="authentication-wrapper authentication-cover">
  <!-- Logo -->
  <a href="{{url('/')}}" class="app-brand auth-cover-brand">
        <span class="app-brand-logo">
             @if(empty($configs['logo']))
                @include('_partials.macros',['height'=>20,'withbg' => "fill: #fff;"])
            @else
                <img width="34" height="34" src="{{ $configs['logo'] }}" alt="Logo">
            @endif
                </span>
      <span
          class="app-brand-text demo text-heading fw-bold">{{ !empty($configs['app_name']) ? $configs['app_name'] : config('app.name')  }}</span>
  </a>
  <!-- /Logo -->
  <div class="authentication-inner row m-0">

    <!-- /Left Text -->
    <div class="d-none d-lg-flex col-lg-8 p-0">
      <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
        <img
                src="{{ asset('assets/img/illustrations/boy-with-rocket-light.png') }}"
                alt="auth-login-cover" class="my-5 auth-illustration"
                data-app-light-img="illustrations/boy-with-rocket-light.png"
                data-app-dark-img="illustrations/boy-with-rocket-dark.png">

        <img src="{{ asset('assets/img/illustrations/bg-shape-image-'.$configData['style'].'.png') }}"
             alt="auth-login-cover" class="platform-bg"
             data-app-light-img="illustrations/bg-shape-image-light.png"
             data-app-dark-img="illustrations/bg-shape-image-dark.png">
      </div>
    </div>
    <!-- /Left Text -->

    <div class="d-flex col-12 col-lg-4 align-items-center authentication-bg p-6 p-sm-12">
      <div class="w-px-400 mx-auto mt-12 mt-5">
        <h4 class="mb-1">{{ __('choose_organization') }}</h4>
        <div class="mt-6 d-flex flex-column gap-2">
          <div class="mb-6">
            <label class="form-label" for="organization">{{ __('organization') }}</label>
            <select id="organization" class="select2 form-select" name="organization">
              <option value="">{{ __('select_organization') }}</option>
            </select>
          </div>
          <button type="button" class="w-100 btn btn-label-secondary" id="chooseOrganization" disabled>{{ __('go_to_home') }}</button>
          @if(Auth::guard('web')->check())
          <li style="list-style: none">
            <div class="d-grid px-2 pt-2 pb-1">
              <a class="btn btn-sm btn-danger d-flex" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <small class="align-middle">{{ __('logout') }}</small>
                <i class="ti ti-logout ms-2 ti-14px"></i>
              </a>
            </div>
          </li>
          <form method="POST" id="logout-form" action="{{ route('logout') }}">
            @csrf
          </form>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
