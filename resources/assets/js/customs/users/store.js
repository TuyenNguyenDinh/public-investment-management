'use strict';

import {clearValidationOnChange, CSRF_TOKEN} from "../../config.js";

$(function () {
    const addNewUserForm = document.getElementById('addNewUserForm');
    const submitButton = document.getElementById('submitAddUser');

    // Initialize form validation
    const initializeFormValidation = () => {
        return FormValidation.formValidation(addNewUserForm, {
            fields: {
                name: getNameValidation(),
                email: getEmailValidation(),
                password: getPasswordValidation(),
                confirm_password: getConfirmPasswordValidation(),
                'role[]': getRoleValidation(),
                'organizations[]': getOrganizationsValidation(),
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: '',
                    rowSelector: () => '.mb-6',
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),
                autoFocus: new FormValidation.plugins.AutoFocus()
            }
        });
    };

    // Validation for the name field
    const getNameValidation = () => ({
        validators: {
            notEmpty: {message: translations.name_required},
            stringLength: {max: 50, message: translations.name_max_length},
            remote: {message: ''},
        }
    });

    // Validation for the email field
    const getEmailValidation = () => ({
        validators: {
            notEmpty: {message: translations.email_required},
            emailAddress: {message: translations.email_invalid},
            stringLength: {max: 50, message: translations.email_max_length},
            remote: {message: ''},
        }
    });

    // Validation for the password field
    const getPasswordValidation = () => ({
        validators: {
            notEmpty: {message: translations.password_required},
            stringLength: {min: 6, message: translations.password_min_length},
            remote: {message: ''},
        }
    });

    // Validation for the confirm password field
    const getConfirmPasswordValidation = () => ({
        validators: {
            notEmpty: {message: translations.confirm_password_required},
            identical: {
                compare: () => addNewUserForm.querySelector('[name="password"]').value,
                message: translations.confirm_password_mismatch
            },
            stringLength: {min: 6, message: translations.password_min_length},
            remote: {message: ''},
        }
    });

    // Validation for the role field
    const getRoleValidation = () => ({
        validators: {
            notEmpty: {message: translations.role_required},
            remote: {message: ''},
        }
    });

    // Validation for the organizations field
    const getOrganizationsValidation = () => ({
        validators: {
            notEmpty: {message: translations.organizations_required},
            remote: {message: ''},
        }
    });

    // Collect form data
    const collectFormData = () => {
        return {
            name: addNewUserForm.querySelector('input#fullNameAdd').value,
            email: addNewUserForm.querySelector('input#emailAdd').value,
            role: Array.from(addNewUserForm.querySelectorAll('select#createRole option:checked')).map(option => option.value),
            password: addNewUserForm.querySelector('input#passwordAdd').value,
            confirm_password: addNewUserForm.querySelector('input#confirmPasswordAdd').value,
            organizations: Array.from(addNewUserForm.querySelectorAll('select#createOrganizations option:checked')).map(option => option.value),
        };
    };

    // Handle form submission
    const handleFormSubmit = async (e, fv) => {
        e.preventDefault();
        const status = await fv.validate();

        if (status === 'Valid') {
            const data = collectFormData();
            let nodeTree = $('#jstree-menu');
            const selectedNodes =  nodeTree.jstree('get_selected', true);
            const selectedIds = selectedNodes.map(node => node.id);
            const parentIds = selectedNodes
                .map(node => node.parent)
                .filter(parentId => parentId && parentId !== "#")
            data.menus = [...new Set([...selectedIds, ...parentIds])];
            await submitFormData(data);
        }
    };

    // Submit form data via fetch
    const submitFormData = async (data) => {
        try {
            const response = await fetch('/api/v1/users', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            handleResponse(result);
        } catch (error) {
            toastr.error(translations.server_error);
            console.error('Error:', error);
        }
    };

    // Handle server response
    const handleResponse = (data) => {
        if (data.code === 200) {
            window.location.reload();
        } else {
            handleErrors(data.errors);
        }
    };

    // Handle validation errors
    const handleErrors = (errors) => {
        if (errors) {
            for (const field in errors) {
                const fieldName = field === 'organizations' ? 'organizations[]' : field;
                fv
                    .updateValidatorOption(fieldName, 'remote', 'message', errors[field][0])
                    .updateFieldStatus(fieldName, 'Invalid', 'remote');
            }
        } else {
            toastr.error(translations.server_error);
        }
    };

    // Initialize the form validation and set up event listener
    const fv = initializeFormValidation();
    submitButton.addEventListener('click', (e) => handleFormSubmit(e, fv));
    clearValidationOnChange(addNewUserForm, fv);
});
