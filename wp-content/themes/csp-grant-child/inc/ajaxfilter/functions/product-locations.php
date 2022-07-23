<?php


function get_product_ids_by_region($region_code){
	global $wpdb;
	$results = $wpdb->get_results(
					$wpdb->prepare( "SELECT object_id,city,region_code,region_name,postcode FROM {$wpdb->prefix}gmw_locations  AS gl WHERE gl.region_code = %s", $region_code ),
					OBJECT_K
			   );
	$product_ids = array();
	foreach($results as $result){
		$product_ids[]= $result->object_id;
	}
	return $product_ids;
}

function get_suburbs_by_product_ids($product_ids_array){
	global $wpdb;
	$sql = "SELECT CONCAT(city,' ',region_code,' ',postcode) AS suburb FROM {$wpdb->prefix}gmw_locations  AS gl 
			WHERE gl.object_id IN (" . implode(',', array_map('intval', $product_ids_array)) . ")";
	
	$results = $wpdb->get_results($sql,OBJECT_K);
	$suburbs = array();
	foreach($results as $result){
		$suburbs[]= $result->suburb;
	}
	return $suburbs;	
}

function get_product_ids_by_suburb($suburb){
	global $wpdb;
	
	
    $results = $wpdb->get_results(
				$wpdb->prepare("SELECT object_id FROM {$wpdb->prefix}gmw_locations WHERE CONCAT(city,' ',region_code,' ',postcode) LIKE %s;",'%' . $wpdb->esc_like( $suburb ) . '%'),
				OBJECT_K
				);
	$product_ids = array();
	foreach($results as $result){
		$product_ids[]= $result->object_id;
	}
	return $product_ids;
}

function get_product_ids_by_postcodes($postcodes){
	global $wpdb;
	//$sql = array('0');
	foreach($postcodes as $postcode){
	    $sql[] = $wpdb->prepare("gl.postcode LIKE %s","%" . $wpdb->esc_like( $postcode ) . "%");
	}
	$sql = "SELECT object_id FROM {$wpdb->prefix}gmw_locations AS gl WHERE ".implode(' OR ', $sql);
	$results = $wpdb->get_results($sql,OBJECT_K);
	$product_ids = array();
	foreach($results as $result){
		$product_ids[]= $result->object_id;
	}
	return $product_ids;
}

/*
add_filter( 'posts_where', 'extend_wp_query_where', 10, 2 );
function extend_wp_query_where( $where, $wp_query ) {
    if ( $extend_where = $wp_query->get( 'extend_where' ) ) {
        $where .= " AND " . $extend_where;
    }
    return $where;
}
*/
/*
add_filter( 'posts_join', 'add_gmw_locations', 10, 2 );
function add_gmw_locations( $join, $wp_query ) {
 global $wpdb;
 //$join .= " JOIN {$wpdb->prefix}gmw_locations as mytable on mytable.object_id = {$wpdb->posts}.ID ";
 $join .= "LEFT JOIN nd20_gmw_locations as gmw_locations ON nd20_posts.ID = gmw_locations.object_id AND gmw_locations.object_type = 'post'";
 return $join;
}
*/

/*
	$sql = array('0'); // Stop errors when $words is empty

	foreach($words as $word){
	    $sql[] = 'name LIKE %'.$word.'%'
	}
	
	$sql = 'SELECT * FROM users WHERE '.implode(" OR ", $sql);
*/