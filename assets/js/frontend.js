( function( $ ) {

    var pipecareers_frontend = {

        init: function() {
            $( document.body ).on( 'pipecareers_loaded', this.initSearchForm )
                              .on( 'pipecareers_loaded', this.initMap )
                              .on( 'click', '.use-location', this.geolocate )
                              .on( 'change', '.pipecareers-search form :input', this.nextSearchFormPage )
                              .trigger( 'pipecareers_loaded' );

        },

        initSearchForm: function() {
            var $form = $( '.pipecareers-search form' );

            if ( $form.length ) {
                pipecareers_frontend.maybeGeolocate();

                $form.trigger( 'reset' );
                $form.slick( {
                    nextArrow:      false,
                    prevArrow:      false,
                    infinite:       false,
                    swipe:          false,
                    draggable:      false,
                    swipeToSlide:   false,
                    touchMove:      false
                } );
            }
        },

        initMap: function() {
            mapboxgl.accessToken = 'pk.eyJ1IjoiZGtqZW5zZW4iLCJhIjoiY2p6eWFqMDhtMWxjcDNtbWxwbzIwdG52MyJ9.tdUWFKDgl7TOxhY9AqcObw';

            var $map      = $( '#pipe-careers-map' ),
                state     = $map.data( 'state' ) || '',
                center    = pipecareers_frontend.getStateCoords( state.toUpperCase() ),
                hovering  =  null;

            if ( $map.length ) {
                map = new mapboxgl.Map( { 
                    container: 'pipe-careers-map', 
                    style: 'mapbox://styles/mapbox/streets-v11' 
                } )

                if ( typeof center === 'object' ) {
                    map.flyTo( {
                        zoom: 5,
                        center: [ center.longitude, center.latitude ]
                    } );
                }
                
                map.on( 'load', function() {
                    map.addSource( 'counties', { 'type': 'vector', 'url': 'mapbox://mapbox.82pkq93d' } );
                    map.addLayer( {
                        'id': 'counties',
                        'type': 'fill',
                        'source': 'counties',
                        'source-layer': 'original',
                        'paint': {
                            'fill-outline-color': 'rgba(0,0,0,0.1)',
                            'fill-color': 'rgba(0,0,0,0.1)'
                        }
                    }, 'settlement-label' );

                    for ( var property in pipecareers.locals ) {
                        if ( pipecareers.locals.hasOwnProperty( property ) ) {
                            if ( pipecareers.locals[property].fips.length ) {
                                map.addLayer( {
                                    'id': pipecareers.locals[property].color,
                                    'type': 'fill',
                                    'source': 'counties',
                                    'source-layer': 'original',
                                    'paint': {
                                        'fill-outline-color': '#730405',
                                        'fill-color': pipecareers.locals[property].color,
                                        'fill-opacity': ['case',
                                        ['boolean', ['feature-state', 'hover'], false],
                                        1,
                                        0.75
                                        ]
                                    },
                                    'filter': ['in', 'COUNTY', '']
                                }, 'settlement-label' );
                        
                                map.on( 'mousemove', pipecareers.locals[property].color, function(e) {
                                    if ( e.features.length > 0 ) {
                                        if ( hovering ) {
                                            map.setFeatureState({source: 'counties', id: hovering, sourceLayer: 'original'}, { hover: false } );
                                        }

                                        hovering = e.features[0].id;
                                        map.setFeatureState({source: 'counties', id: hovering, sourceLayer: 'original'}, { hover: true } );
                                    }
                                } );
                                    
                                map.on( 'mouseleave', pipecareers.locals[property].color, function() {
                                    if ( hovering ) {
                                        map.setFeatureState( { source: 'counties', id: hovering, sourceLayer: 'original'}, { hover: false } );
                                    }
                                    
                                    hovering =  null;
                                } );
                    
                                map.setFilter( pipecareers.locals[property].color, ['match', ['get', 'FIPS'], pipecareers.locals[property].fips, true, false ] );
                            }
                        }
                    }
                } );

                map.on( 'click', function( e ) {
                    var bbox = [ [ e.point.x - 5, e.point.y - 5 ], [ e.point.x + 5, e.point.y + 5 ] ];
                    var features = map.queryRenderedFeatures( bbox, { layers: ['counties'] } );
                        
                    features.reduce( function( memo, feature ) {
                        if ( typeof feature.properties.FIPS !== 'undefined' && feature.properties.FIPS ) {
                            window.location = pipecareers.current_url + ( pipecareers.current_url.indexOf( '?' ) == -1 ? '?' : '&' ) + 'county=' + feature.properties.FIPS;
                            return false;
                        }
                
                        memo.push( feature.properties.FIPS );
                
                        return memo;
                    }, [ 'in', 'FIPS' ] );
                } );
            
                map.getCanvas().style.cursor = 'pointer';
            }
        },

        maybeGeolocate: function() {
            if( document.cookie.indexOf( 'pcuser-lprompt' ) === -1 && ( typeof pc_allow_geolocation === 'undefined' || pc_allow_geolocation !== false ) ) {
                pipecareers_frontend.geolocate();
            }
        },

        geolocate: function() {
            var $form = $( '.pipecareers-search form' ),
                $state = $form.find( '[name="state"]' );

            if ( navigator.geolocation ) {
                navigator.geolocation.getCurrentPosition( function( position ) {
                    endpoint = 'https://geo.fcc.gov/api/census/block/find?latitude=' + position.coords.latitude + '&longitude=' + position.coords.longitude + '&format=json';

                    $.get( endpoint, function( data, status ) {
                        date = new Date();
                        date.setTime( date.getTime() + 365 * 24 * 60 * 60 * 1000 );
                
                        fips  = data.County.FIPS;
                        state = data.State.code; 
                
                        document.cookie = 'pcuser-lprompt=1; expires=' + date.toUTCString() + '; path=/;'

                        if ( $state.length ) {
                            $state.val( state.toLowerCase() );

                            setTimeout( function() {
                                $state.trigger( 'change' );
                            }, 500 );
                        }

                        if ( $.isNumeric( fips ) ) {
                            $form.append( '<input type="hidden" name="county" value="' + fips + '" />' );
                        }
                    } );
                } );
            }
        },

        nextSearchFormPage: function() {
            var $this = $( this ),
                $form = $this.closest( 'form' );

            $form.slick( 'slickNext' );

            if ( $this.closest( '.pipecareers-search-page' ).is( ':last-child' ) ) {
                $form.submit();
            }
        },

        getStateCoords: function( state ) {
            var states = {'AK':{'latitude':61.385,'longitude':-152.2683},'AL':{'latitude':32.799,'longitude':-86.8073},'AR':{'latitude':34.9513,'longitude':-92.3809},'AZ':{'latitude':33.7712,'longitude':-111.3877},'CA':{'latitude':36.170,'longitude':-119.7462},'CO':{'latitude':39.0646,'longitude':-105.3272},'CT':{'latitude':41.5834,'longitude':-72.7622},'DE':{'latitude':39.3498,'longitude':-75.5148},'FL':{'latitude':27.8333,'longitude':-81.717},'GA':{'latitude':32.9866,'longitude':-83.6487},'HI':{'latitude':21.1098,'longitude':-157.5311},'IA':{'latitude':42.0046,'longitude':-93.214},'ID':{'latitude':44.2394,'longitude':-114.5103},'IL':{'latitude':40.3363,'longitude':-89.0022},'IN':{'latitude':39.8647,'longitude':-86.2604},'KS':{'latitude':38.5111,'longitude':-96.8005},'KT':{'latitude':37.669,'longitude':-84.6514},'LA':{'latitude':31.1801,'longitude':-91.8749},'MA':{'latitude':42.2373,'longitude':-71.5314},'MD':{'latitude':39.0724,'longitude':-76.7902},'ME':{'latitude':44.6074,'longitude':-69.3977},'MI':{'latitude':43.3504,'longitude':-84.5603},'MN':{'latitude':45.7326,'longitude':-93.9196},'MO':{'latitude':38.4623,'longitude':-92.302},'MS':{'latitude':32.7673,'longitude':-89.6812},'MT':{'latitude':46.9048,'longitude':-110.3261},'NC':{'latitude':35.6411,'longitude':-79.8431},'ND':{'latitude':47.5362,'longitude':-99.793},'NE':{'latitude':41.1289,'longitude':-98.2883},'NH':{'latitude':43.4108,'longitude':-71.5653},'NJ':{'latitude':40.314,'longitude':-74.5089},'NM':{'latitude':34.8375,'longitude':-106.2371},'NV':{'latitude':38.4199,'longitude':-117.1219},'NY':{'latitude':42.1497,'longitude':-74.9384},'OH':{'latitude':40.3736,'longitude':-82.7755},'OK':{'latitude':35.5376,'longitude':-96.9247},'OR':{'latitude':44.5672,'longitude':-122.1269},'PA':{'latitude':40.5773,'longitude':-77.264},'RI':{'latitude':41.6772,'longitude':-71.5101},'SC':{'latitude':33.8191,'longitude':-80.9066},'SD':{'latitude':44.2853,'longitude':-99.4632},'TN':{'latitude':35.7449,'longitude':-86.7489},'TX':{'latitude':31.106,'longitude':-97.6475},'UT':{'latitude':40.1135,'longitude':-111.8535},'VA':{'latitude':37.768,'longitude':-78.2057},'VT':{'latitude':44.0407,'longitude':-72.7093},'WA':{'latitude':47.3917,'longitude':-121.5708},'WI':{'latitude':44.2563,'longitude':-89.6385},'WV':{'latitude':38.468,'longitude':-80.9696},'WY':{'latitude':42.7475,'longitude':-107.2085}};

            return states[ state ];
        }

    };

    pipecareers_frontend.init();

} )( jQuery );
