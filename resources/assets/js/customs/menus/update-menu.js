'use strict';

import {clearValidationOnChange, CSRF_TOKEN} from "../../config.js";

$(function () {
    const editMenuForm = document.getElementById('editMenuForm');
    const submitButton = document.getElementById('submitEditMenu');
    const groupFlagCheckbox = document.getElementById('group-menu-flag');
    const csrfToken = CSRF_TOKEN;
    const formFields = ['icon', 'slug', 'url', 'parent-update-id'];

    const fv = FormValidation.formValidation(editMenuForm, {
        fields: {
            name: {
                validators: {
                    notEmpty: {message: translations.menu_name_required},  // Use translations.tên_key
                    stringLength: {max: 50, message: translations.menu_name_length},  // Use translations.tên_key
                    remote: {message: ''},
                }
            },
            icon: {
                validators: {
                    notEmpty: {message: translations.menu_icon_required},  // Use translations.tên_key
                    stringLength: {max: 50, message: translations.menu_icon_length},  // Use translations.tên_key
                    remote: {message: ''},
                }
            },
            slug: {
                validators: {
                    notEmpty: {message: translations.menu_slug_required},  // Use translations.tên_key
                    stringLength: {max: 100, message: translations.menu_slug_length},  // Use translations.tên_key
                    remote: {message: ''},
                }
            },
            url: {
                validators: {
                    notEmpty: {message: translations.menu_url_required},  // Use translations.tên_key
                    stringLength: {max: 100, message: translations.menu_url_length},  // Use translations.tên_key
                    remote: {message: ''},
                }
            },
            parent_id: {
                validators: {
                    remote: {message: ''}
                }
            }
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
                eleValidClass: '',
                rowSelector: '.mb-6'
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            autoFocus: new FormValidation.plugins.AutoFocus(),
        }
    });

    const updateFormFieldState = (disable) => {
        formFields.forEach((field) => {
            const element = $(editMenuForm).find(`#${field}`);
            element.prop('disabled', disable);
            disable ? fv.disableValidator(field) : fv.enableValidator(field);
        });
    };

    const checkUpdateGroupMenuFlag = () => {
        updateFormFieldState(groupFlagCheckbox.checked);
    };
    
    if (submitButton) {
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();
            checkUpdateGroupMenuFlag();

            fv.validate().then(function (status) {
                if (status === 'Valid') {
                    const formData = {
                        name: $(editMenuForm).find('#name').val(),
                        icon: $(editMenuForm).find('#icon').val(),
                        slug: $(editMenuForm).find('#slug').val(),
                        url: $(editMenuForm).find('#url').val(),
                        parent_id: $(editMenuForm).find('#parent-update-id').val(),
                        group_menu_flag: groupFlagCheckbox.checked,
                    };

                    fetch(editMenuForm.getAttribute('action'), {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify(formData),
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.code === 200) {
                                window.location.reload();
                            } else if (data.errors) {
                                Object.keys(data.errors).forEach(field => {
                                    fv.updateValidatorOption(field, 'remote', 'message', data.errors[field][0])
                                        .updateFieldStatus(field, 'Invalid', 'remote');
                                });
                            } else {
                                console.log(data.message);
                                toastr.error(translations.error_occurred)
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error)
                            toastr.error(translations.error_occurred)
                        });
                }
            });
        });
    }
    
    checkUpdateGroupMenuFlag();

    groupFlagCheckbox.addEventListener('change', function () {
        checkUpdateGroupMenuFlag();
    });
    clearValidationOnChange(editMenuForm, fv);
});
