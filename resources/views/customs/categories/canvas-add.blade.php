<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddOrganization" aria-labelledby="offcanvasAddOrganizationLabel">
   <div class="offcanvas-header border-bottom">
      <h5 id="offcanvasAddOrganizationLabel" class="offcanvas-title">{{__('add_category')}}</h5>
       <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
   </div>
    <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
        <form class="add-new-user pt-0" id="addOrganizationFom" action="{{ route('api.categories.store') }}"
              method="POST">
            @csrf
            <div class="mb-6">
                <label class="form-label" for="name">{{__('name')}}</label>
                <input type="text" class="form-control" id="name" placeholder="ABC" name="name"
                       aria-label="name"/>
            </div>
            <div class="mb-6">
                <label class="form-label" for="createParentId">{{__('category_parent')}}</label>
                <select id="createParentId" class="form-select create-category-parent" name="parent_id">
                    <option value="">{{__('organization_no_selected')}}</option>
                </select>
            </div>
            <div class="mb-6">
                <label for="organizations" class="form-label">{{ __('organization') }}</label>
                <select id="organizations" class="organizations form-select" name="organizations[]">
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
            <button type="submit" id="submitAddOrganization"
                    class="btn btn-primary me-3 data-submit">{{__('submit')}}</button>
            <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">{{__('cancel')}}</button>
      </form>
   </div>
</div>
