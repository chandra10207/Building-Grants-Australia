<?php 

/*Change default visual editor to text editor*/
add_filter( 'wcfm_is_allow_wpeditor_quicktags', '__return_true' );

add_filter( 'woocommerce_price_trim_zeros', '__return_true' );

  
function bbloomer_add_price_prefix( $price, $product ){
    $price = '<small>From </small> ' . $price;
    return $price;
}
// add_filter( 'woocommerce_get_price_html', 'bbloomer_add_price_prefix', 99, 2 );

function nd_add_land_measurement_details(){
    global $product;
	$product_id = $product->get_id();
    $house_size = get_the_terms( $product_id , 'pa_house-size-sqm');
    $lot_size = get_the_terms( $product_id , 'pa_total-lot-area-sqm');
// 	$house_name = get_field('house_name', $post_id);
// 	echo '<pre>';
// 	print_r($house_size);
	$data = '';
    $data .= '<div class="lead1 mb-0 nd-specification-number1 nd_land_measurement_details">';
    $data .= '<ul class="list list-unstyled mt-2 short-desc1 overflow-hidden">';
//     if($house_name){
//         $data .= '<li class="mb-1"><strong>'.$house_name.'</strong></li>';
//     }
    if($house_size){
        $data .= '<li class="mb-1"><strong>House Size: </strong> <span class="m1l-1">'.$house_size[0]->name.' <span class="nd-property-lbl-details bed"></span>m<sup>2</sup></span></li>';
    }
    if($lot_size){
        $data .= '<li class="mb-1"><strong>Lot Size: </strong> <span class="m1l-1">'.$lot_size[0]->name.' <span class="nd-property-lbl-details bath"></span>m<sup>2</sup></span></li>';
    }
    $data .= '</ul>';
    $data .= '</div>';
	echo $data;
}


function nd_add_bed_bath_garage_number(){
    global $product;
	$product_id = $product->get_id();
    $bed_num = get_the_terms( $product_id , 'pa_bedrooms');
    $bath_num = get_the_terms( $product_id , 'pa_bathrooms');
    $garage_num = get_the_terms( $product_id , 'pa_garage');
    $lot_size = get_the_terms( $product_id , 'pa_total-block-area-sqm');
// 	$house_name = get_field('house_name', $post_id);
// 	$location = get_field('full_address', $post_id);
// 	echo '<pre>';
// 	print_r($bed_num);
// 	
	
	
	$data = '';
//     if($location){
//         $data .= '<p class="my-3"><i class="fas fa-map-marker-alt"></i> <span class="ml-1">'.$location.' <span class="nd-property-lbl-details bed"></span></span></p>';
//     }
	
// 	if ( is_single() ){
		
//     if($house_name){
//         $data .= '<p class="my-3"><span class="ml-1"><strong>'.$house_name.' </strong></span></p>';
//     }
// 		$data .= wc_get_product_category_list( $product->get_id(), ', ', '<p class="nd_design_type lead1 mb-2 text-dark overflow-hidden"> Type: ' . _n( ' ', ' ', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</p>' );
// 	}
	
    $data .= '<div class="lead mb-0 nd-specification-number nd-beds-numbers-house-and-land">';
    $data .= '<ul class="list list-unstyled mt-2 short-desc1 overflow-hidden">';
    if($bed_num){
        $data .= '<li><i class="fas fa-bed fa-xs1 ml-1 text-color-primary"></i> <span class="ml-1">'.$bed_num[0]->name.' <span class="nd-property-lbl-details bed"></span></span></li>';
    }
    if($bath_num){
        $data .= '<li><i class="fas fa-shower fa-xs1 ml-1 text-color-primary"></i> <span class="ml-1">'.$bath_num[0]->name.' <span class="nd-property-lbl-details bath"></span></span></li>';
    }
    if($garage_num){
        $data .= '<li><i class="fas fa-car fa-xs1 ml-1 text-color-primary"></i> <span class="ml-1">'.$garage_num[0]->name.' <span class="nd-property-lbl-details garage"></span></span></li>';
    }
    if($lot_size){
        $data .= '<li class="mb-1"><i class="text-color-primary fas fa-home1 fa-vector-square1 fa-expand-arrows-alt font-size-lg fa-xs ml-1"></i> <span class="m1l-1">'.$lot_size[0]->name.' <span class="nd-property-lbl-details bath"></span>m<sup>2</sup></span></li>';
    }
	
	
	
	
	
		
    $data .= '</ul>';
    $data .= '</div>';
	
	echo $data;
	/*
    ?>
 
			<div class="addtionalInfo mb-3">
			    <?php
	
             if($product->get_sku()) { 
             echo 'SKU: <span class="sku sku-brand-code">'.$product->get_sku().'</span><br>';
             }
 $product_brands = get_the_terms( $product->get_id() , 'pa_bed');
	
            if($product_brands)
            {
			echo 'Bed: <span class="brand sku-brand-code">'.$product_brands[0]->name.'</span><br>';
            }
              ?>
			</div>
    <?php*/
}
// add_action( 'woocommerce_after_shop_loop_item_title', 'nd_add_land_measurement_details', 20 );
add_action( 'woocommerce_after_shop_loop_item_title', 'nd_add_bed_bath_garage_number', 21 );







// Product manage path
// /Applications/Ampps/www/grant/wp-content/plugins/wc-frontend-manager/controllers/products-manager/wcfm-controller-products-manage.php

// add_filter( 'wcfm_product_content_before_save', 'nd_products_data_filter_before_save', 10,3 );

function nd_products_data_filter_before_save ($post_data, $wcfm_products_manage_form_data)
{
	echo '<pre>';
	print_r($wcfm_products_manage_form_data);

	die;

}


// add_filter( 'gmw_lf_location_args_before_location_updated', 'nd_ad_wp_geo_data_to_attriubutes', 10,3 );

function nd_ad_wp_geo_data_to_attriubutes ($location_args, $location, $wcfm_products_manage_form_data)
{
	echo '<pre>';
	print_r($location_args);
	echo '<pre>';
	print_r($wcfm_products_manage_form_data);

	die;

}




// add_action( 'save_post', 'custom_process_state_save_post', 10,3 );

function custom_process_state_save_post( $post_id, $post, $update ) {



    // exit if not the target post type
    if ( ('product' !== $post->post_type) OR ($post->post_status == 'auto-draft') OR (empty($_POST)) ) {
        return;
    }
    // current post ID
    $post_id = $post->ID;
    // target taxonomy slug
    $taxonomy_slug = 'pa_suburb';

    // var_dump($_POST['wcfm_products_manage_form']);die;

    // $_POST['wpcf-state'] value exists, it means that post is being added using the front-end form
    if(!empty($_POST['wpcf-state'])) {
        // get the term's name from the form's state field
        $term_name = $_POST['wpcf-state'];
        // call the function that processes the taxonomy attachment
        custom_process_new_state_term($post_id, $term_name, $taxonomy_slug);
    }
    // $_POST['wpcf']['state'] value exists, it means that post is being added using the back-end post edit screen
    if(!empty($_POST['wpcf']['state'])) {
        // get the term's name from the state field
        $term_name = $_POST['wpcf']['state'];
        // call the function that processes the taxonomy attachment
        custom_process_new_state_term($post_id, $term_name, $taxonomy_slug);
    }
}
 
// function that processes the taxonomy attachment
function custom_process_new_state_term($post_id, $term_name, $taxonomy_slug) {
    // check if the term already exists
    $term = term_exists( $term_name, $taxonomy_slug );
    // if does exist
    if ( $term !== 0 && $term !== null ) {
        // attach it to the current post
        wp_set_post_terms( $post_id, $term['term_id'], $taxonomy_slug );
    }
    // if doesn't exist
    else
    {   
        // first add it as a new term in the taxonomy
        $inset_new_term = wp_insert_term($term_name, $taxonomy_slug);
        if ( ! is_wp_error( $inset_new_term ) )
        {
            // and then attach this new term to the current post
            $term_id = $inset_new_term['term_id'];
            wp_set_post_terms( $post_id, $term_id, $taxonomy_slug );
        }
    }
}






