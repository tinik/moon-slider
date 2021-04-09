define([
    'underscore',
    'Magento_Ui/js/form/components/insert-listing',
], (_, InsertListing) => {
    'use strict';

    return InsertListing.extend({
        defaults: {
            links: {
                slide_id: '${ $.provider }:data.slide_id',
                store_id: '${ $.provider }:data.store_id',
            },
            imports: {
                data: '${ $.provider }:${ $.dataScope }',
            },
        },
        initialize: function () {
            this._super();

            this.params['slide_id'] = this.slide_id;
        },
        onAction: function (data) {
            debugger;
            this[data.action + 'Action'].call(this, data.data);
        },

        /**
         * On mass action call
         *
         * @param {Object} data - customer address
         */
        onMassAction: function (data) {
            debugger;
            this[data.action + 'Massaction'].call(this, data.data);
        },

        /**
         * Delete customer address
         *
         * @param {Object} data - customer address
         */
        deleteAction: function (data) {
            debugger;
            var item_id = _.get(data, 'item_id');
            this._delete([parseFloat(data[item_id])]);
        },

        /**
         * Mass action delete
         *
         * @param {Object} data - customer address
         */
        deleteMassaction: function (data) {
            debugger;
            var ids = data.selected || this.selections().selected();
            ids = _.map(ids, val => parseFloat(val));

            this._delete(ids);
        },

        /**
         * Delete customer address and selections by provided ids.
         *
         * @param {Array} ids
         */
        _delete: function (ids) {
            debugger;

            var selections = this.selections();
            if (selections) {
                _.each(ids, function (id) {
                    this.selections().deselect(id.toString(), false);
                }, this);
            }
        }
    });
});
