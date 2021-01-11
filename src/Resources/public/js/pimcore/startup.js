pimcore.registerNS("pimcore.plugin.MonitoringManagerBundle");

pimcore.plugin.MonitoringManagerBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.MonitoringManagerBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {
        // For admin users, add a menu item to display the status overview
        var user = pimcore.globalmanager.get("user");
        if (user.admin) {
            var monitoringOverviewMenu = new Ext.Action({
                text: "Monitoring status overview",
                iconCls: "pimcore_nav_icon_commerce_backoffice",
                handler: this.showMonitoringStatusOverview
            });
            layoutToolbar.extrasMenu.add(monitoringOverviewMenu);
        }
    },

    /**
     * Open the overview page in an embedded iframe
     */
    showMonitoringStatusOverview: function () {
        pimcore.helpers.openGenericIframeWindow(
            "monitoring_manager_status_overview",
            '/monitoring/overview',
            "pimcore_icon_multiselect",
            "Monitoring status overview"
        );
    }
});

var MonitoringManagerBundlePlugin = new pimcore.plugin.MonitoringManagerBundle();
