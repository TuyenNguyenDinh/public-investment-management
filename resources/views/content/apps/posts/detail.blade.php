@php use App\Enums\BaseEnum;use App\Enums\Posts\PostType; $user = auth()->user(); @endphp
@extends('layouts/layoutMaster')

@section('title', __('update_post'))

<!-- Vendor Styles -->
@section('vendor-style')
    @vite([
      'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss',
      'resources/assets/vendor/libs/select2/select2.scss',
      'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
      'resources/assets/vendor/libs/typeahead-js/typeahead.scss',
      'resources/assets/vendor/libs/tagify/tagify.scss',
      'resources/assets/vendor/libs/@form-validation/form-validation.scss',
      'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
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
      'resources/assets/vendor/libs/@form-validation/auto-focus.js',
      'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
      'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js',
      'resources/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js',
      'resources/assets/vendor/libs/jquery-timepicker/jquery-timepicker.js',
      'resources/assets/vendor/libs/pickr/pickr.js'
    ])
@endsection


<!-- Page Scripts -->
@section('page-script')
    <script>
        let categories = @json($post->categories);
        let organizations = @json($organizations);
        const postId = '{{ $post->id }}'
        const postOrganization = @json($post->organizations->pluck('id')->toArray());
        const routeCKFinderBrowser = '{{ route('ckfinder_browser') }}'
    </script>
    <script src="{{ url('ckeditor/ckeditor.js') }}"></script>
    @vite(['resources/assets/js/customs/posts/update.js'])
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('app-posts-index') }}" class="btn btn-primary">{{__('back')}}</a>
        </div>
    </div>
    <div class="row" id="update-post">
        <!-- FormValidation -->
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">{{__('update_post')}}</h5>
                <div class="card-body">
                    <form id="formCreatePost" class="row g-6 fv-plugins-bootstrap5 fv-plugins-framework fv-plugins-icon-container" action="{{ route('app-posts-update', request()->slug) }}"
                          method="post" enctype="multipart/form-data">
                        {{ method_field('PATCH') }}
                        {{ csrf_field() }}
                        <div class="col-md-12">
                            <label class="form-label" for="formValidationTitle">{{__('title')}}</label>
                            <input type="text" id="formValidationTitle" class="form-control @error('title') is-invalid @enderror"
                                   placeholder="{{__('title')}}"
                                   name="title" value="{{ $post->title }}"/>
                            @include('customs.validations.error', ['field' => 'title'])
                        </div>
                        <div class="col-md-12">
                            <label for="formValidationFile" class="form-label">{{__('thumbnail')}}</label>
                            <input class="form-control @error('thumbnail') is-invalid @enderror" type="file" id="formValidationFile" name="thumbnail">
                            @include('customs.validations.error', ['field' => 'thumbnail'])
                        </div>
                        @isset($post->thumbnail)
                            <div class="col-md-12">
                                <img width="{{$configs['thumbnail_post_width_size'] ?? '40'}}" height="{{$configs['thumbnail_post_height_size'] ?? '40'}}" src="{{ $post->thumbnail }}" alt="Post thumbnail">
                            </div>
                        @endisset
                        @if($user->checkHasOrganizationPermission(BaseEnum::POST['REVIEW']))
                            <div class="col-md-12">
                                <label class="form-label" for="status">{{__('status')}}</label>
                                <select id="status" class="status form-select @error('status') is-invalid @enderror" name="status">
                                    @foreach(PostType::postTypeText() as $key => $value)
                                        <option
                                            value="{{ $key }}" {{ $post->status == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @include('customs.validations.error', ['field' => 'status'])
                            </div>
                        @endif
                        <div class="col-md-12">
                            <label for="formValidationCategory" class="form-label">{{__('Categories')}}</label>
                            <select id="formValidationCategory" class="formValidationCategory form-select @error('categories') is-invalid @enderror" multiple
                                    name="categories[]">
                                @forelse($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @empty
                                    <option disabled>{{__('no_data')}}</option>
                                @endforelse
                            </select>
                            @include('customs.validations.error', ['field' => 'categories'])
                        </div>
                        <div class="col-md-12">
                            <label for="formValidationOrganization"
                                   class="form-label">{{(__('organizations'))}}</label>
                            <select id="formValidationOrganization" class="formValidationOrganization form-select @error('organizations') is-invalid @enderror"
                                    multiple name="organizations[]">
                                <option value="">{{ __('please_select_post') }}</option>
                            </select>
                            @include('customs.validations.error', ['field' => 'organizations'])
                        </div>
                        <div class="col-md-12">
                            <label for="formValidationSchedule" class="form-label">{{__('schedule_date')}}</label>
                            <input class="formValidationSchedule form-control @error('scheduled_date') is-invalid @enderror" id="formValidationSchedule"
                                   type="text"
                                   name="scheduled_date"
                                   value="{{ $post->scheduled_date }}" placeholder={{  app()->getLocale() === 'vn' ? 'dd-mm-yyyy hh:mm' : 'yyyy-mm-dd hh:mm' }}>
                            @include('customs.validations.error', ['field' => 'scheduled_date'])
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">{{__('content')}}</label>
                            <textarea name="content" id="content">
                                {{ $post->content }}
                            </textarea>
                            @include('customs.validations.error', ['field' => 'content'])
                        </div>
                        <div class="col-12">
                            @if($post->status == PostType::DRAFT || $user->hasRole('Admin'))
                                <input type="hidden" id="tmp" name="tmp">
                                <button type="submit" name="submitButton"
                                        class="btn btn-primary">{{__('update_post')}}</button>
                                <button type="button" name="deletePost" id="deletePost"
                                        class="btn btn-danger ml-3">{{__('delete')}}</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
