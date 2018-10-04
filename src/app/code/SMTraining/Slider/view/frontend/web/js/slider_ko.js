define([
    'ko',
    'uiComponent',
    'mage/url',
    'mage/storage',
], function (ko, Component, urlBuilder,storage) {
    'use strict';
    var page = 1;

    return Component.extend({

        defaults: {
            template: 'SMTraining_Slider/slider_ko',
        },

        sliderList: ko.observableArray([]),

        getSliders: function (p) {
            var self = this;
            page = p;
            var serviceUrl = urlBuilder.build('sliders/slider/koslider?p=' + page);
            // page ++;
            return storage.post(
                serviceUrl,
                ''
            ).done(
                function (response) {
                    console.log(JSON.parse(response));
                    self.sliderList.removeAll();
                    ko.utils.arrayPushAll(self.sliderList, JSON.parse(response))
                }
            ).fail(
                function (response) {
                    alert(response);
                }
            );
        },

        initialize: function () {
            //initialize parent Component
            this._super();
            this.getSliders();
        },
    });
});