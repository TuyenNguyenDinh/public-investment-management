<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddMenu" aria-labelledby="offcanvasAddMenuLabel">
    <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddMenuLabel" class="offcanvas-title">{{ __('menu_add') }}</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
        <div class="pb-4">
            <a href="javascript:void(0);" class="fw-medium" data-bs-toggle="modal" data-bs-target="#ruleMenu">{{ __('menu_rule_create') }}</a>
        </div>
        <form class="add-new-user pt-0" id="addMenuForm" action="{{ route('api.menus.store') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="form-label" for="name">{{ __('menu_name') }}</label>
                <input type="text" class="form-control" id="name" placeholder="{{ __('menu_name_placeholder') }}" name="name" aria-label="name"/>
            </div>
            <div class="form-check d-flex align-items-center gap-1 mb-4">
                <input class="form-check-input" type="checkbox" id="group-create-menu-flag" name="group-menu-flag"/>
                <label for="group-create-menu-flag" class="form-check-label">
                    <span class="h6">{{ __('menu_is_group') }}</span>
                </label>
            </div>
            <div class="mb-6">
                <label class="form-label" for="parent-id">{{ __('menu_parent') }}</label>
                <select id="parent-id" class="form-select" name="parent_id">
                    <option value="">{{ __('menu_no_selected') }}</option>
                </select>
            </div>
            <div class="mb-6">
                <label class="form-label" for="icon">{{ __('menu_icon') }}</label>
                <input type="text" class="form-control" id="icon" placeholder="{{ __('menu_icon_placeholder') }}" name="icon" aria-label="icon"/>
            </div>
            <div class="mb-6">
                <label class="form-label" for="slug">{{ __('menu_slug') }}</label>
                <input type="text" class="form-control" id="slug" placeholder="{{ __('menu_slug_placeholder') }}" name="slug" aria-label="slug"/>
            </div>
            <div class="mb-6">
                <label class="form-label" for="url">{{ __('menu_url') }}</label>
                <input type="text" class="form-control" id="url" placeholder="{{ __('menu_url_placeholder') }}" name="url" aria-label="url"/>
            </div>
            <button type="submit" id="submitAddMenu" class="btn btn-primary me-3 data-submit">{{ __('submit') }}</button>
            <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">{{ __('cancel') }}</button>
        </form>
    </div>
</div>
