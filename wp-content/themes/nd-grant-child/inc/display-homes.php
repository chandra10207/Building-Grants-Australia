<?php 

if(!is_admin()){
	
add_shortcode('nd_display_opening_hours', 'nd_display_opening_hours_func'); 
add_shortcode('nd_designs_on_display', 'nd_designs_on_display_func'); 
}

function nd_display_opening_hours_func(){

    $post_id = get_the_ID();
	// get the location	
$location = gmw_get_post_location( $post_id );
// echo '<pre>';var_dump($location);die;

if($location){


// verify location ID
$location_id = ( int ) $location->ID;
	echo '<h5 class="vc_custom_heading align-left">Display Hours</h5>';


// Display the days and hours.
echo gmw_get_hours_of_operation( $location_id );
}
}



function nd_designs_on_display_func(){
global $product;
$cross_sell_ids = $product->get_cross_sell_ids();
$string_ids = implode(",", $cross_sell_ids);

if($cross_sell_ids){
	echo '<h2 class="vc_custom_heading align-left">Home Designs on Display</h2>';
	$product_shortcode = '[products columns="3" orderby="title" order="ASC" ids='.$string_ids.']';
	echo  do_shortcode($product_shortcode);
}
}


