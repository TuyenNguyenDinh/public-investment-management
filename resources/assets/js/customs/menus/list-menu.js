'use strict';

import {CSRF_TOKEN, translationNoResultSelect2} from "../../config.js";

$(function () {
    const nodeTree = $('#jstree-ajax');
    const ALLOW_DELETE = 1;
    const DENY_DELETE = 0;

    const apiRequest = async (url, method = 'GET', body = null) => {
        try {
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
            return null;
        }
    };

    const fetchMenuApi = async () => {
        const data = await apiRequest('/api/v1/menus');
        return data && data.status === 200 ? data.data : null;
    };

    const loadTree = async (treeData) => {
        if (!nodeTree.length) return;
        nodeTree.jstree({
            core: {
                themes: {
                    name: $('html').hasClass('light-style') ? 'default' : 'default-dark'
                },
                data: treeData,
                check_callback: (operation, node, parent) => {
                    if (operation === "move_node" && node.original.group_menu_flag) {
                        return parent.id === '#';  // Allow only top-level drops
                    }
                    return true;
                }
            },
            dnd: {
                is_draggable: () => window.menuPermission.Update,
            },
            plugins: ['types', 'dnd', 'wholerow'],
            types: {
                default: {icon: 'ti ti-folder'},
                html: {icon: 'ti ti-brand-html5 text-danger'},
                css: {icon: 'ti ti-brand-css3 text-info'},
                img: {icon: 'ti ti-photo text-success'},
                js: {icon: 'ti ti-brand-javascript text-warning'},
            }
        }).on('move_node.jstree', async () => {
            const newTree = nodeTree.jstree(true).get_json('#', {'flat': false});
            const result = await apiRequest('/api/v1/menus/bulk-update', 'PUT', {new_tree: newTree});
            if (result && result.code === 200) {
                window.location.reload();
            } else {
                toastr.error(translations.server_error)
                console.log(result?.message);
            }
        });
    };

    const loadSelectParentMenuTraverse = (treeData, prefix = '-') => {
        const selectAddParent = $('#addMenuForm #parent-id');
        const selectUpdateParent = $('#editMenuForm #parent-update-id');
        treeData.forEach(data => {
            const option = `<option value='${data.id}'>${prefix} ${data.text}</option>`;
            selectAddParent.append(option);
            selectUpdateParent.append(option);
            loadSelectParentMenuTraverse(data.children, `${prefix}-`);
        });
    };

    const updateFormFields = (formDetail, data) => {
        const {text, icon, slug, url, parent_id, group_menu_flag, allow_delete} = data.data;
        const menuFlag = formDetail.find('input#group-menu-flag');
        const iconInput = formDetail.find('input#icon');
        const slugInput = formDetail.find('input#slug');
        const urlInput = formDetail.find('input#url');
        const selectParent = formDetail.find('select#parent-update-id');

        formDetail.find('input#name').val(text);
        menuFlag.prop('checked', group_menu_flag);
        iconInput.val(group_menu_flag ? '' : icon).prop('disabled', group_menu_flag);
        slugInput.val(group_menu_flag ? '' : slug).prop('disabled', group_menu_flag);
        urlInput.val(group_menu_flag ? '' : url).prop('disabled', group_menu_flag);
        if (group_menu_flag) {
            selectParent.val('')
            selectParent.trigger('change')
            selectParent.prop('disabled', true);
        } else {
            selectParent.val(parent_id)
            selectParent.trigger('change')
            selectParent.prop('disabled', false);
        }

        const deleteButton = formDetail.find('#deleteMenu');
        deleteButton.prop('disabled', allow_delete === DENY_DELETE).attr('data-id', allow_delete === ALLOW_DELETE ? data.data.id : null);

        formDetail.removeClass('invisible').addClass('visible');
    };

    const showMenuDetail = () => {
        const formDetail = $('.card-detail-menu');
        nodeTree.on('select_node.jstree', async (e, data) => {
            const menuData = await apiRequest(`/api/v1/menus/${data.node.id}`);
            if (menuData) updateFormFields(formDetail, menuData);
            formDetail.find('#editMenuForm').attr('action', `/api/v1/menus/${data.node.id}`);
        });
    };

    const initialize = async () => {
        const treeData = await fetchMenuApi();
        if (treeData) {
            await loadTree(treeData);
            loadSelectParentMenuTraverse(treeData);
            showMenuDetail();
            console.log('Tree and options loaded');
        }
    };

    const initSelect2 = (selector) => {
        let select = translations.select_menu;
        $(selector).wrap('<div class="position-relative"></div>').select2({
            placeholder: `${select}`,
            dropdownParent: $(selector).parent(),
            ...translationNoResultSelect2().config
        });
    };
    initSelect2('#parent-id');
    initSelect2('#parent-update-id');

    initialize().then(r => console.log('Menus loaded'));
})
