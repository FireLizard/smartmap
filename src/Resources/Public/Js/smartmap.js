var Smartmap = (function(window, document, $, undefined){

    var $objects, settings;
    var map, markers;

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
                    createLeafletMap();
                    break;
            }
        });
    }

    function createLeafletMap() {

        if (typeof L === 'undefined'){
            throw 'smartmap requires Leaflet to be loaded first!';
        }

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
    }

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
