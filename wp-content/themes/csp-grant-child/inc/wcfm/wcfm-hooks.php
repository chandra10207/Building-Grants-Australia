<?php
// apply_filters( 'wcfm_vendor_store_taxomonies', $vendor_taxonomies, $this->get_id(), $taxonomy )
add_action('wcfm_product_manager_left_panel_before', 'csp_add_label_before_listing_title');
add_action('wcfm_product_manager_right_panel_before', 'csp_add_label_before_featured_image');
add_filter( 'upload_size_limit', 'csp_filter_site_upload_size_limit', 20 );
//add_action('wcfm_main_contentainer_after','csp_wcfm_add_js_dashboard');

if(!is_admin()){
add_filter( 'wcfm_vendor_store_taxomonies', 'csp_filter_vendor_categories',10,3 );
}

function csp_wcfm_add_js_dashboard() { ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.checklist_type_product_cat').change(function() {
                if( ($(this).is(":checked")) && ($(this).val()==107) ) {
                    //replace 107 with your "Group listing Item category" category id
                    // add your code to hide Rebates tab
                    } else
                    { //add your code to show Rebates tab
                        }
                    });
        });
                </script> <?php }


// add_filter( 'wcfm_is_allow_store_articles', '__return_true' );
    
    // add_filter( 'wcfm_is_allow_wpeditor_quicktags', '__return_true' );
    // add_filter( 'wcfmmp_store_tabs', 'csp_new_wcfmmp_store_tabs',90,2);
    // add_action( 'wcfmmp_before_store_article', 'csp_add_text_before_article_loop', 50, 2);

// add_action('wcfmmp_store_article_template','csp_store_article_html');

//     add_filter( 'wcfm_article_manage_fields_content', function( $article_fields, $article_id ) {
//     if( isset( $article_fields['excerpt'] ) ) {
//         $article_fields['excerpt']['type'] = 'textarea';
//         $article_fields = wcfm_hide_field( 'excerpt', $article_fields );
//     }
//     return $article_fields;
// }, 50, 2);




function csp_filter_site_upload_size_limit( $size ) {

    $current_user = wp_get_current_user();
    // if ( is_user_wcmp_vendor($current_user->ID) ){

    // }
        // Set the upload size limit to 500KB for users lacking the ‘manage_options’ capability.
        if (!current_user_can( 'manage_options' ) ) {
        // 500 KB.
        $size = 1024 * 4096;//chnage this code as oer your size requirement
    }
    else{
        $size = 1024 * 5000;
    }
    return $size;
}

function csp_new_wcfmmp_store_tabs($store_tabs, $id) {  
   $store_tabs['articles'] =  __( 'Articles', 'wc-multivendor-marketplace' );  
   return $store_tabs;
}

function csp_store_article_html() {
    $article_id = get_the_ID();
    $src = wp_get_attachment_image_src( get_post_thumbnail_id($article_id), 'thumbnail' );
    $url = $src[0];
    ?>
        <!-- Add your own HTML-->
    <div style="display: inline-block;">
        <img src="<?php echo $url;?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
        <p><a href="<?php echo esc_url(get_permalink($article_id)); ?>"><?php echo esc_attr(get_the_title()); ?></a></p>
    </div>
    <?php
}


function csp_add_text_before_article_loop( $store_id, $store_info ) {
    ?>
   <p>BEFORE LOOP </p>;
    <?php
};


add_action( 'wcfmmp_store_article_template_none', function() {
    ?>
    <h5><?php _e('Sorry. No Rebate yet!', 'porto'); ?></h5>
    <?php
});

add_action( 'wcfmmp_after_store_article', function( $store_id, $store_info ) {
    ?>
  </div><div class="clearfix"></div>
  <?php

    // rehub_pagination();
    echo 'End here';
}, 50, 2);



function csp_filter_vendor_categories ($vendor_taxonomies, $id, $taxonomy){
    if(array_key_exists('70', $vendor_taxonomies)){
        unset($vendor_taxonomies['70']);
    }
    return $vendor_taxonomies;
}

 function csp_add_label_before_listing_title(){
 	?>
 	<p>Select Listing Type</p>
 	<?php
 }


  function csp_add_label_before_featured_image(){
 	?>
 	<p class="text-center font-weight-bold">Select Facade or Main Images.</p>
    <p class="text-center px-lg-4 small"> (1200 px width by 900 px height. Max 4MB in size. )</p>
 	<?php
 }





