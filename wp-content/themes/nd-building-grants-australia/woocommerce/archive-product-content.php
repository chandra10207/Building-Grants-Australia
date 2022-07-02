<?php

global $porto_settings,$wp_query;


$js_wc_prdctfltr = false;
$porto_settings['category-ajax'] = false;

$term_id = get_queried_object()->term_id;
$posts_per_page = 12;

$tax_query = [];
$att_queries = [];
$meta_query = [];

$att_queries[] = array('taxonomy' => 'product_visibility', 'field' => 'term_taxonomy_id', 'terms' => array( 18 ),'operator' => 'NOT IN');
$att_queries[] = array('taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => array( 'home-design-floor-plans' ),'operator' => 'NOT IN');

if(isset($_GET['storeys']) && !empty($_GET['storeys'])){
	$storeys_array = (strpos($_GET['storeys'], ',') !== false )?explode (',', gt_clean($_GET['storeys'])):gt_clean($_GET['storeys']);
	$att_queries[] = array('taxonomy' => 'pa_storeys', 'field' => 'name', 'terms' => $storeys_array);
}
if(isset($_GET['beds']) && !empty($_GET['beds'])){
	$beds_array = (strpos($_GET['beds'], ',') !== false )?explode (',', gt_clean($_GET['beds'])):gt_clean($_GET['beds']);
	$att_queries[] = array('taxonomy' => 'pa_bedrooms', 'field' => 'name', 'terms' => $beds_array );
}
if(isset($_GET['baths']) && !empty($_GET['baths'])){
	$baths_array = (strpos($_GET['baths'], ',') !== false )?explode (',', gt_clean($_GET['baths'])):gt_clean($_GET['baths']);
	$att_queries[] = array('taxonomy' => 'pa_bathrooms', 'field' => 'name', 'terms' => $baths_array);
}
if(isset($_GET['garage']) && !empty($_GET['garage'])){
	$garage_array = (strpos($_GET['garage'], ',') !== false )?explode (',', gt_clean($_GET['garage'])):gt_clean($_GET['garage']);
	$att_queries[] = array('taxonomy' => 'pa_garage', 'field' => 'name', 'terms' => $garage_array );
}
if(isset($_GET['lr']) && !empty($_GET['lr'])){
	$lr_array = (strpos($_GET['lr'], ',') !== false )?explode (',', gt_clean($_GET['lr'])):gt_clean($_GET['lr']);
	$att_queries[] = array('taxonomy' => 'pa_living-rooms', 'field' => 'name', 'terms' => $lr_array );
}
if(isset($_GET['design']) && !empty($_GET['design'])){
	$design_array = (strpos($_GET['design'], ',') !== false )?explode (',', gt_clean($_GET['design'])):gt_clean($_GET['design']);
	$att_queries[] = array('taxonomy' => 'pa_design', 'field' => 'name', 'terms' => $design_array );
}
if(isset($_GET['type']) && !empty($_GET['type'])){
	$type_array = (strpos($_GET['type'], ',') !== false )?explode (',', gt_clean($_GET['type'])):gt_clean($_GET['type']);
	$att_queries[] = array('taxonomy' => 'pa_dhouse-type', 'field' => 'name', 'terms' => $type_array );
}
if(isset($_GET['feature']) && !empty($_GET['feature'])){
	$feature_array = (strpos($_GET['feature'], ',') !== false )?explode (',', gt_clean($_GET['feature'])):gt_clean($_GET['feature']);
	$att_queries[] = array('taxonomy' => 'pa_other-features', 'field' => 'name', 'terms' => $feature_array );
}
if(isset($_GET['p_status']) && !empty($_GET['p_status'])){
	$p_status_array = (strpos($_GET['p_status'], ',') !== false )?explode (',', gt_clean($_GET['p_status'])):gt_clean($_GET['p_status']);
	$att_queries[] = array('taxonomy' => 'pa_property-status', 'field' => 'name', 'terms' => $p_status_array );
}
if(isset($_GET['rebate_t']) && !empty($_GET['rebate_t'])){
	$rebate_t_array = (strpos($_GET['rebate_t'], ',') !== false )?explode (',', gt_clean($_GET['rebate_t'])):gt_clean($_GET['rebate_t']);
	$att_queries[] = array('taxonomy' => 'pa_rebate-type', 'field' => 'name', 'terms' => $rebate_t_array );
}
if(isset($_GET['gt_fed']) && !empty($_GET['gt_fed'])){
	$gt_fed_array = (strpos($_GET['gt_fed'], ',') !== false )?explode (',', gt_clean($_GET['gt_fed'])):gt_clean($_GET['gt_fed']);
	$att_queries[] = array('taxonomy' => 'pa_grants-federal', 'field' => 'name', 'terms' => $gt_fed_array );
}
if(isset($_GET['state']) && !empty($_GET['state'])){
	$state_grant_attr = 'pa_grants-available-in-'.trim(strtolower($_GET['state']));
	$state_grant_key = 'gt_'.trim(strtolower($_GET['state']));
	if(isset($_GET[$state_grant_key]) && !empty($_GET[$state_grant_key])){
		$gt_st_array = (strpos($_GET[$state_grant_key], ',') !== false )?explode (',', gt_clean($_GET[$state_grant_key])):gt_clean($_GET[$state_grant_key]);
		$att_queries[] = array('taxonomy' => $state_grant_attr, 'field' => 'name', 'terms' => $gt_st_array );
	}
}

if(!empty($att_queries)){
	$tax_query[]= ['relation' => 'AND'];
	$tax_query[] = array('taxonomy' => 'product_cat', 'field' => 'term_id', 'terms' => gt_clean($term_id));
	$tax_query = array_merge($tax_query,$att_queries);
}

$args = array(
				'post_type'				=> 'product',
				'post_status' 			=> 'publish',
				'suppress_filters'		=> true,
				'ignore_sticky_posts' 	=> true,
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
			
if(isset($_GET['state']) && !empty($_GET['state'])){
	$product_ids_array = get_product_ids_by_region(gt_clean($_GET['state']));
	$args['post__in'] = $product_ids_array;
	$attr_args['post__in'] = $product_ids_array;
}
				
if(isset($_GET['suburbs']) && !empty($_GET['suburbs'])){
	//$product_ids_array = get_product_ids_by_suburb(gt_clean($_GET['suburbs']));
	if(strpos($_GET['suburbs'], ',') !== false ){
		$postcodes_array = explode (',', gt_clean($_GET['suburbs']));
	}else{
		$postcodes_array = [$_GET['suburbs']];
	}
	$product_ids_array = get_product_ids_by_postcodes($postcodes_array);
	$args['post__in'] = $product_ids_array;
	$attr_args['post__in'] = $product_ids_array;
}
//Meta Queries
$meta_query_cnt = 0;
if(isset($_GET['min_price']) && !empty($_GET['min_price']) && isset($_GET['max_price']) && !empty($_GET['max_price']) ){
	$meta_query[] = array('key' => '_price', 'value' => array( gt_clean($_GET['min_price']), gt_clean($_GET['max_price']) ), 'type' => 'NUMERIC', 'compare' => 'BETWEEN');
	$meta_query_cnt = $meta_query_cnt + 1;
}else{
	if(isset($_GET['max_price']) && !empty($_GET['max_price'])){
		$meta_query[] = array('key' => '_price', 'value' => gt_clean($_GET['max_price']), 'type' => 'NUMERIC', 'compare' => '<=');
		$meta_query_cnt = $meta_query_cnt + 1;
	}
	if(isset($_GET['min_price']) && !empty($_GET['min_price'])){
		$meta_query[] = array('key' => '_price', 'value' => gt_clean($_GET['min_price']), 'type' => 'NUMERIC', 'compare' => '>=');
		$meta_query_cnt = $meta_query_cnt + 1;
	}
}
//House Size(Area)
if(isset($_GET['min_hs']) && !empty($_GET['min_hs']) && isset($_GET['max_hs']) && !empty($_GET['max_hs'])){
	$meta_query[] = array('key' => 'house_size', 'value' => array( gt_clean($_GET['min_hs']), gt_clean($_GET['max_hs']) ), 'type' => 'NUMERIC', 'compare' => 'BETWEEN');
	$meta_query_cnt = $meta_query_cnt + 1;
}else{
	if(isset($_GET['max_hs']) && !empty($_GET['max_hs'])){
		$meta_query[] = array('key' => 'house_size', 'value' => gt_clean($_GET['max_hs']), 'type' => 'NUMERIC', 'compare' => '<=');
		$meta_query_cnt = $meta_query_cnt + 1;
	}
	if(isset($_GET['min_hs']) && !empty($_GET['min_hs'])){
		$meta_query[] = array('key' => 'house_size', 'value' => gt_clean($_GET['min_hs']), 'type' => 'NUMERIC', 'compare' => '>=');
		$meta_query_cnt = $meta_query_cnt + 1;
	}
}
//Land Size(Area)
if(isset($_GET['min_ls']) && !empty($_GET['min_ls']) && isset($_GET['max_ls']) && !empty($_GET['max_ls'])){
	$meta_query[] = array('key' => 'land_size', 'value' => array( gt_clean($_GET['min_ls']), gt_clean($_GET['max_ls']) ), 'type' => 'NUMERIC', 'compare' => 'BETWEEN');
	$meta_query_cnt = $meta_query_cnt + 1;
}else{
	if(isset($_GET['max_ls']) && !empty($_GET['max_ls'])){
		$meta_query[] = array('key' => 'land_size', 'value' => gt_clean($_GET['max_ls']), 'type' => 'NUMERIC', 'compare' => '<=');
		$meta_query_cnt = $meta_query_cnt + 1;
	}
	if(isset($_GET['min_ls']) && !empty($_GET['min_ls'])){
		$meta_query[] = array('key' => 'land_size', 'value' => gt_clean($_GET['min_ls']), 'type' => 'NUMERIC', 'compare' => '>=');
		$meta_query_cnt = $meta_query_cnt + 1;
	}
}
//Block Depth
if(isset($_GET['min_bd']) && !empty($_GET['min_bd']) && isset($_GET['max_bd']) && !empty($_GET['max_bd'])){
	$meta_query[] = array('key' => 'block_depth', 'value' => array( gt_clean($_GET['min_bd']), gt_clean($_GET['max_bd']) ), 'type' => 'NUMERIC', 'compare' => 'BETWEEN');
	$meta_query_cnt = $meta_query_cnt + 1;
}else{
	if(isset($_GET['max_bd']) && !empty($_GET['max_bd'])){
		$meta_query[] = array('key' => 'block_depth', 'value' => gt_clean($_GET['max_bd']), 'type' => 'NUMERIC', 'compare' => '<=');
		$meta_query_cnt = $meta_query_cnt + 1;
	}
	if(isset($_GET['min_bd']) && !empty($_GET['min_bd'])){
		$meta_query[] = array('key' => 'block_depth', 'value' => gt_clean($_GET['min_bd']), 'type' => 'NUMERIC', 'compare' => '>=');
		$meta_query_cnt = $meta_query_cnt + 1;
	}
}
//Block Width
if(isset($_GET['min_bw']) && !empty($_GET['min_bw']) && isset($_GET['max_bw']) && !empty($_GET['max_bw'])){
	$meta_query[] = array('key' => 'block_width', 'value' => array( gt_clean($_GET['min_bw']), gt_clean($_GET['max_bw']) ), 'type' => 'NUMERIC', 'compare' => 'BETWEEN');
	$meta_query_cnt = $meta_query_cnt + 1;
}else{
	if(isset($_GET['max_bw']) && !empty($_GET['max_bw'])){
		$meta_query[] = array('key' => 'block_width', 'value' => gt_clean($_GET['max_bw']), 'type' => 'NUMERIC', 'compare' => '<=');
		$meta_query_cnt = $meta_query_cnt + 1;
	}
	if(isset($_GET['min_bw']) && !empty($_GET['min_bw'])){
		$meta_query[] = array('key' => 'block_width', 'value' => gt_clean($_GET['min_bw']), 'type' => 'NUMERIC', 'compare' => '>=');
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


if(isset($_GET['page']) && !empty($_GET['page'])){
    $args['paged'] = intval(gt_clean($_GET['page']));
}

if(isset($_GET['count']) && !empty($_GET['count'])){
    $args['posts_per_page'] = gt_clean($filters['count']);
}else{
    $args['posts_per_page'] = gt_clean($posts_per_page);
}

if(isset($_GET['sortby']) && !empty($_GET['sortby'])){
	if($_GET['sortby']=='price'){
		$args['meta_key'] = '_price';
		$args['orderby'] = 'meta_value_num';
		$args['order'] = 'asc';
	}
	if($_GET['sortby']=='price-desc'){
		$args['meta_key'] = '_price';
		$args['orderby'] = 'meta_value_num';
		$args['order'] = 'desc';
	}
	if($_GET['sortby']=='date'){
		$args['orderby'] = 'post_date';
		$args['order'] = 'DESC';
	}
	if($_GET['sortby']=='popularity'){
		$args['meta_key'] = 'total_sales';
		$args['orderby'] = 'meta_value_num';
		$args['order'] = 'DESC';	
	}
}
	//$state_query = "LEFT JOIN nd20_gmw_locations gmw_locations ON nd20_posts.ID = gmw_locations.object_id AND gmw_locations.object_type = 'post'";

?>

<?php
	/**
	 * Hook: woocommerce_before_main_content.
	 *
	 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
	 * @hooked woocommerce_breadcrumb - 20
	 * @hooked WC_Structured_Data::generate_website_data() - 30
	 */
	do_action( 'woocommerce_before_main_content' );
?>

<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

	<h2 class="page-title"><?php woocommerce_page_title(); ?><?php if(isset($_GET['state']) && !empty($_GET['state'])){ echo " in ".$_GET['state']; }?></h2>

<?php endif; ?>

<?php

	/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );
?>

<?php 
// the query
$the_query = new WP_Query( $args ); 
/*
echo "<pre>";
print_r($the_query);
echo "</pre>";
*/
$total = $the_query->max_num_pages;
$total_pages = $the_query->found_posts;
$per_page = 12;
$current = 1;
$term_name = get_term( $term_id )->name;
?>

<?php //if ( ( function_exists( 'woocommerce_product_loop' ) && woocommerce_product_loop() ) || ( ! function_exists( 'woocommerce_product_loop' ) && have_posts() ) ) { ?>
<?php if ( $the_query->have_posts() ) : ?>
<?php
		/**
		 * Hook: woocommerce_before_shop_loop.
		 *
		 * @hooked woocommerce_output_all_notices - 10
		 * @hooked woocommerce_result_count - 20
		 * @hooked woocommerce_catalog_ordering - 30
		 */
		//do_action( 'woocommerce_before_shop_loop' );
		woocommerce_output_all_notices();
		
		//woocommerce_catalog_ordering();
		//get_template_part('inc/ajaxfilter/partials/grant','filter');
		include(locate_template('inc/ajaxfilter/partials/grant-filter.php')); 
?>
<?php
	global $woocommerce_loop;
	
	if ( is_shop() && ! is_product_category() ) {
		$woocommerce_loop['columns']        = $porto_settings['shop-product-cols'];
		$woocommerce_loop['columns_mobile'] = $porto_settings['shop-product-cols-mobile'];
	}
?>

<div class="archive-products">
<?php woocommerce_product_loop_start();?>
<?php //echo '<ul class="gt-products products-container grid pcols-lg-4 pcols-md-3 pcols-xs-3 pcols-ls-2 pwidth-lg-4 pwidth-md-3 pwidth-xs-2 pwidth-ls-1" data-product_layout="product-default">'; ?>	
<?php		
/*
	if ( ! function_exists( 'wc_get_loop_prop' ) || wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();
*/
?>
<!-- the loop -->
<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
<?php
			/**
			 * Hook: woocommerce_shop_loop.
			 */
			
			do_action( 'woocommerce_shop_loop' );
?>			

<?php wc_get_template_part( 'content', 'product' ); ?>
<?php endwhile; ?>
<!-- end of the loop -->
<?php
/*
			}
	}
*/
?>
<?php	woocommerce_product_loop_end(); ?>

</div>
<!-- pagination here -->
<?php
		/**
		 * Hook: woocommerce_after_shop_loop.
		 *
		 * @hooked woocommerce_pagination - 10
		 */
		//do_action( 'woocommerce_after_shop_loop' );
		//woocommerce_pagination();
		//if (  $wp_query->max_num_pages > 1 )
		//echo '<div class="gt_load_more btn btn-default">Load more</div>';

?>
    <nav class="gt-woocommerce-ajax-pagination">
		<?php include(locate_template('inc/ajaxfilter/partials/grant-pagination.php')); ?>
    </nav>
<?php wp_reset_postdata(); ?>
<?php
//} else {
else:
?>
<?php
/*	global $porto_shop_filter_layout;
	if ( isset( $porto_shop_filter_layout ) && 'horizontal2' == $porto_shop_filter_layout ) {
		do_action( 'woocommerce_before_shop_loop' );
	} else {
		?>
	<div class="shop-loop-before" style="display:none;"> </div>
<?php } */?>
    
	<div class="archive-products">
	<?php
		/**
		 * Hook: woocommerce_no_products_found.
		 *
		 * @hooked wc_no_products_found - 10
		 */
		do_action( 'woocommerce_no_products_found' );
	?>
	</div>

	<div class="shop-loop-after clearfix" style="display:none;"> </div>

	<?php
//}
endif;
	/**
	 * Hook: woocommerce_after_main_content.
	 *
	 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action( 'woocommerce_after_main_content' );
?>

<?php
	/**
	 * Hook: woocommerce_sidebar.
	 *
	 * @hooked woocommerce_get_sidebar - 10
	 */
	//do_action( 'woocommerce_sidebar' );
?>
