<?php

add_action( 'porto_after_main', 'csp_add_cta_bottom_grant_cat',99 );
function csp_add_cta_bottom_grant_cat() {
	
	$current_term = get_queried_object();
    
    // If the current term does not belong to advert post type, bail
    if ( isset($current_term->taxonomy) AND $current_term->taxonomy == 'grant-type' ){
		echo do_shortcode( '[porto_block name="footer-top"]' );
	}
	
	elseif ( is_product() && has_term( 'rebates', 'product_cat' ) ) {

      echo do_shortcode( '[porto_block name="rebates-details-page-bottom"]' );
}
	// elseif(is_product()){
	// 	echo do_shortcode( '[porto_block name="default-cta"]' );
	// }
	else{
		
	}
        
    
}

	
// Meta Fields
function porto_grant_meta_fields() {

	return array(
		// Share
		'grant_share'     => array(
			'name'    => 'grant_share',
			'title'   => __( 'Share', 'porto-functionality' ),
			'type'    => 'radio',
			'default' => '',
			'options' => porto_ct_share_options(),
		),
		// Microdata Rich Snippets
		'grant_microdata' => array(
			'name'    => 'grant_microdata',
			'title'   => __( 'Microdata Rich Snippets', 'porto-functionality' ),
			'type'    => 'radio',
			'default' => '',
			'options' => porto_ct_enable_options(),
		),
	);
}

function porto_grant_view_meta_fields() {
	$meta_fields = porto_ct_default_view_meta_fields();
	// Layout
	$meta_fields['layout']['default'] = 'fullwidth';
	return $meta_fields;
}

function porto_grant_skin_meta_fields() {
	$meta_fields = porto_ct_default_skin_meta_fields();
	return $meta_fields;
}

// Show Meta Boxes
add_action( 'add_meta_boxes', 'porto_add_grant_meta_boxes' );
function porto_add_grant_meta_boxes() {
	if ( ! function_exists( 'get_current_screen' ) ) {
		return;
	}
	global $porto_settings;
	$screen = get_current_screen();
	if ( function_exists( 'add_meta_box' ) && $screen && 'post' == $screen->base && 'grant' == $screen->id ) {
//		add_meta_box( 'grant-meta-box', __( 'grant Options', 'porto-functionality' ), 'porto_grant_meta_box', 'grant', 'normal', 'high' );
		add_meta_box( 'view-meta-box', __( 'View Options', 'porto-functionality' ), 'porto_grant_view_meta_box', 'grant', 'normal', 'low' );
		if ( $porto_settings['show-content-type-skin'] ) {
			add_meta_box( 'skin-meta-box', __( 'Skin Options', 'porto-functionality' ), 'porto_grant_skin_meta_box', 'grant', 'normal', 'low' );
		}
	}
}

function porto_grant_meta_box() {
	$meta_fields = porto_grant_meta_fields();
	porto_show_meta_box( $meta_fields );
}

function porto_grant_view_meta_box() {
	$meta_fields = porto_grant_view_meta_fields();
	porto_show_meta_box( $meta_fields );
}

function porto_grant_skin_meta_box() {
	$meta_fields = porto_grant_skin_meta_fields();
	porto_show_meta_box( $meta_fields );
}

// Save Meta Values
add_action( 'save_post', 'porto_save_grant_meta_values' );
function porto_save_grant_meta_values( $post_id ) {
	if ( ! function_exists( 'get_current_screen' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( $screen && 'post' == $screen->base && 'grant' == $screen->id ) {
		porto_save_meta_value( $post_id, porto_grant_meta_fields() );
		porto_save_meta_value( $post_id, porto_grant_view_meta_fields() );
		porto_save_meta_value( $post_id, porto_grant_skin_meta_fields() );
	}
}

// Remove in default custom field meta box
add_filter( 'is_protected_meta', 'porto_grant_protected_meta', 10, 3 );
function porto_grant_protected_meta( $protected, $meta_key, $meta_type ) {
	if ( ! function_exists( 'get_current_screen' ) ) {
		return $protected;
	}
	$screen = get_current_screen();
	if ( ! $protected && $screen && 'post' == $screen->base && 'grant' == $screen->id ) {
		if ( array_key_exists( $meta_key, porto_grant_meta_fields() )
			|| array_key_exists( $meta_key, porto_grant_view_meta_fields() )
			|| array_key_exists( $meta_key, porto_grant_skin_meta_fields() ) ) {
			$protected = true;
		}
	}
	return $protected;
}


 /*



////////////////////////////////////////////////////////////////////////

// Taxonomy Meta Fields
function porto_grant_cat_meta_fields() {
	global $porto_settings;

	$meta_fields = porto_ct_default_view_meta_fields();
	if ( isset( $porto_settings['show-category-skin'] ) && $porto_settings['show-category-skin'] ) {
		$meta_fields = array_merge( $meta_fields, porto_ct_default_skin_meta_fields( true ) );
	}

	return $meta_fields;
}

$taxonomy             = 'grant-type';
$table_name           = $wpdb->prefix . $taxonomy . 'meta';
$variable_name        = $taxonomy . 'meta';
$wpdb->$variable_name = $table_name;

// Add Meta Fields when edit taxonomy
add_action( 'grant-type_edit_form_fields', 'porto_edit_grant_type_meta_fields', 100, 2 );
function porto_edit_grant_type_meta_fields( $tag, $taxonomy ) {
	if ( 'grant-type' !== $taxonomy ) {
		return;
	}
	porto_edit_tax_meta_fields( $tag, $taxonomy, porto_grant_cat_meta_fields() );
}

// Save Meta Values
add_action( 'edit_term', 'porto_save_granttype_meta_values', 100, 3 );
function porto_save_granttype_meta_values( $term_id, $tt_id, $taxonomy ) {
	if ( 'grant-type' !== $taxonomy ) {
		return;
	}
	porto_create_tax_meta_table( $taxonomy );
	return porto_save_tax_meta_values( $term_id, $taxonomy, porto_grant_cat_meta_fields() );
}

// Delete Meta Values
add_action( 'delete_term', 'porto_delete_granttype_meta_values', 10, 5 );
function porto_delete_granttype_meta_values( $term_id, $tt_id, $taxonomy, $deleted_term, $object_ids ) {
	if ( 'grant-type' !== $taxonomy ) {
		return;
	}
	return porto_delete_tax_meta_values( $term_id, $taxonomy, porto_grant_cat_meta_fields() );
}

*/



