var notificationsBuilder = new function () {
    this.initNotificationsList = function (data) {
        if (data) {
            this.setConfig(data.datafields, data.notifications);
            this.setDatafields(
                [
                    {name: 'id', type: 'int'},
                    {name: 'message', type: 'string'},
                    {name: 'status', type: 'string'},
                    {name: 'user_id', type: 'string'},
                    {name: 'user_name', type: 'string'},
                    {name: 'create_at', type: 'datetime'},
                ]
            );

            this.initGrid($('#notifications-list'), {}, null);
            var _window = $('#notifications-window');
            var offset = $('.ui.segment.content').offset();
            _window.jqxWindow({
                position: {x: offset.left + 50, y: offset.top + 50},
                theme: 'ui-redmond',
                showCollapseButton: true,
                maxHeight: 800,
                maxWidth: 1000,
                minHeight: 200,
                minWidth: 200,
                height: 500,
                width: 740,
                initContent: function () {
                    _window.jqxWindow('focus');
                }
            });
            _window.jqxWindow('open');
        }

    }.bind(gridBuilder);

    this.displayMessage = function (callback) {
        $.ajax({
            url: 'getListNotifications',
            type: 'POST',
        }).done(function (res) {
            if (res.notifications) {
                this.initNotificationsList(res);
            }
        }.bind(this));
    }
}