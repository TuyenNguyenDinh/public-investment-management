'use strict';

import {fetchCategories, translationNoResultSelect2} from "../../config.js";

const nodeTree = $('#jstree-ajax');
const loadTree = async (treeData) => {
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
                }
            }
        }).on('loaded.jstree', function () {
            // Expand all nodes after the tree is fully loaded
            nodeTree.jstree('open_all');
        });
    }
}

const loadSelectParentOrganizationTraverse = async (treeData, prefix = '-') => {
    let selectAddParent = $('#addOrganizationFom #createParentId');
    let selectUpdateParent = $('#editOrganizationFom #updateParentId');
    $.each(treeData, function (index, data) {
        selectAddParent.append(`<option value='${data.id}'>${prefix} ${data.text}</option>`)
        selectUpdateParent.append(`<option value='${data.id}'>${prefix} ${data.text}</option>`)
        loadSelectParentOrganizationTraverse(data.children, `${prefix}-`);
    })
}

const showCategoryDetail = async () => {
    let formDetail = $('.card-detail-category');
    nodeTree.on('select_node.jstree', async function (e, data) {
        $("#overlay").fadeIn(300);
        let nodeId = data.node.id;
        let api = `/api/v1/categories/${nodeId}`;
        await fetch(api, {
            method: 'GET',
        })
            .then(response => response.json())
            .then(async (data) => {
                $(formDetail).find('input#name').val(data.text)
                if (data.text !== null) {
                    let selectParent = $(formDetail).find('select.update-category-parent');
                    selectParent.val(data.parent_id).trigger('change');
                    let selectOrganizations = $(formDetail).find('select.updateOrganizations');
                    selectOrganizations.val(data.organizations[0].id).trigger('change');
                }
                $(formDetail).find('#editOrganizationFom').attr('action', api)
                $(formDetail).find('#deleteOrganization').attr('data-id', nodeId)
                formDetail.removeClass('invisible')
                formDetail.addClass('visible')
            })
            .catch(error => {
                toastr.error(translations.server_error);
                console.error('Error:', error);
            }).finally(() => {
                $("#overlay").fadeOut(300);
            });
    })
}
const initSelect2 = (selector, placeholder, hasParent = false) => {
    let option = {
        placeholder: placeholder,
        ...translationNoResultSelect2().config
    }
    hasParent ? option.dropdownParent = $(selector).parent() : null;
    $(selector).wrap('<div class="position-relative"></div>').select2(option);
};


const initialize = async () => {
    $("#overlay").fadeIn(300);
    const treeData = await fetchCategories(true);

    await showCategoryDetail();
    await loadTree(treeData);
    await loadSelectParentOrganizationTraverse(treeData);
    $("#overlay").fadeOut(300);
};

$(function () {
    initialize().then(r => console.log('Categories loaded'));

    initSelect2('.update-category-parent', translations.select_category);

    initSelect2('.create-category-parent', translations.select_category, true);

    initSelect2('#organizations', translations.select_organization, true);

    initSelect2('#updateOrganizations', translations.select_organization, true);
});
