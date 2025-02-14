/**
 * Add Permission Modal JS
 */

'use strict';

import {CSRF_TOKEN} from '../../config.js';

// Add permission form validation
$(function () {
    const storePermissionsForm = document.getElementById('addPermissionForm');
    const storePermissionButton = document.getElementById('submitAddPermission');
    const fv = FormValidation.formValidation(storePermissionsForm, {
        fields: {
            modalPermissionName: {
                validators: {
                    notEmpty: {
                        message: translations.enter_permission_name
                    },
                    stringLength: {
                        max: 100,
                        message: translations.permission_name_length
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

    storePermissionButton.addEventListener('click', function (e) {
        e.preventDefault();
        fv.validate().then(function (status) {
            if (status === 'Valid') {
                fetch(`/api/v1/permissions`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({
                        modalPermissionName: $(storePermissionsForm).find('input#modalPermissionName').val(),
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
                                toastr.error(translations.error_occurred);
                            }
                        }
                    })
                    .catch(error => {
                        toastr.error(translations.error_occurred);
                        console.error('Error:', error);
                    });
            }
        });
    });

});
