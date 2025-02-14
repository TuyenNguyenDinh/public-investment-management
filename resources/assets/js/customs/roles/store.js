/**
 * Add new role Modal JS
 */

'use strict';

import {CSRF_TOKEN} from "../../config.js";

$(function () {
    const addNewRoleForm = document.getElementById('addRoleForm');
    const submitButton = document.getElementById('submitAddRole');

    const fv = FormValidation.formValidation(addNewRoleForm, {
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: translations.enter_role_name
                    },
                    stringLength: {
                        max: 50,
                        message: translations.role_name_length
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
        const permissions = $('#addRoleForm').find('input[name^=permissions]:checked');
        permissions.each(function () {
            let id = $(this).attr('id');
            let index = id.split('-')[1];
            permissionObject[index] = $(this).val();
        });

        return permissionObject;
    };

    submitButton.addEventListener('click', function (e) {
        e.preventDefault();

        fv.validate().then(function (status) {
            if (status === 'Valid') {
                const perObj = generatePermissionObject();
                fetch('/api/v1/roles', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({
                        name: $(addNewRoleForm).find('input#modalRoleName').val(),
                        organizations: $(addNewRoleForm).find('select#createOrganizations').val(),
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
                                        .updateValidatorOption(
                                            field, 'remote', 'message', data.errors[field][0]
                                        )
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
