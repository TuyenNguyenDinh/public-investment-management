'use strict';

import {apiRequest, getDataTableLanguage} from "../../config.js";

const fetchLogActivityData = async () => {
    const res = await apiRequest('/api/v1/log_activities');
    return res && res.status === 200 ? res.data : null;
}

$(async function () {
    let dt_activity_table = $('.datatables-activity');
    const logActivityData = await fetchLogActivityData();
    if (logActivityData) {
        dt_activity_table.DataTable({
            data: logActivityData,
            columns: [
                {data: 'description'},
                {data: 'route'},
                {data: 'method'},
                {data: 'ip_address'},
                {data: 'user_agent'},
                {data: 'country'},
                {data: 'created_at'}
            ],
            columnDefs: [
                {
                    targets: 0,
                    render: function (data, type, full, meta) {
                        return '<span class="wrap">' + full['description'] + '</span>';
                    }
                },

                {
                    targets: 1,
                    render: function (data, type, full, meta) {
                        return '<span class="wrap">' + full['route'] + '</span>';
                    }
                },
                {
                    targets: 2,
                    render: function (data, type, full, meta) {
                        let method = full['method_type'];
                        let methodArr = {
                            'GET': { title: method, class: 'bg-label-success' },
                            'POST': { title: method, class: ' bg-label-warning' },
                            'PUT': { title: method, class: ' bg-label-warning' },
                            'PATCH': { title: method, class: ' bg-label-warning' },
                            'DELETE': { title: method, class: ' bg-label-danger' }
                        };
                        return '<span class="badge ' + methodArr[method].class + '">' + full['method_type'] + '</span>';
                    }
                },
                {
                    targets: 3,
                    render: function (data, type, full, meta) {
                        return '<span class="text-nowrap">' + full['ip_address'] + '</span>';
                    }
                },
                {
                    targets: 4,
                    render: function (data, type, full, meta) {
                        return '<span class="text-nowrap">' + full['user_agent'] + '</span>';
                    }
                },
                {
                    targets: 5,
                    render: function (data, type, full, meta) {
                        return '<span class="text-nowrap">' + full['country'] + '</span>';
                    }
                },
                {
                    targets: 6,
                    render: function (data, type, full, meta) {
                        return '<span class="text-nowrap">' + full['created_at'] + '</span>';
                    }
                },
            ],
            order: [[6, 'desc']],
            scrollY: '300px',
            scrollX: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end mt-n6 mt-md-0"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            language: getDataTableLanguage(translations.search_activities),
        });
    }
});
