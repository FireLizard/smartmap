var Smartmap = (function(window, document, $, undefined){

    var _Smartmap = {};
    var $objects;
    var settings;
    var provider;

    function initialize() {

        $objects = {
            wrapper: $('.smartmap')
        };
        $objects.mapContainer = $('#map-general.map', $objects.wrapper);
        $objects.filterContainer = $('#map-filter-general.map-filter', $objects.wrapper);
        $objects.filterForm = $('form:first', $objects.filterContainer);
        $objects.apiKey = $('#map-general.map', $objects.wrapper).data('apiKey');
    }

    function addCssLoader() {

        $objects.mapContainer.append('<div class="cssload-wrapper" style="display: none;"><div class="cssload-loader"><div class="cssload-inner cssload-one"></div><div class="cssload-inner cssload-two"></div><div class="cssload-inner cssload-three"></div></div></div>');
        $objects.cssLoader = $('.cssload-wrapper', $objects.mapContainer);
    }

    function registerEventHandler() {

        var findLabel = function(object){

            var $element = $('input[name="'+ object.name +'"][value="'+ object.value +'"]', $objects.filterForm);
            var $label = undefined;

            if ($element.length > 0){
                $label = $('label[for="'+ $element.attr('id') +'"]', $objects.filterForm);
                if ($label.length === 0){
                    $label = $element.closest('label');
                }
            }
            else {
                $label = $('select[name="'+ object.name +'"] option[value="'+ object.value +'"]', $objects.filterForm);
            }

            return $label.text().trim();
        };

        $objects.filterForm.on('submit', function(e){

            var origFields = $objects.filterForm.serializeArray();
            var fields = [];

            for (var i in origFields){

                if (origFields[i] && /^tx_smartmap_map\[__/.exec(origFields[i].name) === null) {
                    origFields[i].label = findLabel(origFields[i]);
                    fields.push(origFields[i]);
                }
            }

            subscriptions.events.setData([e.type, $objects.filterForm.attr('id'), fields]).notify();

            e.preventDefault();
            var $thisForm = $(this);

            $objects.cssLoader.fadeIn(250);

            $.post($objects.filterContainer.data('api-url'), $thisForm.serializeArray(), function(response){

                settings = response.metadata.settings;
                subscriptions.data.setData(response.data).notify();
                provider.mainLayerGroup.clearLayers();
                provider.pinMarker(response.data);
                $objects.cssLoader.fadeOut(250);
            });
        });
    }

    function getData() {

        $.post($objects.mapContainer.data('api-url'), null, function(response){

            settings = response.metadata.settings;

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
                        provider.pinMarker(response.data);
                        break;
                    default:
                }
            }

            subscriptions.data.setData(response.data).notify();
        });
    }

    /**
     * Subscription for incoming data
     */
    var Subscription = function(){

        this.data = undefined;
        this.subscribers = [];

        /**
         * Sets data for publishing to subscribers.
         * @param data
         */
        this.setData = function(data){
            this.data = data;

            return this;
        };

        /**
         * Registers a subscriber.
         * @param newSubscriber
         */
        this.subscribe = function(newSubscriber){
            this.subscribers.push(newSubscriber);

            return this;
        };

        /**
         * Notifies subcribers by passing data to method "update".
         */
        this.notify = function(){
            this.subscribers.forEach(
                function(e){
                    e.update(this.data);
                },
                this
            );

            return this;
        };
    };

    var subscriptions = {
        data: new Subscription(),
        events: new Subscription()
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

                $objects.mapContainer[0].map = map;

                L.tileLayer('https://api.mapbox.com/v4/mapbox.streets/{z}/{x}/{y}.jpg70?access_token='+ $objects.apiKey, {
                    maxZoom: 18,
                    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                    '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                    'Imagery © <a href="http://mapbox.com">Mapbox</a>',
                }).addTo(map);

                this.mainLayerGroup.addTo(map);

                map.on('popupopen', function(e){
                    subscriptions.events.setData([e.type, e.popup._source.options.title]).notify();
                });

                return this;
            };

            /**
             * Pins markers to map
             * @return this
             */
            this.pinMarker = function(data) {

                var latLngArray = [];

                for (var element in data.coords) {
                    if (data.coords.hasOwnProperty(element)) {
                        if (data.coords[element].hasOwnProperty('lat') &&
                            data.coords[element].hasOwnProperty('lon') &&
                            data.coords[element].lat &&
                            data.coords[element].lon
                        ) {
                            var latLng = L.latLng(parseFloat(data.coords[element].lat), parseFloat(data.coords[element].lon));
                            latLngArray.push( latLng );

                            var options = {
                                title: element
                            };

                            if (data.coords[element].hasOwnProperty('icon')) {
                                options.icon = L.icon(data.coords[element].icon);
                            }

                            this.mainLayerGroup.addLayer(L.marker(latLng, options).bindPopup(data.popup[element]));
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

    _Smartmap.createMap = function(){

        initialize();

        if (typeof $objects.wrapper.get(0) === 'undefined'){
            return this;
        }

        registerEventHandler();
        getData();

        return this;
    };

    _Smartmap.subscriptions = {
        data: {
            subscribe: function(newSubscriber){
                subscriptions.data.subscribe(newSubscriber);
            }
        },
        events: {
            subscribe: function(newSubscriber){
                subscriptions.events.subscribe(newSubscriber);
            }
        }
    };

    return _Smartmap;

})(window, document, jQuery);

jQuery(document).ready(function($){
    Smartmap.createMap();
});
