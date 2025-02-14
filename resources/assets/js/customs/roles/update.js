/**
 * Edit role Modal JS
 */

'use strict';

import {CSRF_TOKEN} from "../../config.js";

$(function () {
    const editRoleForm = document.getElementById('editRoleForm');
    const submitButton = document.getElementById('submitEditRole');

    const fv = FormValidation.formValidation(editRoleForm, {
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: translations.enter_role_name,
                    },
                    stringLength: {
                        max: 50,
                        message: translations.role_name_length,
                    },
                    remote: {
                        message: ''
                    }
                }
            }
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
                // Use this for enabling/changing valid/invalid class
                // eleInvalidClass: '',
                eleValidClass: '',
                rowSelector: '.col-12'
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            // Submit the form when all fields are valid
            autoFocus: new FormValidation.plugins.AutoFocus()
        }
    });

    const generatePermissionObject = () => {
        const permissionObject = {};
        const permissions = $('#editRoleForm').find('input[name^=permissions]:checked');
        permissions.each(function () {
            let id = $(this).attr('id');
            let index = id.split('-')[2];
            permissionObject[index] = $(this).val();
        });

        return permissionObject;
    };

    submitButton.addEventListener('click', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        fv.validate().then(function (status) {
            if (status === 'Valid') {
                const perObj = generatePermissionObject();
                fetch(`/api/v1/roles/${id}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({
                        name: $(editRoleForm).find('input#modalRoleName').val(),
                        organizations: $(editRoleForm).find('select#updateOrganizations').val(),
                        permissions: perObj,
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.code === 200) {
                            window.location.reload();
                        } else {
                            if (data.errors) {
                                for (const field in data.errors) {
                                    fv
                                        // Update the message option
                                        .updateValidatorOption(
                                            field, 'remote', 'message', data.errors[field][0]
                                        )
                                        // Set the field as invalid
                                        .updateFieldStatus(field, 'Invalid', 'remote');
                                }
                            } else {
                                toastr.error(translations.server_error);
                                console.error(data.message);
                            }
                        }
                    })
                    .catch(error => {
                        toastr.error(translations.server_error);
                        console.error('Error:', error);
                    });
            }
        });
    });
});
