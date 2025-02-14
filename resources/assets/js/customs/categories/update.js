'use strict';

import {clearValidationOnChange} from "../../config.js";

$(function () {
    const editOrganizationForm = document.getElementById('editOrganizationFom');
    const submitButton = document.getElementById('submitEditOrganization');
    const fv = FormValidation.formValidation(editOrganizationForm, {
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: translations.category_name_required
                    },
                    stringLength: {
                        max: 100,
                        message: translations.category_name_length
                    },
                    remote: {
                        message: ''
                    },
                }
            },
            parent_id: {
                validators: {
                    remote: {
                        message: ''
                    }
                }
            },
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
                // Use this for enabling/changing valid/invalid class
                eleValidClass: '',
                rowSelector: function () {
                    // field is the field name & ele is the field element
                    return '.mb-6';
                }
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            // Submit the form when all fields are valid
            autoFocus: new FormValidation.plugins.AutoFocus(),
        }
    });

    if (submitButton) {
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();
            let route = editOrganizationForm.getAttribute('action');
            fv.validate().then(function (status) {
                if (status === 'Valid') {
                    fetch(route, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            name: $(editOrganizationForm).find('input#name').val(),
                            parent_id: $(editOrganizationForm).find('select#updateParentId').val(),
                            organizations: $(editOrganizationForm).find('select#updateOrganizations').val(),
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.code === 200) {
                                window.location.reload()
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
                                    console.log(data.message)
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
    }

    clearValidationOnChange(editOrganizationForm, fv);
});
