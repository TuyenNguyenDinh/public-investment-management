@php use App\Enums\BaseEnum;use App\Enums\Posts\PostType;
 $user = auth()->user();
 $postTypes = PostType::postTypeText();
@endphp
@extends('layouts/layoutMaster')

@section('title', __('post_list'))

@section('vendor-style')
    @vite([
        'resources/assets/vendor/libs/@form-validation/form-validation.scss',
        'resources/assets/vendor/libs/jstree/jstree.scss',
        'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
        'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
        'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
        'resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss',
        'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
        'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
        'resources/assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.scss',
        'resources/assets/vendor/libs/@form-validation/form-validation.scss',
        'resources/assets/vendor/libs/select2/select2.scss',
        'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.scss',
    ])
@endsection

@section('vendor-script')
    @vite([
        'resources/assets/vendor/libs/@form-validation/popular.js',
        'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
        'resources/assets/vendor/libs/@form-validation/auto-focus.js',
        'resources/assets/vendor/libs/jstree/jstree.js',
        'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
        'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
        'resources/assets/vendor/libs/moment/moment.js',
        'resources/assets/vendor/libs/flatpickr/flatpickr.js',
        'resources/assets/vendor/libs/@form-validation/popular.js',
        'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
        'resources/assets/vendor/libs/@form-validation/auto-focus.js',
        'resources/assets/vendor/libs/select2/select2.js',
        'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js',
    ])
    <script>
        window.postPermission = {
            Access: false,
            Create: false,
            Update: false,
            Delete: false,
            Export: false,
            Import: false,
            Review: false,
        }
        window.hasAdmin = false;
        @if($user->hasRole('Admin')) window.hasAdmin = true;@endif
        @foreach(BaseEnum::ROLE_ACTION as $role)
            @if($user->checkHasOrganizationPermission("$role Posts"))
            window.postPermission['{{$role}}'] = true
        @endif
        @endforeach
    </script>
@endsection

@section('page-script')
    @vite('resources/assets/js/customs/posts/list.js')
    <script>
        const organizations = @json($organizations);
        const postTypes = @json($postTypes);
    </script>
@endsection

@section('content')
    <style>
        tbody td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }
    </style>
    <div class="row g-6 mb-6">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">{{ __('post_count_post') }}</span>
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">{{ $totalPosts }}</h4>
                            </div>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="ti ti-news ti-26px"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">{{ __('total_views_post') }}</span>
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">{{ $totalViews }}</h4>
                            </div>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                               <i class="ti ti-eye ti-26px"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">{{ __('category_count_post') }}</span>
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">{{ $totalCategories }}</h4>
                            </div>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                              <i class="ti ti-category ti-26px"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">{{ __('published_post_count_post') }}</span>
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">{{ $totalPostsPublished }}</h4>
                            </div>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                               <i class="ti ti-upload ti-26px"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- JSTree -->
    <div class="row">
        <!-- Custom Icons -->
        <div class="col-md-12 col-12">
            <div class="card card-header mb-6 col-12">
                <div
                    class="row dt-action-buttons text-start d-flex align-items-center justify-content-between justify-content-center flex-wrap">
                    <div class="col-3">
                        <h5 class="m-0">{{ __('search_post') }}</h5>
                    </div>
                    @if($user->checkHasOrganizationPermission(BaseEnum::POST['EXPORT']))
                        <div class="col-3 bdt-buttons btn-group flex-wrap w-25">
                            <form style="width: 100%" action="{{ route('app-posts-export-excel') }}" method="post"
                                  enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <button
                                    style="width: 100%;"
                                    class="btn btn-secondary btn-primary waves-effect waves-light rounded border-left-0 border-right-0"
                                    data-bs-toggle="modal"
                                    data-bs-target="#exportExcel"
                                >
                                    <i class="ti ti-table-export"></i>&nbsp;<span
                                        class="d-none d-sm-inline-block">{{ __('export_post') }}</span>
                                </button>
                            </form>
                        </div>
                    @endif
                    @if($user->checkHasOrganizationPermission(BaseEnum::POST['IMPORT']))
                        <div class="col-3 bdt-buttons btn-group flex-wrap w-25">
                            <button
                                class="btn btn-secondary btn-primary waves-effect waves-light rounded border-left-0 border-right-0"
                                data-bs-toggle="modal"
                                data-bs-target="#importExcel"
                            >
                                <i class="ti ti-file-arrow-right"></i>&nbsp;<span
                                    class="d-none d-sm-inline-block">{{ __('import_from_excel_post') }}</span>
                            </button>
                            <div class="modal fade" id="importExcel" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('app-posts-import-excel') }}" method="post"
                                              enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="exampleModalLabel1">{{ __('upload_posts_from_excel_post') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col mb-4">
                                                        <label for="excel"
                                                               class="form-label">{{ __('file_post') }}</label>
                                                        <input type="file" id="excel" class="form-control" name="excel"
                                                               accept=".xlsx, .xls, .csv">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-label-secondary"
                                                        data-bs-dismiss="modal">{{ __('close_post') }}</button>
                                                <button type="submit" class="btn btn-primary"
                                                        id="btn-import-excel">{{ __('import_post') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-3 bdt-buttons btn-group flex-wrap w-25">
                        @if($user->checkHasOrganizationPermission(BaseEnum::POST['CREATE']))
                            <a
                                href="{{ route('app-posts-create') }}"
                                class="btn btn-secondary btn-primary waves-effect waves-light rounded border-left-0 border-right-0"
                            >
                                <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                                    class="d-none d-sm-inline-block">{{ __('add_post_post') }}</span>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form id="formSearch" class="row g-6">
                                    <div class="col-md-6">
                                        <label class="form-label" for="parent-id">{{ __('organization_post') }}</label>
                                        <select id="parent-id" class="form-select organization-post"
                                                name="organization_id">
                                            <option value="">{{ __('please_select_post') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="status" class="form-label">{{ __('status_post') }}</label>
                                        <select id="status" class="status form-select" name="status">
                                            <option value="">{{ __('select_status_post') }}</option>
                                            @foreach($postTypes as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="start-date" class="form-label">{{ __('start_date_post') }}</label>
                                        <input id="start-date" class="form-control" type="text" name="start_date"
                                               placeholder="{{ app()->getLocale() === 'vn' ? 'dd-mm-yyyy' : 'yyyy-mm-dd' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="end-date" class="form-label">{{ __('end_date_post') }}</label>
                                        <input class="form-control" id="end-date" type="text" name="end_date"
                                               placeholder="{{ app()->getLocale() === 'vn' ? 'dd-mm-yyyy' : 'yyyy-mm-dd' }}">
                                    </div>
                                    <div class="col-12">
                                        <button type="button" name="submitButton" class="btn btn-primary">
                                            <i class="ti ti-search me-sm-1"></i>
                                            {{ __('search_post') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="card mb-6 h-100">
                <div class="card-body">
                    <div id="jstree-ajax"></div>
                    <p id="jstree-no-data" style="display: none;">{{ __('no_data_post') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-12">
            <div class="card mb-6 h-100">
                <div class="card-body">
                    <!-- DataTable with Buttons -->
                    <div class="card">
                        <div class="card-datatable table-responsive pt-0">
                            <table class="datatables-basic table">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>{{ __('id_post') }}</th>
                                    <th>{{ __('title_post') }}</th>
                                    <th>{{ __('status_post') }}</th>
                                    <th>{{ __('categories_post') }}</th>
                                    <th>{{ __('organizations_post') }}</th>
                                    <th>{{ __('views_post') }}</th>
                                    <th>{{ __('created_date_post') }}</th>
                                    <th>{{ __('updated_date_post') }}</th>
                                    <th>{{ __('creator_post') }}</th>
                                    <th>{{ __('updater_post') }}</th>
                                    <th>{{ __('action_post') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
