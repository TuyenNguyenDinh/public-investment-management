/**
 * App eCommerce Settings Script
 */
'use strict';

//Javascript to handle the e-commerce settings page

import {translationNoResultSelect2} from "../../config.js";

$(function () {
    // Select2
    const select2 = $('.select2');
    if (select2.length) {
        select2.each(function () {
            const $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
                dropdownParent: $this.parent(),
                ...translationNoResultSelect2().config,
                placeholder: $this.data('placeholder') // for dynamic placeholder
            });
        });
    }
});
