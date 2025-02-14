<!-- Offcanvas to add new user -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
    <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddUserLabel" class="offcanvas-title">{{ __('add_user') }}</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
        <form class="add-new-user pt-0" id="addNewUserForm" action="#">
            @csrf
            <div class="mb-6">
                <label class="form-label" for="fullNameAdd">{{ __('full_name') }}</label>
                <input type="text" class="form-control" id="fullNameAdd" placeholder="{{ __('enter_full_name') }}"
                       name="name"
                       aria-label="{{ __('enter_full_name') }}"/>
            </div>
            <div class="mb-6">
                <label class="form-label" for="emailAdd">{{ __('email') }}</label>
                <input type="text" id="emailAdd" class="form-control" placeholder="{{ __('enter_email') }}"
                       aria-label="{{ __('enter_email') }}" name="email"/>
            </div>
            <div class="mb-6">
                <label for="createOrganizations" class="form-label">{{ __('organization') }}</label>
                <select id="createOrganizations" class="createOrganizations form-select" multiple
                        name="organizations[]">
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
                <label for="createRole" class="form-label">{{ __('user_role') }}</label>
                <select id="createRole" class="createRole form-select" multiple name="role[]">
                    <option disabled>{{ __('no_data') }}</option>
                </select>
            </div>
            <div class="mb-6">
                <label for="assignMenu" class="form-label">{{ __('Menu') }}</label>
                <div id="jstree-menu"></div>
            </div>
            <div class="mb-6">
                <label class="form-label" for="passwordAdd">{{ __('password') }}</label>
                <input type="password" id="passwordAdd" class="form-control" placeholder="*******" aria-label="*******"
                       name="password"/>
            </div>
            <div class="mb-6">
                <label class="form-label" for="confirmPasswordAdd">{{ __('confirm_password') }}</label>
                <input type="password" id="confirmPasswordAdd" class="form-control" placeholder="*******"
                       aria-label="*******" name="confirm_password"/>
            </div>
            <button type="submit" id="submitAddUser"
                    class="btn btn-primary me-3 data-submit">{{ __('submit') }}</button>
            <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">{{ __('cancel') }}</button>
        </form>
    </div>
</div>
