<?php
	
/*
echo "<pre>";
print_r($attr_args);
echo "</pre>";
*/
global $wp_query,$woocommerce;

$term_id = (get_queried_object()->term_id)?get_queried_object()->term_id:58;

$attribute_names = wc_get_attribute_taxonomy_names();

$gt_attributes = gt_get_combined_filters($attr_args);


//Suburbs
$suburbs = $gt_attributes['suburbs'];

//Prices
$product_prices = $gt_attributes['product_prices'];
$min_prices = $gt_attributes['min_prices'];
$max_prices = $gt_attributes['max_prices'];

//House Sizes
$house_sizes = $gt_attributes['house_sizes'];
$min_house_sizes = $gt_attributes['min_house_sizes'];
$max_house_sizes = $gt_attributes['max_house_sizes'];

//House Sizes
$land_sizes = $gt_attributes['land_sizes'];
$min_land_sizes = $gt_attributes['min_land_sizes'];
$max_land_sizes = $gt_attributes['max_land_sizes'];



//Land Sizes
$land_sizes = $gt_attributes['land_sizes'];
$min_land_sizes = $gt_attributes['min_land_sizes'];
$max_land_sizes = $gt_attributes['max_land_sizes'];

//Block Widths
$block_widths = $gt_attributes['block_widths'];
$min_block_widths = $gt_attributes['min_block_widths'];
$max_block_widths = $gt_attributes['max_block_widths'];

//Block Depths
$block_depths = $gt_attributes['block_depths'];
$min_block_depths = $gt_attributes['min_block_depths'];
$max_block_depths = $gt_attributes['max_block_depths'];

// Load on the main/Mini filter Bar
$refined_attributes = array('pa_storeys','pa_bedrooms','pa_bathrooms','pa_garage');

//$refined_attributes = array('suburbs','pa_storeys','pa_bathrooms','pa_garage');
$adv_refined_attributes = array('pa_living-rooms','pa_other-features','pa_house-type','pa_design','pa_property-status');



$gt_page = !empty($_GET['page'])?gt_clean($_GET['page']):1;

$gt_federal_grants = gt_get_products_meta_values('federal_grants',$attr_args);
var_dump($gt_federal_grants);die;
$state = !empty($_GET['state'])?gt_clean($_GET['state']):"";
if(!empty($state)){
$grant_attribute_by_state = 'pa_grants-available-in-'.strtolower($state);
$grant_refined_attributes = array($grant_attribute_by_state, 'pa_grants-federal');
$gt_state_grants = gt_get_products_meta_values('state_grants',$attr_args);
}else{
$grant_refined_attributes = array('pa_grants-federal');	
}

?> 
                    
<div class="grant-filter">
	<form id="gtFilterForm" data-term="<?php echo $term_id;?>" method="post" action="<?php echo site_url() ?>/wp-admin/admin-ajax.php"> 
		<input id="page" type="hidden" name="page" value="<?php echo $gt_page;?>">
		<input id="state" type="hidden" name="state" value="<?php echo $state;?>">
		

		<div class="row">
			<div class="col-lg-12">
				<div class="gt-filters-row gt-filters-mini">
					<?php  ?>
					<?php // Suburbs Start  ?>
					<?php if(!empty($suburbs)) {?>
					<div class="suburbs gt-field">
						<label for="Suburbs">Suburbs</label>
						<select class="gt-select-search gt-suburbs" name="suburbs[]" id="suburbs" multiple>
							<?php $attr_terms_temp = $gt_attributes['suburbs'];?>
							<?php sort($attr_terms_temp);?>
							<?php $attr_terms = array_count_values($attr_terms_temp);?>
							<?php 
								if(isset($_GET['suburbs']) && !empty($_GET['suburbs'])){
									if(strpos($_GET['suburbs'], ',') !== false ){
									$postcodes = explode(",", $_GET['suburbs']); 
									}else{
										$postcodes = [$_GET['suburbs']];
									}
								}
							?>
							<?php foreach($attr_terms as $value => $count){?>
							<?php $postcode = preg_replace('/[^0-9.]+/', '', $value);?>
									<option 
									value="<?php echo $postcode;?>"
									<?php if(!empty($postcodes) && in_array($postcode, $postcodes)){?> selected<?php } ?>
									<?php if($count==0){?> disabled="disabled"<?php } ?>
									>
									<?php echo $value;?><?php //if($attr_key=='pa_garage'){echo ($value>1)?" Cars ":" Car ";}?>(<?php echo $count; ?>)
									</option>
								<?php }?>
						</select> 
					</div>
					<?php } ?>
					<?php // Suburbs End  ?>

					
					<?php 
					// Load on the main/Mini filter Bar

					foreach($refined_attributes as $attr_key){?>
						<?php if(!empty($gt_attributes[$attr_key])){?>
						<?php $nice_tax_attr_lbl = get_nice_attr_tax_lbl($attr_key);?>
						<div class="<?php echo $attr_key;?> gt-field">
							<label for="<?php echo $nice_tax_attr_lbl[1];?>"><?php echo $nice_tax_attr_lbl[1];?></label>
							<select class="gt-select gt-<?php echo $nice_tax_attr_lbl[0];?>" name="<?php echo $nice_tax_attr_lbl[0];?>[]" id="<?php echo $nice_tax_attr_lbl[0];?>" multiple>
								<?php $attr_terms_temp = $gt_attributes[$attr_key];?>
								<?php sort($attr_terms_temp);?>
								<?php $attr_terms = array_count_values($attr_terms_temp);?>
								<?php 
									if(isset($_GET[$nice_tax_attr_lbl[0]]) && !empty($_GET[$nice_tax_attr_lbl[0]])){
										if(strpos($_GET[$nice_tax_attr_lbl[0]], ',') !== false ){
										$selected_values_arr = explode(",", $_GET[$nice_tax_attr_lbl[0]]); 
										}else{
											$selected_values_arr = [$_GET[$nice_tax_attr_lbl[0]]];
										}
									}
								?>
								<?php foreach($attr_terms as $value => $count){?>
									<option 
									value="<?php echo $value;?>"
									<?php if(isset($_GET[$nice_tax_attr_lbl[0]]) && in_array($value, $selected_values_arr)){?> selected="selected"<?php } ?>
									<?php if($count==0){?> disabled="disabled"<?php } ?>
									>
									<?php echo $value;?><?php //if($attr_key=='pa_garage'){echo ($value>1)?" Cars ":" Car ";}?>(<?php echo $count; ?>)
									</option>
								<?php }?>
							</select>
						</div>
						<?php } ?>
					<?php }?>
					<?php ?>
					<?php // Min Price Start  ?>
					<?php /*if(!empty($min_prices)) { ?>
					<div class="min_prices gt-field">
						<label for="Min Price">Min Price</label>
						<select class="gt-select-max-min gt-min_price" name="min_price" id="min_price">
							<option value="">All</option>
							<?php foreach($min_prices as $key => $value){?>
							<?php //if(($value['num'] < ($highest_price + 50000)) && ($value['num'] > $lowest_price)){?>
							<?php $count = get_min_frequency_count($product_prices,$value['num']); ?>
							<option 
							value="<?php echo $value['num'];?>" 
							<?php if(isset($_GET['min_price']) && $_GET['min_price']==$value['num']){?> selected <?php }?>
							<?php echo ($count==0)?"disabled":"";?>
							>
							$<?php echo $value['abbr'];?>(<?php echo $count;?>)
							</option>
							<?php //} ?>
							<?php } ?>
						</select> 
					</div>
					<?php }*/ ?>
					<?php // Min Price End  ?>
					
					<?php // Max Price Start  ?>
					<?php if(!empty($max_prices)) {?>
					<div class="max_prices gt-field">
						<label for="Max Price">Max Price</label>
						<select class="gt-select-max-min gt-max_price" name="max_price" id="max_price">
							<option value="">All</option>
							<?php foreach($max_prices as $key => $value){?>
							<?php //if(($value['num'] < ($highest_price + 50000)) && ($value['num'] > $lowest_price)){?>
							<?php $count = get_max_frequency_count($product_prices,$value['num']); ?>
							<option 
							value="<?php echo $value['num'];?>" 
							<?php if(isset($_GET['max_price']) && $_GET['max_price']==$value['num']){?> selected="selected"<?php }?>
							<?php echo ($count==0)?"disabled":"";?>
							>
							$<?php echo $value['abbr'];?>(<?php echo $count;?>)
							</option>
							<?php //} ?>
							<?php } ?>
						
						</select> 
					</div>
					<?php } ?>

					<?php // Max Price End  ?>
					
					
					<div class="gt-field">
						<span id="gtToggleSearch" class="btn btn-default"><span class="gt-more">More Filters <i class="fas fa-chevron-down"></i></span><span class="gt-less">Less Filters <i class="fas fa-chevron-up"></i></span></span>
					</div>
				</div>
			</div>
		</div>
		
		<div class="gt-advanced-search">
			<div class="gt-filter-spacer"></div>
		<div class="row">
			<div class="col-lg-12">
				<h4>Additional Features</h4>
				<div class="gt-filters-row gt-filters-adv1">
				<?php foreach($adv_refined_attributes as $attr_key){?>
						<?php if(!empty($gt_attributes[$attr_key])){?>
						<?php $nice_tax_attr_lbl = get_nice_attr_tax_lbl($attr_key);?>
						<div class="<?php echo $attr_key;?> gt-field">
							<label for="<?php echo $nice_tax_attr_lbl[1];?>"><?php echo $nice_tax_attr_lbl[1];?></label>
							<select class="gt-select gt-<?php echo $nice_tax_attr_lbl[0];?>" name="<?php echo $nice_tax_attr_lbl[0];?>[]" id="<?php echo $nice_tax_attr_lbl[0];?>" multiple>
								<?php $attr_terms_temp = $gt_attributes[$attr_key];?>
								<?php sort($attr_terms_temp);?>
								<?php $attr_terms = array_count_values($attr_terms_temp);?>
								<?php foreach($attr_terms as $value => $count){?>
									<option 
									value="<?php echo $value;?>"
									<?php if(isset($_REQUEST[$nice_tax_attr_lbl[0]]) && $_REQUEST[$nice_tax_attr_lbl[0]]==$value){?> selected="selected"<?php } ?>
									<?php echo ($count==0)?"disabled":"";?>
									>
									<?php echo $value;?><?php //if($attr_key=='pa_garage'){echo ($value>1)?" Cars ":" Car ";}?>(<?php echo $count; ?>)
									</option>
								<?php }?>
							</select>
						</div>
						<?php } ?>
					<?php }?>
				</div>	
				
			</div>
		</div>
		
		<?php if(!empty($house_sizes) OR !empty($land_sizes) ) {?>
			<div class="gt-filter-spacer"></div>
		<div class="row">
			<div class="col-lg-12">
				<h4>Size(Area)</h4>
				<div class="gt-filters-row gt-filters-adv2">
					
					<?php // Min House Size Start // ?>
					<?php if(!empty($min_house_sizes)) {?>
					<div class="min_house_sizes gt-field">
						<label for="Min House Size">Min House Size</label>
						<select class="gt-select-max-min gt-min_hs" name="min_hs" id="min_hs">
							<option value="">All</option>
							<?php foreach($min_house_sizes as $value){?>
							<?php $count = get_min_frequency_count($house_sizes,$value); ?>
							<option 
							value="<?php echo $value;?>" 
							<?php if(isset($_GET['min_hs']) && $_GET['min_hs']==$value){?> selected="selected"<?php }?>
							<?php echo ($count==0)?"disabled":"";?>
							>
							<?php echo $value;?>&#13217; (<?php echo $count;?>)
							</option>
							<?php //} ?>
							<?php } ?>
						</select> 
					</div>
					<?php } ?>
					<?php // Min House Size End // ?>
					
					<?php /* Max House Size Start */ ?>
					<?php if(!empty($max_house_sizes)) {?>
					<div class="max_house_sizes gt-field">
						<label for="Max House Size">Max House Size</label>
						<select class="gt-select-max-min gt-max_hs" name="max_hs" id="max_hs">
							<option value="">All</option>
							<?php foreach($max_house_sizes as $value){?>
							<?php $count = get_max_frequency_count($house_sizes,$value); ?>
							<option 
							value="<?php echo $value;?>" 
							<?php if(isset($_GET['max_hs']) && $_GET['max_hs']==$value){?> selected="selected"<?php }?>
							<?php echo ($count==0)?"disabled":"";?>
							>
							<?php echo $value;?>&#13217; (<?php echo $count;?>)
							</option>
							<?php //} ?>
							<?php } ?>
						</select> 
					</div>
					<?php } ?>
					<?php // Max House Size End // ?>
					
					<?php // Min Land Size Start // ?>
					<?php if(!empty($min_land_sizes)) {?>
					<div class="min_land_sizes gt-field">
						<label for="Min Land Size">Min Land Size</label>
						<select class="gt-select-max-min gt-min_ls" name="min_ls" id="min_ls">
							<option value="">All</option>
							<?php foreach($min_land_sizes as $value){?>
							<?php $count = get_min_frequency_count($land_sizes,$value); ?>
							<option 
							value="<?php echo $value;?>" 
							<?php if(isset($_GET['min_ls']) && $_GET['min_ls']==$value){?> selected="selected"<?php }?>
							<?php echo ($count==0)?"disabled":"";?>
							>
							<?php echo $value;?>&#13217; (<?php echo $count;?>)
							</option>
							<?php //} ?>
							<?php } ?>
						</select> 
					</div>
					<?php } ?>
					<?php // Min Land Size End // ?>
					
					<?php // Max Land Size Start // ?>
					<?php if(!empty($max_land_sizes)) {?>
					<div class="max_land_sizes gt-field">
						<label for="Max Land Size">Max Land Size</label>
						<select class="gt-select-max-min gt-max_ls" name="max_ls" id="max_ls">
							<option value="">All</option>
							<?php foreach($max_land_sizes as $value){?>
							<?php $count = get_max_frequency_count($land_sizes,$value); ?>
							<option 
							value="<?php echo $value;?>" 
							<?php if(isset($_GET['max_ls']) && $_GET['max_ls']==$value){?> selected="selected"<?php }?>
							<?php echo ($count==0)?"disabled":"";?>
							>
							<?php echo $value;?>&#13217; (<?php echo $count;?>)
							</option>
							<?php //} ?>
							<?php } ?>
						</select> 
					</div>
					<?php } ?>
					<?php // Max House Size End // ?>


				</div>
			</div>
		</div>
	<?php } ?>


		
		<?php if(!empty($block_widths) OR !empty($block_depths) ) {?>
			<div class="gt-filter-spacer"></div>
		<div class="row">
			<div class="col-lg-12">
				<h4>Block Dimensions</h4>
				<div class="gt-filters-row gt-filters-adv3">
				<?php /* Min Block Width Start */ ?>
				<?php if(!empty($min_block_widths)) {?>
				<div class="min_block_widths gt-field">
					<label for="Min Block Width">Min Block Width</label>
					<select class="gt-select-max-min gt-min_bw" name="min_bw" id="min_bw">
						<option value="">All</option>
						<?php foreach($min_block_widths as $value){?>
						<?php $count = get_min_frequency_count($block_widths,$value); ?>
						<option 
						value="<?php echo $value;?>" 
						<?php if(isset($_GET['min_bw']) && $_GET['min_bw']==$value){?> selected="selected"<?php }?>
						<?php echo ($count==0)?"disabled":"";?>
						>
						<?php echo $value;?>m (<?php echo $count;?>)
						</option>
						<?php //} ?>
						<?php } ?>
					</select> 
				</div>
				<?php } ?>
				<?php /* Min Block Width End */ ?>
				
				<?php /* Max Block Width Start */ ?>
				<?php if(!empty($max_block_widths)) {?>
				<div class="max_block_widths gt-field">
					<label for="Max Block Width">Max Block Width</label>
					<select class="gt-select-max-min gt-max_bw" name="max_bw" id="max_bw">
						<option value="">All</option>
						<?php foreach($max_block_widths as $value){?>
						<?php $count = get_max_frequency_count($block_widths,$value); ?>
						<option 
						value="<?php echo $value;?>" 
						<?php if(isset($_GET['max_bw']) && $_GET['max_bw']==$value){?> selected="selected"<?php }?>
						<?php echo ($count==0)?"disabled":"";?>
						>
						<?php echo $value;?>m (<?php echo $count;?>)
						</option>
						<?php //} ?>
						<?php } ?>
					</select> 
				</div>
				<?php } ?>
				<?php /* Max Block Width End */ ?>
				
				<?php /* Min Block Depth Start */ ?>
				<?php if(!empty($min_block_depths)) {?>
				<div class="min_block_depths gt-field">
					<label for="Min Block Depth">Min Block Depth</label>
					<select class="gt-select-max-min gt-min_bd" name="min_bd" id="min_bd">
						<option value="">All</option>
						<?php foreach($min_block_depths as $value){?>
						<?php $count = get_min_frequency_count($block_depths,$value); ?>
						<option 
						value="<?php echo $value;?>" 
						<?php if(isset($_GET['min_bd']) && $_GET['min_bd']==$value){?> selected="selected"<?php }?>
						<?php echo ($count==0)?"disabled":"";?>
						>
						<?php echo $value;?>m (<?php echo $count;?>)
						</option>
						<?php //} ?>
						<?php } ?>
					</select> 
				</div>
				<?php } ?>
				<?php /* Min Block Depth End */ ?>
				
				<?php /* Max Block Depth Start */ ?>
				<?php if(!empty($max_block_depths)) {?>
				<div class="max_block_depths gt-field">
					<label for="Max Block Depth">Max Block Depth</label>
					<select class="gt-select-max-min gt-max_bd" name="max_bd" id="max_bd">
						<option value="">All</option>
						<?php foreach($max_block_depths as $value){?>
						<?php $count = get_max_frequency_count($block_depths,$value); ?>
						<option 
						value="<?php echo $value;?>" 
						<?php if(isset($_GET['max_bd']) && $_GET['max_bd']==$value){?> selected="selected"<?php }?>
						<?php echo ($count==0)?"disabled":"";?>
						>
						<?php echo $value;?>m (<?php echo $count;?>)
						</option>
						<?php //} ?>
						<?php } ?>
					</select> 
				</div>
				<?php } ?>
				<?php /* Max Block Depth End */ ?>
				</div>
			</div>
		</div>

	<?php } ?>






		<div class="gt-filter-spacer"></div>
		<div class="row">
			<div class="col-lg-12">
				<h4>Eligible Grants</h4>
				<div class="gt-filters-row gt-filters-adv4">
				<?php foreach($grant_refined_attributes as $attr_key){?>
						<?php if(!empty($gt_attributes[$attr_key])){?>
						<?php $nice_tax_attr_lbl = get_nice_attr_tax_lbl($attr_key);?>
						<div class="<?php echo $attr_key;?> gt-field">
							<label for="<?php echo $nice_tax_attr_lbl[1];?>"><?php echo $nice_tax_attr_lbl[1];?></label>
							<select class="gt-select gt-<?php echo $nice_tax_attr_lbl[0];?>" name="<?php echo $nice_tax_attr_lbl[0];?>[]" id="<?php echo $nice_tax_attr_lbl[0];?>" multiple>
								<?php $attr_terms_temp = $gt_attributes[$attr_key];?>
								<?php sort($attr_terms_temp);?>
								<?php $attr_terms = array_count_values($attr_terms_temp);?>
								<?php foreach($attr_terms as $value => $count){?>
									<option 
									value="<?php echo $value;?>"
									<?php if(isset($_REQUEST[$nice_tax_attr_lbl[0]]) && $_REQUEST[$nice_tax_attr_lbl[0]]==$value){?> selected="selected"<?php } ?>
									<?php echo ($count==0)?"disabled":"";?>
									>
									<?php echo $value;?><?php //if($attr_key=='pa_garage'){echo ($value>1)?" Cars ":" Car ";}?>(<?php echo $count; ?>)
									</option>
								<?php }?>
							</select>
						</div>
						<?php } ?>
					<?php }?>
				</div>	
			</div>
		</div>
		<div class="gt-filter-spacer"></div>
		<div class="row">
			<div class="col-lg-12">
				<div class="text-center">
					<?php //if(isset($_GET) && count($_GET) > 1){?><span class="gt-clear-filters btn btn-secondary">Clear Filters <i class="fas fa-times"></i></span><?php //} ?>
					<span class="gt-less-filters btn btn-default">Less Filters <i class="fas fa-chevron-up"></i></span>
				</div>
			</div>
		</div>
		<div class="gt-filter-spacer"></div>
		</div>
		
		<div class="gt-separator"></div>
		<div class="row">
			<div class="col-lg-6">
				<?php include(locate_template('inc/ajaxfilter/partials/grant-result-count.php')); ?>
			</div>
			<div class="col-lg-6">
				<div class="gt-ord-pag-gl">
					<?php //woocommerce_catalog_ordering(); ?>
					<div class="gt-woocommerce-ordering">
						<label>Sort By: </label>
						<select name="sortby" class="sortby" aria-label="Shop order">
							<option value="">
								Default sorting
							</option>
							<option 
								value="date"
								<?php if(isset($_GET['sortby']) && $_GET['sortby']=='date'){?> selected="selected"<?php } ?>
							>
								Sort by latest
							</option>
							<option 
								value="price"
								<?php if(isset($_GET['sortby']) && $_GET['sortby']=='price'){?> selected="selected"<?php } ?>
							>
								Sort by price: low to high
							</option>
							<option 
								value="price-desc"
								<?php if(isset($_GET['sortby']) && $_GET['sortby']=='price-desc'){?> selected="selected"<?php } ?>
							>
								Sort by price: high to low
							</option>
						</select>
					</div>
					<?php /* ?>
					<nav class="gt-woocommerce-pagination">	
						<div class="gt-woocommerce-viewing">
							<label>Show: </label>
							<select name="count" class="count">
								<option value="12" selected="selected">12</option>
								<option value="24">24</option>
								<option value="36">36</option>
							</select>
						</div>
					</nav>
					<div class="gt-gridlist-toggle">
						<a href="#" id="grid" title="Grid View" class="active"></a>
						<a href="#" id="list" title="List View"></a>
					</div>
					<?php */ ?>
				</div>
			</div>
		</div>
	</form>
</div>