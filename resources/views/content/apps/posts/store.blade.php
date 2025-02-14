@extends('layouts/layoutMaster')

@section('title', __('create_post'))

<!-- Vendor Styles -->
@section('vendor-style')
    @vite([
      'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss',
      'resources/assets/vendor/libs/select2/select2.scss',
      'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
      'resources/assets/vendor/libs/typeahead-js/typeahead.scss',
      'resources/assets/vendor/libs/tagify/tagify.scss',
      'resources/assets/vendor/libs/@form-validation/form-validation.scss'
    ])
@endsection

<!-- Page Styles -->
@section('page-style')
    @vite([
      'resources/assets/css/customs/posts/store.scss'
    ])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite([
      'resources/assets/vendor/libs/select2/select2.js',
      'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js',
      'resources/assets/vendor/libs/moment/moment.js',
      'resources/assets/vendor/libs/flatpickr/flatpickr.js',
      'resources/assets/vendor/libs/typeahead-js/typeahead.js',
      'resources/assets/vendor/libs/tagify/tagify.js',
      'resources/assets/vendor/libs/@form-validation/popular.js',
      'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
      'resources/assets/vendor/libs/@form-validation/auto-focus.js'
    ])
@endsection


<!-- Page Scripts -->
@section('page-script')
    <script>
        const currentOrganizationId = '{{ session('organization_id') }}'
        const organizations = @json($organizations);
        const routeCKFinderBrowser = '{{ route('ckfinder_browser') }}'
    </script>
    <script src="{{ url('ckeditor/ckeditor.js') }}"></script>
    @vite(['resources/assets/js/customs/posts/store.js'])
    @include('ckfinder::setup')
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('app-posts-index') }}" class="btn btn-primary">{{__('back')}}</a>
        </div>
    </div>
    <div class="row" id="store-post">
        <!-- FormValidation -->
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">{{__('create_post')}}</h5>
                <div class="card-body">
                    <form id="formCreatePost" class="row g-6" action="{{ route('app-posts-store') }}" method="post"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="col-md-12">
                            <label class="form-label" for="formValidationTitle">{{__('title')}}</label>
                            <input type="text" id="formValidationTitle"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}"
                                   placeholder="{{__('title')}}" name="title"/>
                           @include('customs.validations.error', ['field' => 'title'])
                        </div>
                        <div class="col-md-12">
                            <label for="formValidationFile" class="form-label">{{__('thumbnail')}}</label>
                            <input class="form-control @error('thumbnail') is-invalid @enderror" type="file"
                                   id="formValidationFile" name="thumbnail">
                            @include('customs.validations.error', ['field' => 'thumbnail'])
                        </div>
                        <div class="col-md-12">
                            <label for="formValidationCategory" class="form-label">{{__('Categories')}}</label>
                            <select id="formValidationCategory"
                                    class="formValidationCategory form-select @error('categories') is-invalid @enderror"
                                    multiple name="categories[]">
                            </select>
                            @include('customs.validations.error', ['field' => 'categories'])
                        </div>
                        <div class="col-md-12">
                            <label for="formValidationOrganization" class="form-label">{{__('organization')}}</label>
                            <select id="formValidationOrganization"
                                    class="formValidationOrganization form-select @error('organizations') is-invalid @enderror"
                                    multiple name="organizations[]">
                            </select>
                            @include('customs.validations.error', ['field' => 'organizations'])
                        </div>
                        <div class="col-md-12">
                            <label for="formValidationSchedule" class="form-label">{{__('schedule_date')}}</label>
                            <input
                                class="formValidationSchedule form-control @error('scheduled_date') is-invalid @enderror"
                                id="formValidationSchedule" type="datetime-local" name="scheduled_date"
                                value="{{ old('scheduled_date') }}"
                                placeholder="{{ app()->getLocale() === 'vn' ? 'dd-mm-yyyy hh:mm' : 'yyyy-mm-dd hh:mm' }}">
                            @include('customs.validations.error', ['field' => 'scheduled_date'])
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">{{__('content')}}</label>
                            <textarea name="content" id="content">{{ old('content') }}</textarea>
                            @include('customs.validations.error', ['field' => 'content'])
                        </div>
                        <div class="col-12">
                            <input type="hidden" id="tmp" name="tmp">
                            <input type="hidden" name="action" value="save">
                            <button type="button" id="save" class="btn btn-primary">{{ __('save') }}</button>
                            <button type="button" id="save-exit" class="btn btn-secondary">{{ __('save_and_exit') }}</button>
                            <button type="button" id="save-duplicate" class="btn btn-info">{{ __('save_and_duplicate') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
