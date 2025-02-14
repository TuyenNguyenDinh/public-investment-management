'use strict';

(function () {
    const customDatepicker = $('.custom-datepicker');
    if (customDatepicker.length) {
        customDatepicker.each(function () {
            $(this).datepicker({
                todayHighlight: true,
                orientation: isRtl ? 'auto right' : 'auto left',
                language: locale === 'vn' ? 'vi' : 'en',
                format: locale === 'vn' ? 'dd-mm-yyyy' : 'yyyy-mm-dd'
            });
        });
    }
})();
