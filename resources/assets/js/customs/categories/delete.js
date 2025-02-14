'use strict';

import {CSRF_TOKEN} from "../../config.js";

$(function () {
    const deleteOrganizationButton = $('#deleteOrganization');
    deleteOrganizationButton.on('click', function () {
        let nodeId = $(this).data('id');
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
        }).then(function (result) {
            if (result.value) {
                fetch(`/api/v1/categories/${nodeId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.code === 200) {
                            window.location.reload()
                        } else {
                            if (data.errors) {
                                console.error('Error:', data.errors);
                                toastr.error(translations.server_error);
                            }
                        }
                    })
                    .catch(error => {
                        toastr.error(translations.server_error);
                        console.error('Error:', error);
                    });
            }
        });
    });
});
