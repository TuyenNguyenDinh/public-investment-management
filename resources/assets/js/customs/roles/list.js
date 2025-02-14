'use strict';

import {CSRF_TOKEN, getDataTableLanguage, translationNoResultSelect2} from "../../config.js";

// Datatable và các plugin
$(function () {
    initializeSelect2($('.createOrganizations'), translations.select_organization);
    initializeSelect2($('.updateOrganizations'), translations.select_organization);
    initializeDataTable($('.datatables-roles'));
    setupCheckAll('#selectAll', '#addRoleForm')
    setupCheckAll('#selectUpdateAll', '#editRoleForm')
    setupCheckAlLLine('.check-all', 'tr');
    setupCheckAlLLine('.check-update-all', 'tr');
    handleDeleteRecord('.datatables-roles tbody', '.delete-record', 'api/v1/roles/');
    getRoleDetail('#editRoleForm', 'api/v1/roles/', '#editRoleModal');
});

// Khởi tạo select2 cho các input tổ chức
function initializeSelect2($element, placeholder) {
    if ($element.length) {
        $element.wrap('<div class="position-relative"></div>').select2({
            placeholder: placeholder,
            dropdownParent: $element.parent(),
            ...translationNoResultSelect2().config
        });
    }
}

// Khởi tạo DataTable
function initializeDataTable($table) {
    if ($table.length) {
        $table.DataTable({
            ajax: {
                url: '/api/v1/roles',
                error: handleAjaxError
            },
            columns: [
                {data: ''}, {data: 'id'}, {data: 'name'}, {data: 'created_at'}, {data: ''}
            ],
            columnDefs: getColumnDefinitions(),
            order: [[0, 'asc']],
            dom: getTableDomLayout(),
            language: getDataTableLanguage(translations.search_role),
            buttons: getTableButtons(),
            responsive: getResponsivePopupSettings(),
        });
    }
}

// Định nghĩa các cột trong DataTable
function getColumnDefinitions() {
    return [
        {
            className: 'control',
            orderable: false,
            searchable: false,
            responsivePriority: 2,
            targets: 0,
            render: () => ''
        },
        {
            targets: 1,
            orderable: false,
            checkboxes: {selectAllRender: '<input type="checkbox" class="form-check-input">'},
            render: () => '<input type="checkbox" class="dt-checkboxes form-check-input">'
        },
        {
            targets: 2,
            orderable: true,
            render: (data, type, full) => `<span class="text-nowrap text-heading name-${full.id}">${full.name}</span>`
        },
        {
            targets: 3,
            orderable: true,
            render: (data) => `<span class="text-nowrap">${data}</span>`
        },
        {
            targets: -1,
            searchable: false,
            orderable: false,
            render: (data, type, full) => renderActionButtons(full)
        }
    ];
}

// Tạo layout cho DataTable
function getTableDomLayout() {
    return '<"row mx-1"' +
        '<"col-sm-12 col-md-3" l>' +
        '<"col-sm-12 col-md-9"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end justify-content-center flex-wrap"<"me-4 mt-n6 mt-md-0"f>B>>' +
        '>t' +
        '<"row"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>';
}

// Hiển thị các nút action trong cột cuối
function renderActionButtons(full) {
    let editRole = window.rolePermission.Update
        ? `<button class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill me-1 edit-role" 
            data-id="${full.id}"><i class="ti ti-edit ti-md"></i></button>`
        : '';
    let deleteRole = window.rolePermission.Delete
        ? `<button class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect" 
            data-id="${full.id}"><i class="ti ti-trash"></i></button>`
        : '';
    return `<div class="d-flex align-items-center">${editRole}${deleteRole}</div>`;
}

// Cài đặt các nút cho DataTable
function getTableButtons() {
    if (window.rolePermission.Create) {
        return [
            {
                text: `<i class="ti ti-plus ti-xs me-md-2"></i><span class="d-md-inline-block d-none">${translations.add_new_role}</span>`,
                className: 'add-new btn btn-primary waves-effect waves-light rounded border-left-0 border-right-0',
                attr: {
                    'data-bs-toggle': 'modal',
                    'data-bs-target': '#addRoleModal'
                }
            }
        ];
    }

    return [];
}

// Cài đặt popup responsive cho DataTable
function getResponsivePopupSettings() {
    return {
        details: {
            display: $.fn.dataTable.Responsive.display.modal({
                header: (row) => `${translations.details_of} ${row.data().full_name}`
            }),
            type: 'column',
            renderer: function (api, rowIdx, columns) {
                let data = $.map(columns, function (col) {
                    return col.title ? `<tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
                        <td>${col.title}:</td> 
                        <td>${col.data}</td></tr>` : '';
                }).join('');
                return data ? $('<table class="table"/><tbody />').append(data) : false;
            }
        }
    };
}

function getRoleDetail(formSelector, apiUrl, modalSelector) {
    $(document).on('click', '.edit-role', function () {
        let id = $(this).data('id');
        const form = $(formSelector);
        fetchRoleData(apiUrl, id, form, modalSelector);
    });
}

// Xử lý lỗi Ajax
function handleAjaxError(jqXHR, textStatus, errorThrown) {
    toastr.error(translations.fetch_error_message);
    console.error('AJAX error:', textStatus, errorThrown);
}

// Fetch dữ liệu vai trò
function fetchRoleData(apiUrl, id, form, modalSelector) {
    fetch(`${baseUrl}${apiUrl}${id}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        }
    })
        .then(handleFetchError)
        .then(response => {
            populateRoleForm(response, form);
            $(modalSelector).modal('show');
        })
        .catch(error => {
            toastr.error(translations.fetch_error_message);
            console.error('Fetch error:', error);
        });
}

// Điền dữ liệu vào form chỉnh sửa vai trò
function populateRoleForm(response, form) {
    let selectEditOrganization = form.find('select#updateOrganizations');
    form.find('input[type="checkbox"], input[type="radio"], select').prop('checked', false);
    form.attr('method', 'POST');
    form.find(`input[name='name'].form-control`).val(response.data.name);
    form.find(`input[name^='permissions'].form-check-input`).removeAttr('checked');
    $('#submitEditRole').attr('data-id', response.data.id)

    if (response.data.permissions.length > 0) {
        response.data.permissions.forEach((permission) => {
            form.find(`input[name='permissions[${permission.id}]'].form-check-input`).prop('checked', true);
        });
    }

    selectEditOrganization.val(null).trigger('change');
    if (response.data.organizations.length > 0) {
        selectEditOrganization.val(response.data.organizations).trigger('change');
    }
}

// Thiết lập chức năng Check All cho toàn bộ permission
function setupCheckAll(selector, containerSelector) {
    $(selector).on('change', function (t) {
        const checkboxes = $(containerSelector).find('[type="checkbox"]').not(selector);
        checkboxes.each(function () {
            this.checked = t.target.checked;
        });
    });
}

// Thiết lập chức năng Check All cho từng dòng permission
function setupCheckAlLLine(selector, parentSelector) {
    $(selector).on('change', function () {
        const isChecked = $(this).prop('checked');
        const $parentRow = $(this).closest(parentSelector);
        $parentRow.find('input.form-check-input[type="checkbox"]').not(selector).prop('checked', isChecked);
    });
}

// Xử lý xóa vai trò
function handleDeleteRecord(containerSelector, deleteButtonSelector, apiUrl) {
    $(containerSelector).on('click', deleteButtonSelector, function () {
        let id = $(this).data('id');
        confirmDelete(apiUrl, id);
    });
}

// Hiển thị xác nhận xóa với SweetAlert
function confirmDelete(apiUrl, id) {
    Swal.fire({
        title: translations.delete_confirmation_title,
        text: translations.delete_confirmation_text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: translations.confirm_delete_yes,
        cancelButtonText: translations.no,
        customClass: {
            confirmButton: 'btn btn-primary me-3',
            cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.value) {
            deleteRecord(apiUrl, id);
        }
    });
}

// Gửi yêu cầu xóa bản ghi
function deleteRecord(apiUrl, id) {
    fetch(`${baseUrl}${apiUrl}${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        }
    })
        .then(handleFetchError)
        .then(() => {
            window.location.reload();
        })
        .catch(error => {
            toastr.error(translations.delete_error_message);
            console.error('Delete error:', error);
        });
}

function handleFetchError(response) {
    if (!response.ok) {
        toastr.error(translations.server_error);
        throw new Error('Network response was not ok');
    }
    return response.json();
}
