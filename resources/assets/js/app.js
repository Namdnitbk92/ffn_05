var app = (function () {
    var map = {};

    var utils = new function () {
        this.search = function (arr, key, value) {
            for (var k in arr) {
                if (arr[k]['' + key] == value)
                    return arr[k];
            }

            return null;
        }

        this.formatMoney = function (c, d, t) {
            var n = this,
                c = isNaN(c = Math.abs(c)) ? 2 : c,
                d = d == undefined ? "." : d,
                t = t == undefined ? "," : t,
                s = n < 0 ? "-" : "",
                i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
                j = (j = i.length) > 3 ? j % 3 : 0;
            return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
        };

    }

    var initValidate = function () {
        var form = $('form[name="form-bet"]');
        if (form.length > 0) {
            $('form[name="form-bet"]').jqxValidator({
                animation: 'none',
                rules: [{
                    input: '#team-guess',
                    message: 'Team guess is required!',
                    action: 'blur',
                    rule: function (input) {
                        var val = input.val();
                        if (!val)
                            return false
                        return true;
                    },
                    position: 'topcenter'
                }, {
                    input: '#result',
                    message: 'result',
                    action: 'blur',
                    rule: function (input) {
                        var val = input.val();
                        if (!val)
                            return false
                        return true;
                    },
                    position: 'topcenter'
                }, {
                    input: '#price',
                    message: 'Price is required',
                    action: 'blur',
                    rule: function (input) {
                        var val = input.val();
                        if (!val)
                            return false
                        return true;
                    },
                    position: 'topcenter'
                }]
            });
        }
    }

    var getTotalNotification = function () {
        $.ajax({
            url: 'getTotalNotification',
            type: 'POST',
            beforeSend: function () {
                $('#_message').addClass('ui loading form');
            }
        }).done(function (res) {
            if (res.total) {
                $('#_message').removeClass('ui loading form');
                $('#_message').text(res.total);
            }
        });
    }

    var betMatch = function (_grid) {
        var grid = $('#' + _grid);
        initMapWindow(grid, $('#usermatchWindow'));
    }

    var bindEvent = function () {

        $('#usermatchWindow').on('close', function () {
            $('form[name="form-bet"]').jqxValidator('hide');
        })

        $('#price').on('change', function () {
            var rate = localStorage.getItem('rate');
            var price = $('#price').val();
            var bonus = rate > 0 ? rate * price : price;
            $('.bonus').text(bonus.formatMoney(2, '.', ',') + ' d');
        })

        $('button[name="bet-match"]').on('click', function () {
            var flag = $('form[name="form-bet"]').jqxValidator('validate');
            if (!flag)
                return;

            $.ajax({
                type: 'GET',
                data: {
                    sendNotification: true,
                    teamGuess: $('#team-guess').val(),
                    result: $('#result').val(),
                    price: $('#price').val(),
                    matchId: $('#match_bet').attr('matchId')
                },
                beforeSend: function () {
                    $('form[name="form-bet"]').addClass('ui loading form');
                }
            }).done(function (res) {
                $('form[name="form-bet"]').removeClass('ui loading form');
                if (res.resultBet) {
                    $("#result-bet").jqxNotification({template: 'info'});
                    $("#result-bet").text('Bet Match Successfully!');
                    $("#result-bet").jqxNotification('open');
                } else {
                    $("#result-bet").jqxNotification({template: 'error'});
                    $("#result-bet").text('Bet Match Error!');
                    $("#result-bet").jqxNotification('open');
                }
            })
        });

        /*binding event socket*/
        var socket = io.connect('http://localhost:8890');
        socket.on('message', function (data) {
            var msg = $('#message');
            try {
                data = $.parseJSON(data);
            } catch (e) {
                if (data.indexOf('admin')) {
                    $('.msg-user-content').text('Admin has close match!');
                    $('#messageToUser').jqxNotification('open');
                }
            }

            if (data.avatar) {
                $('#user-bet').attr('src', data.avatar);
            }

            $('.msg-content').text('User ' + (data.user_name ? data.user_name : '') + ' bet a match');
            var totalMsg = parseInt($('#_message').text());
            if (typeof totalMsg != NaN) {
                $('#_message').text(++totalMsg);
            }
            msg.jqxNotification('open');
        });

        return {
            bindEvent: bindEvent,
            betMatch: betMatch,
            utils: utils
        }
    }

});

$(document).on('ready page:load', function () {
    app.bindEvent();
})
