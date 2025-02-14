<!-- Offcanvas to edit user -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditUser" aria-labelledby="offcanvasEditUserLabel">
    <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasEditUserLabel" class="offcanvas-title">{{ __('edit_user') }}</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
        <form class="edit-user pt-0" id="editUserForm" action="" method="POST">
            @csrf
            @method("PUT")
            <div class="mb-6">
                <label class="form-label" for="fullnameEdit">{{ __('full_name') }}</label>
                <input type="text" class="form-control" id="fullnameEdit" placeholder="{{ __('enter_full_name') }}" name="name"
                       aria-label="{{ __('enter_full_name') }}"/>
            </div>
            <div class="mb-6">
                <label class="form-label" for="emailEdit">{{ __('email') }}</label>
                <input type="text" id="emailEdit" class="form-control" placeholder="{{ __('enter_email') }}"
                       aria-label="{{ __('enter_email') }}" name="email"/>
            </div>
            <div class="mb-6">
                <label for="updateOrganizations" class="form-label">{{ __('organization') }}</label>
                <select id="updateOrganizations" class="updateOrganizations form-select" multiple name="organizations[]">
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
            <div class="mb-6">
                <label for="updateRole" class="form-label">{{ __('user_role') }}</label>
                <select id="updateRole" class="updateRole form-select" multiple name="role[]">
                    <option disabled>{{ __('no_data') }}</option>
                </select>
            </div>
            <div class="mb-6">
                <label for="reAssignMenu" class="form-label">{{ __('Menu') }}</label>
                <div id="jstree-menu-update"></div>
            </div>
            <div class="mb-6">
                <label class="form-label" for="passwordEdit">{{ __('password') }}</label>
                <input type="password" id="passwordEdit" class="form-control" placeholder="*******" aria-label="*******"
                       name="password"/>
            </div>
            <div class="mb-6">
                <label class="form-label" for="confirmPasswordEdit">{{ __('confirm_password') }}</label>
                <input type="password" id="confirmPasswordEdit" class="form-control" placeholder="*******"
                       aria-label="*******" name="confirm_password"/>
            </div>
            <button type="submit" id="submitEditUser" class="btn btn-primary me-3 data-submit">{{ __('update') }}</button>
            <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">{{ __('cancel') }}</button>
        </form>
    </div>
</div>
