define([
    "Splide",
    "domReady!",
], function () {
    "use strict";

    return function (config, node) {
        let options = Object.assign(config || {}, {
            cover: true,
            perPage: 1,
        });

        new window.Splide(node, options).mount();
    }
});
