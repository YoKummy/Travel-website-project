let map;
let service;
let markers = [];
let fetchCount = 0;
const maxFetchCount = 10;

function initMap() {
    const center = { lat: 37.4161493, lng: -122.0812166 };
    map = new google.maps.Map(document.getElementById("map"), {
        center: center,
        zoom: 14,
    });

    service = new google.maps.places.PlacesService(map);
}

function searchPlaces(query, region) {
    let request = {
        query: query,
        fields: ["name", "geometry", "rating", "business_status", "photos"]
    };

    if (region) {
        request.region = region;
    }

    clearMarkers();
    fetchCount = 0; // Reset fetch count
    fetchAllResults(request);
}

function fetchAllResults(request, pagetoken = null) {
    if (fetchCount >= maxFetchCount) {
        console.log(`Reached max fetch count of ${maxFetchCount}`);
        return;
    }

    if (request.next_page_token) {
        pagetoken = request.next_page_token;
    }

    service.textSearch(request, (results, status, pagination) => {
        if (status === google.maps.places.PlacesServiceStatus.OK && results) {
            const bounds = new google.maps.LatLngBounds();

            results.forEach(place => {
                if (place.geometry && place.geometry.location) {
                    const marker = new google.maps.Marker({
                        map: map,
                        position: place.geometry.location,
                        title: place.name,
                    });

                    markers.push(marker);
                    bounds.extend(place.geometry.location);
                }
            });

            map.fitBounds(bounds);
            fetchCount++; // Increment fetch count

            if (pagination && pagination.hasNextPage && fetchCount < maxFetchCount) {
                // Fetch the next page after a short delay to avoid quota issues
                setTimeout(() => fetchAllResults(request, pagination.nextPage()), 2000);
            }
        } else {
            console.log('No more results found or an error occurred.');
        }
    });
}

function clearMarkers() {
    markers.forEach(marker => marker.setMap(null));
    markers = [];
}

document.getElementById("search-button").addEventListener("click", () => {
    const query = document.getElementById("search-input").value;
    const region = document.getElementById("region-input").value;

    searchPlaces(query, region);
});

window.onload = initMap;
