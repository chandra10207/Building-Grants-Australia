<?php 

add_shortcode('nd_single_product_slider', 'nd_single_product_slider_func'); 



function nd_single_product_slider_func(){

global $product;
$product_id = $product->get_id();
$count = 0;

$gallery_ids_array = $product->get_gallery_image_ids();

/*For Grouped Products*/
// $gallery_ids_array   = $product->get_children();


// if(!$gallery_ids_array){
    $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'large' );
// }



$interior_images_id = get_post_meta($product_id,'wpcf-interior-gallery');
    // $rows = get_post_meta($product->ID,'wpcf-interior-gallery');



// echo '<pre>';
// print_r($interior_images_id);die;
// print_r($featured_image);
// print_r($gallery_ids_array);
// die;

// Design Single page functionlaity

	?>
	<div class="bootstrap-wrapper" id="slider-wrapper">
		<section class=" container-fluid m-0 p-0">
			<div class="slider-section">
				<!-- <div class="slick-controls">
					<p class="next"><i class="fas fa-angle-right"></i></p>
					<p class="previous"><i class="fas fa-angle-left"></i></p>
				</div> -->
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
							<div class="nd-banner-slider-item item border nd-gallery">
								<img src="<?php echo $image; ?> " >
							</div>
							<?php
								$i++;$count++;
							}
						}


                        if($featured_image){
                            $count++;
                            ?>
                            <div class="nd-banner-slider-item item border nd-featured">
                                <img src="<?php echo $featured_image[0]; ?> " >
                            </div>


                            <?php

                        }




                        if($count < 3){



                        if($interior_images_id){

                            foreach($interior_images_id as $slide){

                            $count++;

                            // $image = wp_get_attachment_image_url( $slide, 'large' );

                                /*For products Images*/
                                $image = wp_get_attachment_image_url($slide , array(1200, 0) );
                                // echo $image;

                            ?>

                            <div class="nd-banner-slider-item item border nd-interior">
                                <img src="<?php echo $image; ?> " >
                            </div>

                            <?php } 

                        }




                            for($count; $count < 3; $count++){




                        if($featured_image){
                            ?>
                            <div class="nd-banner-slider-item item border nd-featured">
                                <img src="<?php echo $featured_image[0]; ?> " >
                            </div>


                            <?php

                        }


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



/*Shortcode to print Design Specifications*/
function nd_get_design_specifications ( $atts = '' ) 

{
    $nd_shortcode_attr = shortcode_atts( array(
        'design_id' => '',
        'design_slug' => '',
    ), $atts );
	
	$post_id = '';
	if($nd_shortcode_attr['design_id']){
		$post_id = $nd_shortcode_attr['design_id'];
	}
    if ( $nd_shortcode_attr['design_slug']){
		$post = get_page_by_path( $nd_shortcode_attr['design_slug'], OBJECT, 'home_designs' );
		$post_id = $post->ID;
	}
    
    if($post_id){
        $bedroom = get_the_terms('bedrooms', $post_id);
        $bathroom = get_the_terms('bathrooms', $post_id);
        $garage = get_the_terms('garage', $post_id);
        $ground_floor_area = get_the_terms('ground_floor_area', $post_id);
        $first_floor_area = get_the_terms('first_floor_area', $post_id);
        $alfresco_area = get_the_terms('alfresco_area', $post_id);
        $balcony_area_sqm = get_the_terms('balcony_area_sqm', $post_id);
        $porch_area = get_the_terms('porch_area', $post_id);
        $garage_area = get_the_terms('garage_area', $post_id);
        $private_living_space_area = get_the_terms('private_living_space_area', $post_id);
        $outdoor_leisure_area = get_the_terms('outdoor_leisure_area', $post_id);
        $patio_area = get_the_terms('patio_area', $post_id);
        $alfresco_granny_area = get_the_terms('alfresco_granny_area', $post_id);
        $granny_flat_area = get_the_terms('granny_flat_area', $post_id);
        $loft_area = get_the_terms('loft_area', $post_id);
        $carport_area = get_the_terms('carport_area', $post_id);
        $total_home_area = get_the_terms('total_home_area', $post_id);
        $floor_plan_pdf = get_the_terms('floor_plan_pdf', $post_id);
        $featured_img_url = get_the_post_thumbnail_url($post_id,'full'); 
    }
    // echo $bedroom;
    $data = '';
    $data .= '<div class="row bg-primary1 py-3">';
    
    $data .= '<div class="col-md-12 col-lg-5 pb-4">';
    $data .= '<h4 class="nd-specification-lbl">Specifications</h4>';
    $data .= '<div class="lead mb-3 nd-specification-number">';
    $data .= '<ul class="list list-unstyled mt-2 short-desc overflow-hidden">';
    if($bedroom){
        $data .= '<li><i class="fas fa-bed fa-xs ml-1"></i> <span class="ml-1">'.$bedroom.' <span class="nd-property-lbl-details bed">Beds</span></span></li>';
    }
    if($bathroom){
        $data .= '<li><i class="fas fa-shower fa-xs ml-1"></i> <span class="ml-1">'.$bathroom.' <span class="nd-property-lbl-details bath">Baths</span></span></li>';
    }
    if($garage){
        $data .= '<li><i class="fas fa-car fa-xs ml-1"></i> <span class="ml-1">'.$garage.' <span class="nd-property-lbl-details garage">Garage</span></span></li>';
    }
    $data .= '</ul>';
    $data .= '</div>';
    
    $data .= '<div class="table-responsive"><table><tbody>';
    if($ground_floor_area){
        $data .= '<tr><td>Ground Floor</td><td>'.$ground_floor_area.' Sqm</td></tr>';
    }
    if($first_floor_area){
        $data .= '<tr><td>First Floor </td><td>'.$first_floor_area.' Sqm</td></tr>';
    }
    if($loft_area){
        $data .= '<tr><td>Loft </td><td>'.$loft_area.' Sqm</td></tr>';
    }
    if($outdoor_leisure_area){
        $data .= '<tr><td>Outdoor Leisure</td><td>'.$outdoor_leisure_area.' Sqm</td></tr>';
    }
    if($alfresco_area){
        $data .= '<tr><td>Alfresco</td><td>'.$alfresco_area.' Sqm</td></tr>';
    }
    if($balcony_area_sqm){
        $data .= '<tr><td>Balcony</td><td>'.$balcony_area_sqm.' Sqm</td></tr>';
    }
    if($porch_area){
        $data .= '<tr><td>Porch</td><td>'.$porch_area.' Sqm</td></tr>';
    }
    if($garage_area){
        $data .= '<tr><td>Garage</td><td>'.$garage_area.' Sqm</td></tr>';
    }
    if($private_living_space_area){
        $data .= '<tr><td>Private Living Space</td><td>'.$private_living_space_area.' Sqm</td></tr>';
    }
    if($patio_area){
        $data .= '<tr><td>Patio</td><td>'.$patio_area.' Sqm</td></tr>';
    }
    if($alfresco_granny_area){
        $data .= '<tr><td>Alfresco Granny</td><td>'.$alfresco_granny_area.' Sqm</td></tr>';
    }
    if($granny_flat_area){
        $data .= '<tr><td>Granny Flat</td><td>'.$granny_flat_area.' Sqm</td></tr>';
    }
    if($carport_area){
        $data .= '<tr><td>Carport</td><td>'.$carport_area.' Sqm</td></tr>';
    }
    if($total_home_area){
        $data .= '<tr><td>Total Home Area</td><td>'.$total_home_area.' Sqm</td></tr>';
    }
    
    $data .= '</tbody></table></div>';
	
	$data .= '<div class="nd-button-container overflow-hidden py-3 py-xl-5">';

    if($floor_plan_pdf){
	$data .= '<a class="vc_btn3 vc_btn3-shape-default btn btn-modern btn-md btn-secondary mr-2" href="/wp-content/uploads/designs_pdf/'.$floor_plan_pdf.'" title="" target="_blank">Download Floor Plan</a>';
	}
	$data .= '<a class="vc_btn3 vc_btn3-shape-default btn btn-modern btn-md btn-primary my-3" href="/designs/inclusions/" title="">View Inclusions</a>';
	
	$data .= '</div>';

    $data .= '</div>';
    
    $data .= '<div class="col-md-12 col-lg-7">';
    $data .= '<h4 class="nd-specification-lbl">Floor Plan</h4>';
    
     $data .= '<div class="facade img-container mb-2">
									<a data-fancybox="gallery" href="'.$featured_img_url.'">
										<img class="img-fluid" src="'.$featured_img_url.'" ></a>	</div>';
    
    
    $data .= '</div>';
    
    $data .= '</div>';
    
    echo $data;
    
}
add_shortcode( 'nd_home_design_specifications', 'nd_get_design_specifications' );









