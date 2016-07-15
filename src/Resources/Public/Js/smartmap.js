var Smartmap = (function(window, document, $, undefined){

    var $objects, settings, provider, dataSubscription;

    function initialize() {

        $objects = {
            wrapper: $('.smartmap'),
        };
        $objects.mapContainer = $('#map-general.map', $objects.wrapper);
        $objects.filterContainer = $('#map-filter-general.map-filter', $objects.wrapper);
        $objects.filterForm = $('form:first', $objects.filterContainer);
        $objects.apiKey = $('#map-general.map', $objects.wrapper).data('apiKey');

        dataSubscription = new Subscription();
    }

    function addCssLoader() {

        $objects.mapContainer.append('<div class="cssload-wrapper" style="display: none;"><div class="cssload-loader"><div class="cssload-inner cssload-one"></div><div class="cssload-inner cssload-two"></div><div class="cssload-inner cssload-three"></div></div></div>');
        $objects.cssLoader = $('.cssload-wrapper', $objects.mapContainer);
    }

    function registerEventHandler() {

        $objects.filterForm.on('submit', function(e){
            e.preventDefault();
            var $thisForm = $(this);

            $objects.cssLoader.fadeIn(250);

            $.post($objects.filterContainer.data('api-url'), $thisForm.serializeArray(), function(response){

                settings = response.metadata.settings;
                data = response.data;
                dataSubscription.setData(data).notify();
                provider.mainLayerGroup.clearLayers();
                provider.pinMarker();
                $objects.cssLoader.fadeOut(250);
            });
        });
    }

    function getData() {

        $.post($objects.mapContainer.data('api-url'), null, function(response){

            settings = response.metadata.settings;
            data = response.data;
            dataSubscription.setData(data).notify();

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
                addCssLoader();

                switch (response.metadata.service) {
                    case 'getMarkers':
                        provider.pinMarker();
                        break;
                    default:
                }
            }
        });
    }

    /**
     * Subscription for incoming data
     */
    var Subscription = function(){

        this.data = [];
        this.subscriber = [];

        this.setData = function(data){
            this.data = data || [];

            return this;
        };

        this.register = function(newSubscriber){
            this.subscriber.push(newSubscriber);

            return this;
        };

        this.notify = function(){
            this.subscriber.forEach(function(e){
                e.update(this.data);
            });

            return this;
        };
    };

    /**
     * Register different map provider here.
     */
    var mapProvider = {

        /**
         * Leaflet.
         * @see http://leafletjs.com/reference.html
         */
        leaflet: function() {

            var map;
            this.mainLayerGroup = L.layerGroup();

            /**
             * Creates a map.
             * @return this
             */
            this.createMap = function() {

                map = L.map($objects.mapContainer[0], {
                    center: [51.06971, 13.77797],
                    zoom: 12
                });

                L.tileLayer('https://api.mapbox.com/v4/mapbox.streets/{z}/{x}/{y}.jpg70?access_token='+ $objects.apiKey, {
                    maxZoom: 18,
                    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                    '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                    'Imagery © <a href="http://mapbox.com">Mapbox</a>',
                }).addTo(map);

                this.mainLayerGroup.addTo(map);

                return this;
            };

            /**
             * Pins markers to map
             * @return this
             */
            this.pinMarker = function() {

                var latLngArray = [];

                for (var element in data.coords) {
                    if (data.coords.hasOwnProperty(element)) {
                        if (data.coords[element].hasOwnProperty('lat') && data.coords[element].hasOwnProperty('lon')) {
                            var latLng = L.latLng(parseFloat(data.coords[element].lat), parseFloat(data.coords[element].lon));
                            latLngArray.push( latLng );

                            var icon = {};
                            if (data.coords[element].hasOwnProperty('icon')) {
                                icon = {icon: L.icon(data.coords[element].icon)};
                            }
                            this.mainLayerGroup.addLayer(L.marker(latLng, icon).bindPopup(data.popup[element]));
                        }
                    }
                }

                if (latLngArray.length > 0){
                    map.fitBounds(L.latLngBounds(latLngArray));
                }

                return this;
            };
        }
    };

    return {
        createMap: function(){

            initialize();

            if (typeof $objects.wrapper.get(0) === 'undefined'){
                return this;
            }

            registerEventHandler();
            getData();

            return this;
        },
        subscription: {
            subscribe: function (newSubscriber) {
                dataSubscription.register(newSubscriber);
            }
        }
    };

})(window, document, jQuery);

jQuery(document).ready(function($){
    Smartmap.createMap();
});
