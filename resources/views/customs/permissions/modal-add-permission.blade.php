<!-- Add Permission Modal -->
<div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-simple">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-6">
                    <h4 class="mb-2">{{ __('add_new_permission') }}</h4>
                    <p>{{ __('permissions_description') }}</p>
                </div>
                <form id="addPermissionForm"
                      class="row">
                    @csrf
                    <div class="col-12 mb-4">
                        <label class="form-label" for="modalPermissionName">{{ __('permission_name') }}</label>
                        <input type="text" id="modalPermissionName" name="modalPermissionName" class="form-control"
                               placeholder="{{ __('permission_name_placeholder') }}" autofocus/>
                        @error('modalPermissionName')
                        <span class="invalid-feedback" role="alert">
                            <span class="fw-medium">{{ $message }}</span>
                        </span>
                        @enderror
                    </div>
                    <div class="col-12 text-center demo-vertical-spacing">
                        <button type="submit" id="submitAddPermission" class="btn btn-primary me-4">{{ __('create_permission') }}</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
                            {{ __('discard') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ Add Permission Modal -->
