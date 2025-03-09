@php use App\Enums\BaseEnum;$user = auth()->user(); @endphp
@extends('layouts/layoutMaster')

@section('title', __('categories_list'))

@section('vendor-style')
    @vite([
     'resources/assets/vendor/libs/@form-validation/form-validation.scss',
     'resources/assets/vendor/libs/jstree/jstree.scss',
     'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
     'resources/assets/vendor/libs/select2/select2.scss',
    ])
@endsection

@section('vendor-script')
    @vite([
      'resources/assets/vendor/libs/@form-validation/popular.js',
      'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
      'resources/assets/vendor/libs/@form-validation/auto-focus.js',
      'resources/assets/vendor/libs/jstree/jstree.js',
      'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
      'resources/assets/vendor/libs/select2/select2.js',
    ])
@endsection

@section('page-script')
    @vite('resources/assets/js/customs/categories/list.js')
    @vite('resources/assets/js/customs/categories/store.js')
    @vite('resources/assets/js/customs/categories/update.js')
    @vite('resources/assets/js/customs/categories/delete.js')
@endsection

@section('content')

    <div class="row g-6 mb-6">
    </div>
    <!-- JSTree -->
    <div class="row">
        <!-- Custom Icons -->
        <div class="col-md-12 col-12">
            <div class="card card-header mb-6 col-12">
                <div
                    class="row dt-action-buttons text-start d-flex align-items-center justify-content-between justify-content-center flex-wrap">
                    <div class="col-6">
                        <h5 class="m-0">{{__('Categories')}}</h5>
                    </div>
                    <div class="col-6 bdt-buttons btn-group flex-wrap w-25">
                        @if($user->checkHasOrganizationPermission(BaseEnum::CATEGORY['CREATE']))
                        <button
                            class="btn btn-secondary btn-primary waves-effect waves-light rounded border-left-0 border-right-0"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasAddOrganization">
                            <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                                class="d-none d-sm-inline-block">{{__('add_new_category')}}</span>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div>

        </div>
        <div class="col-md-6 col-12">
            <div class="card mb-6 h-100">
                <div class="card-header align-items-center">
                    <h5 class="card-action-title mb-0">{{__('categories_tree')}}</h5>
                </div>
                <div class="card-body">
                    <div id="jstree-ajax"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card mb-6 h-100">
                <div class="card-header align-items-center">
                    <h5 class="card-action-title mb-0">{{__('categories_detail')}}</h5>
                </div>
                <div class="card-body card-detail-category invisible">
                    @include('customs/categories/canvas-edit')
                </div>
            </div>
        </div>
        @include('customs/categories/canvas-add')
    </div>
@endsection
