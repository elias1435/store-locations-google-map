// Enqueue Google Maps JS API in your footer
// post type 'branch-location'
// custom fields 1. websiteLink, 2. lat, 3. lng
function enqueue_google_maps_script() {
    wp_enqueue_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY', [], null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_google_maps_script');

// Shortcode to display dynamic map with branch locations
function custom_google_map_shortcode() {
    $args = array(
        'post_type' => 'branch-location',
        'posts_per_page' => -1,
    );

    $query = new WP_Query($args);

    ob_start();
    ?>
    <div id="customMap" style="width: 100%; height: 450px;"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const locations = [
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                {
                    storeName: "<?php the_title(); ?>",
                    storeAddress: `<?php echo nl2br(wp_strip_all_tags(get_the_content())); ?>`,
                    websiteLink: "<?php echo get_post_meta(get_the_ID(), 'websiteLink', true); ?>",
                    lat: <?php echo floatval(get_post_meta(get_the_ID(), 'lat', true)); ?>,
                    lng: <?php echo floatval(get_post_meta(get_the_ID(), 'lng', true)); ?>
                },
                <?php endwhile; wp_reset_postdata(); ?>
            ];

            const map = new google.maps.Map(document.getElementById('customMap'), {
                zoom: 6,
                center: {lat: locations[0].lat, lng: locations[0].lng},
            });

            const infowindow = new google.maps.InfoWindow({pixelOffset: new google.maps.Size(0, 0)});
            let closeTimeout;

            locations.forEach(location => {
                const marker = new google.maps.Marker({
                    position: {lat: location.lat, lng: location.lng},
                    map: map,
                    icon: {
                        url: 'https://diamondlogistics.co.uk/wp-content/uploads/2025/03/markerpinSmall-1.png',
                        scaledSize: new google.maps.Size(40, 40),
                        anchor: new google.maps.Point(20, 40)
                    }
                });

                let contentString = `
                    <div style="padding:10px; width:200px; max-height:220px;">
                        <div style="font-size:20px; font-weight:bold; margin-bottom:5px;">${location.storeName}</div>
                        <div style="font-size:14px; margin-bottom:5px;">${location.storeAddress}</div>
                        ${location.websiteLink ? `<a href="${location.websiteLink}" target="_blank" style="margin-top:5px; display:inline-block;">Visit ${location.storeName}</a>` : ''}
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
        });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('custom_google_map', 'custom_google_map_shortcode');
