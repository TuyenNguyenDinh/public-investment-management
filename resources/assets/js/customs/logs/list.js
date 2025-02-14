'use strict';

import {getDataTableLanguage} from "../../config.js";

$(function () {
    let dt_activity_table = $('.datatables-activity');

    if (dt_activity_table.length) {
        dt_activity_table.DataTable({
            ajax: '/api/v1/logs',
            columns: [
                {data: 'user_name'},
                {data: 'organization_name'},
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
                        return '<span class="wrap">' + full['user_name'] + '</span>';
                    }
                },
                {
                    targets: 1,
                    render: function (data, type, full) {
                        return '<span class="wrap">' + full['organization_name'] + '</span>';
                    }
                },
                {
                    targets: 2,
                    render: function (data, type, full) {
                        return '<span class="wrap">' + full['description'] + '</span>';
                    }
                },
                {
                    targets: 3,
                    render: function (data, type, full, meta) {
                        return '<span class="wrap">' + full['route'] + '</span>';
                    }
                },
                {
                    targets: 4,
                    render: function (data, type, full) {
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
                    targets: 5,
                    render: function (data, type, full) {
                        return '<span class="text-nowrap">' + full['ip_address'] + '</span>';
                    }
                },
                {
                    targets: 6,
                    render: function (data, type, full) {
                        return '<span class="text-nowrap">' + full['user_agent'] + '</span>';
                    }
                },
                {
                    targets: 7,
                    render: function (data, type, full) {
                        return '<span class="text-nowrap">' + full['country'] + '</span>';
                    }
                },
                {
                    targets: 8,
                    render: function (data, type, full) {
                        return '<span class="text-nowrap">' + full['created_at'] + '</span>';
                    }
                },
            ],
            order: [[8, 'desc']],
            scrollY: '300px',
            scrollX: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end mt-n6 mt-md-0"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            language: getDataTableLanguage(translations.search_activities),
        });
    }
});
