@php use App\Enums\BaseEnum; @endphp
<div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
    <form class="add-new-user pt-0" id="editMenuForm" method="POST">
        @csrf
        @method("PUT")
        <div class="mb-6">
            <label class="form-label" for="name">{{ __('menu_name') }}</label>
            <input type="text" class="form-control" id="name" placeholder="{{ __('menu_name_placeholder') }}" name="name" aria-label="name"/>
        </div>
        <div class="form-check d-flex align-items-center gap-1 mb-4">
            <input class="form-check-input" type="checkbox" id="group-menu-flag" name="group-menu-flag"/>
            <label for="group-menu-flag" class="form-check-label">
                <span class="h6">{{ __('menu_is_group') }}</span>
            </label>
        </div>
        <div class="mb-6">
            <label class="form-label" for="parent-update-id">{{ __('menu_parent') }}</label>
            <select id="parent-update-id" class="form-select" name="parent_id">
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
        @if($user->hasOrganizationPermission(BaseEnum::MENUS['UPDATE'], session('organization_id')))
            <button type="submit" id="submitEditMenu" class="btn btn-primary me-3 data-submit">{{ __('menu_update') }}</button>
        @endif
        @if($user->hasOrganizationPermission(BaseEnum::MENUS['DELETE'], session('organization_id')))
            <button type="button" id="deleteMenu" class="btn btn-label-danger">{{ __('menu_delete') }}</button>
        @endif
    </form>
</div>
