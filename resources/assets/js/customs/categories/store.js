'use strict';

$(function () {
    const addNewOrganizationForm = document.getElementById('addOrganizationFom');
    const submitButton = document.getElementById('submitAddOrganization');
    const fv = FormValidation.formValidation(addNewOrganizationForm, {
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
            description: {
                validators: {
                    stringLength: {
                        max: 1000,
                        message: translations.category_description_length
                    },
                    remote: {
                        message: ''
                    },
                }
            },
            phone_number: {
                validators: {
                    notEmpty: {
                        message: translations.phone_number_required
                    },
                    stringLength: {
                        max: 15,
                        message: translations.phone_number_length
                    },
                    remote: {
                        message: ''
                    },
                }
            },
            address: {
                validators: {
                    stringLength: {
                        max: 95,
                        message: translations.address_length
                    },
                    remote: {
                        message: ''
                    },
                }
            },
            tax_code: {
                validators: {
                    stringLength: {
                        max: 255,
                        message: translations.tax_code_length
                    },
                    remote: {
                        message: ''
                    },
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

    submitButton.addEventListener('click', function (e) {
        e.preventDefault();

        fv.validate().then(function (status) {
            if (status === 'Valid') {
                fetch('/api/v1/categories', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        name: $(addNewOrganizationForm).find('input#name').val(),
                        parent_id: $(addNewOrganizationForm).find('select#createParentId').val(),
                        organizations: $(addNewOrganizationForm).find('select#organizations').val(),
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
                                console.log(data.message);
                            }
                        }
                    })
                    .catch((error) => {
                        toastr.error(translations.server_error);
                        console.error(error.message);
                    });
            }
        });
    });
});
