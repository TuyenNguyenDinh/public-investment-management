'use strict';

import {apiRequest, getDataTableLanguage} from "../../config.js";

const fetchPermissionData = async () => {
    const res = await apiRequest('/api/v1/permissions');
    return res && res.status === 200 ? res.data : null
}

$( async function () {
    $('#overlay').fadeIn(300);
    let dataTablePermissions = $('.datatables-permissions');
    const permissionData = await fetchPermissionData();
    // Users List datatable
    if (permissionData) {
        dataTablePermissions.DataTable({
            data: permissionData,
            columns: [
                {data: ''},
                {data: 'id'},
                {data: 'name'},
                {data: 'created_at'},
                {data: ''}
            ],
            columnDefs: [
                {
                    // For Responsive
                    className: 'control',
                    orderable: false,
                    searchable: false,
                    responsivePriority: 2,
                    targets: 0,
                    render: function () {
                        return '';
                    }
                },
                {
                    targets: 1,
                    searchable: false,
                    visible: false
                },
                {
                    // Name
                    targets: 2,
                    render: function (data, type, full, meta) {
                        let parent_id = full.parent_id;
                        let text = (parent_id === null ? '- ' : '-- ') + full.name;
                        return '<span class="text-nowrap text-heading name_' + full.id + '">'
                            + text
                            + '</span>';
                    }
                },
                {
                    // Created Date
                    targets: 3,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        var $date = full['created_at'];
                        return '<span class="text-nowrap">' + $date + '</span>';
                    }
                },
                {
                    // Actions
                    targets: -1,
                    searchable: false,
                    title: translations.actions,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        let isShowAddChildPermission = full.parent_id !== null ? 'invisible' : '';
                        let addChildPermission = window.permissions.Create
                            ? `<span class="text-nowrap">
                        <button class="btn btn-icon btn-text-secondary add-child-permission waves-effect waves-light rounded-pill me-1 ${isShowAddChildPermission}"
                            data-bs-target="#addChildPermissionModal"
                            data-bs-toggle="modal"
                            data-bs-dismiss="modal"
                            data-id="${full['id']}"
                            data-name="${full['name']}">
                            <i class="ti ti-circle-plus"></i>
                         </button>`
                            : ``;
                        let editPermission = window.permissions.Update
                            ? `<span class="text-nowrap"><div class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill me-1 btn-edit-permission"
                                data-id="${full['id']}"
                                data-name="${full['name']}"><i class="ti ti-edit ti-md"></i></div>`
                            : ``;
                        let deletePermission = window.permissions.Delete
                            ? `<button class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect" data-id="${full['id']}"><i class="ti ti-trash"></i></button>`
                            : ``;
                        return (
                            '<div class="d-flex align-items-center">' +
                            addChildPermission +
                            editPermission +
                            deletePermission +
                            '</div>'
                        );
                    }
                }
            ],
            dom:
                '<"row mx-1"' +
                '<"col-sm-12 col-md-3" l>' +
                '<"col-sm-12 col-md-9"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end justify-content-center flex-wrap"<"me-4 mt-n6 mt-md-0"f>B>>' +
                '>t' +
                '<"row"' +
                '<"col-sm-12 col-md-6"i>' +
                '<"col-sm-12 col-md-6"p>' +
                '>',
            language: getDataTableLanguage(translations.search_permissions),
            buttons: [
                {
                    text: `<i class="ti ti-plus ti-xs me-0 me-sm-2"></i><span class="d-none d-sm-inline-block">${translations.add_permission}</span>`,
                    className: 'add-new btn btn-primary mb-6 mb-md-0 waves-effect waves-light',
                    attr: {
                        'data-bs-toggle': 'modal',
                        'data-bs-target': '#addPermissionModal'
                    },
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                    }
                }
            ],
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (row) {
                            var data = row.data();
                            return translations.details_of + ' ' + data['name'];
                        }
                    }),
                    type: 'column',
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col, i) {
                            return col.title !== ''
                                ? '<tr data-dt-row="' +
                                col.rowIndex +
                                '" data-dt-column="' +
                                col.columnIndex +
                                '">' +
                                '<td>' +
                                col.title +
                                ':' +
                                '</td> ' +
                                '<td>' +
                                col.data +
                                '</td>' +
                                '</tr>'
                                : '';
                        }).join('');

                        return data ? $('<table class="table"/><tbody />').append(data) : false;
                    }
                }
            },
        });
    }

    if (!window.permissions.Create) {
        $('.add-new').remove();
    }

    let body = $('.datatables-permissions tbody');
    // Delete Record
    body.on('click', '.delete-record', function () {
        let id = $(this).data('id'),
            dtrModal = $('.dtr-bs-modal.show');

        // hide responsive modal in small screen
        if (dtrModal.length) {
            dtrModal.modal('hide');
        }

        Swal.fire({
            title: translations.are_you_sure,
            text: translations.cannot_revert,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: translations.no,
            confirmButtonText: translations.yes_delete_it,
            customClass: {
                confirmButton: 'btn btn-primary me-3',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.value) {
                let deleteForm = $('#deletePermissionForm');
                deleteForm.attr('action', `permissions/${id}`)
                deleteForm.trigger('submit');
            }
        });
    });

    setTimeout(() => {
        $('.dataTables_info').addClass('ms-n1');
        $('.dataTables_paginate').addClass('me-n1');
    }, 300);

    body.on('click', '.btn-edit-permission', function () {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let modal = $('#editPermissionModal');
        const form = $('#editPermissionForm');
        form.attr('action', `permissions/${id}`)
        form.attr('data-id', id)
        form.attr('method', 'POST')
        $('input#editPermissionName.form-control').val(name)
        modal.modal('show');
    })

    $('#addChildPermissionModal').on('shown.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        let id = button.data('id');
        let name = button.data('name');
        $('.permission-parent-id').val(id);
        $('h4.add-child-permission-title').text(`${translations.add_new_permission_for} ${name}`);
    });
    $('#overlay').fadeOut(300);
});
