/**
 * Theses are the needed scripts for the creation of the map and other parts
 * 
 * // MAP ITSELF
 * <link href="https://api.mapbox.com/mapbox-gl-js/v3.9.1/mapbox-gl.css" rel="stylesheet"> 
 * <script src="https://api.mapbox.com/mapbox-gl-js/v3.9.1/mapbox-gl.js"></script>
 * 
 * //GEOCODING
 * <script src="https://unpkg.com/@mapbox/mapbox-sdk/umd/mapbox-sdk.min.js"></script>
 * 
 * We will split this into reusabe functions, we want to be able to make a map, add markers to that map and 
 * get the coordinations of these markers so as to calculat a route.
 * 
 * Once a route has been made we hope to turn this into an image so that a user can jusr see the image
 * of the route and not the map itself recalculating the route. 
 */

// this token will be used for making request to the API, both for the client and the server
const accessToken = "pk.eyJ1Ijoibm90LWEtbWFwIiwiYSI6ImNtNTg3Nmt5MzI3NXgybnNlZmF1bGR2Z2sifQ.TnEN4JriDQhh9qEOlnJMVw"
const country = "France";
const city = "La Rochelle"
const annotations = "annotations=";
const default_params = ["duration", "distance"];
"https://api.mapbox.com/directions/v5/mapbox/driving/13.43,52.51;13.43,52.5?approaches=unrestricted;curb&access_token=YOUR_MAPBOX_ACCESS_TOKEN"


// for a request from server
mapboxgl.accessToken = accessToken;

// for a request from the client
export const mapboxClient = mapboxSdk({ accessToken: accessToken });

/**
 * This will be for the div in which the map will be generated
 * @param {string} div_id 
 */
export function createMap(div_id) {
    return new mapboxgl.Map({
        container: document.getElementById(div_id),
        center: [-1.151700,46.159100], // La Rochelle
        zoom: 12
    });
}

/**
 * This adds a marker to the map
 * @param {*} map 
 * @param {*} coordinates 
 */
export function addMarker(map, coordinates) {
    return new mapboxgl.Marker().setLngLat(coordinates).addTo(map)
}


export function remove(array) {
    array.splice(0, 1);
}

/**
 * 
 * @param {*} array_coords coordinates given directly buy the mapboxgl object
 * @param {*} params this will be an array of things I want from the query
 */
export function getRoute(array_coords, params = default_params) {

    console.log(array_coords);
    console.log(array_coords[1]);
    // let args = array_coords.map((point) => point.lng + ',' + point.lat);
    let args = [array_coords['A'].join(','),
                array_coords['B'].join(',')];

    const API = 'https://api.mapbox.com/directions/v5/mapbox/driving/'+args[0]+';'+ args[1]
                +'?overview=full&annotations=duration,distance&access_token=' + accessToken;

    fetch(API, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    }).then(data => {return data.json()
     })
        .then(res => {
            const route = res.routes[0].geometry.coordinates;
            console.log(route);
            return;
          const geojson = {
              type: 'Feature',
              properties: {},
              geometry: {
                  type: 'LineString',
                  coordinates: route
              }
          };

          // If the route already exists on the map, remove it
          if (map.getSource('route')) {
              map.removeLayer('route');
              map.removeSource('route');
          }

          // Add the route as a new layer
          map.addSource('route', {
              type: 'geojson',
              data: geojson
          });

          map.addLayer({
              id: 'route',
              type: 'line',
              source: 'route',
              layout: {
                  'line-join': 'round',
                  'line-cap': 'round'
              },
              paint: {
                  'line-color': '#3887be',
                  'line-width': 5,
                  'line-opacity': 0.75
              }
          });
      })
      .catch(error => console.log(error));
}
