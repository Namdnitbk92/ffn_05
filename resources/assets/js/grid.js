var gridBuilder = new function () {

    this.initFormBet = function (match) {
        var teams = localStorage.getItem('teams');
        teams = teams != 'undefined' ? $.parseJSON(teams) : [];
        localStorage.setItem('rate', match.rate);
        if (match != null && teams != null) {
            var home = app.utils.search(teams, 'id', match.home_id);
            var guest = app.utils.search(teams, 'id', match.guest_id);
            var data = [];
            if (guest != null && home != null) {
                data.push({value: home.id, description: home.name, logo: home.logo});
                data.push({value: guest.id, description: guest.name, logo: guest.logo});
                $('#match_bet').html(home.name + '-' + guest.name);
                $('#match_bet').attr('matchId', match.id);
            }
        }
        this.setDropDownList($('#result'), null, ['Win', 'Lose', 'Draw'], {
            width: '225px',
            height: '25px',
            value: null
        });
        this.setDropDownList($('#team-guess'), null, data, {width: '225px', height: '25px'});
        $('#price').jqxNumberInput({theme: 'ui-redmond', width: '225px', height: '25px', spinButtons: true});
        $("#result-bet").jqxNotification({
            width: 500, opacity: 0.9, appendContainer: '#result-bet-container',
            autoOpen: false, animationOpenDelay: 800, autoClose: true,
            autoCloseDelay: 1000, template: "info",
        });
    }

}