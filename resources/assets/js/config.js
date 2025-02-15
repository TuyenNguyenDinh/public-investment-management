/**
 * Config
 * -------------------------------------------------------------------------------------
 * ! IMPORTANT: Make sure you clear the browser local storage In order to see the config changes in the template.
 * ! To clear local storage: (https://www.leadshook.com/help/how-to-clear-local-storage-in-google-chrome-browser/).
 */

'use strict';

import $ from 'jquery';
import 'select2';

// JS global variables
window.config = {
    colors: {
        primary: '#7367f0',
        secondary: '#808390',
        success: '#28c76f',
        info: '#00bad1',
        warning: '#ff9f43',
        danger: '#FF4C51',
        dark: '#4b4b4b',
        black: '#000',
        white: '#fff',
        cardColor: '#fff',
        bodyBg: '#f8f7fa',
        bodyColor: '#6d6b77',
        headingColor: '#444050',
        textMuted: '#acaab1',
        borderColor: '#e6e6e8'
    },
    colors_label: {
        primary: '#7367f029',
        secondary: '#a8aaae29',
        success: '#28c76f29',
        info: '#00cfe829',
        warning: '#ff9f4329',
        danger: '#ea545529',
        dark: '#4b4b4b29'
    },
    colors_dark: {
        cardColor: '#2f3349',
        bodyBg: '#25293c',
        bodyColor: '#b2b1cb',
        headingColor: '#cfcce4',
        textMuted: '#8285a0',
        borderColor: '#565b79'
    },
    enableMenuLocalStorage: true // Enable menu state with local storage support
};

window.assetsPath = document.documentElement.getAttribute('data-assets-path');
window.baseUrl = document.documentElement.getAttribute('data-base-url') + '/';
window.templateName = document.documentElement.getAttribute('data-template');
window.rtlSupport = true; // set true for rtl support (rtl + ltr), false for ltr only.

export let CONST = {
    POST_DRAFT: 0,
    POST_APPROVED: 1,
    POST_SCHEDULED: 2,
    POST_SUBMITTED: 3,
    POST_REJECTED: 4,
    POST_LOCKED: 5
}

export const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

export const translationNoResultSelect2 = () => ({
    config: {
        language: {
            "noResults": function () {
                return translations.no_result_found;
            }
        },

        escapeMarkup: function (markup) {
            return markup;
        }
    }
});


export const getDataTableLanguage = (placeholder) => ({
    sLengthMenu: `${translations.show} _MENU_`,
    sInfoFiltered: `${translations.filtered_from}`,
    search: '',
    searchPlaceholder: placeholder,
    paginate: {
        next: '<i class="ti ti-chevron-right ti-sm"></i>',
        previous: '<i class="ti ti-chevron-left ti-sm"></i>',
    },
    info: translations.info_showing_entries,
    infoEmpty: translations.info_no_entries,
    emptyTable: translations.empty_table,
    zeroRecords: translations.empty_table,
});

export const clearValidationOnChange = (form, formValidator) => {
    const inputs = form.querySelectorAll('input, textarea, select');

    inputs.forEach(input => {
        if (input.tagName.toLowerCase() === 'select' && $(input).data('select2')) {
            $(input).on('select2:select select2:unselect', () => {
                const fieldName = input.name;
                formValidator.resetField(fieldName);
            });
        } else {
            input.addEventListener('input', () => {
                const fieldName = input.name;
                formValidator.resetField(fieldName);
            });
        }
    });
};

export const clearValidationForCKEditor = (form, formValidator) => {
    const textareas = form.querySelectorAll('textarea');

    textareas.forEach(textarea => {
        if (CKEDITOR.instances[textarea.name]) {
            CKEDITOR.instances[textarea.name].on('change', () => {
                const fieldName = textarea.name;
                formValidator.resetField(fieldName);
            });
        }
    });
};

export const fetchCategories = async () => {
    try {
        const response = await fetch('/api/v1/categories', {
            method: 'GET',
        });
        return await response.json()
    } catch (error) {
        toastr.error(translations.error_fetching_categories);
        console.log('Error: ', error);
        return null;
    }
}
setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
}, 300);

export const fetchCallbackData = async (callback) => {
    try {
        $("#overlay").fadeIn(300);
        await callback();
    } catch (error) {
        toastr.error(translations.error_occurred);
        console.log('Error: ', error);
    } finally {
        $("#overlay").fadeOut(300);
    }
}

export const apiRequest = async (url, method = 'GET', body = null) => {
    try {
        $("#overlay").fadeIn(300);
        const options = {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            }
        };
        if (body) options.body = JSON.stringify(body);
        const response = await fetch(url, options);
        return await response.json();
    } catch (error) {
        toastr.error(translations.error_occurred)
        console.error('API Error:', error);
        return false;
    } finally {
        $("#overlay").fadeOut(300);
    }
};

