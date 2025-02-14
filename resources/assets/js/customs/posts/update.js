/**
 * Page Create Post
 */

'use strict';

import {translationNoResultSelect2} from "../../config.js";

$(function () {
    $('a[href$="/posts"]').parent('.menu-item').addClass('active');
    const organizationMultipleSelect = $('.formValidationOrganization');
    const formValidationCategory = $('.formValidationCategory');
    const btnDeletePost = $('#deletePost')

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

    formValidationCategory.val(categories.map(item => item.id)).trigger('change');

    CKEDITOR.replace('content', {
        filebrowserBrowseUrl: routeCKFinderBrowser,
        height: '1000px'
    })

    $('#formValidationSchedule').flatpickr({
        enableTime: true,
        autoclose: true,
        dateFormat: locale === 'vn' ? 'd-m-Y H:i' : 'Y-m-d H:i'
    });

    const deletePostApi = async (params) => {
        try {
            const response = await fetch('/api/v1/posts/bulk-delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(params)
            });
            return await response.json();
        } catch (error) {
            toastr.error(translations.delete_fail);
            console.log('Error: ', error);
            return null;
        }
    }

    btnDeletePost.on('click', function () {
        // sweetalert for confirmation of delete
        Swal.fire({
            title: translations.confirm_delete,
            text: translations.confirm_delete_message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: translations.confirm_delete_yes,
            cancelButtonText: translations.no,
            customClass: {
                confirmButton: 'btn btn-primary me-3',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then(async function (result) {
            if (result.value) {
                await deletePostApi({'post_ids': [postId]})
                window.location.href = '/app/posts'
            }
        });
    })
    const loadSelectParentOrganizationTraverse = (treeData, prefix = '-') => {
        let selectAddParent = $('#formValidationOrganization');
        $.each(treeData, function (index, data) {
            selectAddParent.append(`<option value='${data.id}' ${postOrganization.includes(data.id) ? 'selected' : ''}>${prefix} ${data.text}</option>`)
            loadSelectParentOrganizationTraverse(data.children, `${prefix}-`);
        })
    }
    loadSelectParentOrganizationTraverse(organizations);

    const initSelect2 = (selector, placeholder, hasParent = false) => {
        let option = {
            placeholder: placeholder,
            ...translationNoResultSelect2().config
        }
        hasParent ? option.dropdownParent = $(selector).parent() : null;
        $(selector).wrap('<div class="position-relative"></div>').select2(option);
    };

    initSelect2('.status', translations.select_status_post);
    initSelect2('.formValidationOrganization', translations.select_organization, true);
});
