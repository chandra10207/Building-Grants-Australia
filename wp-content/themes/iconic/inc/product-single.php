<?php
 

add_action('init',function(){
    global $WCFM, $WCFMmp;

    remove_action('woocommerce_after_shop_loop_item_title', array( $WCFMmp->frontend, 'wcfmmp_sold_by_product' ), 9 );
    remove_action('woocommerce_after_shop_loop_item_title', array( $WCFMmp->frontend, 'wcfmmp_sold_by_product' ), 50 );
        // remove_action('woocommerce_after_shop_loop_item', array( $WCFMmp->frontend, 'wcfmmp_sold_by_product' ), 50 );
    remove_action( 'woocommerce_product_meta_start',   array( $WCFMmp->frontend, 'wcfmmp_sold_by_single_product' ), 50 );
});

/*Change Sold By Label*/
add_filter( 'wcfmmp_is_allow_sold_by_label', '__return_false' );
// add_filter( 'wcfmmp_is_allow_sold_by_logo', '__return_false' );


// Load CSS
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

add_action( 'wp_enqueue_scripts', 'nd_load_single_product_scripts', 1001 );

function nd_add_short_desc() {
    $post_id = get_the_ID();
	$short_desc = get_the_excerpt($post_id);
	$data = '';
	if($short_desc){
		$data .= '<h2 class="vc_custom_heading align-left">Description</h2><p>';
		$data .= $short_desc;
		$data .= '</p><div class="porto-separator  "><hr class="separator-line  align_center solid my-4" style="background-color:#f5f5f5;"></div>';
	}
	
	echo $data;

}
add_shortcode('nd_short_desc', 'nd_add_short_desc'); 



add_filter('woocommerce_product_additional_information_heading', 'nd_product_additional_information_heading');
 
function nd_product_additional_information_heading() {
    echo '<h5>Property Details</h5>';
}

add_action( 'woocommerce_product_meta_end', 'nd_add_brochure_pdf_single_product', 5 );
 
function nd_add_brochure_pdf_single_product() {
    $post_id = get_the_ID();
	// $pdf_ufile_name = get_field('upload_pdf_brochure', $post_id);
    // $pdf_array = explode(",",$pdf_ufile_name);
    // $pdf_array = array_filter($pdf_array);
	nd_add_bed_bath_garage_number();
   
	wc_get_template( 'single-product/tabs/additional-information.php' );
	     echo '<div class="mt-3 vc_btn3-container  d-inline-block mb-0 py-2 mr-2 text-uppercase vc_btn3-inline">';
echo '<a class="hash-scroll3 vc_btn3 vc_btn3-shape-default btn btn-modern btn-lg btn-secondary" href="#nd-message">Enquire Now</a>';
	   echo '</div>';
// 	   if( $pdf_ufile_name) {
// //    foreach($pdf_array as $value){
    
   echo '<div class="vc_btn3-container  d-inline-block mb-0 py-2 text-uppercase vc_btn3-inline">
	<a class="vc_btn3 vc_btn3-shape-default btn btn-borders11 btn-lg btn-dark" target="_blank" href="'.$pdf_ufile_name.'" title="">Download Brochure </a>	</div>';
// //}

// }

}

function nd_house_and_lands_key_features(){
	
    $post_id = get_the_ID();
	$rows = get_post_meta($post_id,'wpcf-key-feature');
	// echo '<pre>';
	// var_dump($rows);die;
if( $rows ) {
	echo '<h2 class="vc_custom_heading align-left">Key Features</h2>';
    echo '<div class="wpb_content_element overflow-hidden">
    <ul class="pl-0 nd-list-half list list-icons list-icons-style-2 list-icons-lg1">';
    foreach( $rows as $row ) {
        $item = $row;
        echo '<li><i class="fa fa-check"></i> ';
            echo $item;
        echo '</li>';
    }
    echo '</ul></div>';
	echo'<div class="porto-separator  "><hr class="separator-line  align_center solid" style="background-color:#f5f5f5;"></div>';
}
	
}

add_shortcode('nd_key_features', 'nd_house_and_lands_key_features'); 

