// Enqueue Google Maps JS API in your footer
// post type 'branch-location'
// custom fields 1. websiteLink, 2. lat, 3. lng
// icons will be display term wise

function enqueue_google_maps_script() {
    // Add callback=initMap to ensure map loads only after API is ready
    wp_register_script(
        'google-maps-api',
        'https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY',
        [],
        null,
        true
    );
    wp_enqueue_script('google-maps-api');
}
add_action('wp_enqueue_scripts', 'enqueue_google_maps_script');

function custom_google_map_shortcode() {
    $args = array(
        'post_type' => 'branch-location',
        'posts_per_page' => -1,
    );
    $query = new WP_Query($args);

    // Updated icon URLs
    $icon_urls = [
        'orange' => 'https://example/assets/img/orange_icon.png',
        'pink' => 'https://example/assets/img/pink_icon.png',
        'default' => 'https://example/assets/img/default_icon.png'
    ];

    $locations = [];

    while ($query->have_posts()) {
        $query->the_post();

        $term = get_the_terms(get_the_ID(), 'branch_ctg');
        $term_slug = $term && !is_wp_error($term) ? $term[0]->slug : 'default';
        $icon = isset($icon_urls[$term_slug]) ? $icon_urls[$term_slug] : $icon_urls['default'];

        $locations[] = [
            'storeName' => get_the_title(),
            'storeAddress' => apply_filters('the_content', get_the_content()),
            'websiteLink' => get_post_meta(get_the_ID(), 'websiteLink', true),
            'lat' => (float) get_post_meta(get_the_ID(), 'lat', true),
            'lng' => (float) get_post_meta(get_the_ID(), 'lng', true),
            'icon' => $icon
        ];
    }

    wp_reset_postdata();

    ob_start(); ?>
    <div id="customMap" style="width: 100%; height: 450px;"></div>
    <script>
        const locations = <?php echo wp_json_encode($locations); ?>;

        function initMap() {
            if (!locations.length) return;

            const map = new google.maps.Map(document.getElementById('customMap'), {
                zoom: 6,
                center: {lat: locations[0].lat, lng: locations[0].lng},
            });

            const infowindow = new google.maps.InfoWindow({ pixelOffset: new google.maps.Size(0, 0) });
            let closeTimeout;

            locations.forEach(location => {
                const marker = new google.maps.Marker({
                    position: { lat: location.lat, lng: location.lng },
                    map: map,
                    icon: {
                        url: location.icon,
                        scaledSize: new google.maps.Size(40, 40),
                        anchor: new google.maps.Point(20, 40)
                    }
                });

                const contentString = `
                    <div style="padding:10px; width:200px; max-height:220px;">
                        <div style="font-size:20px; font-weight:bold; margin-bottom:5px;color:#C6007E;">${location.storeName}</div>
                        <div style="font-size:14px; margin-bottom:5px;">${location.storeAddress}</div>
                        ${location.websiteLink ? `<a href="${location.websiteLink}" target="_blank" style="margin-top:5px; padding:10px; border: 1px solid #C6007E; display:inline-block;color:#C6007E;font-weight:bold;">Visit ${location.storeName}</a>` : ''}
                    </div>
                `;

                marker.addListener('mouseover', function() {
                    clearTimeout(closeTimeout);
                    infowindow.setContent(contentString);
                    infowindow.open(map, marker);
                });

                marker.addListener('mouseout', function() {
                    closeTimeout = setTimeout(() => infowindow.close(), 250);
                });

                google.maps.event.addListener(infowindow, 'domready', function() {
                    const iwOuter = document.querySelector('.gm-style-iw');
                    if (!iwOuter) return;

                    iwOuter.parentElement.style.pointerEvents = 'auto';

                    iwOuter.addEventListener('mouseenter', function() {
                        clearTimeout(closeTimeout);
                    });

                    iwOuter.addEventListener('mouseleave', function() {
                        infowindow.close();
                    });
                });
            });
        }
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('custom_google_map', 'custom_google_map_shortcode');
