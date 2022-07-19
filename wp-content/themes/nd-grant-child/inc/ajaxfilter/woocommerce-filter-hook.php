<?php


/* Filter AJAX */
add_action( 'wp_enqueue_scripts', 'grant_ajax_script' );
function grant_ajax_script() {
	//global $wp_query; 
    wp_enqueue_script( 'ajax-script', get_stylesheet_directory_uri() . '/inc/ajaxfilter/js/grant-ajax-script.js', array('jquery') );
    wp_enqueue_style('extended', get_stylesheet_directory_uri() . '/inc/ajaxfilter/extended.css', array(), '0.1.0', 'all');
 
    wp_localize_script( 'ajax-script', 'ajax_object', 
	    array(
	    'ajaxurl' => admin_url( 'admin-ajax.php' ),
	    //'tax_query' => json_encode( $wp_query->tax_query->queries ), 
	    //'query_vars' => json_encode( $wp_query->query_vars),  
	    'security' => wp_create_nonce( 'my-special-string' )
	    )
    );
}

add_action('wp_ajax_grant_action', 'grant_action_function');
add_action('wp_ajax_nopriv_grant_action', 'grant_action_function');

function grant_action_function(){
	
	global $porto_settings, $woocommerce_loop, $woocommerce;
	
	$builder_id = porto_check_builder_condition( 'shop' );
	if ( !wp_verify_nonce( $_REQUEST['security'], "my-special-string")) {
		exit("Not allowed.");
	} 
	$posts_per_page = 12;
	$filters = array();
	parse_str($_POST['formData'], $filters);
	$term_id =gt_clean($_POST['term_id']);
	
/*
	echo "<pre>";
	print_r($filters);
	echo "</pre>";
	wp_die();
*/

	$tax_query = [];
	$att_queries = [];
	$meta_query = [];
	
	$att_queries[] = array('taxonomy' => 'product_visibility', 'field' => 'term_taxonomy_id', 'terms' => array( 18 ),'operator' => 'NOT IN');
	$att_queries[] = array('taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => array( 'home-design-floor-plans' ),'operator' => 'NOT IN');
	
	if(array_key_exists('storeys',$filters) && !empty($filters['storeys'])){
		$att_queries[] = array('taxonomy' => 'pa_storeys', 'field' => 'name', 'terms' => $filters['storeys']);
	}
	if(array_key_exists('baths',$filters) && !empty($filters['baths'])){
		$att_queries[] = array('taxonomy' => 'pa_bathrooms', 'field' => 'name', 'terms' => $filters['baths']);
	}
	if(array_key_exists('beds',$filters) && !empty($filters['beds'])){
		$att_queries[] = array('taxonomy' => 'pa_bedrooms', 'field' => 'name', 'terms' => $filters['beds']);
	}
	if(array_key_exists('garage',$filters) && !empty($filters['garage'])){
		$att_queries[] = array('taxonomy' => 'pa_garage', 'field' => 'name', 'terms' => $filters['garage']);
	}
	if(array_key_exists('lr',$filters) && !empty($filters['lr'])){
		$att_queries[] = array('taxonomy' => 'pa_living-rooms', 'field' => 'name', 'terms' => $filters['lr']);
	}
	if(array_key_exists('design',$filters) && !empty($filters['design'])){
		$att_queries[] = array('taxonomy' => 'pa_design', 'field' => 'name', 'terms' => $filters['design']);
	}
	if(array_key_exists('type',$filters) && !empty($filters['type'])){
		$att_queries[] = array('taxonomy' => 'pa_house-type', 'field' => 'name', 'terms' => $filters['type']);
	}
	if(array_key_exists('feature',$filters) && !empty($filters['feature'])){
		$att_queries[] = array('taxonomy' => 'pa_other-features', 'field' => 'name', 'terms' => $filters['feature']);
	}
	if(array_key_exists('p_status',$filters) && !empty($filters['p_status'])){
		$att_queries[] = array('taxonomy' => 'pa_property-status', 'field' => 'name', 'terms' => $filters['p_status']);
	}
	if(array_key_exists('rebate_t',$filters) && !empty($filters['rebate_t'])){
		$att_queries[] = array('taxonomy' => 'pa_rebate-type', 'field' => 'name', 'terms' => $filters['rebate_t']);
	}
	if(array_key_exists('gt_fed',$filters) && !empty($filters['gt_fed'])){
		$att_queries[] = array('taxonomy' => 'pa_grants-federal', 'field' => 'name', 'terms' => $filters['gt_fed']);
	}
	if(array_key_exists('state',$filters) && !empty($filters['state'])){
		$state_grant_attr = 'pa_grants-available-in-'.trim(strtolower($filters['state']));
		$state_grant_key = 'gt_'.trim(strtolower($filters['state']));
		if(array_key_exists($state_grant_key,$filters) && !empty($filters[$state_grant_key])){
			$att_queries[] = array('taxonomy' => $state_grant_attr, 'field' => 'name', 'terms' => $filters[$state_grant_key]);
		}
	}
	
	
	if(!empty($att_queries)){
		$tax_query[]= ['relation' => 'AND'];
		$tax_query[] = array('taxonomy' => 'product_cat', 'field' => 'term_id', 'terms' => $term_id);
		$tax_query = array_merge($tax_query,$att_queries);
	}

	$args = array(
	    'post_type'        => 'product',
	    'post_status' => 'publish',
	    'suppress_filters' => true,
	    'ignore_sticky_posts' => true,
	    'tax_query' => $tax_query, 
	);
	
	$attr_args = array(
		'numberposts'	=> -1,
	    'post_type'        => 'product',
	    'post_status' => 'publish',
	    'suppress_filters' => true,
	    'ignore_sticky_posts' => true,
	    'fields' => 'ids',
	    'tax_query' => $tax_query,
	);
	if(array_key_exists('state',$filters) && !empty($filters['state'])){
		$product_ids_array = get_product_ids_by_region(gt_clean($filters['state']));
		$args['post__in'] = $product_ids_array;
		$attr_args['post__in'] = $product_ids_array;
	}			
	if(array_key_exists('suburbs',$filters) && !empty($filters['suburbs'])){
		//$product_ids_array = get_product_ids_by_suburb(gt_clean($filters['suburbs']));
		$product_ids_array = get_product_ids_by_postcodes($filters['suburbs']);
		$args['post__in'] = $product_ids_array;
		$attr_args['post__in'] = $product_ids_array;
	}
	
	//Meta Queries
	$meta_query_cnt = 0;
	//Price
	if(array_key_exists('min_price',$filters) && !empty($filters['min_price']) && array_key_exists('max_price',$filters) && !empty($filters['max_price']) ){
		$meta_query[] = array('key' => '_price', 'value' => array( gt_clean($filters['min_price']), gt_clean($filters['max_price']) ), 'type' => 'NUMERIC', 'compare' => 'BETWEEN');
		$meta_query_cnt = $meta_query_cnt + 1;
	}else{
		
		if(array_key_exists('max_price',$filters) && !empty($filters['max_price'])){
			$meta_query[] = array('key' => '_price', 'value' => gt_clean($filters['max_price']), 'type' => 'NUMERIC', 'compare' => '<=');
			$meta_query_cnt = $meta_query_cnt + 1;
		}
		if(array_key_exists('min_price',$filters) && !empty($filters['min_price'])){
			$meta_query[] = array('key' => '_price', 'value' => gt_clean($filters['min_price']), 'type' => 'NUMERIC', 'compare' => '>=');
			$meta_query_cnt = $meta_query_cnt + 1;
		}
	}
	//House Size(Area)
	if(array_key_exists('min_hs',$filters) && !empty($filters['min_hs']) && array_key_exists('max_hs',$filters) && !empty($filters['max_hs']) ){
		$meta_query[] = array('key' => 'house_size', 'value' => array( gt_clean($filters['min_hs']), gt_clean($filters['max_hs']) ), 'type' => 'NUMERIC', 'compare' => 'BETWEEN');
		$meta_query_cnt = $meta_query_cnt + 1;
	}else{
		if(array_key_exists('max_hs',$filters) && !empty($filters['max_hs'])){
			$meta_query[] = array('key' => 'house_size', 'value' => gt_clean($filters['max_hs']), 'type' => 'NUMERIC', 'compare' => '<=');
			$meta_query_cnt = $meta_query_cnt + 1;
		}
		if(array_key_exists('min_hs',$filters) && !empty($filters['min_hs'])){
			$meta_query[] = array('key' => 'house_size', 'value' => gt_clean($filters['min_hs']), 'type' => 'NUMERIC', 'compare' => '>=');
			$meta_query_cnt = $meta_query_cnt + 1;
		}
	}
	//Land Size(Area)
	if(array_key_exists('min_ls',$filters) && !empty($filters['min_ls']) && array_key_exists('max_ls',$filters) && !empty($filters['max_ls']) ){
		$meta_query[] = array('key' => 'land_size', 'value' => array( gt_clean($filters['min_ls']), gt_clean($filters['max_ls']) ), 'type' => 'NUMERIC', 'compare' => 'BETWEEN');
		$meta_query_cnt = $meta_query_cnt + 1;
	}else{
		if(array_key_exists('max_ls',$filters) && !empty($filters['max_ls'])){
			$meta_query[] = array('key' => 'land_size', 'value' => gt_clean($filters['max_ls']), 'type' => 'NUMERIC', 'compare' => '<=');
			$meta_query_cnt = $meta_query_cnt + 1;
		}
		if(array_key_exists('min_ls',$filters) && !empty($filters['min_ls'])){
			$meta_query[] = array('key' => 'land_size', 'value' => gt_clean($filters['min_ls']), 'type' => 'NUMERIC', 'compare' => '>=');
			$meta_query_cnt = $meta_query_cnt + 1;
		}
	}
	//Block Depth
	if(array_key_exists('min_bd',$filters) && !empty($filters['min_bd']) && array_key_exists('max_bd',$filters) && !empty($filters['max_bd']) ){
		$meta_query[] = array('key' => 'block_depth', 'value' => array( gt_clean($filters['min_bd']), gt_clean($filters['max_bd']) ), 'type' => 'NUMERIC', 'compare' => 'BETWEEN');
		$meta_query_cnt = $meta_query_cnt + 1;
	}else{
		if(array_key_exists('max_bd',$filters) && !empty($filters['max_bd'])){
			$meta_query[] = array('key' => 'block_depth', 'value' => gt_clean($filters['max_bd']), 'type' => 'NUMERIC', 'compare' => '<=');
			$meta_query_cnt = $meta_query_cnt + 1;
		}
		if(array_key_exists('min_bd',$filters) && !empty($filters['min_bd'])){
			$meta_query[] = array('key' => 'block_depth', 'value' => gt_clean($filters['min_bd']), 'type' => 'NUMERIC', 'compare' => '>=');
			$meta_query_cnt = $meta_query_cnt + 1;
		}
	}
	//Block Width
	if(array_key_exists('min_bw',$filters) && !empty($filters['min_bw']) && array_key_exists('max_bw',$filters) && !empty($filters['max_bw']) ){
		$meta_query[] = array('key' => 'block_width', 'value' => array( gt_clean($filters['min_bw']), gt_clean($filters['max_bw']) ), 'type' => 'NUMERIC', 'compare' => 'BETWEEN');
		$meta_query_cnt = $meta_query_cnt + 1;
	}else{
		if(array_key_exists('max_bw',$filters) && !empty($filters['max_bw'])){
			$meta_query[] = array('key' => 'block_width', 'value' => gt_clean($filters['max_bw']), 'type' => 'NUMERIC', 'compare' => '<=');
			$meta_query_cnt = $meta_query_cnt + 1;
		}
		if(array_key_exists('min_bw',$filters) && !empty($filters['min_bw'])){
			$meta_query[] = array('key' => 'block_width', 'value' => gt_clean($filters['min_bw']), 'type' => 'NUMERIC', 'compare' => '>=');
			$meta_query_cnt = $meta_query_cnt + 1;
		}
	}

	if($meta_query_cnt > 1){
		$args['meta_query'] = array_merge(['relation' => 'AND'],$meta_query);
	}else{
		if(count($meta_query) > 0){
			$args['meta_query'] = $meta_query;
			$attr_args['meta_query'] = $meta_query;
		}
	}
	if(array_key_exists('page',$filters) && !empty($filters['page'])){
	    $args['paged'] = intval($filters['page']);
	}
	if(array_key_exists('count',$filters) && !empty($filters['count'])){
	    $args['posts_per_page'] = gt_clean($filters['count']);
	}else{
	    $args['posts_per_page'] = gt_clean($posts_per_page);
	}

    if(array_key_exists('sortby',$filters) && !empty($filters['sortby'])){
		if($filters['sortby']=='price'){
			$args['meta_key'] = '_price';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'asc';
		}
		if($filters['sortby']=='price-desc'){
			$args['meta_key'] = '_price';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'desc';
		}
		if($filters['sortby']=='date'){
			$args['orderby'] = 'post_date';
			$args['order'] = 'DESC';
		}
		if($filters['sortby']=='popularity'){
			$args['meta_key'] = 'total_sales';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'DESC';	
		}
	}

	//$updated_attributes = gt_get_product_attributes($attr_args);
	$updated_attributes = gt_get_combined_filters($attr_args);
	$the_query = new WP_Query( $args );
	
	$total_pages = $the_query->max_num_pages;
	$count = $the_query->found_posts;
	$per_page = $filters['count']?$filters['count']:$posts_per_page;
	$current = $filters['page']?$filters['page']:1;
	$term_name = get_term( $term_id )->name;
	

//Woocommerce Result Count
if ( 1 === intval( $count ) ) {
		$woocommerceResultCount = 'Showing the single '.rtrim($term_name,'s');
	} elseif ( $count <= $per_page || -1 === $per_page ) {
		/* translators: %d: total results */
		//$woocommerceResultCount = printf( _n( 'Showing all %d result', 'Showing all %d results', $total, 'woocommerce' ), $total );
		$woocommerceResultCount = ($count > 0)?'Showing all ' .$count. ' '.$term_name:'';
		
	} else {
		$first = ( $per_page * $current ) - $per_page + 1;
		$last  = min( $count, $per_page * $current );
		/* translators: 1: first result 2: last result 3: total results */
	 //$woocommerceResultCount = printf( _nx( 'Showing %1$d%2$d of %3$d result', 'Showing %1$d%2$d of %3$d results', $total, 'with first and last result', 'woocommerce' ), $first, $last, $total );
		$woocommerceResultCount = 'Showing ' .$first. ' &ndash; '.$last. ' of ' .$count. ' '.$term_name;
}

//Woocommerce Pagination
$page = $_REQUEST['page']?$_REQUEST['page']:1;
$cur_page = $page;
$page -= 1;
$per_page = $posts_per_page;
$count = $the_query->found_posts;
$total_pages = $the_query->max_num_pages;
$previous_btn = true;
$next_btn = true;

if ( $total_pages > 1 ) {

	$no_of_paginations = ceil($count / $per_page);
	
	
	if ($cur_page >= 7) {
	  $start_loop = $cur_page - 3;
	  if ($no_of_paginations > $cur_page + 3)
	    $end_loop = $cur_page + 3;
	  else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
	    $start_loop = $no_of_paginations - 6;
	    $end_loop = $no_of_paginations;
	  } else {
	    $end_loop = $no_of_paginations;
	  }
	} else {
	  $start_loop = 1;
	  if ($no_of_paginations > 7)
	    $end_loop = 7;
	  else
	    $end_loop = $no_of_paginations;
	}
	
	$woocommercePagination = '<ul class="ax">';
	if ($previous_btn && $cur_page > 1) {
	$pre = $cur_page - 1;
	$woocommercePagination .='<li p="' .$pre. '" class="active"><i class="far fa-angle-left"></i> Previous</li>';
	} else if ($previous_btn) {
	$woocommercePagination .='<li class="inactive">Previous</li>';
	}
	for ($i = $start_loop; $i <= $end_loop; $i++) {
		if ($cur_page == $i){
			$woocommercePagination .='<li p="' .$i. '" class="selected" >' .$i. '</li>';
		}else{
			$woocommercePagination .='<li p="' .$i. '" class="active">' .$i. '</li>';
		}
	}
	if ($next_btn && $cur_page < $no_of_paginations) {
	$nex = $cur_page + 1;
	$woocommercePagination .='<li p="' .$nex. '" class="active">Next <i class="far fa-angle-right"></i></li>';
	} else if ($next_btn) {
	$woocommercePagination .='<li class="inactive">Next</li>';
	}
	$woocommercePagination .='</ul>';
}


ob_start();
/*
echo "<pre>";
print_r($the_query);
echo "</pre>";
*/
if ( $the_query->have_posts() ) {
        
        
		echo '<ul data-count='.$count.' class="products products-container grid show-nav-middle pcols-lg-3 pcols-md-3 pcols-xs-2 pcols-ls-1 pwidth-lg-3 pwidth-md-3 pwidth-xs-2 pwidth-ls-1" data-product_layout="product-default">';
		
		while ( $the_query->have_posts() ) : $the_query->the_post();
		
		$output .= wc_get_template_part( 'content', 'product' );
		
		endwhile;
		
		echo  "</ul>";

} else {
	echo '<p><span class="gt-no-results">No results. Please try again.</span> <span class="gt-reset-filters">Reset Filters</span></p>';
}
wp_reset_postdata();

$output = ob_get_contents();
ob_end_clean();

$result = array(
			'filters'=>$filters,
			'args'=>$args,
			'per_page'=> intval($per_page),
			'total' => $count, 
			'woocommerce_result_count'=>$woocommerceResultCount, 
			'html' => $output, 
			'woocommerce_pagination'=>$woocommercePagination,
			'updated_attributes'=> $updated_attributes,
		);
wp_send_json($result);

}



require_once('functions/product-attributes.php');
require_once('functions/product-metas.php');
require_once('functions/product-locations.php');
require_once('functions/product-common.php');


function multiple_select_scripts(){
	wp_enqueue_script( 'multipleSelectJS', get_stylesheet_directory_uri() . '/inc/ajaxfilter/vendor/multiple-select/multiple-select.min.js', array( 'jquery' ), '', true );
	wp_enqueue_style( 'multipleSelectCSS', get_stylesheet_directory_uri() . '/inc/ajaxfilter/vendor/multiple-select/multiple-select.min.css',array('wp-block-library'),'','all');
}
add_action( 'wp_enqueue_scripts', 'multiple_select_scripts' );
