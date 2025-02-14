@php
    use Illuminate\Support\Facades\Route;
    $configData = Helper::appClasses();
    $customizerHidden = 'customizer-hide';
    $configData = Helper::appClasses();
@endphp

@extends('layouts/blankLayout')

@section('title', __('login'))

@section('page-style')
    <!-- Page -->
    @vite('resources/assets/vendor/scss/pages/page-auth.scss')
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
            <span class="app-brand-text demo text-heading fw-bold">{{empty($configs['app_name']) ? config('variables.templateName') : $configs['app_name']}}</span>
        </a>
        <!-- /Logo -->
        <div class="authentication-inner row m-0">
            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-8 p-0">
                <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                    <img src="{{ asset('assets/img/illustrations/boy-with-laptop-light.png') }}" alt="auth-login-cover" class="my-5 auth-illustration">
                    <img src="{{ asset('assets/img/illustrations/bg-shape-image-'.$configData['style'].'.png') }}" alt="auth-login-cover" class="platform-bg">
                </div>
            </div>
            <!-- /Left Text -->

            <!-- Login -->
            <div class="d-flex col-12 col-lg-4 align-items-center authentication-bg p-sm-12 p-6">
                <div class="w-px-400 mx-auto mt-12 pt-5">
                    <h4 class="mb-1">{{ __('welcome_to_app_name', ['app_name' => empty($configs['app_name']) ? config('variables.templateName') : $configs['app_name']]) }}</h4>
                    <p class="mb-6">{{ __('please_sign_in_to_your_account_and_start_the_adventure') }}</p>

                    @if (session('status'))
                        <div class="alert alert-success mb-1 rounded-0" role="alert">
                            <div class="alert-body">
                                {{ session('status') }}
                            </div>
                        </div>
                    @endif

                    <form id="formAuthentication" class="mb-6" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="login-email" class="form-label">{{ __('email') }}</label>
                            <input type="text" class="form-control @error('email') is-invalid @enderror"
                                   id="login-email" name="email" placeholder="nguyenvana@gmail.com" autofocus
                                   value="{{ old('email') }}">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <span class="fw-medium">{{ $message }}</span>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-6 form-password-toggle">
                            <label class="form-label" for="login-password">{{ __('password') }}</label>
                            <div class="input-group input-group-merge @error('password') is-invalid @enderror">
                                <input type="password" id="login-password"
                                       class="form-control @error('password') is-invalid @enderror" name="password"
                                       placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                       aria-describedby="password"/>
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <span class="fw-medium">{{ $message }}</span>
                            </span>
                            @enderror
                        </div>
                        <div class="my-8">
                            <div class="d-flex justify-content-between">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">
                                        <p class="mb-0">{{ __('forgot_password') }}</p>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <button class="btn btn-primary d-grid w-100" type="submit">{{ __('sign_in') }}</button>
                    </form>
                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>
@endsection

