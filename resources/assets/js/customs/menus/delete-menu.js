'use strict';

$(function () {
    const deleteMenuButton = $('#deleteMenu');
    deleteMenuButton.on('click', function () {
        let nodeId = $(this).data('id');
        Swal.fire({
            title: translations.delete_confirmation_title,
            text: translations.delete_confirmation_text,
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
                fetch(`/api/v1/menus/${nodeId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.code === 200) {
                            window.location.reload();
                        } else {
                            toastr.error(translations.error_occurred);
                            console.error('Error:', error);
                        }
                    })
                    .catch(error => {
                        toastr.error(translations.error_occurred);
                        console.error('Error:', error);
                    });
            }
        });
    });
});
