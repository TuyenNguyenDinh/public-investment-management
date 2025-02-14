'use strict';

import {clearValidationOnChange, CSRF_TOKEN} from "../../config.js";

$(function() {
    const editUserForm = document.getElementById('editUserForm');
    const submitButton = document.getElementById('submitEditUser');

    // Initialize form validation
    const initializeFormValidation = () => {
        return FormValidation.formValidation(editUserForm, {
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
            stringLength: {min: 6, message: translations.password_min_length},
            remote: {message: ''},
        }
    });

    // Validation for the confirm password field
    const getConfirmPasswordValidation = () => ({
        validators: {
            identical: {
                compare: () => editUserForm.querySelector('[name="password"]').value,
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
            name: editUserForm.querySelector('input#fullnameEdit').value,
            email: editUserForm.querySelector('input#emailEdit').value,
            role: Array.from(editUserForm.querySelectorAll('select#updateRole option:checked')).map(option => option.value),
            password: editUserForm.querySelector('input#passwordEdit').value,
            confirm_password: editUserForm.querySelector('input#confirmPasswordEdit').value,
            organizations: Array.from(editUserForm.querySelectorAll('select#updateOrganizations option:checked')).map(option => option.value),
        };
    };

    // Handle form submission
    const handleFormSubmit = async (e, fv) => {
        e.preventDefault();
        const id = submitButton.dataset.id;
        const status = await fv.validate();

        if (status === 'Valid') {
            const data = collectFormData();
            let nodeTree = $('#jstree-menu-update');
            const selectedNodes =  nodeTree.jstree('get_selected', true);
            const selectedIds = selectedNodes.map(node => node.id);
            const parentIds = selectedNodes
                .map(node => node.parent)
                .filter(parentId => parentId && parentId !== "#")
            data.menus = [...new Set([...selectedIds, ...parentIds])];
            await submitFormData(id, data);
        }
    };

    // Submit form data via fetch
    const submitFormData = async (id, data) => {
        try {
            const response = await fetch(`/api/v1/users/${id}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (response.ok) {
                window.location.reload();
            } else {
                handleErrors(result.errors);
            }
        } catch (error) {
            toastr.error(translations.server_error);
            console.error('Error:', error);
        }
    };

    // Handle validation errors
    const handleErrors = (errors) => {
        if (errors) {
            Object.entries(errors).forEach(([field, messages]) => {
                const fieldName = field === 'organizations' ? 'organizations[]' : field;

                fv.updateValidatorOption(fieldName, 'remote', 'message', messages[0]);
                fv.updateFieldStatus(fieldName, 'Invalid', 'remote');
            });
        } else {
            toastr.error(translations.server_error);
        }
    };

    // Initialize the form validation and set up event listener
    const fv = initializeFormValidation();
    submitButton.addEventListener('click', (e) => handleFormSubmit(e, fv));
    clearValidationOnChange(editUserForm, fv);
});
