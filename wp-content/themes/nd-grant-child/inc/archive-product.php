<?php



add_action( 'woocommerce_before_shop_loop_item_title', 'csp_add_grants_rebates_available_count', 15 );
function csp_add_grants_rebates_available_count(){
    global $product;
    $grant_count = 0;
    $product_id = $product->get_id();
    $rebate_price = get_post_meta($product_id, 'wpcf-rebate_price', true);
    $location = gmw_get_post_location( $product_id );
//    var_dump($location['region_code']);die;
    $state = strtolower($location->region_code);
    $state_grants_name = 'pa_grants-available-in-'.$state;
    $state_grants = get_the_terms( $product_id , $state_grants_name);
    $federal_grants = get_the_terms( $product_id , 'pa_grants-federal');
    if(!empty($state_grants)){
        $grant_count += count($state_grants);
    }
    if(!empty($federal_grants)){
        $grant_count += count($federal_grants);
    }
    ?>
        <div class="nd-grants-rebates-available">
            <?php if($rebate_price AND $rebate_price !=''){
                $k_rebate_price = intval($rebate_price)/1000;
                ?>
                <div> <span class="badge badge-primary"><?php echo $k_rebate_price ?>K Rebates Available</span></div>
            <?php } ?>
            <?php if($grant_count > 0){ ?>
                 <div><span class="badge badge-primary"><?php echo $grant_count; ?> Grants Available</span></div>
            <?php } ?>
        </div>
        <?php
}


add_action( 'woocommerce_after_main_content', 'nd_archive_ads_section', 10 );
function nd_archive_ads_section(){

	if(is_archive()){

	?>

	<div class="row py-4 py-lg-5 ">
		<div class="col-md-12 col-lg-10 offset-lg-1">
			<?php echo do_shortcode("[ads zone='3968']"); ?>
		</div>
	</div>
	<?php }
}
/**
 * Show products only of selected category.
 */
function get_subcategory_terms( $terms, $taxonomies, $args ) {
 
	$new_terms 	= array();
	$hide_category 	= array( 70 ); // Ids of the category you don't want to display on the shop page
 	
 	  // if a product category and on the shop page
	if ( in_array( 'product_cat', $taxonomies ) && !is_admin() && is_shop() ) {

	    foreach ( $terms as $key => $term ) {

		if ( ! in_array( $term->term_id, $hide_category ) ) { 
			$new_terms[] = $term;
		}
	    }
	    $terms = $new_terms;
	}
  return $terms;
}
// add_filter( 'get_terms', 'get_subcategory_terms', 10, 3 );

add_action('init',function(){
    global $WCFM, $WCFMmp;

	remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 20);
	add_action( 'woocommerce_after_shop_loop_item', 'nd_woocommerce_product_add_to_cart_text', 10,4 );

	


	/*WCFM Geolocate Hook*/
	// add_filter( 'wcfmmp_product_list_geolocate_map_defalt' , 'nd_product_page_custom_map',2000);

	/*Map Section Hooks*/
	// remove_action( 'woocommerce_before_shop_loop', array( $WCFMmp->frontend, 'wcfmmp_product_list_geo_location_filter' ), 1 );
	// add_action( 'woocommerce_before_shop_loop', 'na_add_geowp_map', 2 );
	// add_filter( 'gmw_modify_get_args', 'nd_gmw_map_data_filter', 1000 );

	// gmw_modify_get_args

});

if ( !is_admin() ) {

	// add_action( 'pre_get_posts', 'csp_exclude_design_floor_plan_category_pre_get_posts' );
	add_action( 'woocommerce_product_query', 'csp_exclude_design_floor_plan_category' );  



	}


function csp_exclude_design_floor_plan_category_pre_get_posts( $query ) {
    if ( is_shop() || is_product_category() || is_product_tag() ) {
        $query->set( 'category__not_in', array( 70 ));
    }
}

/**
 * Exclude products from a particular category on the shop page
 */
function csp_exclude_design_floor_plan_category( $q ) {

    $tax_query = (array) $q->get( 'tax_query' );

    $tax_query[] = array(
           'taxonomy' => 'product_cat',
           'field' => 'slug',
           'terms' => array( 'home-design-floor-plans' ), // Don't display products in the clothing category on the shop page.
           'operator' => 'NOT IN'
    );


    $q->set( 'tax_query', $tax_query );

}



function nd_gmw_map_data_filter($data){

	// echo '<pre>'; var_dump($data);die;
	return $data;

}

function na_add_geowp_map(){
	echo do_shortcode( '[gmw form="1"]' );
	// echo do_shortcode( '[gmw map="1"]' );
	// echo do_shortcode( '[gmw form="1" map="1" search_results="0" search_form="0"]]' );


}
/**
 * custom_woocommerce_template_loop_add_to_cart
*/
function nd_woocommerce_product_add_to_cart_text( ) {
	global $product;
	global $porto_settings;
	
	// $product_type = $product->get_type();
	$categories = get_the_terms( $product->get_id(), 'product_cat' );
	// array_push($categories ,$product->get_categories() );
	// $category =  $product->categories();
	$view_more_lbl = 'Find Out More';

	// echo '<pre>'; var_dump(count($categories));die;

	if(count($categories) < 2 )
	{
	switch ( $categories[0]->slug ) {

		case 'display-homes':
		$view_more_lbl = 'View Display';
		break;

		case 'home-designs':
		$view_more_lbl = 'View Design';
		break;

		case 'house-and-land-packages':
		$view_more_lbl = 'View Package';
		break;

		case 'rebates':
		$view_more_lbl = 'View Rebate';
		break;

		case 'apartments':
		$view_more_lbl = 'View Apartment';
		break;

		default:
			return __( 'Find Out More', 'woocommerce' );
	}

	}

	echo '<a class="nd-product-view-more-btn vc_btn3 vc_btn3-shape-default btn btn-borders11 w-100 btn-lg btn-primary" href='.get_permalink().'>'.$view_more_lbl.'</a>';
	
}

function nd_product_page_custom_map($args){
	global $WCFM, $WCFMmp;
	echo '<pre>';
	var_dump($WCFMmp);die;

}









