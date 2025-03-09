@php use App\Enums\BaseEnum; @endphp
<div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
    <form class="add-new-user pt-0" id="editOrganizationFom" method="POST">
        @csrf
        @method("PUT")
        <div class="mb-6">
            <label class="form-label" for="name">{{ __('organization_name') }}</label>
            <input type="text" class="form-control" id="name" placeholder="{{ __('organization_name_placeholder') }}" name="name" aria-label="name"/>
        </div>
        <div class="mb-6">
            <label class="form-label" for="description">{{ __('organization_description') }}</label>
            <textarea id="description" class="form-control" placeholder="{{ __('organization_description_placeholder') }}" aria-label="description" name="description" style="resize: none"></textarea>
        </div>
        <div class="mb-6">
            <label class="form-label" for="parent-update-id">{{ __('organization_owner') }}</label>
            <select id="parent-update-id" class="form-select select_edit_organization" name="parent_id">
                <option value="">{{ __('organization_no_selected') }}</option>
            </select>
        </div>
        <div class="mb-6">
            <label class="form-label" for="phone-number">{{ __('organization_phone_number') }}</label>
            <input type="text" class="form-control" id="phone-number" placeholder="{{ __('organization_phone_number_placeholder') }}" name="phone_number" aria-label="phone_number"/>
        </div>
        <div class="mb-6">
            <label class="form-label" for="address">{{ __('organization_address') }}</label>
            <input type="text" class="form-control" id="address" placeholder="{{ __('organization_address_placeholder') }}" name="address" aria-label="address"/>
        </div>
        <div class="mb-6">
            <label class="form-label" for="tax-code">{{ __('organization_tax_code') }}</label>
            <input type="text" class="form-control" id="tax-code" placeholder="{{ __('organization_tax_code_placeholder') }}" name="tax_code" aria-label="tax_code"/>
        </div>
        @if($user->checkHasOrganizationPermission(BaseEnum::ORGANIZATIONS['UPDATE']))
            <button type="submit" id="submitEditOrganization" class="btn btn-primary me-3 data-submit">{{ __('organization_update') }}</button>
        @endif
        @if($user->checkHasOrganizationPermission(BaseEnum::ORGANIZATIONS['DELETE']))
            <button type="button" id="deleteOrganization" class="btn btn-label-danger">{{ __('organization_delete') }}</button>
        @endif
    </form>
</div>
