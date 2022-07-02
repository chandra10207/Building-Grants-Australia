<?php

/**
 * Check for available grants on grants post type by state and price
 *
 * @param  string $state, string $price
 * @return array
 */
function get_grant_ids_by_state_and_price($state, $price)
{
    $grant_category_slug = [];
    $grant_category_slug[] = 'grants-available-in-'.$state;

    global $wpdb;


//    $wpdb->prepare( "SELECT object_id,city,region_code,region_name,postcode
//    FROM {$wpdb->prefix}gmw_locations  AS gl
//    WHERE gl.region_code = %s",
//        $region_code );


//  WHERE tt.taxonomy IN ( '" . implode("','", $grant_category_slug) . "' )
//  AND tt.term_id = ".$state."

    $query = "SELECT p.ID,p.post_title
            FROM $wpdb->posts AS p
            INNER JOIN $wpdb->term_relationships AS tr ON ('p.ID' = tr.object_id)
            INNER JOIN $wpdb->term_taxonomy AS tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
            INNER JOIN $wpdb->terms AS t ON (t.term_id = tt.term_id)
            WHERE   p.post_status = 'publish'
            AND p.post_type = 'grant'
            ORDER BY p.post_date DESC";

    $wpdb->prepare($query);


//    $query = "SELECT tt.term_id, tt.taxonomy, tt.description, t.name ,t.slug FROM " . $wpdb->term_taxonomy . " tt
//            INNER JOIN " . $wpdb->terms . " t ON t.term_id = tt.term_id
//            INNER JOIN " . $wpdb->term_relationships . " tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
//            WHERE tt.taxonomy IN ( '" . implode("','", $attribute_slugs) . "' ) AND tr.object_id IN ( " . $post_ids . " ) GROUP BY tt.term_id;";


//
//
//    $results = $wpdb->get_results(
//        $wpdb->prepare( "SELECT object_id,city,region_code,region_name,postcode FROM {$wpdb->prefix}gmw_locations  AS gl WHERE gl.region_code = %s", $region_code ),
//        $wpdb->prepare( "SELECT object_id,city,region_code,region_name,postcode FROM {$wpdb->prefix}gmw_locations  AS gl WHERE gl.region_code = %s", $region_code ),
//        OBJECT_K
//    );
//    $product_ids = array();
//    foreach($results as $result){
//        $product_ids[]= $result->object_id;
//    }
//    return $product_ids;



    $grant_category_slug = [];
    $grant_category_slug[] = 'grants-available-in-'.$state;
    $grant_cat = [];


    $args = array(
        'post_type' => 'grant',
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'grant-type',
//                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => array( 'grants-available-in-nsw', 'grants-available-in-vic', ),
//                'terms'    => array( 'first-home-buyers-tips',),
//                'operator' => 'IN'
            )
        )
    );


//    $tax_query = [];
    $meta_query = [];
    $tax_query = array();
//    $meta_query = array(array(
//        'key' => '_stock_status',
//        'value' => 'instock'
//    ));

//    $tax_query[] = array(
//        'taxonomy' => 'grant-type',
//        'field' => 'slug',
//        'terms' =>  $grant_category_slug,
//        'operator' => 'IN'
//    );
    $tax_query[] = array(
        'taxonomy' => 'grant-type',
        'field' => 'id',
        'terms' => array(17020,),
    );
//
//
//    if (!empty($tax_query)) {
//        $args['tax_query'] = $tax_query;
//    }
//
//    if($price != ''){
//        $meta_query[] = array(
//            'key' => 'wpcf-max-price',
//            'value' => $price,
//            'compare' => '<='
//        );
//    }
////
//    if($meta_query ){
//        $args['meta_query'] = $meta_query;
//    }

    var_dump($args);

    $grant_ids = get_posts( $args );
//    $grant_ids = new WP_Query( $args );
    return $grant_ids;
}

