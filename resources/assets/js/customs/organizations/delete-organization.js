'use strict';

import {CSRF_TOKEN} from "../../config.js";

$(function () {
    const deleteOrganizationButton = $('#deleteOrganization');
    deleteOrganizationButton.on('click', function () {
        let nodeId = $(this).data('id');
        Swal.fire({
            title: translations.confirm_delete_title,
            text: translations.confirm_delete_text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: translations.confirm_button_text,
            cancelButtonText: translations.cancel_button_text,
            customClass: {
                confirmButton: 'btn btn-primary me-3',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.value) {
                fetch(`/api/v1/organizations/${nodeId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.code === 200) {
                            window.location.reload();
                        } else {
                            console.log(data.message);
                            toastr.error(translations.error_occurred);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error(translations.error_occurred);
                    });
            }
        });
    });
});
