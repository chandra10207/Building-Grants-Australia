<?php
add_action('init',function(){
    global $WCFM, $WCFMmp;

    if($WCFMmp){
    	

    remove_action('woocommerce_after_shop_loop_item_title', array( $WCFMmp->frontend, 'wcfmmp_sold_by_product' ), 9 );
    remove_action('woocommerce_after_shop_loop_item_title', array( $WCFMmp->frontend, 'wcfmmp_sold_by_product' ), 50 );
        // remove_action('woocommerce_after_shop_loop_item', array( $WCFMmp->frontend, 'wcfmmp_sold_by_product' ), 50 );
    remove_action( 'woocommerce_product_meta_start',   array( $WCFMmp->frontend, 'wcfmmp_sold_by_single_product' ), 50 );
    }
});

if ( !is_admin() ) {
	
/*Change Sold By Label*/
add_filter( 'wcfmmp_is_allow_sold_by_label', '__return_false' );
// add_filter( 'wcfmmp_is_allow_sold_by_logo', '__return_false' );


/*Single product page additional Styles and scripts*/
add_action( 'wp_enqueue_scripts', 'nd_load_single_product_scripts', 1001 );

/*All details page shortcodes*/
add_shortcode('nd_short_desc', 'nd_add_short_desc'); 
add_shortcode( 'nd_inclusion_section', 'nd_add_inclusions_section_func');
add_shortcode('nd_key_features', 'nd_house_and_lands_key_features'); 
add_shortcode('nd_internal_gallery', 'nd_internal_gallery_func'); 
add_shortcode('nd_nearby_features', 'nd_nearby_features_func'); 

add_filter('woocommerce_product_additional_information_heading', 'nd_product_additional_information_heading');
add_action( 'woocommerce_product_meta_end', 'nd_add_brochure_pdf_single_product', 5 );


}


/* Load Single product page additional Scripts*/

function nd_load_single_product_scripts() {

	if (!is_admin() && class_exists( 'Woocommerce' )) {  

		// Slick Slider CSS
		wp_deregister_style( 'slick_slider' );
		wp_register_style( 'slick_slider', esc_url( get_stylesheet_directory_uri() ) . '/vendor/slick/slick.css' );
		wp_enqueue_style( 'slick_slider' );

		wp_deregister_style( 'slick_slider_theme' );
		wp_register_style( 'slick_slider_theme', esc_url( get_stylesheet_directory_uri() ) . '/vendor/slick/slick-theme.css' );
		wp_enqueue_style( 'slick_slider_theme' );


		/*Load JS files*/
		wp_register_script('Slick_slider_js', esc_url( get_stylesheet_directory_uri() ) . '/vendor/slick/slick.min.js', array('jquery'), '1.0', true );  
		wp_enqueue_script('Slick_slider_js');


		/*Fancy Boxs*/
		wp_deregister_style( 'fancybox' );
		wp_register_style( 'fancybox', esc_url( get_stylesheet_directory_uri() ) . '/vendor/fancybox/jquery.fancybox.css' );
		wp_enqueue_style( 'fancybox' );

		wp_register_script('fancybox', esc_url( get_stylesheet_directory_uri() ) . '/vendor/fancybox/jquery.fancybox.js', array('jquery'), '1.0', true );  
		wp_enqueue_script('fancybox');





		// wp_register_script('Bootstrap_bundle_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );  
		// wp_enqueue_script('Bootstrap_bundle_js');

		wp_register_script('single_product_js', esc_url( get_stylesheet_directory_uri() ) . '/assets/js/single-product.js', array('jquery'), '1.0', true );  
		wp_enqueue_script('single_product_js');

	}

}


function nd_add_short_desc() {
    // $post_id = get_the_ID();
    global $product;
	$post_id = $product->get_id();
	$short_desc = trim(strip_tags(get_the_excerpt($post_id)));
	// echo '<pre> dfsbssbswb';var_dump($short_desc) ;die;

	$data = '';
	if($short_desc != ''){
		$data .= '<h2 class="vc_custom_heading align-left">Description</h2><p>';
		$data .= $short_desc;
		$data .= '</p><div class="porto-separator  "><hr class="separator-line  align_center solid my-4" style="background-color:#f5f5f5;"></div>';
	}
	echo $data;
}

 
function nd_product_additional_information_heading() {
    echo '<h5>Property Details</h5>';
}


function nd_add_brochure_pdf_single_product() {
    // $post_id = get_the_ID();
    global $product;
	$post_id = $product->get_id();
    $nd_pdf_brochure = get_post_meta($post_id,'wpcf-pdf-brochure');
	nd_add_bed_bath_garage_number();

// $location = gmw_get_post_location( $post_id );
	// $address = get_post_meta( $post_id, 'location_address', true );
	// echo '<pre>';var_dump($nd_pdf_brochure) ;die;
	// echo '<p><i class="fas fa-map-marker-alt fa-xs1 mr-2 ml-1 color-primary"></i>'. $location->address. '</p>';
    if($nd_pdf_brochure && $nd_pdf_brochure[0]){
    	$pdf_attachment_id = (int)$nd_pdf_brochure[0];


    	$pdf_url = wp_get_attachment_url( $pdf_attachment_id );
   echo '<div class="vc_btn3-container  d-inline-block mb-0 py-2 text-uppercase vc_btn3-inline">
	<a class="vc_btn3 vc_btn3-shape-default btn btn-borders11 btn-lg btn-dark" target="_blank" href="'.$pdf_url.'" title="">Download Brochure </a>	</div>';
}
	echo do_shortcode('[wcfm_inquiry]');

}


function nd_add_inclusions_section_func() {
    // $post_id = get_the_ID();
    global $product;
	$post_id = $product->get_id();
    $nd_inc_title = get_post_meta($post_id,'wpcf-inclusion-cta-title');
    $nd_inc_desc = get_post_meta($post_id,'wpcf-inclusions-section-description');
    $nd_inc_pdf = get_post_meta($post_id,'wpcf-inclusion-pdf');

  if($nd_inc_pdf && $nd_inc_pdf[0]){

  echo '<div class="col-md-12 text-center nd-inclusion-container bg-primary mt-5 text-center py-5">';

    if($nd_inc_title && $nd_inc_title[0]){
    	echo '<h2>'.$nd_inc_title[0].'</h2>';
    }
    else{
    	echo '<h2>View our standard inclusion</h2>';

    }

    if($nd_inc_desc && $nd_inc_desc[0]){
    	echo '<p>'.$nd_inc_desc[0].'</p>';
    }
    else{
    	echo '<p>Click on the Download inclusion brochure to find out more details.</p>';

    }

    if($nd_inc_pdf && $nd_inc_pdf[0]){
    	$pdf_attachment_id = (int)$nd_inc_pdf[0];

    	$pdf_url = wp_get_attachment_url( $pdf_attachment_id );
   echo '<div class="vc_btn3-container  d-inline-block mb-0 py-2 text-uppercase vc_btn3-inline">
	<a class="vc_btn3 vc_btn3-shape-default btn btn-borders11 btn-lg btn-dark" target="_blank" href="'.$pdf_url.'" title="">Download Inclusions Brochure </a>	</div>';

}

	echo '</div>';


}

}





//rename your custom function as you wish ( or leave it as is ).
function nd_gmw_update_post_type_post_location(  $post_id ) {

	// Return if it's a post revision.
	if ( false !== wp_is_post_revision( $post_id ) ) {
		return;
	}

	// check autosave.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// check if user can edit post.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// get the address from the custom field "address".
	$address = get_post_meta( $post_id, 'address', true );

	// varify that address exists.
	if ( empty( $address ) ) {
		return;
	}

	// verify the updater function.
	if ( ! function_exists( 'gmw_update_post_location' ) ) {
		return;
	}

	//run the udpate location function
	gmw_update_post_location( $post_id, $address );


}
//execute the function whenever post type is being updated
// add_action( 'save_post_post', 'nd_gmw_update_post_type_post_location' );



function nd_house_and_lands_key_features(){
    // $post_id = get_the_ID();
    global $product;
	$post_id = $product->get_id();
	$rows = get_post_meta($post_id,'wpcf-key-feature');
	// echo '<pre>';
	// var_dump($rows);die;
if( $rows &&  $rows[0] != '') {
	echo '<h2 class="vc_custom_heading align-left">Other Features</h2>';
    echo '<div class="wpb_content_element overflow-hidden">
    <ul class="pl-0 nd-list-half list list-icons list-icons-style-2 list-icons-lg1">';
    foreach( $rows as $row ) {
        $item = $row;
        if(trim(strip_tags($item)) != ''){
        	echo '<li><i class="fa fa-check"></i> ';
            echo $item;
        	echo '</li>';
        }
    }
    echo '</ul></div>';
	echo'<div class="porto-separator  "><hr class="separator-line  align_center solid" style="background-color:#f5f5f5;"></div>';
}
	
}




function nd_nearby_features_func(){
    // $post_id = get_the_ID();
    global $product;
	$post_id = $product->get_id();
	$rows = get_post_meta($post_id,'wpcf-near-by-feature');
	// echo '<pre>';
	// var_dump($rows);die;
if( $rows &&  $rows[0] != '') {
	echo '<h2 class="vc_custom_heading align-left">Near By Features</h2>';
    echo '<div class="wpb_content_element overflow-hidden">
    <ul class="pl-0 nd-list-half list list-icons list-icons-style-2 list-icons-lg1">';
    foreach( $rows as $row ) {
        $item = $row;
        if(trim(strip_tags($item)) != ''){
        	echo '<li><i class="fa fa-check"></i> ';
            echo $item;
        	echo '</li>';
        }
    }
    echo '</ul></div>';
	echo'<div class="porto-separator  "><hr class="separator-line  align_center solid" style="background-color:#f5f5f5;"></div>';
}
	
}



function nd_internal_gallery_func(){
    // $post_id = get_the_ID();
    global $product;
	$post_id = $product->get_id();
    $interior_images_ids = get_post_meta($post_id,'wpcf-interior-gallery');
	// $rows = get_post_meta($post_id,'wpcf-interior-gallery');
	// echo '<pre>';
	// var_dump($interior_images_ids);die;
if( $interior_images_ids[0] != '') {
	echo '<h2 class="vc_custom_heading align-left">Interior Gallery</h2>';
    echo '<div class="row">';
     // <ul class="pl-0 nd-list-half list list-icons list-icons-style-2 list-icons-lg1">';
    foreach( $interior_images_ids as $image_id ) {

    	// if(is_int($image_id)){
    	// 	$floor_plan_image = wp_get_attachment_image_url( (int)$image_id, 'large' );
    	// }
    	// else{
    	// 	$floor_plan_image = $image_id;
    	// }
    	$floor_plan_image = wp_get_attachment_image_url( (int)$image_id, 'large' );
    	// $image = wp_get_attachment_image_url(int()$image_id , 'large' );
        // $item = $row;
        echo '<div class="nd-interior-image col-md-4 overflow-hidden img-container mb-2">
									<a class="w-100 d-block overflow-hidden" data-fancybox="gallery" href="'.$floor_plan_image.'">
										<img class="img-fluid" src="'.$floor_plan_image.'" ></a>	</div>';
										// echo wp_get_attachment_image( $image_id, array(200, 112) );
        //     echo $item;
        // echo '</li>';
    }
    echo '</div>';
	echo'<div class="porto-separator  "><hr class="separator-line  align_center solid" style="background-color:#f5f5f5;"></div>';
}
	
}






