<?php
function gt_get_combined_filters($args){
	
	$product_ids = get_posts($args);
	
	$attribute_names = wc_get_attribute_taxonomy_names();
		
	$filters = array();
	
	$product_prices = array();
	$house_sizes = array();
	$land_sizes = array();
	$block_depths = array();
	$block_widths = array();
	
	$exclude = array('pa_state','pa_suburb');	
	foreach($product_ids as $product_id){
		foreach($attribute_names as $attr_name){
				//$attr_terms = array_shift( wc_get_product_terms( $productID, $attr_name, array( 'fields' => 'names' ) ) );
			if(!in_array($attr_name, $exclude)){
				$attr_terms = wc_get_product_terms( $product_id, $attr_name, array( 'fields' => 'names' ) ) ;
				if(!empty($attr_terms)){
					foreach($attr_terms as $attr_term){
						$filters[$attr_name][] = $attr_term;
					}	
				}
			}
		}
		$price = get_post_meta( $product_id, '_price', true);
		if(!empty($price)){ 
			$product_prices[] = $price; 
		}
		$house_size = get_post_meta( $product_id, 'wpcf-house_size', true);
		if(!empty($house_size)){ 
			$house_sizes[] = $house_size; 
		}
		$land_size = get_post_meta( $product_id, 'wpcf-land_size', true);
		if(!empty($land_size)){ 
			$land_sizes[] = $land_size; 
		}
		$block_depth = get_post_meta( $product_id, 'wpcf-block_depth', true);
		if(!empty($block_depth)){ 
			$block_depths[] = $block_depth; 
		}
		$block_width = get_post_meta( $product_id, 'wpcf-block_width', true);
		if(!empty($block_width)){ 
			$block_widths[] = $block_width; 
		}
		
		//$product_prices[] = (!empty($price))?$price:0;	
	}
	// $house_sizes = array(100,100,150,150,150,200,250,300,350,400,450,450,500);
	// $land_sizes = array(300,340,400,600,700,850,900,950,1050,1100,1600,1800,2000);
	// $block_widths = array(8,9,10,11,12,17,18.5,19,19,21,22,30,37,38,40,42);
	// $block_depths = array(24,25,26,27,27,28,28,29.5,29.5,30,31,31.5,32,32,32,32,33);
	
	$filters['product_prices'] = $product_prices;
	$filters['house_sizes'] = $house_sizes;
	$filters['land_sizes'] = $land_sizes;
	$filters['block_widths'] = $block_widths;
	$filters['block_depths'] = $block_depths;
	
	
	//Max Min Prices
	if(!empty($filters['product_prices'])){
	$min_prices = generate_static_prices(50000,100);
    $max_prices = array_reverse($min_prices);
    

    // echo "<pre>";
    // print_r($product_prices);
    // echo "</pre>";

    
    
    
    $lowest_price = min($product_prices);
    $highest_price = max($product_prices);
    
    
    $gt_max_prices = array();
    if(!empty($max_prices)&& $highest_price !=0 ) {
	    foreach($max_prices as $key => $value){
		    if($value['num'] < $highest_price + 50000){
		    $gt_max_prices[] = $value;
		    }
	    }
    }
    $filters['max_prices'] = $gt_max_prices;
    $gt_min_prices = array_reverse($gt_max_prices);
/*
    if(!empty($min_prices)&& $highest_price !=0 ) {
	    foreach($min_prices as $key => $value){
		   if($value['num'] < $less_than_price){
		    $gt_min_prices[] = $value;
		   }
	    }
    }
*/
    $filters['min_prices'] = $gt_min_prices;
    }else{
	    $filters['max_prices'] = array();
	    $filters['min_prices'] = array();
    }
    
    //Max Min House Sizes
 	if(!empty($filters['house_sizes'])){
		$lowest_house_size = min($filters['house_sizes']);
	    $highest_house_size = max($filters['house_sizes']);
	    $min_house_sizes = generate_static_house_sizes(50,10);
		$max_house_sizes = array_reverse($min_house_sizes);
		$gt_max_house_sizes = array();
		if(!empty($max_house_sizes)&& $highest_house_size != 0 ) {
			foreach($max_house_sizes as $max_house_size){
				if($max_house_size < ($highest_house_size + 50)){
					$gt_max_house_sizes[] = $max_house_size;
				}
			}
		}
		$gt_min_house_sizes = array_reverse($gt_max_house_sizes);	
		$filters['max_house_sizes'] = $gt_max_house_sizes;
		$filters['min_house_sizes'] = $gt_min_house_sizes;
    }else{
	 	$filters['max_house_sizes'] = array();
		$filters['min_house_sizes'] = array();   
    }
    
    //Max Min Land Sizes
 	if(!empty($filters['land_sizes'])){
		$lowest_land_size = min($filters['land_sizes']);
	    $highest_land_size = max($filters['land_sizes']);
	    $min_land_sizes = generate_static_land_sizes(100,20);
		$max_land_sizes = array_reverse($min_land_sizes);
		$gt_max_land_sizes = array();
		if(!empty($max_land_sizes)&& $highest_land_size != 0 ) {
			foreach($max_land_sizes as $max_land_size){
				if($max_land_size < ($highest_land_size + 500)){
					$gt_max_land_sizes[] = $max_land_size;
				}
			}
		}
		$gt_min_land_sizes = array_reverse($gt_max_land_sizes);	
		$filters['max_land_sizes'] = $gt_max_land_sizes;
		$filters['min_land_sizes'] = $gt_min_land_sizes;
    }else{
	 	$filters['max_land_sizes'] = array();
		$filters['min_land_sizes'] = array();   
    } 

	
	//Max Min Block Widths
	if(!empty($filters['block_widths'])){
		$lowest_block_width = min($filters['block_widths']);
	    $highest_block_width = max($filters['block_widths']);	
		$min_block_widths = generate_static_block_widths(9,66);
		$max_block_widths = array_reverse($min_block_widths);
		$gt_max_block_widths = array();
		if(!empty($max_block_widths)&& $highest_block_width !=0 ) {
			foreach($max_block_widths as $max_block_width){
				if($max_block_width < ($highest_block_width + 0.5)){
				//if($max_block_width > $lowest_block_width){	
					$gt_max_block_widths[] = $max_block_width;
				}
			}
		}
		$gt_min_block_widths = array_reverse($gt_max_block_widths);	
		$filters['max_block_widths'] = $gt_max_block_widths;
		$filters['min_block_widths'] = $gt_min_block_widths;
	}else{
		$filters['max_block_widths'] = array();
		$filters['min_block_widths'] = array();
	}
	
	//Max Min Block Depths
	if(!empty($filters['block_depths'])){
		$lowest_block_depth = min($filters['block_depths']);
	    $highest_block_depth = max($filters['block_depths']);
	    $min_block_depths = generate_static_block_depths(24,20);
		$max_block_depths = array_reverse($min_block_depths);
		$gt_max_block_depths = array();
		if(!empty($max_block_depths)&& $highest_block_depth !=0 ) {
			foreach($max_block_depths as $max_block_depth){
				if($max_block_depth < ($highest_block_depth + 0.5)){
				//if($max_block_depth > $lowest_block_depth){	
					$gt_max_block_depths[] = $max_block_depth;
				}
			}
		}
		$gt_min_block_depths = array_reverse($gt_max_block_depths);	
		$filters['max_block_depths'] = $gt_max_block_depths;
		$filters['min_block_depths'] = $gt_min_block_depths;
    }else{
	 	$filters['max_block_depths'] = array();
		$filters['min_block_depths'] = array();   
    }
    
    //Suburbs
    if(!empty($product_ids)){
    	$suburbs = get_suburbs_by_product_ids($product_ids);
    	$filters['suburbs'] = $suburbs;
    }else{
	  $filters['suburbs'] = array();  
    }

//    var_dump($filters);die;
    return $filters;
}
	

/* For Max Frequency Count */
function get_max_frequency_count($x_arr,$y){
	$count=0;
	foreach($x_arr as $val){
		if($val!==0){
		if($val<=$y){
			$count++;
		}
		}
	}
	return $count;
}
/* For Min Frequency Count */
function get_min_frequency_count($x_arr,$y){
	$count=0;
	foreach($x_arr as $val){
		if($val!==0){
		if($val>=$y){
			$count++;
		}
		}
	}
	return $count;
}

/* URL Params */
function get_gt_params(){
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$components = parse_url($url);
	parse_str($components['query'], $params);
	return $params;
}

function gt_clean($data)
{
    $data = htmlspecialchars($data);
    $data = stripslashes($data);
    $data = trim($data);
    $data = sanitize_text_field($data);
    return $data;
}