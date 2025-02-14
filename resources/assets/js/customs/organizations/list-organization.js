'use strict';

import {translationNoResultSelect2} from "../../config.js";

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
    let selectAddParent = $('#addOrganizationFom #parent-id');
    let selectUpdateParent = $('#editOrganizationFom #parent-update-id');
    $.each(treeData, function (index, data) {
        selectAddParent.append(`<option value='${data.id}'>${prefix} ${data.text}</option>`)
        selectUpdateParent.append(`<option value='${data.id}'>${prefix} ${data.text}</option>`)
        loadSelectParentOrganizationTraverse(data.children, `${prefix}-`);
    })
}

const fetchOrganizationApi = async () => {
    try {
        const response = await fetch('/api/v1/organizations', {
            method: 'GET',
        });
        const data = await response.json();
        if (data.status === 200) {
            return data.data;
        } else {
            toastr.error(translations.organization_update + ' ' + translations.organization_delete);
            console.log(data.message);
            return null;
        }
    } catch (error) {
        toastr.error(translations.organization_update + ' ' + translations.organization_delete);
        console.log('Error: ', error);
        return null;
    }
}

const showOrganizationDetail = () => {
    let formDetail = $('.card-detail-organization');
    nodeTree.on('select_node.jstree', function (e, data) {
        let nodeId = data.node.id;
        let api = `/api/v1/organizations/${nodeId}`;
        fetch(api, {
            method: 'GET',
        })
            .then(response => response.json())
            .then(data => {
                $(formDetail).find('input#name').val(data.data.text)
                $(formDetail).find('textarea#description').val(data.data.description)
                $(formDetail).find('input#phone-number').val(data.data.phone_number)
                $(formDetail).find('input#address').val(data.data.address)
                $(formDetail).find('input#tax-code').val(data.data.tax_code)
                if (data.data.text !== null) {
                    $(formDetail).find('select#parent-update-id').val(data.data.parent_id)
                    $(formDetail).find('select#parent-update-id').trigger('change')
                }
                $(formDetail).find('#editOrganizationFom').attr('action', api)
                $(formDetail).find('#deleteOrganization').attr('data-id', nodeId)
                formDetail.removeClass('invisible')
                formDetail.addClass('visible')
            })
            .catch(error => {
                toastr.error(translations.organization_update + ' ' + translations.organization_delete);
            });
    })
}

const initSelect2 = (selector) => {
    let selectOrganization = translations.select_organization;
    $(selector).wrap('<div class="position-relative"></div>').select2({
        placeholder: `${selectOrganization}`,
        dropdownParent: $(selector).parent(),
        ...translationNoResultSelect2().config
    });
};


const initialize = async () => {
    const treeData = await fetchOrganizationApi();

    showOrganizationDetail();
    initSelect2('#parent-id');
    initSelect2('#parent-update-id');

    await loadTree(treeData);

    await loadSelectParentOrganizationTraverse(treeData);
};

$(function () {
    initialize().then(r => console.log('Organizations loaded'));
});
