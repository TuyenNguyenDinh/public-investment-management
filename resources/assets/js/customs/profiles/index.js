'use strict';

import {clearValidationOnChange, CSRF_TOKEN, translationNoResultSelect2} from "../../config.js";

$(function () {
    const dateOfBirth = $('#date_of_birth');
    // Format
    if (dateOfBirth.length) {
        dateOfBirth.datepicker({
            todayHighlight: true,
            format: locale === 'vn' ? 'dd-mm-yyyy' : 'yyyy-mm-dd',
            orientation: isRtl ? 'auto right' : 'auto left'
        });
    }

    const handleImageUpload = (imageElementId, inputSelector, resetButtonSelector, empty_citizen = null) => {
        let imageElement = document.getElementById(imageElementId);
        let fileInput = document.querySelector(inputSelector);
        let resetButton = document.querySelector(resetButtonSelector);

        if (imageElement && fileInput && resetButton) {
            let originalImageSrc = (imageElement.src && imageElement.src !== window.location.href && imageElement.src !== '' && imageElement.src !== 'about:blank') ? imageElement.src : null;

            fileInput.onchange = () => {
                if (empty_citizen !== null) {
                    $(`${empty_citizen}`).removeClass('d-flex');
                    $(`${empty_citizen}`).addClass('d-none');

                    $(`#${imageElementId}`).removeClass('d-none');
                    $(`#${imageElementId}`).addClass('d-block');
                }
                if (fileInput.files[0]) {
                    imageElement.src = window.URL.createObjectURL(fileInput.files[0]);
                }
            };

            resetButton.onclick = () => {
                fileInput.value = '';
                if (empty_citizen !== null) {
                    $(`${empty_citizen}`).removeClass('d-none');
                    $(`${empty_citizen}`).addClass('d-flex');
                    $(`#${imageElementId}`).addClass('d-none');
                }

                if (originalImageSrc) {
                    $(`#${imageElementId}`).removeClass('d-none');
                }
                imageElement.src = originalImageSrc ? originalImageSrc : '';
                $('#update_profile').prop('disabled', false);
                $('#user_avatar').closest('.button-wrapper').find('.fv-plugins-message-container').remove();
            };
        }
    }

    handleImageUpload('upload_user', '.user_input', '.user_reset');
    handleImageUpload('upload_back_citizen', '.back_citizen_input', '.back_citizen_reset', '.empty_back_citizen');
    handleImageUpload('upload_front_citizen', '.front_citizen_input', '.front_citizen_reset', '.empty_front_citizen');
    
    $('[id^="education_"][id$="_5"]').each(function() {
        $(this).datepicker({
            todayHighlight: true,
            format: locale === 'vn' ? 'dd-mm-yyyy' : 'yyyy-mm-dd',
            orientation: isRtl ? 'auto right' : 'auto left'
        });
    });

    $('[id^="work_history_"][id$="_0"]').each(function() {
        $(this).datepicker({
            todayHighlight: true,
            format: locale === 'vn' ? 'dd-mm-yyyy' : 'yyyy-mm-dd',
            orientation: isRtl ? 'auto right' : 'auto left'
        });
    });

    $('[id^="work_history_"][id$="_1"]').each(function() {
        $(this).datepicker({
            todayHighlight: true,
            format: locale === 'vn' ? 'dd-mm-yyyy' : 'yyyy-mm-dd',
            orientation: isRtl ? 'auto right' : 'auto left'
        });
    });

    let formRepeater = $('.form-repeater'), educationRepeater = $('.education-repeater'), workHistoryRepeater = $('.work-history-repeater');

    if (formRepeater.length) {
        let row = $(".form-repeater div[data-repeater-item]").length ?? 1;
        formRepeater.repeater({
            show: function () {
                let col = 0;
                let fromControl = $(this).find('.form-control, .form-select');
                let formLabel = $(this).find('.form-label');

                fromControl.each(function (i) {
                    let id = 'relative_' + row + '_' + col;
                    $(fromControl[i]).attr('id', id);
                    $(formLabel[i]).attr('for', id);
                    $(fromControl[i]).val('');
                    col++;
                });

                row++;

                $(this).slideDown();
            },
            hide: function (e) {
                confirm(translations.confirm_delete_element) && $(this).slideUp(e);
            }
        });
    }

    if (educationRepeater.length) {
        let educationRow = $(".education-repeater div[data-repeater-item]").length ?? 1;
        educationRepeater.repeater({
            show: function () {
                let educationCol = 0;
                let fromControl = $(this).find('.education-control:not([type=hidden])');
                let formLabel = $(this).find('.education-label');
                let smallText = $(this).find('.education-small')

                fromControl.each(function (i) {
                    let id = 'education_' + educationRow + '_' + educationCol;
                    $(fromControl[i]).attr('id', id);
                    $(formLabel[i]).attr('for', id);
                    $(smallText[i]).addClass(id)
                    $(`small.education_${educationRow}_0`).remove()
                    $(fromControl[i]).val('');
                    if (educationCol === 5) {
                        $(fromControl[i]).datepicker({
                            todayHighlight: true,
                            format: locale === 'vn' ? 'dd-mm-yyyy' : 'yyyy-mm-dd',
                            orientation: isRtl ? 'auto right' : 'auto left'
                        })
                    }
                    educationCol++;
                });

                educationRow++;

                $(this).slideDown();
            },
            hide: function (e) {
                if (confirm(translations.confirm_delete_element)) {
                    let id = $(this).prev().val() ?? null;
                    if (id) {
                        fetch(`${baseUrl}api/v1/profiles/education/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                            },
                        })
                            .then(handleFetchError)
                            .then(() => {
                                console.log('deleted education')
                            })
                            .catch(() => {
                                toastr.error(translations.server_error);
                            });
                    }
                    $(this).slideUp(e);
                }
            }
        });
    }

    if (workHistoryRepeater.length) {
        let workHistoryRow = $(".work-history-repeater div[data-repeater-item]").length ?? 1;
        workHistoryRepeater.repeater({
            show: function () {
                let workHistoryCol = 0;
                let fromControl = $(this).find('.work-history-control');
                let formLabel = $(this).find('.work-history-label');

                fromControl.each(function (i) {
                    let id = 'work_history_' + workHistoryRow + '_' + workHistoryCol;
                    $(fromControl[i]).attr('id', id);
                    $(formLabel[i]).attr('for', id);
                    $(fromControl[i]).val('');
                    if (workHistoryCol === 0 || workHistoryCol === 1) {
                        $(fromControl[i]).datepicker({
                            todayHighlight: true,
                            format: locale === 'vn' ? 'dd-mm-yyyy' : 'yyyy-mm-dd',
                            orientation: isRtl ? 'auto right' : 'auto left'
                        })
                    }
                    workHistoryCol++;
                });

                workHistoryRow++;

                $(this).slideDown();
            },
            hide: function (e) {
                confirm(translations.confirm_delete_element) && $(this).slideUp(e);
            }
        });
    }

    function handleFetchError(response) {
        if (!response.ok) {
            toastr.error(translations.server_error);
            throw new Error('Network response was not ok');
        }
        return response.json();
    }

    const select2 = $('.select2');
    // For all Select2
    if (select2.length) {
        select2.each(function () {
            let $this = $(this);
            $this.wrap('<div class="position-relative"></div>');
            $this.select2({
                dropdownParent: $this.parent(),
                ...translationNoResultSelect2().config
            });
        });
    }
});
