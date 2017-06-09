/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'mageUtils',
    './abstract'
], function (utils, Abstract) {
    'use strict';

    return Abstract.extend({
        defaults: {
            links: {
                value: ''
            }
        },

        initConfig: function () {
            var uid,name,scope,valueUpdate;
            this._super();

            scope   = this.dataScope;
            name    = scope.split('.').slice(1);

            valueUpdate = this.showFallbackReset ? 'afterkeydown' : this.valueUpdate;

            _.extend(this, {
                uid: 'upload_' + name[2] + '_' + name[4],
                noticeId: 'notice-' + uid,
                inputName: utils.serializeName(name.join('.')),
                valueUpdate: valueUpdate
            });
            return this;
        },
        /**
         * Initializes file component.
         *
         * @returns {Media} Chainable.
         */
        initialize: function () {
            this._super()
                .initFormId();

            return this;
        },

        /**
         * Defines form ID with which file input will be associated.
         *
         * @returns {Media} Chainable.
         */
        initFormId: function () {
            var namespace;

            if (this.formId) {
                return this;
            }

            namespace   = this.name.split('.');
            this.formId = namespace[0];

            return this;
        }
    });
});
