/**
 * Page User List
 */
'use strict';

import {CSRF_TOKEN, getDataTableLanguage, translationNoResultSelect2} from "../../config.js";

const nodeTree = $('#jstree-menu');
const nodeTreeUpdate = $('#jstree-menu-update');

// Initialize Select2 for dropdowns
const initSelect2 = (selector, placeholder) => {
    if ($(selector).length) {
        $(selector).wrap('<div class="position-relative"></div>').select2({
            placeholder: placeholder,
            dropdownParent: $(selector).parent(),
            ...translationNoResultSelect2().config
        });
    }
};

// Initialize Select2 elements
const initializeSelect2Elements = () => {
    const selectRoles = [
        {selector: '.createRole', placeholder: translations.select_role},
        {selector: '.updateRole', placeholder: translations.select_role},
    ];
    const selectOrganizations = [
        {selector: '.createOrganizations', placeholder: translations.select_organization},
        {selector: '.updateOrganizations', placeholder: translations.select_organization}
    ];

    selectRoles.forEach(item => initSelect2(item.selector, item.placeholder));
    selectOrganizations.forEach(item => initSelect2(item.selector, item.placeholder));
    initSelect2('.assignMenu', translations.select_menu);
    initSelect2('.reAssignMenu', translations.select_menu);
};

// Initialize DataTable
const initializeDataTable = () => {
    const dt_user_table = $('.datatables-users');

    if (dt_user_table.length) {
        const dt_user = dt_user_table.DataTable({
            ajax: {
                url: '/api/v1/users',
                error: handleAjaxError,
            },
            columns: getDataTableColumns(),
            columnDefs: getColumnDefinitions(),
            order: [[2, 'desc']],
            dom: getDataTableDom(),
            language: getDataTableLanguage(translations.search_user),
            buttons: getDataTableButtons(),
            responsive: getDataTableResponsiveOptions(),
        });

        if (!window.userPermission.Create) {
            $('.add-new').remove();
        }

        setupEventHandlers(dt_user);
    }
};

// Error handler for AJAX requests
const handleAjaxError = (jqXHR, textStatus, errorThrown) => {
    toastr.error('An error occurred while fetching user data.');
    console.error('AJAX error:', textStatus, errorThrown);
};

// Define DataTable columns
const getDataTableColumns = () => [
    {data: 'id'},
    {data: 'name'},
    {data: 'email'},
    {data: 'organization_name'},
    {data: 'status'},
    {data: 'created_at'},
    {data: 'actions'},
];

// Define DataTable column definitions
const getColumnDefinitions = () => [
    {
        className: 'control',
        searchable: false,
        orderable: false,
        responsivePriority: 2,
        targets: 0,
        render: () => '',
    },
    {
        targets: 1,
        orderable: false,
        checkboxes: {
            selectAllRender: '<input type="checkbox" class="form-check-input">',
        },
        render: () => '<input type="checkbox" class="dt-checkboxes form-check-input">',
        searchable: false,
    },
    {
        targets: 2,
        responsivePriority: 4,
        render: (data, type, full) => renderUserDetails(full),
    },
    {
        targets: 3,
        responsivePriority: 4,
        render: (data, type, full) => `<span class='text-nowrap'>${full['organization_name']}</span>`,
    },
    {
        targets: 4,
        responsivePriority: 4,
        render: (data, type, full) => `<span class="text-nowrap">${full['is_active'] ? translations.activate : translations.deactivate}</span>`,
    },
    {
        targets: 5,
        orderable: false,
        render: (data, type, full) => `<span class="text-nowrap">${full['created_at']}</span>`,
    },
    {
        targets: -1,
        searchable: false,
        title: translations.actions,
        orderable: false,
        render: (data, type, full) => renderActions(full),
    },
];

// Define DataTable DOM structure
const getDataTableDom = () => (
    '<"row mx-1"' +
    '<"col-sm-12 col-md-3" l>' +
    '<"col-sm-12 col-md-9"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end justify-content-center flex-wrap"<"me-4 mt-n6 mt-md-0"f>B>>' +
    '>t' +
    '<"row"' +
    '<"col-sm-12 col-md-6"i>' +
    '<"col-sm-12 col-md-6"p>' +
    '>'
);

// Define DataTable buttons
const getDataTableButtons = () => [
    {
        text: `<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">${translations.add_new_user}</span>`,
        className: 'add-new btn btn-primary waves-effect waves-light',
        attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#offcanvasAddUser',
        },
    },
];

// Define DataTable responsive options
const getDataTableResponsiveOptions = () => ({
    details: {
        display: $.fn.dataTable.Responsive.display.modal({
            header: (row) => 'Details of ' + row.data()['name'],
        }),
        type: 'column',
        renderer: (api, rowIdx, columns) => renderDetails(columns),
    },
});

// Render User Details
const renderUserDetails = (full) => {
    const {name, email, id} = full;
    return `
        <div class="d-flex justify-content-start align-items-center user-name">
            <div class="d-flex flex-column">
                <a href="#" class="text-heading text-truncate">
                    <span class="fw-medium user_name_${id}">${name}</span>
                </a>
                <small class="user_email_${id}">${email}</small>
            </div>
        </div>`;
};

// Render Action Buttons
const renderActions = (full) => {
    const editButton = window.userPermission.Update ? renderEditButton(full.id) : '';
    const deleteButton = window.userPermission.Delete ? renderDeleteButton(full.id) : '';
    const triggerDrop = window.userPermission.Update && window.hasAdmin ? renderTriggerButton(full) : '';

    return `<div class="d-flex align-items-center gap-50">${editButton}${deleteButton}${triggerDrop}</div>`;
};

// Render Edit Button
const renderEditButton = (id) => `
    <button class="btn-edit btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill me-1 edit-user" data-id="${id}">
        <i class="ti ti-edit ti-md"></i>
    </button>`;

// Render Delete Button
const renderDeleteButton = (id) => `
    <button class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect" data-id="${id}">
        <i class="ti ti-trash"></i>
    </button>`;

// Render Trigger Drop Button
const renderTriggerButton = (full) => {
    const trigger = full['is_active'] ? 'user-off' : 'user-check';
    return `
        <button class='btn btn-sm btn-icon trigger-user btn-text-secondary rounded-pill waves-effect' data-id='${full['id']}' data-trigger='${full['is_active']}'>
            <i class='ti ti-${trigger}'></i>
        </button>`;
};

// Render Details in Modal
const renderDetails = (columns) => {
    const data = $.map(columns, (col) => {
        return col.title !== ''
            ? `<tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
                <td>${col.title}:</td>
                <td>${col.data}</td>
            </tr>`
            : '';
    }).join('');

    return data ? $('<table class="table"/><tbody />').append(data) : false;
};

// Setup Event Handlers
const setupEventHandlers = () => {
    let tbody = $('.datatables-users tbody');
    tbody.on('click', '.edit-user', handleEditUser);
    tbody.on('click', '.delete-record', handleDeleteUser);
    tbody.on('click', '.trigger-user', handleTriggerUser);
};

// Handle Edit User Click
const handleEditUser = function () {
    const id = $(this).data('id');
    loadUserData(id);
};

// Handle Delete User Click
const handleDeleteUser = function () {
    const id = $(this).data('id');
    confirmDelete(id);
};

// Handle User Activation/Deactivation Click
const handleTriggerUser = function () {
    const id = $(this).data('id');
    const isActive = $(this).data('trigger');
    confirmTriggerUser(id, isActive);
};

// Load User Data for Editing
const loadUserData = (id) => {
    const selectEditUser = $('#editUserForm select#updateRole');
    const selectUpdateOrganizations = $('#editUserForm select#updateOrganizations');
    const selectUpdateMenus = $('#editUserForm select#reAssignMenu');
    const form = $('#editUserForm');

    fetch(`${baseUrl}api/v1/users/${id}`)
        .then(handleFetchError)
        .then((data) => {
            populateEditUserForm(data.data, selectEditUser, selectUpdateOrganizations, selectUpdateMenus, form, id)
                .then(() => console.log('Loaded user data'));
        })
        .catch((error) => {
            toastr.error(translations.failed_to_load_user_data);
            console.error('Error loading user data:', error);
        });
};

// Populate Edit User Form
const populateEditUserForm = async (data, selectEditUser, selectUpdateOrganizations, selectUpdateMenus, form, id) => {
    form.find('input[name="name"]').val(data.name);
    form.find('input[name="email"]').val(data.email);
    form.find('input[name="userId"]').val(id);
    selectEditUser.val(null).trigger('change');
    selectUpdateOrganizations.val(null).trigger('change');
    selectUpdateMenus.val(null).trigger('change');
    $('#submitEditUser').attr('data-id', id)

    await loadRoleByOrganizationIds(data.organizations, true)
        .then(() => console.log('Loaded roles'));
    selectEditUser.val(data.role).trigger('change');
    selectUpdateOrganizations.val(data.organizations).trigger('change');
    selectUpdateMenus.val(data.menus).trigger('change');

    nodeTreeUpdate.jstree('uncheck_all');
    if (data.menus.length > 0) {
        data.menus.forEach((menu) => {
            nodeTreeUpdate.jstree('check_node', menu);
        });
    }
    const offcanvasElement = document.querySelector('#offcanvasEditUser');
    const offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
};

// Confirm Delete User
const confirmDelete = (id) => {
    Swal.fire({
        title: translations.confirm_delete,
        text: translations.confirm_delete_message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: translations.yes,
        cancelButtonText: translations.no,
        customClass: {
            confirmButton: 'btn btn-primary me-3',
            cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            deleteUser(id);
        }
    });
};

// Delete User
const deleteUser = (id) => {
    fetch(`${baseUrl}api/v1/users/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Content-Type': 'application/json',
        },
    })
        .then(handleFetchError)
        .then(() => {
            window.location.reload();
        })
        .catch((error) => {
            toastr.error(translations.failed_to_delete_user);
            console.error('Error deleting user:', error);
        });
};

// Confirm User Activation/Deactivation
const confirmTriggerUser = (id, isActive) => {
    const actionMsg = isActive ? translations.deactivate_user_message : translations.activate_user_message
    Swal.fire({
        title: actionMsg,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: translations.yes,
        cancelButtonText: translations.no,
        customClass: {
            confirmButton: 'btn btn-primary me-3',
            cancelButton: 'btn btn-label-secondary'
        },
    }).then((result) => {
        if (result.isConfirmed) {
            triggerUser(id, isActive);
        }
    });
};

// Trigger User Activation/Deactivation
const triggerUser = (id, isActive) => {
    fetch(`${baseUrl}api/v1/users/${id}/triggers`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({is_active: !isActive}),
    })
        .then(handleFetchError)
        .then(() => {
            window.location.reload();
        })
        .catch((error) => {
            toastr.error(translations.failed_to_change_user_status);
            console.error('Error changing user status:', error);
        });
};

// Add User Form Submission
$('#addUserForm').on('submit', function (e) {
    e.preventDefault();

    const form = $(this);
    const data = {
        name: form.find('input[name="name"]').val(),
        email: form.find('input[name="email"]').val(),
        role_id: form.find('#createRole').val(),
        organizations: form.find('#createOrganizations').val(),
    };

    fetch(`${baseUrl}api/v1/users`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
        .then(handleFetchError)
        .then(() => {
            window.location.reload()
        })
        .catch((error) => {
            toastr.error(translations.failed_to_load_user_data);
            console.error('Error adding user:', error);
        });
});

const loadTree = async (tree, treeData) => {
    if (!tree.length) return;
    tree.jstree({
        core: {
            themes: {
                name: $('html').hasClass('light-style') ? 'default' : 'default-dark'
            },
            data: treeData
        },
        plugins: ['types', 'wholerow', 'checkbox'],
        types: {
            default: {icon: 'ti ti-folder'},
            html: {icon: 'ti ti-brand-html5 text-danger'},
            css: {icon: 'ti ti-brand-css3 text-info'},
            img: {icon: 'ti ti-photo text-success'},
            js: {icon: 'ti ti-brand-javascript text-warning'},
        }
    });
};

function handleFetchError(response) {
    if (!response.ok) {
        toastr.error(translations.server_error);
        throw new Error('Network response was not ok');
    }
    return response.json();
}

const loadRoleByOrganizationIds = async (organizationIds, isUpdate = false) => {
    if (!organizationIds.length) return;
    const response = await fetch(`${baseUrl}api/v1/organizations/roles?organization_ids=${organizationIds.join(',')}`)
        .then(handleFetchError)
        .catch((error) => {
            toastr.error(translations.get_role_by_organization_fail);
            console.error('Error get role by organization :', error);
        });
    const data = await response;
    if (data.code === 200) {
        let select = isUpdate ? $('.updateRole') : $('.createRole');
        select.empty();
        data.data.forEach(item => {
            const newOption = new Option(item.name, item.name, false, false);
            select.append(newOption).trigger('change');
        });
    }

    return data;
}

// Document Ready Function
$(function () {
    initializeSelect2Elements();
    initializeDataTable();

    $('.createOrganizations').on('select2:select', function () {
        // Your code here
        let selectedIds = $(this).val();
        loadRoleByOrganizationIds(selectedIds).then(() => console.log('Loaded roles'));
    });

    $('.updateOrganizations').on('select2:select', function () {
        // Your code here
        let selectedIds = $(this).val();
        loadRoleByOrganizationIds(selectedIds, true).then(() => console.log('Loaded roles'));
    });

    loadTree(nodeTree, window.menuData).then(() => console.log('Loaded tree'));
    loadTree(nodeTreeUpdate, window.menuData).then(() => console.log('Loaded update tree'));
})
