'use strict';

import {clearValidationOnChange, CSRF_TOKEN} from "../../config.js";

$(function () {
    const formProfile = document.querySelector('#form_profile');
    const updateProfileBtn = document.querySelector('#update_profile');
    // Form validation for Add new record
    const fv = FormValidation.formValidation(formProfile, {
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: translations.enter_full_name
                    },
                    stringLength: {
                        max: 255,
                        message: translations.full_name_length
                    },
                    remote: {
                        message: ''
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: translations.enter_email_required
                    },
                    stringLength: {
                        max: 255,
                        message: translations.email_length
                    },
                    emailAddress: {
                        multiple: false,
                        message: translations.invalid_email,
                    },
                    remote: {
                        message: ''
                    }
                }
            },
            'organizations[]': {
                validators: {
                    notEmpty: {
                        message: translations.enter_organization
                    },
                    remote: {
                        message: ''
                    }
                }
            },
            date_of_birth: {
                validators: {
                    notEmpty: {
                        message: translations.enter_date_of_birth
                    },
                    remote: {
                        message: ''
                    }
                }
            },
            citizen_identification: {
                validators: {
                    notEmpty: {
                        message: translations.enter_citizen_identification
                    },
                    remote: {
                        message: ''
                    }
                }
            },
            phone_number: {
                validators: {
                    notEmpty: {
                        message: translations.enter_phone_number
                    },
                    remote: {
                        message: ''
                    }
                }
            },
            hometown: {
                validators: {
                    notEmpty: {
                        message: translations.enter_hometown
                    },
                    remote: {
                        message: ''
                    },
                    stringLength: {
                        max: 255,
                        message: translations.hometown_length
                    },
                }
            },
            permanent_address: {
                validators: {
                    notEmpty: {
                        message: translations.enter_permanent_address
                    },
                    remote: {
                        message: ''
                    },
                    stringLength: {
                        max: 255,
                        message: translations.permanent_address_length
                    },
                }
            },
            temporary_address: {
                validators: {
                    notEmpty: {
                        message: translations.enter_temporary_address
                    },
                    remote: {
                        message: ''
                    }
                },
                stringLength: {
                    max: 255,
                    message: translations.temporary_address_length
                },
            },
            education_level: {
                validators: {
                    remote: {
                        message: ''
                    }
                }
            },
            health_status: {
                validators: {
                    remote: {
                        message: ''
                    }
                }
            },
            height: {
                validators: {
                    remote: {
                        message: ''
                    }
                }
            },
            weight: {
                validators: {
                    remote: {
                        message: ''
                    }
                }
            },
            password: {
                validators: {
                    stringLength: {
                        min: 6,
                        message: translations.password_length
                    },
                    remote: {
                        message: ''
                    },
                }
            },
            confirm_password: {
                validators: {
                    identical: {
                        compare: function () {
                            return formProfile.querySelector('[name="password"]').value;
                        },
                        message: translations.password_mismatch
                    },
                    stringLength: {
                        min: 6,
                        message: translations.password_length
                    },
                    remote: {
                        message: ''
                    },
                }
            },
        }, plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
                eleValidClass: '',
                rowSelector: '.col-md-6'
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            // Submit the form when all fields are valid
            // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
            autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
            instance.on('plugins.message.placed', function (e) {
                if (e.element.parentElement.classList.contains('input-group')) {
                    e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
                }
            });
        }
    });

    // CleaveJS validation

    const phoneNumber = document.querySelector('#phone_number');
    // Phone Mask
    if (phoneNumber) {
        new Cleave(phoneNumber, {
            phone: true,
            phoneRegionCode: 'VN'
        });
    }

    function collectRelativesData() {
        let relatives = [];

        // Lấy tất cả các phần tử data-repeater-item (các dòng relatives đã tạo)
        $('.form-repeater [data-repeater-item]').each(function (index, item) {
            let relative = {
                name: $(item).find('input[id^="relative_"][id$="_0"]').val(), // Full name
                relationship: $(item).find('input[id^="relative_"][id$="_1"]').val(), // Relationship
                address: $(item).find('input[id^="relative_"][id$="_2"]').val(), // Address
                phone_number: $(item).find('input[id^="relative_"][id$="_3"]').val() // Phone Number
            };

            relatives.push(relative);
        });

        return relatives;
    }

    function collectEducationsData() {
        let educationsData = [];

        $('.education-repeater [data-repeater-item]').each(function (index, element) {
            let education = {
                school_name: $(element).find('input[id^="education_"][id$="_0"]').val(),
                education_level: $(element).find('input[id^="education_"][id$="_1"]').val(),
                education_type: $(element).find('input[id^="education_"][id$="_2"]').val(),
                rank_level: $(element).find('input[id^="education_"][id$="_3"]').val(),
                major: $(element).find('input[id^="education_"][id$="_4"]').val(),
                graduation_date: $(element).find('input[id^="education_"][id$="_5"]').val(),
                certificate_image: $(element).find('input[id^="education_"][id$="_6"]')[0].files[0] ?? null
            };

            let id = $(`input[id="education_id_${index}"]`).val();
            if (id) {
                education.id = id
            }
            educationsData.push(education);
        });

        return educationsData;
    }

    function collectWorkHistoriesData() {
        let workHistoriesData = [];

        $('.work-history-repeater [data-repeater-item]').each(function (index, element) {
            let workHistory = {
                start_date: $(element).find('input[id^="work_history"][id$="_0"]').val(),
                end_date: $(element).find('input[id^="work_history"][id$="_1"]').val(),
                organization_name: $(element).find('input[id^="work_history"][id$="_2"]').val(),
                organization_phone_number: $(element).find('input[id^="work_history"][id$="_3"]').val(),
            };

            workHistoriesData.push(workHistory);
        });

        return workHistoriesData;
    }

    updateProfileBtn.addEventListener('click', function (e) {
        e.preventDefault();

        fv.validate().then(function (status) {
            if (status === 'Valid') {
                let relativesData = collectRelativesData();
                let educationsData = collectEducationsData();
                let workHistoriesData = collectWorkHistoriesData();
                let formData = new FormData();

                formData.append('_method', 'PATCH');
                formData.append('name', $('#name').val());
                formData.append('email', $('#email').val());
                formData.append('password', $('#password').val());
                formData.append('confirm_password', $('#confirm_password').val());
                formData.append('sex', $('#sex').val());
                formData.append('date_of_birth', $('#date_of_birth').val());
                formData.append('phone_number', $('#phone_number').val());
                formData.append('hometown', $('#hometown').val());
                formData.append('permanent_address', $('#permanent_address').val());
                formData.append('temporary_address', $('#temporary_address').val());
                formData.append('education_level', $('#education_level').val());
                formData.append('health_status', $('#health_status').val());
                formData.append('height', $('#height').val());
                formData.append('weight', $('#weight').val());
                formData.append('citizen_identification', $('#citizen_identification').val());

                const selectedOrganizations = $('#organization:not([disabled])').val();
                if (selectedOrganizations) {
                    selectedOrganizations.forEach((orgId, index) => {
                        formData.append(`organizations[${index}]`, orgId);
                    });
                }

                if (relativesData) {
                    relativesData.forEach((data, index) => {
                        formData.append(`relatives[${index}][name]`, data.name);
                        formData.append(`relatives[${index}][relationship]`, data.relationship);
                        formData.append(`relatives[${index}][address]`, data.address);
                        formData.append(`relatives[${index}][phone_number]`, data.phone_number);
                    })
                }

                if (educationsData && educationsData.length > 0) {
                    educationsData.forEach((education, index) => {
                        if (education.id) {
                            formData.append(`educations[${index}][id]`, education.id);
                        }

                        formData.append(`educations[${index}][school_name]`, education.school_name);
                        formData.append(`educations[${index}][education_level]`, education.education_level);
                        formData.append(`educations[${index}][education_type]`, education.education_type);
                        formData.append(`educations[${index}][rank_level]`, education.rank_level);
                        formData.append(`educations[${index}][major]`, education.major);
                        formData.append(`educations[${index}][graduation_date]`, education.graduation_date);

                        if (education.certificate_image instanceof File) {
                            formData.append(`educations[${index}][certificate_image]`, education.certificate_image);
                        }
                    });
                }

                if (workHistoriesData) {
                    workHistoriesData.forEach((data, index) => {
                        formData.append(`work_histories[${index}][start_date]`, data.start_date);
                        formData.append(`work_histories[${index}][end_date]`, data.end_date);
                        formData.append(`work_histories[${index}][organization_name]`, data.organization_name);
                        formData.append(`work_histories[${index}][organization_phone_number]`, data.organization_phone_number);
                    })
                }

                let avatarFile = $('.user_input')[0].files[0];
                let frontCitizen = $('.front_citizen_input')[0].files[0];
                let backCitizen = $('.back_citizen_input')[0].files[0];
                if (avatarFile) {
                    formData.append('avatar', avatarFile);
                }
                if (frontCitizen) {
                    formData.append('front_citizen_identification_img', frontCitizen);
                }
                if (backCitizen) {
                    formData.append('back_citizen_identification_img', backCitizen);
                }

                // Đối với input file (avatar)
                fetch(`/api/v1/profiles`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.code === 200) {
                            Swal.fire({
                                icon: 'success',
                                title: translations.profile_updated_successfully,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function () {
                                window.location.reload()
                            });
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
                        console.log(error)
                    });
            }
        });
    });

    $('#user_avatar').on('change', function () {
        const file = this.files[0];
        const maxSize = 2 * 1024 * 1024; // 2MB
        const $wrapper = $(this).closest('.button-wrapper');
        const submitButton = $('#update_profile');

        $wrapper.find('.fv-plugins-message-container').remove();

        if (file && file.size > maxSize) {
            const errorHtml = `
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                    <div data-field="user_avatar" data-validator="">${translations.avatar_do_not_exceed}</div>
                </div>
            `;

            $wrapper.find('div:last').after(errorHtml);

            $(this).val('');
            submitButton.prop('disabled', true);
        } else {
            submitButton.prop('disabled', false);
        }
    });
    clearValidationOnChange(formProfile, fv);
});
