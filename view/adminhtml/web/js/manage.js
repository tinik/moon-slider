define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function ($) {
    'use strict';

    return function (config, node) {
        debugger;

        $(node).on('click', () => {
            alert('Test - 2');
        });
    };
});
