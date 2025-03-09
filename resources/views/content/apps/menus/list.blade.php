@php use App\Enums\BaseEnum; $user = auth()->user(); @endphp
@extends('layouts/layoutMaster')

@section('title', __('menu_title'))

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
    @vite('resources/assets/js/customs/menus/list-menu.js')
    @vite('resources/assets/js/customs/menus/store-menu.js')
    @vite('resources/assets/js/customs/menus/update-menu.js')
    @vite('resources/assets/js/customs/menus/delete-menu.js')
    <script>
        window.menuPermission = {
            Access: false,
            Create: false,
            Update: false,
            Delete: false,
        }
        @foreach(BaseEnum::ROLE_ACTION as $role)
            @if($user->checkHasOrganizationPermission("$role Menus"))
            window.menuPermission['{{$role}}'] = true
        @endif
        @endforeach
    </script>
@endsection

@section('content')
    <div class="row g-6 mb-6">
    </div>
    <!-- JSTree -->
    <div class="row">
        <!-- Custom Icons -->
        <div class="col-md-12 col-12">
            <div class="card card-header mb-6 col-12">
                <div class="row dt-action-buttons text-start d-flex align-items-center justify-content-between justify-content-center flex-wrap">
                    <div class="col-6">
                        <h5 class="m-0">{{ __('menu_title') }}</h5>
                    </div>
                    <div class="col-6 bdt-buttons btn-group flex-wrap w-25">
                        @if($user->checkHasOrganizationPermission(BaseEnum::MENUS['CREATE']))
                            <button
                                class="btn btn-secondary btn-primary waves-effect waves-light rounded border-left-0 border-right-0"
                                data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasAddMenu">
                                <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                <span class="d-none d-sm-inline-block">{{ __('menu_add_new') }}</span>
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
                    <h5 class="card-action-title mb-0">{{ __('menu_tree_title') }}</h5>
                </div>
                <div class="card-body">
                    <div id="jstree-ajax"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card mb-6 h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-action-title mb-0">{{ __('menu_detail_title') }}</h5>
                    <a href="javascript:void(0);" class="fw-medium" data-bs-toggle="modal" data-bs-target="#ruleMenu">{{ __('menu_rule_create') }}</a>
                </div>
                <div class="card-body card-detail-menu invisible">
                    @include('customs/menus/canvas-edit-menu')
                </div>
            </div>
        </div>
        @include('customs/menus/canvas-add-menu')
        @include('customs/menus/modal-rule-menu')
    </div>
@endsection
