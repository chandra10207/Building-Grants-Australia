
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
							<div class="nd-banner-slider-item item border">
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
// print_r()
?>


	<?php
	 $data = '';
	 $count = 0;
	 $activeClass='';

$data = '<div class="accordion pt-4" id="accordionPanelsStayOpenExample">';
	$data .= '<h2>'.count($floor_plans_childrens).' Floor Plans to chooose from</h2>';
    $data .= '<p> Click on Floor plan name to see more details.</p>';

foreach ($floor_plans_childrens as $post_id)
 {
 	$count++;
	 $activeClass='';
	 $floor_plan_pdf = '';
 	if((int)$count !== 1)
 		{
 			$activeClass =  "show";
 		}

if($post_id){

    // $house_size = get_the_terms( $product_id , 'pa_house-size-sqm');
    // $house_size[0]->name

        $bedroom = get_the_terms( $post_id, 'pa_bedrooms');
        $bathroom = get_the_terms($post_id, 'pa_bathrooms');
        $garage = get_the_terms($post_id, 'pa_garage');
        $living_rooms = get_the_terms($post_id, 'pa_living-rooms');
        $stories = get_the_terms($post_id, 'pa_stories');
        $max_block_depth_m = get_the_terms($post_id, 'pa_max-block-depth-m');
        $max_block_width_m = get_the_terms($post_id, 'pa_max-block-width-m');
        $max_house_size_sqm = get_the_terms($post_id, 'pa_max-house-size-sqm');
        $min_block_depth_m = get_the_terms($post_id, 'pa_min-block-depth-m');
        $min_block_width_m = get_the_terms($post_id, 'pa_min-block-width-m');
        $min_house_size_sqm = get_the_terms($post_id, 'pa_min-house-size-sqm');
        $other_features = get_the_terms($post_id, 'pa_other-features');
        $property_status = get_the_terms($post_id, 'pa_property-status');
        $state = get_the_terms($post_id, 'pa_state');
        $stories = get_the_terms($post_id, 'pa_stories');
        $suburb = get_the_terms($post_id, 'pa_suburb');
        $total_block_area_sqm = get_the_terms($post_id, 'pa_total-block-area-sqm', $post_id);
        $total_home_area_sqm = get_the_terms($post_id, 'pa_total-home-area', $post_id);


        


				$floor_plan_image_id = get_post_meta($post_id,'wpcf-floor-plan-image');
        // $floor_plan_image = get_the_post_thumbnail_url((int)$floor_plan_image_id[0],'full'); 

				// $image = wp_get_attachment_image_url( get_post_thumbnail_id( $slide ), 'large' );

				$floor_plan_image = wp_get_attachment_image_url( (int)$floor_plan_image_id[0], 'large' );

				$floor_plan_pdf_id = get_post_meta($post_id,'wpcf-floor-plan-pdf');
				if($floor_plan_pdf_id){
				$floor_plan_pdf = wp_get_attachment_url( (int)$floor_plan_pdf_id[0] );
				// echo '<pre>';
				// var_dump($floor_plan_pdf);

				}
				// echo get_the_title( $post_id );


    }

 $data .= '<div id="floorplan-'.$activeClass."-".$count.'" class="accordion-item">';
    $data .= '<h2 class="accordion-header" id="panelsStayOpen-heading'.$post_id.'">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse'.$post_id.'" aria-expanded="true" aria-controls="#panelsStayOpen-collapse'.$post_id.'">
        '.get_the_title( $post_id ).'
      </button>
    </h2>';
   
    $data .= '<div id="panelsStayOpen-collapse'.$post_id.'" class="row bg-primary1 py-3 accordion-collapse collapse '.$activeClass.'" aria-labelledby="#panelsStayOpen-heading'.$post_id.'"data-bs-parent11="#accordionExample">';
    
    $data .= '<div class="accordion-body col-md-12 col-lg-5 pb-4">';
    $data .= '<h4 class="nd-specification-lbl">Specifications</h4>';
    $data .= '<div class="lead mb-3 nd-specification-number">';
    $data .= '<ul class="list list-unstyled mt-2 short-desc overflow-hidden">';
    if($bedroom){
        $data .= '<li><i class="fas fa-bed fa-xs ml-1"></i> <span class="ml-1">'.$bedroom[0]->name.' <span class="nd-property-lbl-details bed">Beds</span></span></li>';
    }
    if($bathroom){
        $data .= '<li><i class="fas fa-shower fa-xs ml-1"></i> <span class="ml-1">'.$bathroom[0]->name.' <span class="nd-property-lbl-details bath">Baths</span></span></li>';
    }
    if($garage){
        $data .= '<li><i class="fas fa-car fa-xs ml-1"></i> <span class="ml-1">'.$garage[0]->name.' <span class="nd-property-lbl-details garage">Garage</span></span></li>';
    }
    $data .= '</ul>';
    $data .= '</div>';
    
    $data .= '<div class="table-responsive"><table><tbody>';
    if($living_rooms){
        $data .= '<tr><td>Living Rooms</td><td>'.$living_rooms[0]->name.'</td></tr>';
    }
    if($stories){
        $data .= '<tr><td>Story </td><td>'.$stories[0]->name.'</td></tr>';
    }

    $data .= '<tr><td colspan="100%"><h4 class="mt-2">'.__( 'Area & Dimensions', 'porto' ) .'</h4></td></tr>';

    if($min_block_depth_m){
        $data .= '<tr><td>Min Block Depth</td><td>'.$min_block_depth_m[0]->name.' Sqm</td></tr>';
    }
    // if($max_block_depth_m){
    //     $data .= '<tr><td>Max Block depth </td><td>'.$max_block_depth_m[0]->name.' Sqm</td></tr>';
    // }
    // if($min_block_width_m){
    //     $data .= '<tr><td>Min Block width</td><td>'.$min_block_width_m[0]->name.' Sqm</td></tr>';
    // }
    // if($max_block_width_m){
    //     $data .= '<tr><td>Max Block width Leisure</td><td>'.$max_block_width_m[0]->name.' Sqm</td></tr>';
    // }
    if($total_block_area_sqm){
        $data .= '<tr><td>Total block Area</td><td>'.$total_block_area_sqm[0]->name.' Sqm</td></tr>';
    }
    if($total_home_area_sqm){
        $data .= '<tr><td>Total Home Area</td><td>'.$total_home_area_sqm[0]->name.' Sqm</td></tr>';
    }

    if($min_house_size_sqm){
        $data .= '<tr><td>Min House Size</td><td>'.$min_house_size_sqm[0]->name.' Sqm</td></tr>';
    }
    if($max_house_size_sqm){
        $data .= '<tr><td>Max House Size</td><td>'.$max_house_size_sqm[0]->name.' Sqm</td></tr>';
    }


    $data .= '<tr><td colspan="100%"><h4 class="mt-2">'.__( 'Other Features', 'porto' ) .'</h4></td></tr>';

    if($property_status){
        $data .= '<tr><td>Status</td><td>'.$property_status[0]->name.'</td></tr>';
    }
    if($state){
        $data .= '<tr><td>State</td><td>'.$state[0]->name.'</td></tr>';
    }
    if($suburb){
        $data .= '<tr><td>Shuburb</td><td>'.$suburb[0]->name.'</td></tr>';
    }

    if($other_features){
        $data .= '<tr><td>Private Living Space</td><td>'.$other_features[0]->name.' Sqm</td></tr>';
    }

    $data .= '</tbody></table></div>';
	
	$data .= '<div class="nd-button-container overflow-hidden py-3 py-xl-5">';

    if($floor_plan_pdf){
	$data .= '<a class="vc_btn3 vc_btn3-shape-default btn btn-modern btn-md btn-secondary mr-2" href="'.$floor_plan_pdf.'" title="" target="_blank">Download Floor Plan</a>';
	}
	// $data .= '<a class="vc_btn3 vc_btn3-shape-default btn btn-modern btn-md btn-primary my-3" href="/designs/inclusions/" title="">View Inclusions</a>';
	
	$data .= '</div>';

    $data .= '</div>';
    
    $data .= '<div class="col-md-12 col-lg-7">';
    $data .= '<h4 class="nd-specification-lbl">Floor Plan</h4>';
    
     $data .= '<div class="facade img-container mb-2">
									<a data-fancybox="gallery" href="'.$floor_plan_image.'">
										<img class="img-fluid" src="'.$floor_plan_image.'" ></a>	</div>';
    
    
    $data .= '</div>';
    
    $data .= '</div>';
    
}

	 ?>


 <!--  <div class="accordion-item">
    <h2 class="accordion-header" id="headingOne">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        Accordion Item #1
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
      </div>
    </div>
  </div> -->


<!-- </div> -->

    

<?php 
$data .= '</div>';
$data .= '</div>';


    return $data;
}

add_shortcode('nd_single_product_floorplans', 'nd_single_product_floorplans_func'); 




/*Shortcode to print Design Specifications*/
function nd_get_design_specifications ( $atts = '' ) {
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
    
    return $data;
    
}
add_shortcode( 'nd_home_design_specifications', 'nd_get_design_specifications' );









