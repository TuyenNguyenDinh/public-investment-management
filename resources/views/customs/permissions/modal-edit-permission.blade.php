<!-- Edit Permission Modal -->
<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-simple">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-6">
                    <h4 class="mb-2">{{ __('edit_permission') }}</h4>
                    <p>{{ __('edit_permission_description') }}</p>
                </div>
                <div class="alert alert-warning d-flex align-items-start" role="alert">
                    <span class="alert-icon me-4 rounded-2"><i class="ti ti-alert-triangle ti-md"></i></span>
                    <span>
            <span class="alert-heading mb-1 h5">{{ __('warning') }}</span><br>
            <span class="mb-0">{{ __('edit_permission_warning') }}</span>
          </span>
                </div>
                <form id="editPermissionForm" class="row pt-2">
                    @csrf
                    @method('PUT')
                    <div class="col-sm-12 mb-4">
                        <label class="form-label" for="editPermissionName">{{ __('permission_name') }}</label>
                        <input type="text" id="editPermissionName" name="editPermissionName" class="form-control"
                               placeholder="{{ __('permission_name_placeholder') }}" tabindex="-1"/>
                    </div>
                    <div class="col-12 text-center demo-vertical-spacing">
                        <button type="submit" id="submitUpdatePermission" class="btn btn-primary me-4">{{ __('update') }}</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
                            {{ __('discard') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ Edit Permission Modal -->
