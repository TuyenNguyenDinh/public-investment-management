'use strict';

import {clearValidationOnChange, CSRF_TOKEN} from "../../config.js";

$(function () {
    const addNewMenuForm = document.getElementById('addMenuForm');
    const submitButton = document.getElementById('submitAddMenu');

    const fv = FormValidation.formValidation(addNewMenuForm, {
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: translations.menu_name_required  // Use translations.tên_key
                    },
                    stringLength: {
                        max: 50,
                        message: translations.menu_name_length // Use translations.tên_key
                    }
                }
            },
            icon: {
                validators: {
                    notEmpty: {
                        message: translations.menu_icon_required // Use translations.tên_key
                    },
                    stringLength: {
                        max: 50,
                        message: translations.menu_icon_length // Use translations.tên_key
                    }
                }
            },
            slug: {
                validators: {
                    notEmpty: {
                        message: translations.menu_slug_required // Use translations.tên_key
                    },
                    stringLength: {
                        max: 100,
                        message: translations.menu_slug_length // Use translations.tên_key
                    }
                }
            },
            url: {
                validators: {
                    notEmpty: {
                        message: translations.menu_url_required // Use translations.tên_key
                    },
                    stringLength: {
                        max: 100,
                        message: translations.menu_url_length // Use translations.tên_key
                    }
                }
            },
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

    // Handle form submission
    submitButton.addEventListener('click', function (e) {
        e.preventDefault();
        fv.validate().then(function (status) {
            if (status === 'Valid') {
                const menuData = {
                    name: addNewMenuForm.querySelector('#name').value,
                    icon: addNewMenuForm.querySelector('#icon').value,
                    slug: addNewMenuForm.querySelector('#slug').value,
                    url: addNewMenuForm.querySelector('#url').value,
                    parent_id: addNewMenuForm.querySelector('#parent-id').value,
                    group_menu_flag: addNewMenuForm.querySelector('#group-create-menu-flag').checked,
                };

                fetch('/api/v1/menus', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify(menuData)
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.code === 200) {
                            window.location.reload();
                        } else {
                            handleFormErrors(fv, data.errors);
                            toastr.error(translations.error_occurred)
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error(translations.error_occurred)
                    });
            }
        });
    });

    // Handle form validation errors
    const handleFormErrors = (fvInstance, errors) => {
        if (errors) {
            for (const field in errors) {
                fvInstance.updateValidatorOption(field, 'remote', 'message', errors[field][0])
                    .updateFieldStatus(field, 'Invalid', 'remote');
            }
        } else {
            console.log(translations.error_occurred);
        }
    };

    // Handle group menu flag behavior
    const checkGroupMenuFlag = () => {
        const addMenuForm = $('#addMenuForm');
        const icon = addMenuForm.find('#icon');
        const slug = addMenuForm.find('#slug');
        const url = addMenuForm.find('#url');
        const select = addMenuForm.find('#parent-id');

        const toggleFields = (disable) => {
            icon.prop('disabled', disable);
            slug.prop('disabled', disable);
            url.prop('disabled', disable);
            select.prop('disabled', disable);

            if (disable) {
                fv.disableValidator('icon').disableValidator('slug').disableValidator('url').disableValidator('parent_id');
            } else {
                fv.enableValidator('icon').enableValidator('slug').enableValidator('url').enableValidator('parent_id');
            }
        };

        addMenuForm.on('change', '#group-create-menu-flag', function () {
            toggleFields(this.checked);
        });
    };

    checkGroupMenuFlag();
    clearValidationOnChange(addNewMenuForm, fv);
});
