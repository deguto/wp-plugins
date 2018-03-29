<?php

/*
 * GMAPS STUFF
 */
############################MAP STUFF######################
//add_action('wp_enqueue_scripts', 'enqueue_gmap');

function enqueue_gmap() {
    // script goes only in the map page template
    if (!is_page_template('page-google-map.php'))
        return;
    wp_register_script('google-maps-api', 'http://maps.google.com/maps/api/js?sensor=false', false, false);
    wp_register_script('posts_map', get_template_directory_uri() . '/js/mygmap.js', false, false, true);
    wp_enqueue_script('google-maps-api');
    wp_enqueue_script('posts_map');

    // use a custom field on the map page to setup the zoom
    global $post;
    $zoom = (int) get_post_meta($post->ID, 'map_zoom', true);
    if (!$zoom)
        $zoom = 6;

    $map_data = array('markers' => array(), 'center' => array(41.890262, 12.492310), 'zoom' => $zoom);
    $lats = array();
    $longs = array();

    $args = array('posts_per_page' => -1, 'post_type' => 'address'); // put here your query args
    $map_query = new WP_Query($args);
    //print_r($map_query);
    if ($map_query->have_posts()) :
        //print_r($map_query);
        while ($map_query->have_posts()) : $map_query->the_post();
            $meta_coords = get_post_meta(get_the_ID(), 'gmapp_address', true);
            //print_r($meta_coords);
            if ($meta_coords) {
                //echo "i am here";
                $coords = array_map('floatval', array_map('trim', explode(',', $meta_coords)));
                $title = get_the_title();
                $link = sprintf('<a href="%s">%s</a>', get_permalink(), $title);
                $map_data['markers'][] = array(
                    'latlang' => $coords,
                    'title' => $title,
                    'desc' => '<h3 class="marker-title">' . $link . '</h3><div class="marker-desc">' . get_the_excerpt() . '</div>',
                );
                $lats[] = $coords[0];
                $longs[] = $coords[1];
                print_r($lats);
            }
        endwhile;
        // auto calc map center
        if (!empty($lats))
            $map_data['center'] = array((max($lats) + min($lats)) / 2, (max($longs) + min($longs)) / 2);
    endif;
    print_r($map_data);
    wp_reset_postdata;
    wp_localize_script('posts_map', 'map_data', $map_data);
}

#################################

### lat long search############
/**
 * Create Database table for geodata
 */
global $my_db_version;
$my_db_version = '1.1';

function my_install() {
    global $wpdb;
    global $my_db_version;

    $table_name = $wpdb->prefix . 'my_geodata';

    /*
     * We'll set the default character set and collation for this table.
     * If we don't do this, some characters could end up being converted
     * to just ?'s when saved in our table.
     */
    $charset_collate = '';

    if ( ! empty( $wpdb->charset ) ) {
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
    }

    if ( ! empty( $wpdb->collate ) ) {
        $charset_collate .= " COLLATE {$wpdb->collate}";
    }

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        post_id BIGINT NULL UNIQUE,
        lat DECIMAL(9,6) NULL,
        lng DECIMAL(9,6) NULL,
        UNIQUE KEY id (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    add_option( 'my_db_version', $my_db_version );
}

function my_geodata_update_db_check() {
    global $my_db_version;
    if ( get_site_option( 'my_db_version' ) != $my_db_version ) {
        my_install();
    }
}
add_action( 'init', 'my_geodata_update_db_check' );


/**
 * Insert geodata into table
 */
function insert_geodata( $data ) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'my_geodata';

    //Check date validity
    if( !is_float($data['lat']) || !is_float($data['lng']) || !is_int($data['post_id']) )
        return 0;

    $wpdb->insert(
        $table_name,
        array(
            'post_id' => $data['post_id'],
            'lat'     => $data['lat'],
            'lng'     => $data['lng'],
        ),
        array(
            '%d',
            '%f',
            '%f'
        )
    );
}

/**
 * Checks if entry for post_id exists
 */
function check_geodata($post_id) {

    global $wpdb;
    $table_name = $wpdb->prefix . 'my_geodata';

    //Check date validity
    if( !is_int($post_id) )
        return 0;

    $sql = "SELECT * FROM $table_name WHERE post_id = $post_id";
    $geodata = $wpdb->get_row($sql);

    if($geodata) {
        return true;
    }
}


/**
 * Delete entry for post_id
 */
function delete_geodata($post_id) {

    global $wpdb;
    $table_name = $wpdb->prefix . 'my_geodata';

    //Check date validity
    if( !is_int($post_id) )
        return 0;

    $delete = $wpdb->delete( $table_name, array( 'post_id' => $post_id ) );

    return $delete;
}


/**
 * Update existing
 */
function update_geodata($data) {

    global $wpdb;

    $table_name = $wpdb->prefix . 'my_geodata';

    //Check date validity
    if( !is_float($data['lat']) || !is_float($data['lng']) || !is_int($data['post_id']) )
        return 0;

    $wpdb->update(
        $table_name,
        array(
            'lat'     => $data['lat'],
            'lng'     => $data['lng'],
        ),
        array(
            'post_id' => $data['post_id'],
        ),
        array(
            '%f',
            '%f'
        )
    );
}

/**
 * Insert or update current post geodata
 */
function add_geodata( $data ) {
    global $wpdb;

    //Check date validity
    if( !is_float($data['lat']) || !is_float($data['lng']) || !is_int($data['post_id']) )
        return 0;

    /**
     * Check if geodata exists and update if exists else insert
     */
    if( check_geodata( $data['post_id'] ) ) {
        update_geodata( $data );
    } else {
        insert_geodata( $data );
    }
}

/**
 * Loop trough all clinics and update geodata in custom table
 */
function update_post_geodata() {
    $args = array(
        'posts_per_page' => -1,
        'post_type'      => 'address',
    );
    $posts = get_posts( $args );

    if($posts):
        foreach($posts as $item):

            //$tables  = get_field('tables', $item->ID);
            $address = get_field('gmapp_address', $item->ID);

            /**
             * Update Lat/Lng for every clinic
             */
            $id  = (int) $item->ID;
            $lat = (float) $address['lat'];
            $lng = (float) $address['lng'];

            if( $address ):
                $data = array(
                    'post_id' => $id,
                    'lat'     => $lat,
                    'lng'     => $lng
                );

                add_geodata( $data );
            endif;

        endforeach;
    endif;

}

/**
 * Transient for locations
 * Run update post geodata every hour
 */
if ( false === ( $update_geodata = get_transient( 'locations_update_geodata' ) ) ) {
    update_post_geodata();
    set_transient( 'locations_update_geodata', $update_geodata, 1 * HOUR_IN_SECONDS );
}

// Create a simple function to delete our transient
function de_delete_transient() {
    delete_transient( 'locations_update_geodata' );
}
// Add the function to the edit/save post hook so it runs when posts are edited
add_action( 'save_post', 'de_delete_transient' );
add_action( 'edit_post', 'de_delete_transient' );

// calculate destination lat/lon given a starting point, bearing, and distance
function destination($lat,$lon, $bearing, $distance,$units="km") {
    $radius = strcasecmp($units, "km") ? 3963.19 : 6378.137;
    $rLat = deg2rad($lat);
    $rLon = deg2rad($lon);
    $rBearing = deg2rad($bearing);
    $rAngDist = $distance / $radius;

    $rLatB = asin(sin($rLat) * cos($rAngDist) +
        cos($rLat) * sin($rAngDist) * cos($rBearing));

    $rLonB = $rLon + atan2(sin($rBearing) * sin($rAngDist) * cos($rLat),
            cos($rAngDist) - sin($rLat) * sin($rLatB));

    return array("lat" => rad2deg($rLatB), "lon" => rad2deg($rLonB));
}

// calculate bounding box
function bound($lat,$lon, $distance,$units="km") {
    return array("N" => destination($lat,$lon,   0, $distance,$units),
        "E" => destination($lat,$lon,  90, $distance,$units),
        "S" => destination($lat,$lon, 180, $distance,$units),
        "W" => destination($lat,$lon, 270, $distance,$units));
}
/*
// calculate distance between two lat/lon coordinates
function distance($latA,$lonA, $latB,$lonB, $units="km") {
    $radius = strcasecmp($units, "km") ? 3963.19 : 6378.137;
    $rLatA = deg2rad($latA);
    $rLatB = deg2rad($latB);
    $rHalfDeltaLat = deg2rad(($latB - $latA) / 2);
    $rHalfDeltaLon = deg2rad(($lonB - $lonA) / 2);

    return 2 * $radius * asin(sqrt(pow(sin($rHalfDeltaLat), 2) +
        cos($rLatA) * cos($rLatB) * pow(sin($rHalfDeltaLon), 2)));
}
*/

function getGeoQuery($full_address, $range = 20,$offset = 0, $post_per_page = 10)
{
    global $wpdb;
    $sql ="";
    /*
 * Define variables
 */
    $sort        = 'ASC'; // Sort posts
    $table_name  = $wpdb->prefix . 'my_geodata';

    /*
     * Construct basic SQL query
     * Query will return all posts sorted by post title
     */
    $sql_query  = "SELECT SQL_CALC_FOUND_ROWS * FROM $wpdb->posts";
    $sql_join   = " INNER JOIN $table_name AS geo ON ($wpdb->posts.ID = geo.post_id)";
    $sql_where  = " WHERE ($wpdb->posts.post_status = 'publish' AND $wpdb->posts.post_type = 'address')";
    $sql_group  = " GROUP BY {$wpdb->posts}.ID";
    $sql_order  = " ORDER BY $wpdb->posts.post_title $sort";
    $sql_limit  = " LIMIT $offset, $post_per_page";


    $address_one_line = preg_replace('/ *(\r\n|\r|\n)+ */', " ", $full_address); //clean address

    $address = urlencode($address_one_line); // Spaces as + signs

    $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false");

    if ( !$json ) {
        echo 'Es ist ein Fehler aufgetreten!';
        return false;
    }

    $data = json_decode($json);
    if ( !$data ) {
        echo '<h2>ERROR! Google Maps returned an invalid response, expected JSON data:</h2>';
        echo esc_html(print_r($json, true));
        exit;
    }

    if ( isset($data->{'error_message'}) ) {
        echo '<h2>ERROR! Google Maps API returned an error:</h2>';
        echo '<strong>'. esc_html($data->{'status'}) .'</strong> ' . esc_html($data->{'error_message'}) .'<br>';
        exit;
    }

    if ( empty($data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'}) || empty($data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'}) ) {
        echo '<h2>ERROR! Latitude/Longitude could not be found:</h2>';
        echo esc_html(print_r($data, true));
        exit;
    }

    $search_lat = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
    $search_lng = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

    /*
     * If latitude and longitude are defined expand the SQL query
     */
    if( $search_lat && $search_lng )
    {
        /*
         * Calculate range
             * Function will return minimum and maximum latitude and longitude
         */
        $b = bound($search_lat,$search_lng, $range,"km");

        /*
         * Update SQL query
         */
        $sql_where .= " AND ( (geo.lat BETWEEN {$b["S"]["lat"]} AND {$b["N"]["lat"]}) AND (geo.lng BETWEEN {$b["W"]["lon"]} AND {$b["E"]["lon"]} ) )";
    }

    /*
     * Construct SQL query and get results
     */
    $sql   = $sql_query . $sql_join . $sql_where . $sql_group . $sql_order . $sql_limit;

    return $sql;
}

?>
