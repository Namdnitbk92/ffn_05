var app = (function () {
    var map = {};
    var bindEvent = function () {
        $('.toggle').on('click', function () {
            $('.login-form').stop().addClass('active');
        });

        $('.close').on('click', function () {
            $('.login-form').stop().removeClass('active');
        });

        $('.ui.star.rating').rating({
            initialRating: 2,
            maxRating: 4
        });

        $('form[name="match-update-form"]').submit(function (e) {
            e.preventDefault();
            return false;
        });

        $('button[name="edit-match"]').on('click', function () {
            var events = $('#events_list').jqxGrid('getrows');
            if (events != null && events != undefined) {
                $('input[name="events_data"]').val(JSON.stringify(events));
            }
            var form = $('form[name="match-update-form"]');
            form.unbind('submit');
            form.submit();
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }

    var initMap = function () {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 21.0104218, lng: 105.81846159999998},
            zoom: 13,
            mapTypeId: 'roadmap'
        });

        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        map.addListener('bounds_changed', function () {
            searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        searchBox.addListener('places_changed', function () {
            var places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }

            markers.forEach(function (marker) {
                marker.setMap(null);
            });

            markers = [];

            var bounds = new google.maps.LatLngBounds();
            places.forEach(function (place) {
                if (!place.geometry) {
                    console.log('Returned place contains no geometry');
                    return;
                }
                var icon = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25)
                };

                markers.push(new google.maps.Marker({
                    map: map,
                    icon: icon,
                    title: place.name,
                    position: place.geometry.location
                }));

                for (var k in markers) {
                    markers[k].addListener('click', function () {
                        var pos = markers[k].getPosition();
                        var address = markers[k].title;
                        $('input[name="address"]').val(address);
                        $('input[name="location"]').val(address);
                        $('#window').jqxWindow('close');
                    });
                }

                if (place.geometry.viewport) {
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });

            map.fitBounds(bounds);
        });
    }

    var calculateRoute = function (from, to) {
        var directionsService = new google.maps.DirectionsService();
        var directionsRequest = {
            origin: from,
            destination: to,
            travelMode: google.maps.DirectionsTravelMode.DRIVING,
            unitSystem: google.maps.UnitSystem.METRIC
        };

        directionsService.route(
            directionsRequest,
            function (response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    new google.maps.DirectionsRenderer({
                        map: map,
                        directions: response
                    });
                    setTimeout(function () {
                        google.maps.event.trigger(map, 'resize');
                    }, 1000);
                }
                else {
                    $('#map').append('<h3>Unable to retrieve your address<br /></h3>')
                }
                $('#window').jqxWindow('open');
            }
        );
    }

    var getDirections = function (location) {
        var pos = null;
        if (typeof navigator.geolocation == "undefined" || location == null) {
            $('#error').text('Your browser doesnt support the Geolocation API');
            return;
        }

        navigator.geolocation.getCurrentPosition(function (position) {
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({
                        "location": new google.maps.LatLng(position.coords.latitude, position.coords.longitude)
                    },
                    function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            pos = results[0].formatted_address;
                            calculateRoute(pos, location);
                        } else {
                            $('#map').empty();
                            $('#map').html('<h3>Unable to retrieve your address<br /></h3>')
                        }
                    });
            },
            function (positionError) {
                $("#error").append("Error: " + positionError.message + "<br />");
            },
            {
                enableHighAccuracy: true,
                timeout: 10 * 1000
            });

        return pos;
    }

    var initMapWindow = function (_grid, window) {
        var jqxWidget = _grid;
        var offset = jqxWidget.offset();
        window.jqxWindow({
            position: {x: offset.left + 50, y: offset.top + 50},
            theme: 'ui-redmond',
            showCollapseButton: true,
            maxHeight: 400,
            maxWidth: 700,
            minHeight: 200,
            minWidth: 200,
            height: 300,
            width: 500,
            initContent: function () {
                $('#window').jqxWindow('focus');
            }
        });
    }

    var viewLocation = function (_grid) {
        var grid = $('#' + _grid);
        initMapWindow(grid, $('#window'));
        var index = grid.jqxGrid('getselectedrowindex');
        if (index != -1) {
            var row = grid.jqxGrid('getrowdata', index);
            var location = row.location;
            getDirections(location);
        }
    }

    return {
        bindEvent: bindEvent,
        initMap: initMap,
        viewLocation: viewLocation,
        getDirections: getDirections,
        initMapWindow: initMapWindow
    }
}())

$(document).ready(function () {
    app.bindEvent();
});
