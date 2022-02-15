<?php 

function nd_display_opening_hours_func(){

    $post_id = get_the_ID();
	// get the location	
$location = gmw_get_post_location( $post_id );

// verify location ID
$location_id = ( int ) $location->ID;

// Display the days and hours.
echo gmw_get_hours_of_operation( $location_id );
}





add_shortcode('nd_display_opening_hours', 'nd_display_opening_hours_func'); 