<?php
function gt_get_products_meta_values($meta_key,$args){

		$productIDs = get_posts($args);

		$meta_values = array();
		foreach($productIDs as $productID){
			$value = get_post_meta( $productID, $meta_key, true);
            if(!empty($value)){
                $meta_values[]= $value;
            }
//			$meta_values[]= (!empty($value))?$value:0;
			
		}

		return $meta_values;
}

function generate_static_prices($multiple,$iteration){
	$prices_arr = array();
	for($i=0;$i<=$iteration;$i++){
		$val=$multiple*$i;
		$prices_arr[] = array('abbr'=>kmb($val),'num'=>$val);
	}
	return $prices_arr;
}
	
function kmb($count, $precision = 2) {
	if ($count < 1000000) {
	// Anything less than a million
	$n_format = number_format($count / 1000) . 'K';
	} else if ($count < 1000000000) {
	// Anything less than a billion
	$n_format = number_format($count / 1000000, $precision) . 'M';
	} else {
	// At least a billion
	$n_format = number_format($count / 1000000000, $precision) . 'B';
	}
	return $n_format;
}