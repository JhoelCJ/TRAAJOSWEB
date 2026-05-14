function initMap() {
    const location = { lat: -0.180653, lng: -78.467834 };

    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 15,
        center: location,
    });

    new google.maps.Marker({
        position: location,
        map: map,
        title: "Biconoir's Restaurant"
    });
}

window.onload = initMap;