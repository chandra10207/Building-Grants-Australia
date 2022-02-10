
<?php 
function nd_single_product_slider_func(){

global $product;

$gallery_ids_array = $product->get_gallery_image_ids();

/*For Grouped Products*/
// $gallery_ids_array   = $product->get_children();


// echo '<pre>';
// print_r($gallery_ids_array);die;


// Design Single page functionlaity

	?>
	<div class="bootstrap-wrapper" id="slider-wrapper">
		<section class=" container-fluid m-0 p-0">
			<div class="slider-section">
				<div class="slick-controls">
					<p class="next"><i class="fas fa-angle-right"></i></p>
					<p class="previous"><i class="fas fa-angle-left"></i></p>
				</div>
				<div class="slider variable-width">
					<?php
						// $gallery_ids=get_post_meta($id ,'image_gallery_ids',true);
						// $gallery_ids_array = array_filter(explode(",", $gallery_ids));
						$i=1;
						foreach($gallery_ids_array as $slide){
							if($slide!=''){ 
								/*For Group products Images*/
								$image = wp_get_attachment_image_url( get_post_thumbnail_id( $slide ), 'large' );

								/*For products Images*/
								$image = wp_get_attachment_image_url($slide , 'large' );
// echo '<pre>';
// print_r($image);die;

								?>
							<div class="item border">
								<img src="<?php echo $image; ?> " >
							</div>
							<?php
								$i++;
							}
						}
					 /*
						//image_gallery_urls
						$gallery_urls=get_post_meta($id ,'image_gallery_urls',true);
						$gallery_urls_array = array_filter(explode(",", $gallery_urls));
						foreach($gallery_urls_array as $slide){
							if($slide!=''){ ?>
							<div class="item border">
								<img src="<?php echo esc_url($slide); ?>" >
							</div>
							<?php
								$i++;
							}
						}


						if($i<3){
							for($iii=0;$iii<3;$iii++){
								if(has_post_thumbnail($id)){?>
								<div class="item border">
									<?php echo get_the_post_thumbnail($id, 'large');?>
								</div>
								<?php
									}else{
								?>
								<div class="item border">
									<img   src="<?php echo  wp_iv_property_URLPATH."/assets/images/default-directory.jpg";?>">
								</div>
								<?php
								}
							}
						}
						*/
					?>
				</div>
			</div>
		</section>
	</div>
	<?php
	}

add_shortcode('nd_single_product_slider', 'nd_single_product_slider_func'); 


function nd_single_product_floorplans_func(){

global $product;
$floor_plans_childrens   = $product->get_children();



}

add_shortcode('nd_single_product_floorplans', 'nd_single_product_floorplans_func'); 







