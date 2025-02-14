import {translationNoResultSelect2} from "../../config.js";

$(function() {
    const btnChooseOrganization = $('#chooseOrganization')
    const selectChooseOrganization = $('#organization')

    const loadSelectParentOrganizationTraverse = (treeData, prefix = '-') => {
        $.each(treeData, function (index, data) {
            selectChooseOrganization.append(`<option value='${data.id}'>${prefix} ${data.text}</option>`);
            if (data.children && data.children.length > 0) {
                loadSelectParentOrganizationTraverse(data.children, `${prefix}-`); // Tăng cấp bậc khi đi sâu vào tổ chức con
            }
        });
    };

    const initSelect2 = (selector, placeholder) => {
        $(selector).wrap('<div class="position-relative"></div>').select2({
            placeholder: placeholder,
            dropdownParent: $(selector).parent(),
            ...translationNoResultSelect2().config
        });
    };

    initSelect2('#organization', translations.select_organization);

    selectChooseOrganization.on('change', function () {
        btnChooseOrganization.prop('disabled', !selectChooseOrganization.val())
    })

    btnChooseOrganization.on('click', function () {
        $.ajax({
            type: 'POST',
            url: `${baseUrl}api/v1/choose-organization`,
            data: {
                'organization': $(`select[name='organization']`).val(),
                '_token': document.querySelector('meta[name="csrf-token"]').content,
            },
            success: function () {
                window.location.replace('/')
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    loadSelectParentOrganizationTraverse(organizations);

});
