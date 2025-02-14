/**
 * Page Create Post
 */

'use strict';

import {
    clearValidationForCKEditor,
    clearValidationOnChange,
    CSRF_TOKEN,
    fetchCategories,
    translationNoResultSelect2
} from "../../config.js";

$(async function () {
    $('a[href$="/posts"]').parent('.menu-item').addClass('active');
    const formCreatePost = document.getElementById('formCreatePost');
    const organizationMultipleSelect = $('.formValidationOrganization');
    const formValidationCategory = $('.formValidationCategory');
    let contentPostDuplicate = ''
    let content;
    const btnSaveDuplicate = $('#save-duplicate')
    const btnSaveExit = $('#save-exit')
    const btnSave = $('#save')
    const inputAction = $('input[name="action"]');
    const message = $('#message')

    const fv = FormValidation.formValidation(formCreatePost, {
        fields: {
            title: {
                validators: {
                    notEmpty: {
                        message: translations.title_post_required
                    },
                    stringLength: {
                        max: 255,
                        message: translations.title_post_max_length
                    },
                }
            },
            thumbnail: {
                validators: {
                    remote: {
                        message: ''
                    }
                }
            },
            'categories[]': {
                validators: {
                    notEmpty: {
                        message: translations.categories_select_required
                    },
                    remote: {
                        message: ''
                    }
                }
            },
            'organizations[]': {
                validators: {
                    notEmpty: {
                        message: translations.organizations_select_required
                    },
                    remote: {
                        message: ''
                    }
                }
            },
            scheduled_date: {
                validators: {
                    remote: {
                        message: ''
                    }
                }
            },
            content: {
                validators: {
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
                rowSelector: function (field) {
                    // field is the field name & ele is the field element
                    switch (field) {
                        case 'title':
                            // case 'thumbnail':
                            // case 'content':
                            return '.col-md-12'
                        default:
                            return '.row';
                    }
                }
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            // Submit the form when all fields are valid
            // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
            autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
            instance.on('plugins.message.placed', function (e) {
                //* Move the error message out of the `input-group` element
                if (e.element.parentElement.classList.contains('input-group')) {
                    // `e.field`: The field name
                    // `e.messageElement`: The message element
                    // `e.element`: The field element
                    e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
                }
                //* Move the error message out of the `row` element for custom-options
                if (e.element.parentElement.parentElement.classList.contains('custom-option')) {
                    e.element.closest('.row').insertAdjacentElement('afterend', e.messageElement);
                }
            });
        }
    });
    $('#formValidationSchedule').flatpickr({
        enableTime: true,
        autoclose: true,
        dateFormat: locale === 'vn' ? 'd-m-Y H:i' : 'Y-m-d H:i'
    });
    
    const loadSelectParentOrganizationTraverse = (treeData, selectParent, prefix = '-') => {
        let selectAddParent = $(selectParent);
        $.each(treeData, function (index, data) {
            selectAddParent.append(`<option value='${data.id}'>${prefix} ${data.text}</option>`)
            loadSelectParentOrganizationTraverse(data.children, selectParent, `${prefix}-`);
        })
    }
    await loadSelectParentOrganizationTraverse(organizations, '#formValidationOrganization');

    const treeData = await fetchCategories(true);
    await loadSelectParentOrganizationTraverse(treeData, '#formValidationCategory')
    
    organizationMultipleSelect.wrap('<div class="position-relative"></div>').select2({
        placeholder: translations.select_organization,
        dropdownParent: organizationMultipleSelect.parent(),
        ...translationNoResultSelect2().config
    });
    formValidationCategory.wrap('<div class="position-relative"></div>').select2({
        placeholder: translations.select_category,
        dropdownParent: organizationMultipleSelect.parent(),
        ...translationNoResultSelect2().config
    });
    organizationMultipleSelect.val(organizations.filter(item => item.id == currentOrganizationId).map(item => item.id)).trigger('change');

    const loadDataDuplicatePost = () => {
        const urlParams = new URLSearchParams(window.location.search);
        const duplicateParam = urlParams.get('duplicate') === 'true';
        const duplicatePostStorage = localStorage.getItem('duplicate_post')
            ? JSON.parse(localStorage.getItem('duplicate_post'))
            : null
        const isDuplicate = duplicateParam && duplicatePostStorage !== null

        if (isDuplicate) {
            const categories = duplicatePostStorage.categories ?? []
            const organizations = duplicatePostStorage.organizations ?? []

            $('input[name="title"]').val(duplicatePostStorage.title)
            $('textarea[name="content"]').val(duplicatePostStorage.content)
            $('input[name="scheduled_date"]').val(duplicatePostStorage.scheduled_date)
            contentPostDuplicate = duplicatePostStorage.content
            organizationMultipleSelect.val(organizations.map(item => item.id)).trigger('change');
            formValidationCategory.val(categories.map(item => item.id)).trigger('change');

            localStorage.removeItem('duplicate_post')
            $('#is_duplicate').val(isDuplicate ? 1 : 0)
        }
    }

    const loadCKEditor = () => {
        CKEDITOR.replace('content', {
            filebrowserBrowseUrl: routeCKFinderBrowser,
            height: '1000px'
        })
        content = CKEDITOR.instances.content
        if (contentPostDuplicate.length > 0) {
            CKEDITOR.instances.content.setData(contentPostDuplicate)
        }
    }

    loadCKEditor()
    loadDataDuplicatePost()

    const createPost = async () => {
        const serializeArray = jQuery(formCreatePost).serializeArray();
        serializeArray.push({name: 'content', value: content.getData()})
        $('input[type="file"]').each(function () {
            const fileInput = $(this);
            const files = fileInput[0].files;
            if (files.length) {
                $.each(files, function (index, file) {
                    serializeArray.push({name: fileInput.attr('name'), value: file});
                });
            }
        });
        const keyValueObject = serializeArray.reduce((obj, item) => {
            obj[item.name] = item.value;
            return obj;
        }, {});
        const formData = new FormData();
        for (const key in keyValueObject) {
            formData.append(key, keyValueObject[key]);
        }

        try {
            const response = await fetch('/api/v1/posts/store', {
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: formData
            });
            if (response.status === 200) {
                toastr.success(translations.create_post_success)
            } else if (response.status !== 422) {
                toastr.error(translations.create_post_error)
            }
            
            return await response.json()
        } catch (error) {
            console.log(error)
            toastr.error('Đã có lỗi xảy ra');
            return null;
        }
    }

    btnSaveExit.on('click', function (e) {
        e.preventDefault();
        inputAction.val('save_exit')
        fv.validate().then(async function (status) {
            if (status === 'Valid') {
                jQuery(formCreatePost).trigger('submit')
            }
        })
    });

    btnSave.on('click', function (e) {
        e.preventDefault();
        inputAction.val('save')
        fv.validate().then(async function (status) {
            if (status === 'Valid') {
                jQuery(formCreatePost).trigger('submit')
            }
        })
    });

    btnSaveDuplicate.on('click', function (e) {
        e.preventDefault();
        message.removeClass('alert-success').removeClass('alert-danger').empty().hide()
        fv.validate().then(async function (status) {
            if (status === 'Valid') {
                const res = await createPost();
                if (res.errors) {
                    Object.keys(res.errors).forEach(field => {
                        const errorElement = document.querySelector(`.fv-plugins-message-container div[data-field="${field}"]`);
                        if (errorElement) {
                            errorElement.remove();
                        }
                                        
                        fv.updateValidatorOption(field, 'remote', 'message', res.errors[field][0])
                            .updateFieldStatus(field, 'Invalid', 'remote');
                    });
                } else {
                    message.addClass('alert-success').html(translations.create_post_success).show()
                }
                window.scrollTo({top: 0, behavior: 'smooth'});
            }
        })
    });

    clearValidationOnChange(formCreatePost, fv);
    clearValidationForCKEditor(formCreatePost, fv);
});
