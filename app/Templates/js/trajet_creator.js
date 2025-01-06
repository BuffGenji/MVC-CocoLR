import { addMarker, createMap, getRoute, remove } from './elements.js';

/**
 * Here we will get the information from our form and then use it to display markers on the map
 */

let list_of_points = [];
let coords_A_B = [];
console.log('hello');
const map = createMap("map");
const accessToken = "pk.eyJ1Ijoibm90LWEtbWFwIiwiYSI6ImNtNTg3Nmt5MzI3NXgybnNlZmF1bGR2Z2sifQ.TnEN4JriDQhh9qEOlnJMVw"

/**
 * This adds a marker when someone click an option from the search
 */
const A = document.getElementById('A');
const B = document.getElementById('B');

/**
 * GPT GARB
 */

function geocode(address, callback) {
    // return;
    const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent("La Rochelle " + address)}.json?access_token=${accessToken}`;
    fetch(url)  
        .then(response => response.json())
        .then(data => {
            if (data.features && data.features.length > 0) {
                const coords = data.features[0].geometry.coordinates;
                const datus = data;
                console.log('Geocoding result:', datus);
                callback(coords);
            } else {
                console.error('No results found');
            }
        })
        .catch(error => console.error('Error:', error));
}


// Logic here is so that the request runs only once, and markers come and go with the input,
//  to refactor later

let I = 0;
// USED FOR MAKING THE POINTS ON THE MAP
A.addEventListener('change', () => {
    if (I === 0 && A.value !== '') {
        I = 1;
        return;
    }
    if (I === 0 && A.value === '') {
        list_of_points['A'].remove();
        return;
    }
    if (I === 1 && A.value !== '') {
        geocode(A.value, (coords) => {
            console.log('Coordinates at A:', coords);
            updatePoints('A', coords);
        });
        I = 0;
    }

});

let J = 0;
// USED FOR MAKING THE POINTS ON THE MAP 
B.addEventListener('change', () => {
    if (J === 0 && B.value !== '') {
        J = 1;
        return;
    }
    if (J === 0 && B.value === '') {
        list_of_points['B'].remove();
        return;
    }
    if (J === 1 && B.value !== '') {
        geocode(B.value, (coords) => {
            console.log('Coordinates at B:', coords);
            updatePoints('B', coords);
        });
        J = 0;
    }
});

function updatePoints(key, coords) {
    // Remove the old marker if it exists
    if (list_of_points[key]) {
        list_of_points[key].remove();
    }
    // Add the new marker and store it in the points object
    list_of_points[key] = addMarker(map, coords);
    coords_A_B[key] = coords;
}




/**
 * Funcitonality for only having two points on the map at any given time,
 * 
 */

// map.on('click', (e) => {
//     console.log(e.lngLat);
//     list_of_points.length <= 1
//         ? ((list_of_points.push(addMarker(map, e.lngLat))))
//         : (list_of_points[0].remove(), // removes from map
//             remove(list_of_points, list_of_points[0]), // removes from list
//             list_of_points.push(addMarker(map, e.lngLat)))
// });

/**
 * this gets us the 2 last points and the ones we will use to calculate a route
 */
document.getElementById('create').addEventListener("click", () => {
    console.log('Coordinates:', coords_A_B['A'], coords_A_B['B']);
    // getRoute(coords_A_B);
});

const script = document.getElementById('search-js');

script.onload = function () {
    mapboxsearch.config.accessToken = accessToken;
    mapboxsearch.autofill({
        options: {
            country: 'fr',
            streets: true,
            limit: 3,
            language: 'fr',
            proximity: { lat: 46.16, lng: -1.15 }, // Bias toward La Rochelle
        }
    });
};





