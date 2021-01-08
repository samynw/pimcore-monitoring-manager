pimcore.registerNS("pimcore.plugin.MonitoringManagerBundle");

pimcore.plugin.MonitoringManagerBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.MonitoringManagerBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {
        // alert("MonitoringManagerBundle ready!");
    }
});

var MonitoringManagerBundlePlugin = new pimcore.plugin.MonitoringManagerBundle();
