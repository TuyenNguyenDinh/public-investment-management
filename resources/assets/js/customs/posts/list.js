'use strict'

import {CONST, CSRF_TOKEN, getDataTableLanguage, translationNoResultSelect2} from "../../config.js";

$(async function () {
    const nodeTree = $('#jstree-ajax');
    const btnSearch = $('button[name="submitButton"]');
    const postsCopy = []
    const jstreeNotData = $('#jstree-no-data')
    const inputStartDate = $('#start-date')
    const inputEndDate = $('#end-date')
    const selectStatus = $('#status')
    const selectOrganization = $('#parent-id')
    let dt_basic_table = $('.datatables-basic'), dt_basic;
    let titleCopy, thumbnailCopy, contentCopy, organizationsCopy, categoriesCopy, scheduledDateCopy = null

    const loadTree = async () => {
        jstreeNotData.hide()
        if (treeData.length === 0) {
            nodeTree.hide()
            jstreeNotData.show()
            return
        }
        nodeTree.show()
        if (nodeTree.length) {
            nodeTree.jstree({
                core: {
                    themes: {
                        name: $('html').hasClass('light-style') ? 'default' : 'default-dark'
                    },
                    data: treeData,
                },
                plugins: ['types', 'wholerow'],
                types: {
                    default: {
                        icon: 'ti ti-folder'
                    },
                }
            });
        }
    }

    const getAllChildrenNodes = (parentNode, children = []) => {
        const node = nodeTree.jstree("get_node", parentNode);
        children.push(node.id);
        if (node.children) {
            for (let i = 0; i < node.children.length; i++) {
                getAllChildrenNodes(node.children[i], children);
            }
        }
        return children;
    }

    const generateButton = () => {
        let button = [];
        if (window.postPermission.Delete) {
            button.push({
                text: `<i class="ti ti-circle-minus me-sm-1"></i> <span class="d-none d-sm-inline-block">${translations.delete}</span>`,
                className: 'delete-post btn btn-danger waves-effect waves-light me-2',
            })
        }
        if (window.postPermission.Review) {
            button.push({
                text: `<i class="ti ti-edit-circle me-sm-1"></i> <span class="d-none d-sm-inline-block">${translations.change_status}</span>`,
                className: 'change-status btn btn-primary waves-effect waves-light me-2',
            })
        }

        if (!window.hasAdmin && (window.postPermission.Create || window.postPermission.Update)) {
            button.push({
                text: `<i class="ti ti-edit-circle me-sm-1"></i> <span class="d-none d-sm-inline-block">${translations.send_review}</span>`,
                className: 'change-review btn btn-primary waves-effect waves-light me-2',
            }, {
                text: `<i class="ti ti-edit-circle me-sm-1"></i> <span class="d-none d-sm-inline-block">${translations.cancel_send_review}</span>`,
                className: 'cancel-review btn btn-warning waves-effect waves-light',
            });
        }

        return button;
    }

    const reDrawDataTable = (data) => {
        dt_basic.clear();
        dt_basic.rows.add(data);
        dt_basic.draw();
    }

    const loadSelectParentOrganizationTraverse = (treeData, prefix = '-') => {
        let selectAddParent = $('#parent-id');
        $.each(treeData, function (index, data) {
            selectAddParent.append(`<option value='${data.id}'>${prefix} ${data.text}</option>`)
            loadSelectParentOrganizationTraverse(data.children, `${prefix}-`);
        })
    }
    loadSelectParentOrganizationTraverse(organizations);

    const checkDisableButtonOnHeader = () => {
        const selectedValues = $('input[name="post-selected[]"]:checked');
        if (selectedValues.length > 0) {
            btnBulkDeletePost.prop('disabled', false);
            btnBulkChangeStatus.prop('disabled', false);
        } else {
            btnBulkDeletePost.prop('disabled', true);
            btnBulkChangeStatus.prop('disabled', true);
        }
    }

    const checkDisableButtonReview = () => {
        const selectedValues = $('input[name="post-selected[]"]:checked');
        let showButton = false;
        selectedValues.each(function () {
            const status = $(this).closest('tr').find('span[data-status]').data('status');
            showButton = status !== CONST.POST_APPROVED && status !== CONST.POST_SCHEDULED && status !== CONST.POST_LOCKED;
        });
        btnBulkChangeReview.prop('disabled', !showButton);
        btnBulkCancelReview.prop('disabled', !showButton);
        if (!window.hasAdmin) {
            btnBulkDeletePost.prop('disabled', !showButton);
        }
    }

    const fetchPosts = async (params) => {
        try {
            const response = await fetch('/api/v1/posts', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(params)
            });
            const data = await response.json()
            return data.data
        } catch (error) {
            toastr.error(translations.error_fetching_posts);
            return null;
        }
    }

    const fetchCategories = async (params = {}) => {
        try {
            const response = await fetch('/api/v1/categories/tree', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(params)
            });
            return await response.json()
        } catch (error) {
            toastr.error(translations.error_fetching_categories);
            return null;
        }
    }

    const bulkDeletePosts = async (params) => {
        try {
            const response = await fetch('/api/v1/posts/bulk-delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(params)
            });
            return await response.json()
        } catch (error) {
            toastr.error(translations.server_error)
            console.log('Error: ', error);
            return null;
        }
    }

    const bulkChangeStatusPosts = async (params) => {
        try {
            const response = await fetch('/api/v1/posts/bulk-change-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(params)
            });
            return await response.json()
        } catch (error) {
            toastr.error(translations.server_error)
            console.log('Error: ', error);
            return null;
        }
    }

    let [resultPosts, treeData] = await Promise.all([
        fetchPosts(),
        fetchCategories()
    ])

    await loadTree().then(() => console.log('Loaded tree'));

    nodeTree.on('select_node.jstree', async function (e, data) {
        const id = data.node.id
        resultPosts = await fetchPosts({
            'category_id': id
        })
        reDrawDataTable(resultPosts)
    });

    const validateDataInputEndDate = () => {
        const startDate = new Date(inputStartDate.val())
        const endDate = new Date(inputEndDate.val())

        if (startDate && (endDate < startDate)) {
            const date = new Date(startDate);

            const day = String(date.getUTCDate()).padStart(2, '0');
            const month = String(date.getUTCMonth() + 1).padStart(2, '0');
            const year = date.getUTCFullYear();

            inputEndDate.val(`${year}-${month}-${day}`)
        }
    }

    inputEndDate.on('change', function () {
        validateDataInputEndDate();
    })

    inputStartDate.on('change', function () {
        validateDataInputEndDate()
    })

    btnSearch.on('click', async function () {
        const params = {
            'organization_id': selectOrganization.val(),
            'start_date': inputStartDate.val(),
            'end_date': inputEndDate.val(),
            'status': selectStatus.val(),
        }

        await Promise.all([
            fetchPosts(params),
            fetchCategories({'organization_id': selectOrganization.val()})
        ]).then(response => {
            resultPosts = response[0]
            treeData = response[1]

        })

        await loadTree().then(() => console.log('Loaded tree'));

        reDrawDataTable(resultPosts)
    })

    if (dt_basic_table.length) {
        dt_basic = dt_basic_table.DataTable({
            data: resultPosts,
            columns: [
                {data: ''},
                {data: 'id'},
                {data: 'id'},
                {data: 'title'},
                {data: 'status'},
                {data: 'categories'},
                {data: 'organizations'},
                {data: 'views'},
                {data: 'created_at'},
                {data: 'updated_at'},
                {data: 'creator'},
                {data: 'updater'},
                {data: ''}
            ],
            columnDefs: [
                {
                    // For Responsive
                    className: 'control',
                    orderable: false,
                    searchable: false,
                    responsivePriority: 1,
                    targets: 0,
                    render: function () {
                        return '';
                    }
                },
                {
                    // For Checkboxes
                    targets: 1,
                    orderable: false,
                    searchable: false,
                    responsivePriority: 2,
                    render: function (data, type, full) {
                        return '<input type="checkbox" class="dt-checkboxes form-check-input" name="post-selected[]" value="' + full['id'] + '">';
                    },
                    checkboxes: {
                        selectAllRender: '<input type="checkbox" class="form-check-input selected-all-posts" >'
                    }
                },
                {
                    // For id
                    targets: 2,
                    searchable: true,
                    visible: true
                },
                {
                    // For title
                    targets: 3,
                    responsivePriority: 3,
                    render: function (data, type, full) {
                        const newPostDate = new Date(full['created_at']);
                        const daysToAdd = localStorage.getItem('new_post_date')
                            ? Number(localStorage.getItem('new_post_date'))
                            : 0;
                        newPostDate.setDate(newPostDate.getDate() + daysToAdd);
                        const isShowBadge = newPostDate > new Date()
                        return `
                            <a href="/app/posts/detail/${full['slug']}" class="h6 me-1" ${full['isViewed'] ? 'style="color:#8176f2"' : ''}>${full['title']}</a>
                            ${isShowBadge ? '<span class="badge bg-label-primary" style="font-size: 10px">' + translations.new + '</span>' : ''}
                        `;
                    }
                },
                {
                    // For status
                    targets: 4,
                    responsivePriority: 4,
                    render: function (data, type, full) {
                        let $status_number = full['status'];
                        let $status = postTypes.map((name, key) => {
                            let statusClass;
                            switch (key) {
                                case CONST.POST_APPROVED:
                                    statusClass = 'bg-label-success';
                                    break;
                                case CONST.POST_REJECTED:
                                    statusClass = 'bg-label-danger';
                                    break;
                                case CONST.POST_LOCKED:
                                    statusClass = 'bg-label-dark';
                                    break;
                                case CONST.POST_SCHEDULED:
                                    statusClass = 'bg-label-warning';
                                    break;
                                case CONST.POST_SUBMITTED:
                                default:
                                    statusClass = 'bg-label-info';
                                    break;
                            }
                            return {title: name, class: statusClass};
                        });
                        if (typeof $status[$status_number] === 'undefined') {
                            return data;
                        }
                        return (
                            `<span class='badge ${$status[$status_number].class}' data-status='${$status_number}'> ${$status[$status_number].title} </span>`
                        );
                    }
                },
                {
                    // For category
                    targets: 5,
                    responsivePriority: 5,
                    orderable: false,
                    render: function (data, type, full) {
                        const categories = full['categories'];
                        const isManyCategories = categories.length > 1
                        let html = ''
                        categories.forEach(function (item) {
                            html += `
                                <span 
                                    class="badge bg-label-info" 
                                    data-id="${item.id}" 
                                    ${isManyCategories ? 'style="margin-right: 3px"' : ''}>
                                    ${item.name}
                                </span>`
                        });
                        return (html);
                    }
                },
                {
                    // For organizations
                    targets: 6,
                    responsivePriority: 6,
                    orderable: false,
                    render: function (data, type, full) {
                        const organizations = full['organizations'];
                        const isManyOrganizations = organizations.length > 1
                        let html = ''
                        organizations.forEach(function (item) {
                            html += `
                                <span 
                                    class="badge bg-label-info" 
                                    data-id="${item.id}" 
                                    ${isManyOrganizations ? 'style="margin-right: 3px"' : ''}>
                                    ${item.name}
                                </span>`
                        });
                        return (html);
                    }
                },
                {
                    // For view
                    targets: 7,
                },
                {
                    // For create date
                    targets: 8,
                    render: function (data, type, full) {
                        const date = new Date(full['created_at']);

                        const day = String(date.getUTCDate()).padStart(2, '0');
                        const month = String(date.getUTCMonth() + 1).padStart(2, '0');
                        const year = date.getUTCFullYear();

                        return (`${day}-${month}-${year}`);
                    }
                },
                {
                    // For update date
                    targets: 9,
                    render: function (data, type, full) {
                        const date = new Date(full['updated_at']);

                        const day = String(date.getUTCDate()).padStart(2, '0');
                        const month = String(date.getUTCMonth() + 1).padStart(2, '0');
                        const year = date.getUTCFullYear();

                        return (`${day}-${month}-${year}`);
                    }
                },
                {
                    // For creator
                    targets: 10,
                },
                {
                    // For updater
                    targets: 11,
                },
                {
                    targets: -1,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, full) {
                        titleCopy = `Copy - ${full['title']}`
                        thumbnailCopy = full['thumbnail']
                        contentCopy = full['content']
                        organizationsCopy = full['organizations']
                        categoriesCopy = full['categories']
                        scheduledDateCopy = full['scheduled_date']

                        postsCopy.push({
                            'id': full['id'],
                            'title': titleCopy,
                            'organizations': organizationsCopy,
                            'categories': categoriesCopy,
                            'thumbnail': thumbnailCopy,
                            'content': contentCopy,
                            'scheduled_date': scheduledDateCopy,
                        })

                        let status = full['status'];
                        let editButton = status === CONST.POST_DRAFT || window.hasAdmin ? `<a href="/app/posts/detail/${full['slug']}" class="btn btn-sm btn-text-secondary rounded-pill btn-icon item-edit" title="${translations.edit}">
                                    <i class="ti ti-pencil ti-md"></i>
                                </a>` : '';

                        return (
                            ` ${editButton}
                                <button class="btn btn-sm btn-text-secondary rounded-pill btn-icon hide-arrow duplicate-post" title="${translations.clone_post}" data-id="${full['id']}">
                                    <i class="ti ti-copy ti-md"></i>
                                </button>
                            `
                        );
                    }
                }
            ],
            order: [[2, 'desc']],
            dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-6 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end mt-n6 mt-md-0"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            displayLength: 10,
            lengthMenu: [10, 25, 50, 75, 100],
            language: getDataTableLanguage(translations.search_post),
            buttons: [
                generateButton(),
            ],
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function () {
                            return translations.post_detail;
                        }
                    }),
                    type: 'column',
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col) {
                            return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
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
            initComplete: function () {
                $('.card-header').after('<hr class="my-0">');
            }
        });
    }

    const btnBulkDeletePost = $('.delete-post');
    const btnBulkChangeStatus = $('.change-status');
    const btnBulkChangeReview = $('.change-review');
    const btnBulkCancelReview = $('.cancel-review');

    btnBulkDeletePost.prop('disabled', true)
    btnBulkChangeStatus.prop('disabled', true)
    btnBulkChangeReview.prop('disabled', true);
    btnBulkCancelReview.prop('disabled', true);

    $(document).on('change', '.selected-all-posts', function () {
        checkDisableButtonOnHeader();
        checkDisableButtonReview();
    });

    $(document).on('change', 'input[name="post-selected[]"]', function () {
        checkDisableButtonOnHeader();
        checkDisableButtonReview();
    });

    btnBulkChangeReview.on('click', async function () {
        Swal.fire({
            title: translations.confirm_send_review,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: translations.yes,
            cancelButtonText: translations.no,
            html: `
            <div class="reason-input-wrapper mt-3">
                <label for="note" class="form-label text-start d-block mb-2">${translations.reason_for_send_review}:</label>
                <textarea id="note" class="form-control" rows="3" name="note" placeholder="${translations.reason_for_change_input}" style="resize: none"></textarea>
            </div>
            `,
            customClass: {
                confirmButton: 'btn btn-primary me-3',
                cancelButton: 'btn btn-label-secondary'
            },
            preConfirm: () => {
                localStorage.setItem('note', $('#note').val())
            },
            buttonsStyling: false
        }).then(async function (result) {
            if (result.value) {
                await processStatusChange(CONST.POST_SUBMITTED);
            }
        });
    });

    btnBulkDeletePost.on('click', async function () {
        const postIds = []
        $('input[name="post-selected[]"]:checked').each(function () {
            postIds.push(+$(this).val());
        });

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
                await bulkDeletePosts({'post_ids': postIds})

                resultPosts = await fetchPosts()

                reDrawDataTable(resultPosts)
            }
        });
    })

    btnBulkChangeStatus.on('click', async function () {
        handleBulkStatusChange();
    });

    btnBulkCancelReview.on('click', async function () {
        handleBulkCancelReview();
    });

    const handleBulkCancelReview = () => {
        Swal.fire({
            title: translations.confirm_delete,
            text: translations.confirm_cancel_review,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: translations.yes,
            cancelButtonText: translations.no,
            customClass: {
                confirmButton: 'btn btn-primary me-3',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then(async function (result) {
            if (result.value) {
                await processStatusChange(CONST.POST_DRAFT);
            }
        });
    }

    const showConfirmationPopup = () => {
        return Swal.fire({
            title: translations.confirm_delete,
            text: translations.change_status_post,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: translations.yes_change_it,
            cancelButtonText: translations.no,
            customClass: {
                confirmButton: 'btn btn-primary me-3',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        });
    };

    const processStatusChange = async (status) => {
        const postIds = []
        $('input[name="post-selected[]"]:checked').each(function () {
            postIds.push(+$(this).val());
        });
        const note = localStorage.getItem('note')
        await bulkChangeStatusPosts({'post_ids': postIds, 'status': status, 'note': note})
        resultPosts = await fetchPosts()
        reDrawDataTable(resultPosts)
    };

    const showStatusSelectionPopup = () => {
        return Swal.fire({
            title: translations.choose_post_status,
            html: `
            <div class="status-selection-wrapper">
                <label for="post-status" class="form-label text-start d-block mb-2">${translations.status}:</label>
                <select id="post-status" class="status-select form-control select2 status-select2 z-3">
                    ${postTypes.map((name, key) => `<option value="${key}">${name}</option>`).join('')}
                </select>
            </div>
            <div class="reason-input-wrapper mt-3">
                <label for="note" class="form-label text-start d-block mb-2">${translations.reason_for_change}:</label>
                <textarea id="note" class="form-control" rows="3" name="note" placeholder="${translations.reason_for_change_input}" style="resize: none"></textarea>
            </div>
            `,
            didOpen: () => {
                initSelect2('#post-status', translations.select_status_post, true);
                $('div.swal2-actions').css('z-index', '0');
            },
            customClass: {
                confirmButton: 'btn btn-primary me-3',
                cancelButton: 'btn btn-label-secondary',
                popup: 'custom-swal-popup'
            },
            showCancelButton: true,
            confirmButtonText: translations.yes_change_it,
            cancelButtonText: translations.no,
            preConfirm: () => {
                localStorage.setItem('note', $('#note').val())
                return $('#post-status').val();
            }
        });
    };

    const handleBulkStatusChange = () => {
        showStatusSelectionPopup()
            .then((result) => {
                if (result.isConfirmed) {
                    const selectedStatus = result.value;
                    showConfirmationPopup(selectedStatus)
                        .then((confirmResult) => {
                            if (confirmResult.isConfirmed) {
                                processStatusChange(selectedStatus);
                            }
                        });
                }
            });
    };

    $(document).on('click', '.duplicate-post', function () {
        const id = $(this).attr('data-id')
        const post = postsCopy.find(item => item.id == id)
        localStorage.setItem('duplicate_post', JSON.stringify(post))
        window.open('/app/posts/create?duplicate=true', '_blank')
    });


    const initSelect2 = (selector, placeholder, hasParent = false) => {
        let option = {
            placeholder: placeholder,
            ...translationNoResultSelect2().config
        }
        hasParent ? option.dropdownParent = $(selector).parent() : null;
        $(selector).wrap('<div class="position-relative"></div>').select2(option);
    };

    const initDatePicker = (selector) => {
        selector.datepicker({
            todayHighlight: true,
            format: locale === 'vn' ? 'dd-mm-yyyy' : 'yyyy-mm-dd'
        })
    }

    initDatePicker(inputStartDate);
    initDatePicker(inputEndDate);

    initSelect2('.status', translations.select_status_post)

    initSelect2('.organization-post', translations.please_select_post)

    setTimeout(() => {
        $('.dataTables_filter .form-control').removeClass('form-control-sm');
        $('.dataTables_length .form-select').removeClass('form-select-sm');
    }, 300);

});
