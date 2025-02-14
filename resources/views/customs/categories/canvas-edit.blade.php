@php use App\Enums\BaseEnum; @endphp
<div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
    <form class="add-new-user pt-0" id="editOrganizationFom"
          method="POST">
        @csrf
        @method("PUT")
        <div class="mb-6">
            <label class="form-label" for="name">{{__('name')}}</label>
            <input type="text" class="form-control" id="name" placeholder="ABC" name="name"
                   aria-label="name"/>
        </div>
        <div class="mb-6">
            <label class="form-label" for="updateParentId">{{__('category_parent')}}</label>
            <select id="updateParentId" class="form-select update-category-parent" name="parent_id">
                <option value="">{{__('organization_no_selected')}}</option>
            </select>
        </div>
        <div class="mb-6">
            <label for="updateOrganizations" class="form-label">{{ __('organization') }}</label>
            <select id="updateOrganizations" class="updateOrganizations form-select" name="organizations[]">
                @forelse($organizations as $organization)
                    @include('content.apps.partials.organization_option', [
                        'organization' => $organization,
                        'prefix' => '-',
                        'is_check' => null
                    ])
                @empty
                    <option disabled>{{ __('no_data') }}</option>
                @endforelse
            </select>
        </div>
        @if($user->hasOrganizationPermission(BaseEnum::CATEGORY['UPDATE'], session('organization_id')))
            <button type="submit" id="submitEditOrganization"
                    class="btn btn-primary me-3 data-submit">{{__('update')}}</button>
        @endif
        @if($user->hasOrganizationPermission(BaseEnum::CATEGORY['DELETE'], session('organization_id')))
            <button type="button" id="deleteOrganization" class="btn btn-label-danger">{{__('delete')}}</button>
        @endif
    </form>
</div>
