var Smartmap = (function(window, document, $, undefined){

    var $objects, settings, provider;

    function initialize() {

        $objects = {
            wrapper: $('.smartmap'),
            mapContainer: $('div#map-general.map', this.wrapper)
        };
    }

    function getData() {

        $.post($objects.mapContainer.data('api-url'), null, function(response){

            settings = response.metadata.settings;
            data = response.data;

            switch (settings.mapLibraryProvider) {
                case 'leaflet':
                    if (typeof L !== 'undefined'){
                        provider = new mapProvider.leaflet();
                    } else {
                        throw 'smartmap requires Leaflet to be loaded first!';
                    }
                    break;
            }

            if (typeof provider !== 'undefined'){
                provider.createMap();

                switch (response.metadata.service) {
                    case 'getCoords':
                        provider.pinMarker();
                        break;
                    default:
                }
            }
        });
    }

    /**
     * Register different map provider here.
     */
    var mapProvider = {

        /**
         * Leaflet.
         * @see http://leafletjs.com/reference.html
         */
        leaflet: function() {

            var markers = [];
            var map;

            /**
             * Creates a map.
             * @return this
             */
            this.createMap = function() {

                map = L.map($objects.mapContainer[0], {
                    center: [51.06971, 13.77797],
                    zoom: 12
                });

                L.tileLayer('http://otile{s}.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.jpg', {
                    maxZoom: 18,
                    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                        '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                        'Imagery © <a href="http://mapbox.com">Mapbox</a>',
                    subdomains: '1234'
                }).addTo(map);

                return this;
            };

            this.pinMarker = function() {

                for (var coords in data) {
                    if (data.hasOwnProperty(coords)) {
                        markers.push(L.marker([parseFloat(data[coords].lat), parseFloat(data[coords].lon)]).addTo(map));
                    }
                }

                return this;
            };
        }
    };

    return {
        createMap: function(){

            initialize();
            getData();

            return this;
        }
    };

})(window, document, jQuery);

jQuery(document).ready(function($){
    Smartmap.createMap();
});
