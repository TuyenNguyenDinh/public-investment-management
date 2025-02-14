<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-simple modal-dialog-centered modal-add-new-role">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('close') }}"></button>
                <div class="text-center mb-6">
                    <h4 class="role-title mb-2">{{ __('add_new_role') }}</h4>
                    <p>{{ __('set_role_permissions') }}</p>
                </div>
                <!-- Add role form -->
                <form id="addRoleForm" class="row g-6" action="#" method="POST">
                    @csrf
                    <div class="col-12">
                        <label class="form-label" for="modalRoleName">{{ __('role_name') }}</label>
                        <input type="text" id="modalRoleName" name="name" class="form-control"
                               placeholder="{{ __('enter_role_name') }}" tabindex="-1"/>
                    </div>
                    <div class="col-12">
                        <label for="createOrganizations" class="form-label">{{ __('organization') }}</label>
                        <select id="createOrganizations" class="createOrganizations form-select" name="organizations[]">
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
                    <div class="col-12">
                        <h5 class="mb-6">{{ __('role_permissions') }}</h5>
                        <!-- Permission table -->
                        <div class="table-responsive">
                            <table class="table table-flush-spacing">
                                <tbody>
                                <tr>
                                    <td class="text-nowrap fw-medium text-heading">{{ __('check_all') }} <i
                                            class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="{{ __('check_all_tooltip') }}"></i></td>
                                    <td>
                                        <div class="d-flex justify-content-end">
                                            <div class="form-check mb-0" style="width: 160px;">
                                                <input class="form-check-input" type="checkbox" id="selectAll"/>
                                                <label class="form-check-label" for="selectAll">
                                                    {{ __('select_all') }}
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @foreach($permissionList as $i => $permission)
                                    <tr>
                                        <td class="text-nowrap fw-medium text-heading">{{ $permission->name }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap justify-content-start">
                                                @forelse($permission?->childPermission as $child)
                                                    <div class="form-check mb-0" style="width: 150px;">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="permissions-{{$child->id}}"
                                                               name="permissions[{{$child->id}}]"
                                                               value="{{ $child->name }}"
                                                               data-permission="{{ $permission->id }}" />
                                                        <label class="form-check-label" for="permissions-{{$child->id}}">
                                                            {{$child->name}}
                                                        </label>
                                                    </div>
                                                @empty
                                                @endforelse
                                                <div class="form-check mb-0" style="width: 150px;">
                                                    <input class="form-check-input check-all" type="checkbox"
                                                           id="checkAll-{{$i}}"/>
                                                    <label class="form-check-label" for="checkAll-{{$i}}">
                                                        {{ __('select_all') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Permission table -->
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" id="submitAddRole" class="btn btn-primary me-3">{{ __('submit') }}</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="{{ __('cancel') }}">
                            {{ __('cancel') }}
                        </button>
                    </div>
                </form>
                <!--/ Add role form -->
            </div>
        </div>
    </div>
</div>
<!--/ Add Role Modal -->
