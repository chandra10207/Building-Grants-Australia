<?php

function gt_get_product_attributes($args){
		
	$productIDs = get_posts($args);

	$attribute_names = wc_get_attribute_taxonomy_names();
		
	$attributes = array();
	$exclude = array('pa_state','pa_suburb');	
	foreach($productIDs as $productID){
		foreach($attribute_names as $attr_name){
				//$attr_terms = array_shift( wc_get_product_terms( $productID, $attr_name, array( 'fields' => 'names' ) ) );
			if(!in_array($attr_name, $exclude)){
				$attr_terms = wc_get_product_terms( $productID, $attr_name, array( 'fields' => 'names' ) ) ;
				if(!empty($attr_terms)){
					foreach($attr_terms as $attr_term){
						$attributes[$attr_name][] = $attr_term;
					}
						
				}
			}
		}
	}
		
	return $attributes;

}


/* Nice Attribute Taxonomy Label */
function get_nice_attr_tax_lbl($attr_tax_name){
	
	switch($attr_tax_name){
		case 'suburbs': return array("suburbs","Suburbs"); break;
		case 'pa_bathrooms': return array("baths","Baths"); break;
		case 'pa_bedrooms': return array("beds","Beds"); break;
		case 'pa_garage': return array("garage","Garage"); break;
		
		case 'pa_grants-available-in-nsw': return array("gt_nsw","Eligible NSW Grants"); break;
		case 'pa_grants-available-in-qld': return array("gt_qld","Eligible QLD Grants"); break;
		case 'pa_grants-available-in-sa': return array("gt_sa","Eligible SA Grants"); break;
		case 'pa_grants-available-in-vic': return array("gt_vic","Eligible VIC Grants"); break;
		case 'pa_grants-available-in-wa': return array("gt_wa","Eligible WA Grants"); break;
		case 'pa_grants-federal': return array("gt_fed","Eligible Federal Grants"); break;
		
		case 'pa_house-type': return array("type","House Type"); break;
		
		case 'pa_design': return array("design","Design"); break;
		
		case 'pa_house-size-sqm': return array("size","House Size(m<sup>2</sup>)"); break;
		
		case 'pa_living-rooms': return array("lr","Living Rooms"); break;
		
		case 'pa_lot-depth-m': return array("lot_dp","Lot Depth(m)"); break;
		case 'pa_lot-width-m': return array("lot_wd","Lot Width(m)"); break;
		
		//case 'pa_max-block-depth-m': return array("max_bd","Max. Block Depth(m)"); break;
		//case 'pa_max-block-width-m': return array("max_bw","Max. Block Width(m)"); break;
		
		//case 'pa_max-house-size-sqm': return array("max_size","Max. House Area(m<sup>2</sup>)"); break;
		
		//case 'pa_min-block-depth-m': return array("min_bd","Min. Block Depth(m)"); break;
		//case 'pa_min-block-width-m': return array("min_bw","Min. Block Depth(m)"); break;
		
		//case 'pa_min-house-size-sqm': return array("min_size","Min. House Area(m<sup>2</sup>)"); break;
		
		case 'pa_other-features': return array("feature","Features"); break;
		
		case 'pa_property-status': return array("p_status","Property Status"); break;
		
		case 'pa_rebate-type': return array("rebate_t","Rebates"); break;
		
		//case 'pa_state': return array("state","State"); break;
		
		//case 'pa_stories': return array("stories","Stories"); break;
		case 'pa_storeys': return array("storeys","Storeys"); break;
		
		case 'pa_suburb': return array("suburb","Suburb or Postcode"); break;
		
		case 'pa_total-block-area-sqm': return array("total_ba","Total Block Area(m<sup>2</sup>)"); break;
		case 'pa_total-home-area': return array("total_ha","Total Home Area(m<sup>2</sup>)"); break;
		case 'pa_total-lot-area-sqm': return array("total_la","Total Lot Area(m<sup>2</sup>)"); break;
		case 'max_prices': return array("max_price","Max Price"); break;
		case 'min_prices': return array("min_price","Min Price"); break;
	}
}

/* For Min Max Block Width*/
function generate_static_block_widths($start,$iteration){
	$block_widths_arr = array();
	for($i=0;$i<=$iteration;$i++){
		$val=$start;
		$block_widths_arr[] = $val;
		$start= $start+0.5;
	}
	return $block_widths_arr;
}

/* For Min Max Block Depth*/
function generate_static_block_depths($start,$iteration){
	$block_depths_arr = array();
	for($i=0;$i<=$iteration;$i++){
		$val=$start;
		$block_depths_arr[] = $val;
		$start = $start+0.5;
	}
	return $block_depths_arr;
}

/* For Min Max House Size*/
function generate_static_house_sizes($start,$iteration){
	$house_sizes_arr = array();
	for($i=0;$i<=$iteration;$i++){
		$val=$start;
		$house_sizes_arr[] = $val;
		$start = $start+50;
	}
	return $house_sizes_arr;
}

/* For Min Max Land Size*/
function generate_static_land_sizes($start,$iteration){
	$house_sizes_arr = array();
	for($i=0;$i<=$iteration;$i++){
		$val=$start;
		$house_sizes_arr[] = $val;
		if($start < 1000){
		$start = $start+50;
		}else{
		$start = $start+500;	
		}
		
	}
	return $house_sizes_arr;
}


