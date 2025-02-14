@php $organizationList = $profile->organizations->pluck('id')->toArray(); @endphp
@extends('layouts/layoutMaster')

@section('title', __('my_profile'))

<!-- Vendor Styles -->
@section('vendor-style')
    @vite([
      'resources/assets/vendor/libs/select2/select2.scss',
      'resources/assets/vendor/libs/@form-validation/form-validation.scss',
      'resources/assets/vendor/libs/animate-css/animate.scss',
      'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
      'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.scss',
    ])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite([
      'resources/assets/vendor/libs/select2/select2.js',
      'resources/assets/vendor/libs/@form-validation/popular.js',
      'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
      'resources/assets/vendor/libs/@form-validation/auto-focus.js',
      'resources/assets/vendor/libs/cleavejs/cleave.js',
      'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
      'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
      'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js',
       'resources/assets/vendor/libs/jquery-repeater/jquery-repeater.js'
    ])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite([
        'resources/assets/js/customs/profiles/index.js',
        'resources/assets/js/customs/profiles/update.js',
    ])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="nav-align-top">
                <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-2 gap-lg-0">
                    <li class="nav-item"><a
                            class="nav-link @if(request()->route()->getName() === 'app-profiles-index') active @endif"
                            href="javascript:void(0);"><i
                                class="ti-sm ti ti-users me-1_5"></i> {{ __('account') }}</a></li>
                    <li class="nav-item"><a
                            class="nav-link @if(request()->route()->getName() === 'app-log-activities-index') active @endif"
                            href="{{ route('app-log-activities-index') }}"><i
                                class="ti-sm ti ti-lock me-1_5"></i> {{ __('activity') }}</a></li>
                </ul>
            </div>
            <div class="card mb-6">
                <!-- Account -->
                <div class="card-body pt-4">
                    <form id="form_profile" method="POST" onsubmit="return false">
                        <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-6">
                                <img
                                    src="{{ $profile->avatar_url ?: $profile->profile_photo_url }}"
                                    alt="user-avatar"
                                    class="d-block w-px-100 h-px-100 rounded" id="upload_user"/>
                                <div class="button-wrapper">
                                    <label for="user_avatar" class="form-label btn btn-primary me-3 mb-4" tabindex="0">
                                        <span class="d-none d-sm-block">{{ __('upload_new_photo') }}</span>
                                        <i class="ti ti-upload d-block d-sm-none"></i>
                                        <input type="file" id="user_avatar" class="form-control user_input" hidden
                                               name="user_avatar"
                                               accept="image/png, image/jpeg"/>
                                    </label>
                                    <button type="button" class="btn btn-label-secondary user_reset mb-4">
                                        <i class="ti ti-refresh-dot d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">{{ __('reset') }}</span>
                                    </button>

                                    <div>{{ __('allowed_formats') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-4 col-md-6">
                                <label for="name" class="form-label">{{ __('full_name') }}</label>
                                <input class="form-control" type="text" id="name" name="name"
                                       value="{{$profile?->name}}" autofocus/>
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="email" class="form-label">{{ __('email') }}</label>
                                <input class="form-control" type="text" id="email" name="email"
                                       @if(!auth()->user()->hasRole('Admin')) disabled @endif
                                       value="{{$profile?->email}}" placeholder="john.doe@example.com"/>
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="password" class="form-label">{{ __('password') }}</label>
                                <input class="form-control" type="password" id="password" name="password"
                                       value="" placeholder="**********"/>
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="confirm_password" class="form-label">{{ __('confirm_password') }}</label>
                                <input class="form-control" type="password" id="confirm_password"
                                       name="confirm_password"
                                       placeholder="**********"/>
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="organization" class="form-label">{{ __('organization') }}</label>
                                <select id="organization" class="select2 createOrganizations form-select" multiple
                                        @if(!auth()->user()->hasRole('Admin')) disabled @endif
                                        name="organizations[]">
                                    @forelse($organizations as $organization)
                                        @include('content.apps.partials.organization_option', [
                                            'organization' => $organization,
                                            'prefix' => '',
                                            'is_check' => $organizationList,
                                            'first_selected' => true
                                        ])
                                    @empty
                                        <option disabled>{{ __('no_data') }}</option>
                                    @endforelse
                                </select></div>
                            <div class="mb-4 col-md-6">
                                <label for="sex" class="form-label">{{ __('sex') }}</label>
                                <select id="sex" class="select2 sex form-select"
                                        name="sex">
                                    <option value="male"
                                            @if($profile->sex == 'male') selected @endif>{{ __('male') }}</option>
                                    <option value="female"
                                            @if($profile->sex == 'female') selected @endif>{{ __('female') }}</option>
                                    <option value="other"
                                            @if($profile->sex == 'other') selected @endif>{{ __('other') }}</option>
                                </select></div>
                            <div class="mb-4 col-md-6">
                                <label for="date_of_birth" class="form-label">{{ __('date_of_birth') }}</label>
                                <input type="text" class="form-control" id="date_of_birth" name="date_of_birth"
                                       value="{{$profile?->date_of_birth}}" placeholder="{{ app()->getLocale() === 'vn' ? 'dd-mm-yyyy' : 'yyyy-mm-dd' }}"/>
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="hometown" class="form-label">{{ __('citizen_identification') }}</label>
                                <input type="text" class="form-control" id="citizen_identification"
                                       name="citizen_identification"
                                       value="{{$profile?->citizen_identification}}" placeholder="098756432445"/>
                            </div>
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="phone_number">{{ __('phone_number') }}</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">VN (+84)</span>
                                    <input type="text" id="phone_number" name="phone_number" class="form-control phone-mask"
                                           maxlength="10"
                                           value="{{$profile?->phone_number}}"
                                           placeholder="0901555011"/>
                                </div>
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="hometown" class="form-label">{{ __('hometown') }}</label>
                                <input type="text" class="form-control" id="hometown" name="hometown"
                                       value="{{$profile?->hometown}}" placeholder="{{ __('hometown') }}"/>
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="permanent_address" class="form-label">{{ __('permanent_address') }}</label>
                                <input class="form-control" type="text" id="permanent_address" name="permanent_address"
                                       value="{{$profile?->permanent_address}}" placeholder=""/>
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="temporary_address" class="form-label">{{ __('temporary_address') }}</label>
                                <input type="text" class="form-control" id="temporary_address" name="temporary_address"
                                       value="{{$profile?->temporary_address}}" placeholder=""/>
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="education_level" class="form-label">{{ __('education_level') }}</label>
                                <input type="text" class="form-control" id="education_level" name="education_level"
                                       value="{{$profile?->education_level}}" placeholder=""/>
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="health_status" class="form-label">{{ __('health_status') }}</label>
                                <input type="text" class="form-control" id="health_status" name="health_status"
                                       value="{{$profile?->health_status}}" placeholder=""/>
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="height" class="form-label">{{ __('height') }}</label>
                                <input type="text" class="form-control" id="height" name="height"
                                       value="{{$profile?->height}}" placeholder=""/>
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="weight" class="form-label">{{ __('weight') }}</label>
                                <input type="text" class="form-control" id="weight" name="weight"
                                       value="{{$profile?->weight}}" placeholder=""/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-4 col-md-6">
                                <label for="front_citizen" class="form-label">{{ __('front_citizen_identification') }}</label>
                                <div class="d-flex flex-column align-items-start align-items-sm-center gap-6">
                                    @empty($profile->front_identification_img_url)
                                        <div
                                            class="empty_front_citizen d-flex align-items-center justify-content-center w-100 h-px-200 rounded"
                                            style="border: 2px dashed #44485e">
                                            <p class="h4 needsclick pt-3 mb-2 ms-4">{{ __('upload_front_citizen') }}</p>
                                        </div>
                                    @endempty
                                    <img
                                        src="{{ $profile->front_identification_img_url ?: '' }}"
                                        alt="user-avatar"
                                        class="w-100 h-px-200 rounded @empty($profile->front_identification_img_url) d-none @endempty "
                                        id="upload_front_citizen"/>
                                    <div class="button-wrapper">
                                        <label for="front_citizen" class="btn btn-primary me-3 mb-4" tabindex="0">
                                            <span
                                                class="d-none d-sm-block">{{ __('upload_new_photo') }}</span>
                                            <i class="ti ti-upload d-block d-sm-none"></i>
                                            <input type="file" id="front_citizen" class="front_citizen_input" hidden
                                                   accept="image/png, image/jpeg"/>
                                        </label>
                                        <button type="button" class="btn btn-label-secondary front_citizen_reset mb-4">
                                            <i class="ti ti-refresh-dot d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">{{ __('reset') }}</span>
                                        </button>

                                        <div>{{ __('allowed_formats') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="back_citizen" class="form-label">{{ __('back_citizen_identification') }}</label>
                                <div class="d-flex flex-column align-items-start align-items-sm-center gap-6">
                                    @empty($profile->back_identification_img_url)
                                        <div
                                            class="empty_back_citizen d-flex align-items-center justify-content-center w-100 h-px-200 rounded"
                                            style="border: 2px dashed #44485e">
                                            <p class="h4 needsclick pt-3 mb-2 ms-4">{{ __('upload_back_citizen') }}</p>
                                        </div>
                                    @endempty
                                    <img
                                        src="{{ $profile->back_identification_img_url ?: '' }}"
                                        alt="user-avatar"
                                        class="w-100 h-px-200 rounded @empty($profile->back_identification_img_url) d-none @endempty "
                                        id="upload_back_citizen"/>
                                    <div class="button-wrapper">
                                        <label for="back_citizen" class="btn btn-primary me-3 mb-4" tabindex="0">
                                            <span
                                                class="d-none d-sm-block">{{ __('upload_new_photo') }}</span>
                                            <i class="ti ti-upload d-block d-sm-none"></i>
                                            <input type="file" id="back_citizen" class="back_citizen_input" hidden
                                                   accept="image/png, image/jpeg"/>
                                        </label>
                                        <button type="button" class="btn btn-label-secondary back_citizen_reset mb-4">
                                            <i class="ti ti-refresh-dot d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">{{ __('reset') }}</span>
                                        </button>

                                        <div>{{ __('allowed_formats') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <h5 class="card-header">{{ __('relatives') }}</h5>
                            <div class="card-body">
                                <div class="form-repeater">
                                    <div data-repeater-list="group-a">
                                        @forelse($profile->relatives ?? [] as $i => $relative)
                                            <div data-repeater-item>
                                                <div class="row">
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="form-label" for="relative_{{$i}}_0">{{ __('full_name') }}</label>
                                                        <input type="text" id="relative_{{$i}}_0" class="form-control"
                                                               placeholder="Nguyen Van A" value="{{$relative->name}}"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-2 col-12 mb-0">
                                                        <label class="form-label"
                                                               for="relative_{{$i}}_1">{{ __('relationship') }}</label>
                                                        <input type="text" id="relative_{{$i}}_1" class="form-control"
                                                               placeholder="{{ __('dad') }}" value="{{$relative->relationship}}"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label for="relative_{{$i}}_2"
                                                               class="form-label">{{ __('Address') }}</label>
                                                        <input class="form-control" type="text" id="relative_{{$i}}_2"
                                                               value="{{$relative->address}}" placeholder="{{ __('address') }}"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-2 col-12 mb-0">
                                                        <label class="form-label" for="relative_{{$i}}_3">{{ __('phone_number') }}</label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text">VN (+84)</span>
                                                            <input type="text" id="relative_{{$i}}_3"
                                                                   class="form-control phone-mask"
                                                                   maxlength="10"
                                                                   value="{{$relative->phone_number}}"
                                                                   placeholder="0901555011"/>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="mb-6 col-lg-12 col-xl-2 col-12 d-flex align-items-end mb-0">
                                                        <button type="button" class="btn btn-label-danger" data-repeater-delete>
                                                            <i class="ti ti-x ti-xs me-1"></i>
                                                            <span class="align-middle">{{ __('delete') }}</span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <hr class="mt-0">
                                            </div>
                                        @empty
                                            <div data-repeater-item>
                                                <div class="row">
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="form-label" for="relative_0_0">{{ __('full_name') }}</label>
                                                        <input type="text" id="relative_0_0" class="form-control"
                                                               placeholder="Nguyen Van A"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-2 col-12 mb-0">
                                                        <label class="form-label"
                                                               for="relative_0_1">{{ __('relationship') }}</label>
                                                        <input type="text" id="relative_0_1" class="form-control"
                                                               placeholder="{{ __('dad') }}"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label for="relative_0_2" class="form-label">{{ __('Address') }}</label>
                                                        <input class="form-control" type="text" id="relative_0_2"
                                                               value="" placeholder="PY"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-2 col-12 mb-0">
                                                        <label class="form-label" for="relative_0_3">{{ __('phone_number') }}</label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text">VN (+84)</span>
                                                            <input type="text" id="relative_0_3"
                                                                   class="form-control phone-mask"
                                                                   maxlength="10"
                                                                   value=""
                                                                   placeholder="0901555011"/>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="mb-6 col-lg-12 col-xl-2 col-12 d-flex align-items-end mb-0">
                                                        <button type="button" class="btn btn-label-danger" data-repeater-delete>
                                                            <i class="ti ti-x ti-xs me-1"></i>
                                                            <span class="align-middle">{{ __('delete') }}</span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <hr class="mt-0">
                                            </div>
                                        @endforelse
                                    </div>
                                    <div class="mb-0">
                                        <button type="button" class="btn btn-primary" data-repeater-create>
                                            <i class="ti ti-plus ti-xs me-2"></i>
                                            <span class="align-middle">{{ __('add') }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <h5 class="card-header">{{ __('education_activities') }}</h5>
                            <div class="card-body">
                                <div class="education-repeater">
                                    <div data-repeater-list="group-a">
                                        @forelse($profile->educations ?? [] as $i => $education)
                                            <input type="hidden" id="education_id_{{$i}}"
                                                   class="education-control"
                                                   value="{{$education->id}}"/>
                                            <div data-repeater-item>
                                                <div class="row">
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="form-label education-label"
                                                               for="education_{{$i}}_0">{{ __('school_name') }}</label>
                                                        <input type="text" id="education_{{$i}}_0"
                                                               class="form-control education-control"
                                                               placeholder="{{ __('school_name') }}"
                                                               value="{{$education->school_name}}"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="form-label education-label"
                                                               for="education_{{$i}}_1">{{ __('education_level') }}</label>
                                                        <input type="text" id="education_{{$i}}_1"
                                                               class="form-control education-control"
                                                               placeholder="{{ __('expect') }}"
                                                               value="{{$education->education_level}}"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="form-label education-label"
                                                               for="education_{{$i}}_2">{{ __('education_type') }}</label>
                                                        <input type="text" id="education_{{$i}}_2"
                                                               class="form-control education-control"
                                                               placeholder="{{ __('education_type') }}"
                                                               value="{{$education->education_type}}"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="form-label education-label"
                                                               for="education_{{$i}}_3">{{ __('rank_level') }}</label>
                                                        <input type="text" id="education_{{$i}}_3"
                                                               class="form-control education-control"
                                                               placeholder="{{ __('rank_level') }}"
                                                               value="{{$education->rank_level}}"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="form-label education-label"
                                                               for="education_{{$i}}_4">{{ __('major') }}</label>
                                                        <input type="text" id="education_{{$i}}_4"
                                                               class="form-control education-control"
                                                               placeholder="{{ __('major') }}" value="{{$education->major}}"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="form-label education-label"
                                                               for="education_{{$i}}_5">{{ __('graduation_date') }}</label>
                                                        <input type="text" id="education_{{$i}}_5"
                                                               class="form-control education-control"
                                                               placeholder="{{ app()->getLocale() === 'vn' ? 'dd-mm-yyyy' : 'yyyy-mm-dd' }}"
                                                               value="{{$education->graduation_date}}"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="form-label education-label"
                                                               for="education_{{$i}}_6">{{ __('certificate_image') }}</label>
                                                        @if($education->certificate_image)
                                                            <small class="education-small">
                                                                <a href="{{ asset($education->certificate_image) }}"
                                                                   target="_blank">{{ __('view_uploaded_file') }}</a>
                                                            </small>
                                                        @endif
                                                        <input type="file" id="education_{{$i}}_6"
                                                               class="form-control education-control"
                                                               placeholder="{{ __('certificate_image') }}"
                                                               value="{{$education->certificate_image}}"
                                                               accept="image/png, image/jpeg"/>

                                                    </div>
                                                    <div
                                                        class="mb-6 col-lg-12 col-xl-2 col-12 d-flex align-items-end mb-0">
                                                        <button type="button" class="btn btn-label-danger" data-repeater-delete>
                                                            <i class="ti ti-x ti-xs me-1"></i>
                                                            <span class="align-middle">{{ __('delete') }}</span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <hr class="mt-0">
                                            </div>
                                        @empty
                                            <div data-repeater-item>
                                                <div class="row">
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="form-label education-label" for="education_0_0">{{ __('school_name') }}</label>
                                                        <input type="text" id="education_0_0"
                                                               class="form-control education-control"
                                                               placeholder="{{ __('school_name') }}"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="form-label education-label"
                                                               for="education_0_1">{{ __('education_level') }}</label>
                                                        <input type="text" id="education_0_1"
                                                               class="form-control education-control"
                                                               placeholder="{{ __('expect') }}"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="form-label education-label"
                                                               for="education_0_2">{{ __('education_type') }}</label>
                                                        <input type="text" id="education_0_2"
                                                               class="form-control education-control"
                                                               placeholder="{{ __('education_type') }}"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="form-label education-label"
                                                               for="education_0_3">{{ __('rank_level') }}</label>
                                                        <input type="text" id="education_0_3"
                                                               class="form-control education-control"
                                                               placeholder="{{ __('rank_level') }}"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="form-label education-label"
                                                               for="education_0_4">{{ __('major') }}</label>
                                                        <input type="text" id="education_0_4"
                                                               class="form-control education-control"
                                                               placeholder="{{ __('major') }}"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="form-label education-label"
                                                               for="education_0_5">{{ __('graduation_date') }}</label>
                                                        <input type="text" id="education_0_5"
                                                               class="form-control education-control"
                                                               placeholder="{{ app()->getLocale() === 'vn' ? 'dd-mm-yyyy' : 'yyyy-mm-dd' }}"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="form-label education-label"
                                                               for="education_0_6">{{ __('certificate_image') }}</label>
                                                        <input type="file" id="education_0_6"
                                                               class="form-control education-control"
                                                               placeholder="{{ __('certificate_image') }}">
                                                    </div>
                                                    <div
                                                        class="mb-6 col-lg-12 col-xl-2 col-12 d-flex align-items-end mb-0">
                                                        <button type="button" class="btn btn-label-danger" data-repeater-delete>
                                                            <i class="ti ti-x ti-xs me-1"></i>
                                                            <span class="align-middle">{{ __('delete') }}</span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <hr class="mt-0">
                                            </div>
                                        @endforelse
                                    </div>
                                    <div class="mb-0">
                                        <button type="button" class="btn btn-primary" data-repeater-create>
                                            <i class="ti ti-plus ti-xs me-2"></i>
                                            <span class="align-middle">{{ __('add') }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <h5 class="card-header">{{ __('work_histories') }}</h5>
                            <div class="card-body">
                                <div class="work-history-repeater">
                                    <div data-repeater-list="group-a">
                                        @forelse($profile->workHistories ?? [] as $i => $workHistory)
                                            <div data-repeater-item>
                                                <div class="row">
                                                    <div class="mb-6 col-lg-6 col-xl-2 col-12 mb-0">
                                                        <label class="form-label work-history-label"
                                                               for="work_history_{{$i}}_0">{{ __('start_date') }}</label>
                                                        <input type="text" id="work_history_{{$i}}_0"
                                                               class="work-history-control form-control"
                                                               placeholder="{{ app()->getLocale() === 'vn' ? 'dd-mm-yyyy' : 'yyyy-mm-dd' }}"
                                                               value="{{$workHistory->start_date}}"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-2 col-12 mb-0">
                                                        <label class="form-label work-history-label"
                                                               for="work_history_{{$i}}_1">{{ __('end_date') }}</label>
                                                        <input type="text" id="work_history_{{$i}}_1"
                                                               class="work-history-control form-control"
                                                               placeholder="{{ app()->getLocale() === 'vn' ? 'dd-mm-yyyy' : 'yyyy-mm-dd' }}"
                                                               value="{{$workHistory->end_date}}"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label for="work_history_{{$i}}_2"
                                                               class="work-history-label form-label">{{ __('organization_name') }}</label>
                                                        <input class="work-history-control form-control" type="text"
                                                               id="work_history_{{$i}}_2"
                                                               value="{{$workHistory->organization_name}}"
                                                               placeholder="PY"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="work-history-label form-label"
                                                               for="work_history_{{$i}}_3">{{ __('organization_phone_number') }}</label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text">VN (+84)</span>
                                                            <input type="text" id="work_history_{{$i}}_3"
                                                                   class="work-history-control form-control phone-mask"
                                                                   maxlength="10"
                                                                   value="{{$workHistory->organization_phone_number}}"
                                                                   placeholder="0901555011"/>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="mb-6 col-lg-12 col-xl-2 col-12 d-flex align-items-end mb-0">
                                                        <button type="button" class="btn btn-label-danger" data-repeater-delete>
                                                            <i class="ti ti-x ti-xs me-1"></i>
                                                            <span class="align-middle">{{ __('delete') }}</span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <hr class="mt-0">
                                            </div>
                                        @empty
                                            <div data-repeater-item>
                                                <div class="row">
                                                    <div class="mb-6 col-lg-6 col-xl-2 col-12 mb-0">
                                                        <label class="form-label work-history-label"
                                                               for="work_history_0_0">{{ __('start_date') }}</label>
                                                        <input type="text" id="work_history_0_0"
                                                               class="work-history-control form-control"
                                                               placeholder="{{ app()->getLocale() === 'vn' ? 'dd-mm-yyyy' : 'yyyy-mm-dd' }}"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-2 col-12 mb-0">
                                                        <label class="form-label work-history-label"
                                                               for="work_history_0_1">{{ __('end_date') }}</label>
                                                        <input type="text" id="work_history_0_1"
                                                               class="work-history-control form-control"
                                                               placeholder="{{ app()->getLocale() === 'vn' ? 'dd-mm-yyyy' : 'yyyy-mm-dd' }}"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label for="work_history_0_2"
                                                               class="work-history-label form-label">{{ __('organization_name') }}</label>
                                                        <input class="work-history-control form-control" type="text"
                                                               id="work_history_0_2"
                                                               placeholder="PY"/>
                                                    </div>
                                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="work-history-label form-label"
                                                               for="work_history_0_3">{{ __('organization_phone_number') }}</label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text">VN (+84)</span>
                                                            <input type="text" id="work_history_0_3"
                                                                   class="work-history-control form-control phone-mask"
                                                                   maxlength="10"
                                                                   placeholder="0901555011"/>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="mb-6 col-lg-12 col-xl-2 col-12 d-flex align-items-end mb-0">
                                                        <button type="button" class="btn btn-label-danger" data-repeater-delete>
                                                            <i class="ti ti-x ti-xs me-1"></i>
                                                            <span class="align-middle">{{ __('delete') }}</span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <hr class="mt-0">
                                            </div>
                                        @endforelse
                                    </div>
                                    <div class="mb-0">
                                        <button type="button" class="btn btn-primary" data-repeater-create>
                                            <i class="ti ti-plus ti-xs me-2"></i>
                                            <span class="align-middle">{{ __('add') }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" id="update_profile" class="btn btn-primary me-3">{{ __('save_changes') }}</button>
                        </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>
        </div>
    </div>

@endsection
