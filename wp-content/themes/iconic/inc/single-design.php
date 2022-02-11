
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
// print_r()
?>


	<?php
	 $data = '';
	 $count = 0;
	 $activeClass='';

$data = '<div class="accordion pt-4" id="accordionPanelsStayOpenExample">';
	$data = '<h2>'.count($floor_plans_childrens).' Floor Plans to chooose from</h2>';

foreach ($floor_plans_childrens as $post_id)
 {
 	$count++;
	 $activeClass='';
 	if((int)$count == 1)
 		{
 			$activeClass =  "show";
 		}

if($post_id){
        // $bedroom = get_field('bedrooms', $post_id);
        // $bathroom = get_field('bathrooms', $post_id);
        // $garage = get_field('garage', $post_id);
        // $ground_floor_area = get_field('ground_floor_area', $post_id);
        // $first_floor_area = get_field('first_floor_area', $post_id);
        // $alfresco_area = get_field('alfresco_area', $post_id);
        // $balcony_area_sqm = get_field('balcony_area_sqm', $post_id);
        // $porch_area = get_field('porch_area', $post_id);
        // $garage_area = get_field('garage_area', $post_id);
        // $private_living_space_area = get_field('private_living_space_area', $post_id);
        // $outdoor_leisure_area = get_field('outdoor_leisure_area', $post_id);
        // $patio_area = get_field('patio_area', $post_id);
        // $alfresco_granny_area = get_field('alfresco_granny_area', $post_id);
        // $granny_flat_area = get_field('granny_flat_area', $post_id);
        // $loft_area = get_field('loft_area', $post_id);
        // $carport_area = get_field('carport_area', $post_id);
        // $total_home_area = get_field('total_home_area', $post_id);
        // $floor_plan_pdf = get_field('floor_plan_pdf', $post_id);
        // $featured_img_url = get_the_post_thumbnail_url($post_id,'full'); 



        $bedroom = 2;
        $bathroom = 2;
        $garage = 1;
        $ground_floor_area = 11;
        $first_floor_area = 33;
        $alfresco_area = 44;
        $balcony_area_sqm = 44;
        $porch_area = 444;
        $garage_area = 444;
        $private_living_space_area = 767;
        $outdoor_leisure_area = 77;
        $patio_area = 7777;
        $alfresco_granny_area = 777;
        $granny_flat_area = 888;
        $loft_area = 99;
        $carport_area = 22;
        $total_home_area = 45;
        $floor_plan_pdf ="/pdf/";
        $featured_img_url ='http://localhost/grant/wp-content/uploads/2022/02/TheLuna244-1.jpg'; 

    }

 $data .= '<div id="floorplan-'.$activeClass."-".$count.'" class="accordion-item">';
    $data .= '<h2 class="accordion-header" id="panelsStayOpen-heading'.$post_id.'">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse'.$post_id.'" aria-expanded="true" aria-controls="#panelsStayOpen-collapse'.$post_id.'">
        The Luna '.$post_id.'
      </button>
    </h2>';
   
    $data .= '<div id="panelsStayOpen-collapse'.$post_id.'" class="row bg-primary1 py-3 accordion-collapse collapse '.$activeClass.'" aria-labelledby="#panelsStayOpen-heading'.$post_id.'"data-bs-parent11="#accordionExample">';
    
    $data .= '<div class="accordion-body col-md-12 col-lg-5 pb-4">';
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
        $bedroom = get_field('bedrooms', $post_id);
        $bathroom = get_field('bathrooms', $post_id);
        $garage = get_field('garage', $post_id);
        $ground_floor_area = get_field('ground_floor_area', $post_id);
        $first_floor_area = get_field('first_floor_area', $post_id);
        $alfresco_area = get_field('alfresco_area', $post_id);
        $balcony_area_sqm = get_field('balcony_area_sqm', $post_id);
        $porch_area = get_field('porch_area', $post_id);
        $garage_area = get_field('garage_area', $post_id);
        $private_living_space_area = get_field('private_living_space_area', $post_id);
        $outdoor_leisure_area = get_field('outdoor_leisure_area', $post_id);
        $patio_area = get_field('patio_area', $post_id);
        $alfresco_granny_area = get_field('alfresco_granny_area', $post_id);
        $granny_flat_area = get_field('granny_flat_area', $post_id);
        $loft_area = get_field('loft_area', $post_id);
        $carport_area = get_field('carport_area', $post_id);
        $total_home_area = get_field('total_home_area', $post_id);
        $floor_plan_pdf = get_field('floor_plan_pdf', $post_id);
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









