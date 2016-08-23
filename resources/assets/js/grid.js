var gridBuilder = new function () {
    this.labels = [];
    this.setConfig = function (datafields, records) {
        this.datafields = datafields;
        this.records = records;
    }

    this.setDatafields = function (datafields) {
        this.datafields_source = datafields;
    }

    this.columns = function (cellsrenderer) {
        var columns = [];
        for (var k in this.datafields) {
            var datafield = this.datafields[k];
            var column = {
                text: datafield,
                datafield: k,
                align: 'center',
                cellsalign: 'center',
                cellsrenderer: cellsrenderer ? cellsrenderer : function () {
                }
            };
            k == 'rate' ? column.cellsformat = 'p' : void 0;
            if (k == 'rate') {
                column.cellsformat = 'p'
            }
            if (k == 'time') {
                column.columntype = 'datetimeinput';
                column.cellsformat = 'dd-MMMM-yyyy hh:mm:ss';
            }
            columns.push(column);
        }

        return columns;
    }

    this.setDropDownList = function (obj, objHidden, source) {
        var valDefault = $(objHidden).val();
        $(obj).jqxDropDownList(
            {
                source: source,
                width: '325px',
                height: '35px',
                renderer: function (index, label, value) {
                    var datarecord = source[index];
                    return '<img src="' + datarecord.logo + '" width="30" height="30"/> ' + datarecord.description;
                },
                selectionRenderer: function (htmlString) {
                    var item = $(obj).jqxDropDownList('getSelectedItem');
                    if (item) {
                        return "<b>" + source[item.index].description + "</b>";
                    }
                    return '<b>Please Choose:</b>';
                },
                valueMember: 'id',
                autoDropDownHeight: true,
                theme: 'ui-redmond'
            });
        try {
            valDefault ? $(obj).jqxDropDownList('val', valDefault) : '';
        } catch (e) {
        }

    }

    this.contextMenu = function (grid, config, callback) {
        var menu = config.menu;
        if (!menu) {
            return;
        }

        var contextMenu = menu.jqxMenu({
            width: config.width ? config.width : 200,
            height: config.height ? config.height : 58,
            autoOpenPopup: config.autoOpenPopup ? config.autoOpenPopup : false,
            mode: config.mode ? config.mode : 'popup'
        });

        grid.on('contextmenu', function () {
            return false;
        });

        menu.on('itemclick', function (event) {
            if (typeof callback == "function") {
                callback(grid, event);
            }
        });

        $('li[type="excel"], li[type="csv"], li[type="pdf"]').on('click', function () {
            var type = $(this).attr('type');
            if (type == 'pdf') {
                grid.jqxGrid('exportdata', 'pdf', 'jqxGrid');
            } else if (type == 'excel') {
                grid.jqxGrid('exportdata', 'xls', 'jqxGrid');
            } else if (type == 'csv') {
                grid.jqxGrid('exportdata', 'csv', 'jqxGrid');
            }
        })

        $('li[type="add"]').addClass('mini ui teal button');
        $('li[type="edit"]').addClass('mini ui blue button');
        $('li[type="delete"]').addClass('mini ui red button');
        $('li[type="bet"]').addClass('mini ui teal button');
        $('li[type="show"]').addClass('mini ui grown button');
        $('li[type="map"]').addClass('mini ui yellow button');
        $('li[type="export"]').addClass('mini ui blue button');

        grid.on('rowclick', function (event) {
            if (event.args.rightclick) {
                grid.jqxGrid('selectrow', event.args.rowindex);
                var scrollTop = $(window).scrollTop();
                var scrollLeft = $(window).scrollLeft();
                contextMenu.jqxMenu('open', parseInt(event.args.originalEvent.clientX) + 5 + scrollLeft, parseInt(event.args.originalEvent.clientY) + 5 + scrollTop);
                return false;
            }
        });

        grid.on('mousedown', function (event) {
            switch (event.which) {
                case 1:
                    break;
                case 2:
                    break;
                case 3:
                    contextMenu.jqxMenu('open', event.pageX, event.pageY);
                    break;
                default:
                    break;
            }
        });
    }

    this.initGrid = function (grid, configMenu, callback) {
        if (grid === undefined || grid.length <= 0)
            return;

        var cellsrenderer = function (row,
                                      columnfield,
                                      value,
                                      defaulthtml,
                                      columnproperties,
                                      rowdata) {
            var data = grid.jqxGrid('getrowdata', row);
            if (data.end && columnproperties.datafield == 'result') {
                return '<span style="margin: 4px; float: ' + columnproperties.cellsalign + '; color: #ff0000;">' + value + '</span>';
            } else if (columnproperties.datafield == 'logo') {
                return '<img src="' + data.logo + '" width="100%" height="100%" />'
            }

            return '<span style="margin: 4px; float: ' + columnproperties.cellsalign + ';">' + value + '</span>';
        }

        var source = {
            localdata: this.records,
            datafields: this.datafields_source,
            datatype: 'json'
        }

        var data = new $.jqx.dataAdapter(source);
        var columns = this.columns();

        var config = {
            width: $('.ui.segment.content').width() - 40,
            source: data,
            theme: 'ui-redmond',
            pageable: true,
            autoheight: true,
            autorowheight: true,
            showfilterrow: true,
            sortable: true,
            editable: false,
            editmode: 'dblclick',
            altrows: true,
            filterable: true,
            enabletooltips: true,
            columns: this.columns(cellsrenderer)
        };

        if (configMenu == null) {
            config.rendered = function () {
                var gridCells = grid.find('.jqx-grid-cell');
                if (grid.jqxGrid('groups').length > 0) {
                    gridCells = grid.find('.jqx-grid-group-cell');
                }
                gridCells.jqxDragDrop({
                    appendTo: 'body', dragZIndex: 99999,
                    dropAction: 'none',
                    initFeedback: function (feedback) {
                        feedback.height(70);
                        feedback.width(220);
                    }
                });
                gridCells.off('dragStart');
                gridCells.on('dragStart', function (event) {
                    var value = $(this).text();
                    var position = $.jqx.position(event.args);
                    var cell = grid.jqxGrid('getcellatposition', position.left, position.top);
                    $(this).jqxDragDrop('data', grid.jqxGrid('getrowdata', cell.row));
                    var groupslength = grid.jqxGrid('groups').length;
                    var feedback = $(this).jqxDragDrop('feedback');
                    var feedbackContent = $(this).parent().clone();
                    var table = '<table>';
                    $.each(feedbackContent.children(), function (index) {
                        if (index < groupslength)
                            return true;
                        table += '<tr>';
                        table += '<td>';
                        table += columns[index - groupslength].text + ': ';
                        table += '</td>';
                        table += '<td>';
                        table += $(this).text();
                        table += '</td>';
                        table += '</tr>';
                    });
                    table += '</table>';
                    feedback.html(table);
                });
                gridCells.off('dragEnd');
                gridCells.on('dragEnd', function (event) {
                    var value = $(this).jqxDragDrop('data');
                    var position = $.jqx.position(event.args);
                    var pageX = position.left;
                    var pageY = position.top;
                    var $home = $(".home-text");
                    var $guest = $(".guest-text");
                    var targetX = $home.offset().left;
                    var targetY = $home.offset().top;
                    var guestX = $guest.offset().left;
                    var guestY = $guest.offset().top;
                    var width = $home.width();
                    var height = $home.height();
                    var guestW = $guest.width();
                    var guestH = $guest.height();

                    if (pageX >= targetX && pageX <= targetX + width) {
                        if (pageY >= targetY && pageY <= targetY + 40) {
                            $home.html('<img src="' + value.logo + '" width="25" height="25" /> ' + value.name);
                            $('input[name="home_id"]').val(value.id);
                            $home.css('color', 'red');
                        }
                    }

                    if (pageX >= guestX && pageX <= guestX + guestW) {
                        if (pageY >= guestY && pageY <= guestY + 40) {
                            $guest.html('<img src="' + value.logo + '" width="25" height="25"/> ' + value.name);
                            $('input[name="guest_id"]').val(value.id);
                            $guest.css('color', 'red');
                        }
                    }
                });
            }
        }

        grid.jqxGrid(config);
        try {
            if (configMenu != null) {
                this.contextMenu.call(grid, configMenu, callback);
            }
        } catch (e) {
        }
    }

}