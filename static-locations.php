// use the code to your wordpress functions.php and shortcode is '[custom_google_map]'

// Add Google Maps JS API in your footer
function enqueue_google_maps_script() {
    wp_enqueue_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_API_KEY', [], null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_google_maps_script');

// Shortcode to display the map
function custom_google_map_shortcode() {
    ob_start();
    ?>
    <div id="customMap" style="width: 100%; height: 450px;"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const locations = [
                {
                    storeName: "Store 1",
                    storeAddress: "123 Street, City",
                    websiteLink: "https://example.com",
                    lat: 51.5074,
                    lng: -0.1278
                },
                {
                    storeName: "Store 2",
                    storeAddress: "456 Avenue, City",
                    websiteLink: "https://example2.com",
                    lat: 52.4862,
                    lng: -1.8904
                }
                // Add more locations here
            ];

            const map = new google.maps.Map(document.getElementById('customMap'), {
                zoom: 6,
                center: { lat: locations[0].lat, lng: locations[0].lng },
            });
        
            const infowindow = new google.maps.InfoWindow({
                pixelOffset: new google.maps.Size(0, 0)
            });
        

            locations.forEach(location => {
                const marker = new google.maps.Marker({
                    position: { lat: location.lat, lng: location.lng },
                    map: map,
                    icon: {
                        url: 'https://diamondlogistics.co.uk/wp-content/uploads/2025/03/markerpinSmall-1.png',
                        scaledSize: new google.maps.Size(40, 40),
                        anchor: new google.maps.Point(20, 40)
                    }
                });
        
                const contentString = `
                    <div class="info-window-content" style="padding:10px;">
                        <strong>${location.storeName}</strong><br>
                        ${location.storeAddress}<br>
                        <a href="${location.websiteLink}" target="_blank" style="margin-top:5px;display:inline-block;">Visit Website</a>
                    </div>
                `;
        
                let closeTimeout;
        
                marker.addListener('mouseover', function() {
                    clearTimeout(closeTimeout);
                    infowindow.setContent(contentString);
                    infowindow.open(map, marker);
                });
        
                marker.addListener('mouseout', function() {
                    closeTimeout = setTimeout(() => infowindow.close(), 300);
                });
        
                google.maps.event.addListener(infowindow, 'domready', function() {
                    const iwOuter = document.querySelector('.gm-style-iw');
        
                    if (!iwOuter) return; // safely handle if not found
        
                    const iwContainer = iwOuter.parentElement;
        
                    // Set pointer events to keep infoWindow hoverable
                    iwContainer.style.pointerEvents = 'auto';
        
                    // clear previous listeners to avoid duplicates
                    iwOuter.onmouseenter = null;
                    iwOuter.onmouseleave = null;
        
                    iwOuter.addEventListener('mouseenter', () => clearTimeout(closeTimeout));
                    iwOuter.addEventListener('mouseleave', () => infowindow.close());
                });
            });
        });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('custom_google_map', 'custom_google_map_shortcode');

