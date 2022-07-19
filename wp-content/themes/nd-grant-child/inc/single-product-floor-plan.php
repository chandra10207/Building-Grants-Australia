<?php

if ( !is_admin()) {

add_shortcode('nd_single_product_floorplans', 'nd_single_product_group_floorplans_func'); 

    }

function nd_single_product_group_floorplans_func(){

global $product;
$product_type = $product->get_type();
 $floor_plans_childrens   = [];

// echo $product_type;die;

if( $product->is_type( 'grouped' ) ) {

    $floor_plans_childrens   = $product->get_children();
    }
     else {

        array_push($floor_plans_childrens, $product->get_id());
    }

// print_r($product);die;
?>


	<?php
	 $data = '';
	 $count = 0;
	 $activeClass='';

if( $product->is_type( 'grouped' ) ) {
$data = '<div class="nd-design-floor-plan accordion py-4" id="accordionPanelsStayOpenExample">';
	$data .= '<h2>'.count($floor_plans_childrens).' variations available</h2>';
    $data .= '<p> Click to see more details.</p>';
}
else{

    $data .= '<h2>Floor Plan Details</h2>';
}
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

    // echo $po/st_id; die;

    // $house_size = get_the_terms( $product_id , 'pa_house-size-sqm');
    // $house_size[0]->name
    // get_attributes($post_id);

// include_once( get_stylesheet_directory() .'/inc/floor-pklans/nd-product-attributes.php.php');


 $bedroom = get_the_terms( $post_id, 'pa_bedrooms');
        $bathroom = get_the_terms($post_id, 'pa_bathrooms');
        $garage = get_the_terms($post_id, 'pa_garage');
        $living_rooms = get_the_terms($post_id, 'pa_living-rooms');
        $stories = get_the_terms($post_id, 'pa_storeys');
        $other_features = get_the_terms($post_id, 'pa_other-features');
        $property_status = get_the_terms($post_id, 'pa_property-status');
        $state = get_the_terms($post_id, 'pa_state');
        // $house_type = get_the_terms($post_id, 'pa_house-type');
        $house_type = get_the_terms($post_id, 'pa_house-type');
        $suburb = get_the_terms($post_id, 'pa_suburb');

        // Block Size
        $land_size = get_post_meta($post_id, 'wpcf-land_size',true);
        $land_size_depth = get_post_meta($post_id, 'wpcf-block_depth',true);
        $land_size_width = get_post_meta($post_id, 'wpcf-block_width',true);

        // House Size
        $house_size = get_post_meta($post_id, 'wpcf-house_size',true);
        $house_size_width = get_post_meta($post_id, 'wpcf-house-width',true);
        $house_size_height = get_post_meta($post_id, 'wpcf-house-depth',true);
        $ground_floor_area = get_post_meta($post_id, 'wpcf-ground-floor-area-sqm',true);
        $first_floor_area = get_post_meta($post_id, 'wpcf-first-floor-area-sqm',true);

        // Other Area
        $garage_area = get_post_meta($post_id, 'wpcf-garage-area-m',true);
        $alfresco_area = get_post_meta($post_id, 'wpcf-alfresco-area-sqm',true);
        $alfresco_granny_area = get_post_meta($post_id, 'wpcf-alfresco-granny-area-sqm',true);
        $loft_area = get_post_meta($post_id, 'wpcf-loft-area-sqm',true);
        $patio_area = get_post_meta($post_id, 'wpcf-patio-area-sqm',true);
        $porch_area = get_post_meta($post_id, 'wpcf-porch-area-m',true);
        // wpcf-
        


				$floor_plan_image_ids = get_post_meta($post_id,'wpcf-floor-plan-image');
                $floor_plan_images = array();
                foreach ($floor_plan_image_ids as $floor_plan){
                     $floor_plan_image_array = array();
                    $image_alt = get_post_meta( $floor_plan, '_wp_attachment_image_alt', true);
                    array_push($floor_plan_image_array, $image_alt);
                    $floor_plan_image = wp_get_attachment_image_url( (int)$floor_plan, 'large' );
                    array_push($floor_plan_image_array, $floor_plan_image);
                    // echo '<pre>';
                    // print_r($image_alt);
                    // var_dump($floor_plan_image);

                    // $floor_plan_image = wp_get_attachment_image_url( (int)$floor_plan, 'large' );
                    array_push($floor_plan_images, $floor_plan_image_array);
                }
                $floor_plan_image = wp_get_attachment_image_url((int)$floor_plan_image_ids[0],'large'); 

				// $image = wp_get_attachment_image_url( get_post_thumbnail_id( $slide ), 'large' );

				$floor_plan_pdf_id = get_post_meta($post_id,'wpcf-floor-plan-pdf');
				if($floor_plan_pdf_id){
				$floor_plan_pdf = wp_get_attachment_url( (int)$floor_plan_pdf_id[0] );
				// echo '<pre>';
				// var_dump($floor_plan_pdf);

				}
				// echo get_the_title( $post_id );


    }

if( $product->is_type( 'grouped' ) ) {

 $data .= '<div id="floorplan-'.$activeClass."-".$count.'" class="accordion-item">';
    $data .= '<h2 class="accordion-header" id="panelsStayOpen-heading'.$post_id.'">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse'.$post_id.'" aria-expanded="true" aria-controls="#panelsStayOpen-collapse'.$post_id.'">
        '.get_the_title( $post_id ).'
      </button>
    </h2>';
   
    $data .= '<div id="panelsStayOpen-collapse'.$post_id.'" class="row bg-primary1 py-3 accordion-collapse collapse '.$activeClass.'" aria-labelledby="#panelsStayOpen-heading'.$post_id.'"data-bs-parent11="#accordionExample">';
    
    $data .= '<div class="row py-31 accordion-body">';
    
    $data .= '<div class="col-md-12 col-lg-5 pl-4 pb-41">';

}
else{
   $data .= '<div class="accordion-body px-0">';
   $data .= '<div class="row pb-311">';
    $data .= '<div class="col-md-12 col-lg-5 pb-41">';
}

if($bedroom || $bathroom || $garage){
    $data .= '<h4 class="nd-specification-lbl">Specifications</h4>';
    $data .= '<div class="lead1 mb-3 nd-specification-number">';
    $data .= '<ul class="list list-unstyled mt-2 short-desc overflow-hidden">';
    if($bedroom){
        $data .= '<li><i class="fas fa-bed fa-xs ml-1 text-color-primary"></i> <span class="ml-1">'.$bedroom[0]->name.' <span class="nd-property-lbl-details bed small"></span></span></li>';
    }
    if($bathroom){
        $data .= '<li><i class="fas fa-shower fa-xs ml-1 text-color-primary"></i> <span class="ml-1">'.$bathroom[0]->name.' <span class="nd-property-lbl-details bath small"></span></span></li>';
    }
    if($garage){
        $data .= '<li><i class="fas fa-car fa-xs ml-1 text-color-primary"></i> <span class="ml-1">'.$garage[0]->name.' <span class="nd-property-lbl-details garage small"></span></span></li>';
    }
    if($stories){
        $data .= '<li><i class="fas fa-building fa-xs ml-1 text-color-primary"></i> <span class="ml-1"> <span class="nd-property-lbl-details garage small"></span>'.$stories[0]->name.'</span></li>';
    }
    $data .= '</ul>';
    $data .= '</div>';

}





    // $data .=  '<h4 class="vc_custom_heading align-left">Features</h4>';
if( $other_features && $other_features[0] != '' || $living_rooms || $stories )   {
    $data .=  '<h4 class="vc_custom_heading align-left">'.__( "Features", "porto" ).'</h4>';
    $data .=  '<div class="wpb_content_element overflow-hidden">';
    $data .= '<div class="table-responsive nd-table"><table><tbody>';


    if($living_rooms){
        $data .= '<tr><td>'.$living_rooms[0]->name.' Living Rooms</td></tr>';
    }

    if($other_features &&  $other_features[0] != ''){
          foreach( $other_features as $row ) {
                $item = $row;
                $data .= '<tr><td>'.$item->name.'</td></tr>';
            }
    }


  
        $data .= '</tbody></table></div></div>';
}

    // echo '</ul></div>';
    // echo'<div class="porto-separator  "><hr class="separator-line  align_center solid" style="background-color:#f5f5f5;"></div>';
    // $data .= '<tr><td>Private Living Space</td><td>'.$other_features[0]->name.' Sqm</td></tr>';

// if($min_block_depth_m || $total_block_area_sqm|| $total_home_area_sqm|| $total_block_area_sqm|| $min_house_size_sqm|| $max_house_size_sqm){

    $data .=  '<h4 class="vc_custom_heading align-left">'.__( "Area & Dimensions", "porto" ).'</h4>';


        // // Block Size
        // $land_size = get_post_meta($post_id, 'wpcf-land_size');
        // $land_size_depth = get_post_meta($post_id, 'wpcf-block_depth');
        // $land_size_width = get_post_meta($post_id, 'wpcf-block_width');

        // // House Size
        // $house_size = get_post_meta($post_id, 'wpcf-house_size');
        // $house_size_width = get_post_meta($post_id, 'wpcf-land_size');
        // $house_size_height = get_post_meta($post_id, 'wpcf-land_size');
        // $ground_floor_area = get_post_meta($post_id, 'wpcf-ground-floor-area-sqm');
        // $first_floor_area = get_post_meta($post_id, 'wpcf-first-floor-area-sqm');

        // // Other Area
        // $garage_area = get_post_meta($post_id, 'wpcf-garage-area-m');
        // $alfresco_area = get_post_meta($post_id, 'wpcf-alfresco-area-sqm');
        // $alfresco_granny_area = get_post_meta($post_id, 'wpcf-alfresco-granny-area-sqm');
        // $loft_area = get_post_meta($post_id, 'wpcf-loft-area-sqm');
        // $patio_area = get_post_meta($post_id, 'wpcf-patio-area-sqm');
        // $porch_area = get_post_meta($post_id, 'wpcf-porch-area-m');
        // // wpcf-



    if($land_size || $land_size_depth || $land_size_width){
        $data .=  '<h5 class="vc_custom_heading align-left">'.__( "Land Size", "porto" ).'</h5>';
    }

    $data .= '<div class="table-responsive nd-table mb-4"><table><tbody>';

    if($land_size){
        $data .= '<tr><td>Total Block | Lot Area (Sqm)</td><td>'.$land_size.'</td></tr>';
    }
    if($land_size_depth){
        $data .= '<tr><td>Block | Lot Depth (M)</td><td>'.$land_size_depth.'</td></tr>';
    }
    if($land_size_width){
        $data .= '<tr><td>Block | Lot Width (M)</td><td>'.$land_size_width.'</td></tr>';
    }
     $data .= '</tbody></table></div>';


     if($house_size || $house_size_width || $house_size_height || $ground_floor_area || $first_floor_area){
        $data .=  '<h5 class="vc_custom_heading align-left">'.__( "House Size", "porto" ).'</h5>';
    }


    $data .= '<div class="table-responsive nd-table mb-4"><table><tbody>';
    if($house_size){
        $data .= '<tr><td>Total Home Area (Sqm)</td><td>'.$house_size.'</td></tr>';
    }
    if($house_size_width){
        $data .= '<tr><td>House Width (M)</td><td>'.$house_size_width.'</td></tr>';
    }
    if($house_size_height){
        $data .= '<tr><td>House Depth (M)</td><td>'.$house_size_height.'</td></tr>';
    }

    if($ground_floor_area){
        $data .= '<tr><td>Ground Floor Area (Sqm)</td><td>'.$ground_floor_area.'</td></tr>';
    }
    if($first_floor_area){
        $data .= '<tr><td>First Floor Area (Sqm)</td><td>'.$first_floor_area.'</td></tr>';
    }
      $data .= '</tbody></table></div>';


    if($garage_area || $alfresco_area || $alfresco_granny_area || $loft_area || $patio_area || $porch_area){
        $data .=  '<h5 class="vc_custom_heading align-left">'.__( "Other", "porto" ).'</h5>';
    }

    $data .= '<div class="table-responsive nd-table"><table><tbody>';
    if($garage_area){
        $data .= '<tr><td>Garage Area (Sqm)</td><td>'.$garage_area.'</td></tr>';
    }
    if($alfresco_area){
        $data .= '<tr><td>Alfresco Area (Sqm)</td><td>'.$alfresco_area.'</td></tr>';
    }
    if($alfresco_granny_area){
        $data .= '<tr><td>Alfresco Granny Area (Sqm)</td><td>'.$alfresco_granny_area.'</td></tr>';
    }
    
    if($loft_area){
        $data .= '<tr><td>Loft Area (Sqm)</td><td>'.$loft_area.'</td></tr>';
    }
    if($patio_area){
        $data .= '<tr><td>Patio Area (Sqm)</td><td>'.$patio_area.'</td></tr>';
    }
    if($porch_area){
        $data .= '<tr><td>Porch Area (Sqm)</td><td>'.$porch_area.'</td></tr>';
    }



    // if($min_house_size_sqm){
    //     $data .= '<tr><td>Min House Size</td><td>'.$min_house_size_sqm[0]->name.' Sqm</td></tr>';
    // }
    // if($max_house_size_sqm){
    //     $data .= '<tr><td>Max House Size</td><td>'.$max_house_size_sqm[0]->name.' Sqm</td></tr>';
    // }

    $data .= '</tbody></table></div>';

// }




    
   

        // echo '<pre>';var_dump($other_features);die;





    
    // $data .= '<div class="table-responsive nd-table"><table><tbody>';


    // $data .= '</tbody></table></div>';

    if($floor_plan_pdf){

    
    $data .= '<div class="nd-button-container overflow-hidden py-3 py-xl-5">';
	$data .= '<a class="vc_btn3 vc_btn3-shape-default btn btn-modern btn-md btn-secondary mr-2" href="'.$floor_plan_pdf.'" title="" target="_blank">Download Floor Plan</a>';
    
    $data .= '</div>';
	}
	// $data .= '<a class="vc_btn3 vc_btn3-shape-default btn btn-modern btn-md btn-primary my-3" href="/designs/inclusions/" title="">View Inclusions</a>';

    $data .= '</div>';
    
if($floor_plan_images AND (count($floor_plan_images) > 0)) {


    $data .= '<div class="col-md-12 col-lg-7 nd-floor-plan-image mb-5">';


    $data .= '<h4 class="nd-specification-lbl">Floor Plan</h4>';
    $data .= '<div class="nav nav-tabs" id="nav-tab" role="tablist">';
    $count2 = 0;
     foreach ($floor_plan_images as $plan){
        $count2 ++;$active='';
        if($count2 == 1){
            $active = 'active';
        }
        if($plan[0] == ''){
            $label = $count2;
        }
        else{
             $label = $plan[0];
        }
        $count3 = $count .'-'.$count2;


        $data .= '<button class="nav-link text-dark '.$active.'" id="tab-'.$count3.'" data-bs-toggle="tab" data-bs-target="#floor-plan-'.$count3.'" type="button" role="tab" aria-controls="floor-plan-'.$count3.'" aria-selected="true">'.$label.'</button>';

  //        $data .= '<li class="nav-item" role="presentation">
  //   <button class="nav-link '.$active.'" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#floor-plan-'.$count.'" type="button" role="tab" aria-controls="pills-home" aria-selected="true">'.$label.'</button>
  // </li>';
        
    }
$data .= '</div><div class="tab-content" id="nav-tabContent">';
    $count2 = 0;
     foreach ($floor_plan_images as $plan){
        $count2 ++;$active='';
        if($count2 == 1){
            $active = 'active';
        }
        $count3 = $count .'-'.$count2;
  $data .= '<div class="tab-pane fade show '.$active.'" id="floor-plan-'.$count3.'" role="tabpanel" aria-labelledby="tab-'.$count3.'">';
$data .= '<div class="facade img-container mb-2">
                                    <a data-fancybox="gallery" href="'.$plan[1].'">
                                        <img class="img-fluid" src="'.$plan[1].'" ></a>    </div>';
   $data .= '</div>';
}
 

$data .= '</div>';
    
     // $data .= '<div class="facade img-container mb-2">
     //                                <a data-fancybox="gallery" href="'.$floor_plan_image.'">
     //                                    <img class="img-fluid" src="'.$floor_plan_image.'" ></a>    </div>';
    
    
    $data .= '</div>';
}



     $data .= '</div>';
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


// $data .= '<div class="porto-separator my-4 "><hr class="separator-line  align_center solid" style="background-color:#f5f5f5;"></div>';

if( $product->is_type( 'grouped' ) ) {


$data .= '</div>';
$data .= '</div>';

}
    return $data;
}

