@php
    use Illuminate\Support\Facades\Route;
    $customizerHidden = 'customizer-hide';
    $configData = Helper::appClasses();
@endphp

@extends('layouts/blankLayout')

@section('title', __('forgot_password'))

@section('page-style')
    <!-- Page -->
    @vite('resources/assets/vendor/scss/pages/page-auth.scss')
@endsection

@section('content')
    <div class="authentication-wrapper authentication-cover">
        <!-- Logo -->
        <a href="{{url('/')}}" class="app-brand auth-cover-brand">
        <span class="app-brand-logo demo">
             @if(empty($configs['logo']))
                @include('_partials.macros',['height'=>20,'withbg' => "fill: #fff;"])
            @else
                <img class="w-100" src="{{ asset($configs['logo']) }}" alt="Logo">
            @endif
        </span>
            <span class="app-brand-text demo text-heading fw-bold">{{empty($configs['app_name']) ? config('variables.templateName') : $configs['app_name']}}</span>
        </a>
        <!-- /Logo -->
        <div class="authentication-inner row m-0">

            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-8 p-0">
                <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                    <img
                        src="{{ asset('assets/img/illustrations/auth-forgot-password-illustration-'.$configData['style'].'.png') }}"
                        alt="auth-forgot-password-cover" class="my-5 auth-illustration d-lg-block d-none"
                        data-app-light-img="illustrations/auth-forgot-password-illustration-light.png"
                        data-app-dark-img="illustrations/auth-forgot-password-illustration-dark.png">

                    <img src="{{ asset('assets/img/illustrations/bg-shape-image-'.$configData['style'].'.png') }}"
                         alt="auth-forgot-password-cover" class="platform-bg"
                         data-app-light-img="illustrations/bg-shape-image-light.png"
                         data-app-dark-img="illustrations/bg-shape-image-dark.png">
                </div>
            </div>
            <!-- /Left Text -->

            <!-- Forgot Password -->
            <div class="d-flex col-12 col-lg-4 align-items-center authentication-bg p-sm-12 p-6">
                <div class="w-px-400 mx-auto mt-12 mt-5">
                    <h4 class="mb-1">{{ __('forgot_password_title') }}</h4>
                    <p class="mb-6">{{ __('forgot_password_instructions') }}</p>

                    @if (session('status'))
                        <div class="mb-1 text-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form id="formAuthentication" class="mb-6" action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="email" class="form-label">{{ __('email') }}</label>
                            <input type="text" class="form-control @error('email') is-invalid @enderror" id="email"
                                   name="email" placeholder="john@example.com" autofocus>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <span class="fw-medium">{{ $message }}</span>
                            </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary d-grid w-100">{{ __('send_reset_link') }}</button>
                    </form>
                    <div class="text-center">
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center">
                                <i class="ti ti-chevron-left scaleX-n1-rtl me-1_5"></i>
                                {{ __('back_to_login') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <!-- /Forgot Password -->
        </div>
    </div>
@endsection

