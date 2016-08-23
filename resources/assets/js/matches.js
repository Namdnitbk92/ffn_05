var matches = (function () {
    var container = $('#Menu');
    if (!container) {
        return;
    }
    var getList = function () {
        $.ajax({
            url: 'matches',
            type: 'GET',
            beforeSend: function () {
                container.addClass('ui loading form');
            }
        }).done(function (res) {
            container.removeClass('ui loading form');
            var matches = res.records;
            var datafields = res.datafields;
            var leagues = res.leagues;
            this.setConfig(datafields, matches);
            this.setDatafields(
                [
                    {name: 'id', type: 'string'},
                    {name: 'home_id', type: 'int'},
                    {name: 'guest_id', type: 'int'},
                    {name: 'league_season_id', type: 'int'},
                    {name: 'result', type: 'string'},
                    {name: 'location', type: 'string'},
                    {name: 'rate', type: 'float'},
                    {name: 'start', type: 'datetime'},
                    {name: 'end', type: 'datetime'},
                ]
            );

            this.initGrid($('#jqxgrid'), {menu: $('#Menu')},
                function (grid, event) {
                    var args = event.args;
                    var rowindex = grid.jqxGrid('getselectedrowindex');
                    var row = grid.jqxGrid('getrowdata', rowindex);
                    var content = $.trim($(args).text());
                    if (content == 'Edit Selected Row') {
                        localStorage.setItem('action_form', 'edit');
                        window.location.href = 'matches/' + row.id + '/edit';
                    } else if (content == 'Add New Row') {
                        localStorage.setItem('action_form', 'create');
                        window.location.href = 'matches/create';
                    } else if (content == 'Bet a Match') {
                        $('#usermatchWindow').jqxWindow('open');
                    }
                }
            );
        }.bind(this));
    }.bind(this);

    var initFormCreate = function () {
        var grid = $('#team_list');
        if (!grid) {
            return;
        }
        var action = localStorage.getItem('action_form');
        var url = action != null ? action : 'create';
        $.ajax({
            url: url,
            type: 'GET',
            beforeSend: function () {
                grid.addClass('ui loading form');
            }
        }).done(function (res) {
            grid.removeClass('ui loading form');
            var teams = res.records;
            var datafields = res.datafields;
            var leagues = res.leagues;
            this.setConfig(datafields, teams);
            this.setDropDownList($('#league-list'), $('input[name="league_season_id"]'), leagues);
            this.setDatafields(
                [
                    {name: 'id', type: 'int'},
                    {name: 'name', type: 'string'},
                    {name: 'logo', type: 'string'},
                    {name: 'country_id', type: 'string'},
                    {name: 'description', type: 'string'},
                ]
            );
            this.initGrid(grid, null, null);

            var events = res.events;
            var datafields_events = res.datafields_events;
            this.setConfig(datafields_events, events);
            this.setDatafields(
                [
                    {name: 'id', type: 'int'},
                    {name: 'content', type: 'string'},
                    {name: 'time', type: 'date'},
                ]
            );

            this.initGrid($('#events_list'), {menu: $('#menu-events'), height: '106px', width: '160px'},
                function (grid, event) {
                    var args = event.args;
                    var rowindex = grid.jqxGrid('getselectedrowindex');
                    var row = grid.jqxGrid('getrowdata', rowindex);
                    var content = $.trim($(args).text());
                    if (content == 'Edit Selected Row') {

                    } else if (content == 'Add New Row') {
                        grid.jqxGrid('addrow', null, {});
                    } else if (content == 'Delete Selected Row') {
                        grid.jqxGrid('deleterow', rowindex);
                    }
                }
            );

        }.bind(this));

        var rate = $('#rate');
        var league_list = $('#league-list');
        rate.jqxNumberInput({theme: 'ui-redmond', width: '325px', height: '35px', spinButtons: true});
        var val = $('input[name="rate"]').val();
        val ? rate.jqxNumberInput('val', val) : '';

        rate.change(function () {
            $('input[name="rate"]').val(rate.val());
        })

        league_list.change(function () {
            $('input[name="league_season_id"]').val(league_list.val());
        })
    }.bind(this);

    var run = function () {
        getList();
        initFormCreate();
        app.initMapWindow($('input[name="address"]'), $('#window'));
        app.initMapWindow($('#jqxgrid'), $('#usermatchWindow'));
    }

    return run();
}).bind(gridBuilder);