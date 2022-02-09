<?php

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