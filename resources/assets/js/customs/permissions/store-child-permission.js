'use strict';

$(function () {
    const addNewChildPermissionsForm = document.getElementById('addChildPermissionForm');
    const submitButton = document.getElementById('submitAddChildPermission');
    const fv = FormValidation.formValidation(addNewChildPermissionsForm, {
        fields: {
            name: {
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
                    },
                }
            }
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
                eleValidClass: '',
                rowSelector: '.col-12'
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            autoFocus: new FormValidation.plugins.AutoFocus()
        }
    });

    submitButton.addEventListener('click', function (e) {
        e.preventDefault();

        fv.validate().then(function (status) {
            if (status === 'Valid') {
                fetch('/api/v1/permissions/children', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        name: $(addNewChildPermissionsForm).find('input#name').val(),
                        parent_id: $(addNewChildPermissionsForm).find('input#parent-id').val(),
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
