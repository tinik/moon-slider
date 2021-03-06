define([
    'Magento_Ui/js/form/components/insert-form'
], function (Insert) {
    'use strict';

    return Insert.extend({
        defaults: {
            listens: {
                responseData: 'onResponse'
            },
            modules: {
                sliderListing: '${ $.sliderListingProvider }',
                sliderModal: '${ $.sliderModalProvider }'
            },
            params: {
                namespace: '${ $.ns }',
            },
        },

        onResponse: function (responseData) {
            debugger;

            var data;

            if (!responseData.error) {
                this.sliderModal().closeModal();
                this.sliderListing().reload({
                    refresh: true
                });

                data = this.externalSource().get('data');
                this.saveSlide(responseData, data);
            }
        },

        saveSlide: function (responseData, data) {
            debugger;

            // data['entity_id'] = responseData.data['entity_id'];
            //
            // if (parseFloat(data['default_billing'])) {
            //     this.source.set('data.default_billing_address', data);
            // } else if (
            //     parseFloat(this.source.get('data.default_billing_address')['entity_id']) === data['entity_id']
            // ) {
            //     this.source.set('data.default_billing_address', []);
            // }
            //
            // if (parseFloat(data['default_shipping'])) {
            //     this.source.set('data.default_shipping_address', data);
            // } else if (
            //     parseFloat(this.source.get('data.default_shipping_address')['entity_id']) === data['entity_id']
            // ) {
            //     this.source.set('data.default_shipping_address', []);
            // }
        },
    });
});
