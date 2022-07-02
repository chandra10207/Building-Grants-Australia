<?php
//THIS HOOK MUST ENABLE
/*Restrict google map address from Australia only*/
// add_action('wp_head','nd_ced_map_location');

add_action( 'woocommerce_after_shop_loop_item_title', 'nd_add_bed_bath_garage_number', 21 );

/*Change default visual editor to text editor on WCFM Frontend builder*/
add_filter( 'wcfm_is_allow_wpeditor_quicktags', '__return_true' );
add_filter( 'woocommerce_price_trim_zeros', '__return_true' );
/*Add prefix to price*/
add_filter( 'woocommerce_get_price_html', 'bbloomer_add_price_prefix', 99, 2 );

/* Add single product value attributes to group product on save */
add_action('woocommerce_after_product_object_save', 'nd_update_group_product_attributes_after_save_func', 9993, 2);

/*Add child product attributes to group product on save*/
add_action( 'woocommerce_update_product', 'nd_update_group_product_attributes_on_child_update', 9994, 1 );

/* Add location to attributes on product save*/
add_action( 'gmw_lf_after_location_updated', 'nd_add_google_map_location_to_attributes_after_object_save', 9999, 2 );


// add_action('after_wcfm_products_manage_title', 'csp_after_wcfm_products_manage_title');


function csp_after_wcfm_products_manage_title()
{
echo '<div class="row"><div class="col-md-12"><span class="ced_product_create">Select listing type <span></div></div>';
}

function nd_update_group_product_attributes_after_save_func($product, $data_store)
{
    // exit if not the target post type
    if ('product' !== $product->post_type) {
        return;
    }
    if ($product->is_type('grouped')) {
        $group_product_id = $product->get_id();
        $child_product_ids = $product->get_children();
        $all_child_attributes_to_sync = [
            'bedrooms' => array(),
            'bathrooms' => array(),
            'garage' => array(),
            'house-size-sqm' => array(),
            'living-rooms' => array(),
            'total-lot-area-sqm' => array(),
        ];
//        $all_child_attributes_to_remove = $all_child_attributes_to_sync;

        if ($child_product_ids) {
            foreach ($child_product_ids as $child_product_id) {
                foreach ($all_child_attributes_to_sync as $attribute => $terms) {
                    $child_attribute_terms = wc_get_product_terms($child_product_id, 'pa_' . $attribute, array('fields' => 'names'));
                    $all_child_attributes_to_sync[$attribute] = array_unique(array_merge($terms, $child_attribute_terms));
                }
            }
        }

        foreach ($all_child_attributes_to_sync as $attribute => $terms) {
            $old_group_attributes_terms = wc_get_product_terms($group_product_id, 'pa_' . $attribute, array('fields' => 'names'));
            $new_child_attributes = $all_child_attributes_to_sync[$attribute];
            $attributes_to_remove = array_diff($old_group_attributes_terms, $new_child_attributes);
            $attributes_to_add = array_diff($new_child_attributes, $old_group_attributes_terms);
//            $all_child_attributes_to_remove[$attribute] = $attributes_to_remove;
            $all_child_attributes_to_sync[$attribute] = $attributes_to_add;
            if($attributes_to_remove){
                csp_remove_product_attributes($group_product_id,$attributes_to_remove,'pa_' . $attribute,);
            }
        }

        add_product_attribute($group_product_id, [
                'attributes' => $all_child_attributes_to_sync,
            ]
        );
    }
}

function csp_remove_product_attributes($product_id,$terms, $taxonomy ){
    if($product_id AND $terms AND $taxonomy){
        wp_remove_object_terms( $product_id, $terms, $taxonomy );
    }
}




function nd_sync_child_attribute_to_group($group_product_id, $attribute_slug){

        $group_product = wc_get_product($group_product_id);
        $child_product_ids = $group_product->get_children();
        $all_child_attributes = array();

        if($child_product_ids){

            foreach ($child_product_ids as $child_product_id) {
                $child_product = wc_get_product($child_product_id);
                $child_attributes = wc_get_product_terms( $child_product_id, $attribute_slug, array( 'fields' => 'names' ) );
                $all_child_attributes = array_unique(array_merge($all_child_attributes, $child_attributes));
            }

        }
//     echo 'qwefqef <pre>';var_dump($all_child_attributes); echo '</pre>';die;

//        $_product_attributes = get_post_meta($group_product_id, '_product_attributes', true);
//         echo 'qwefqef <pre>';var_dump($_product_attributes); echo '</pre>';die;

        if ($all_child_attributes) {
            $group_attributes_terms = wc_get_product_terms( $group_product_id, $attribute_slug, array( 'fields' => 'names' ) );
            if($group_attributes_terms){
                wp_remove_object_terms( $group_product_id, $group_attributes_terms, $attribute_slug );
            }
            foreach ($all_child_attributes as $attr) {
                wp_set_object_terms($group_product_id, $attr, $attribute_slug, true);
//                $data = csp_update_location_to_attributes($group_product_id, $all_child_attributes, $attribute_slug);
//                $_product_attributes = array_merge($_product_attributes, $data);
            }
//            update_post_meta($group_product_id, '_product_attributes', $_product_attributes);

        }

}



function nd_update_group_product_attributes_on_child_update($product_id) {

    $product = wc_get_product( $product_id );
     // exit if not the target product category type
    if ( !$product->is_type('simple') || !has_term( 'home-design-floor-plans', 'product_cat' , $product_id )) {
        return;
    }

     $group_args = array(
       'post_type' => 'product',
       'meta_query' => array(
         array(
           'key' => '_children',
           'value' => 'i:' . $product->get_id() . ';',
           'compare' => 'LIKE',
         )
         ),
         'fields' => 'ids'
      );
     $parent_ids = get_posts( $group_args );
     if($parent_ids){
    // echo 'qwefqef <pre>';var_dump($parent_ids); echo '</pre>';die;
        foreach ($parent_ids as $parent_grouped_id){
             if($parent_grouped_id){
                $parent_product = wc_get_product( $parent_grouped_id );
                if($parent_product){
                    $parent_product->save();
                }
            }
        }
     }
}

function wc_get_first_parent($prod_id) {
     $group_args = array(
       'post_type' => 'product',
       'meta_query' => array(
         array(
           'key' => '_children',
           'value' => 'i:' . $prod_id . ';',
           'compare' => 'LIKE',
         )
         ),
         'fields' => 'ids' // THIS LINE FILTERS THE SELECT SQL
      );
     $parents = get_posts( $group_args );
     return count($parents) > 0 ? array_shift($parents) : false;
   }



function get_parent_grouped_id( $children_id ){
    global $wpdb;
    $results = $wpdb->get_col("SELECT post_id FROM {$wpdb->prefix}postmeta
        WHERE meta_key = '_children' AND meta_value LIKE '%$children_id%'");
    // Will only return one product Id or false if there is zero or many
    return sizeof($results) == 1 ? reset($results) : false;
}

function csp_update_location_to_attributes($post_id, $value, $taxonomy){

    wp_set_object_terms( $post_id, $value, $taxonomy,false );
     $data = array(
         $taxonomy => array(
             'name' => $taxonomy,
             'value' => $value,
             'is_visible' => '1',
             'is_variation' => '0',
             'is_taxonomy' => '1'
         )
     );
     return $data;
}


function nd_add_google_map_location_to_attributes_after_object_save($location, $wcfm_data) {
//    $product = wc_get_product( $location['object_id'] );
//    $_product_attributes = $product->get_attributes();
//    $_product_attributes = get_post_meta($location['object_id'], '_product_attributes', true);
//    echo 'hello';
//    print_r($_product_attributes);die;
    if($location){
        $full_address = $location['city'].' '.$location['region_code'].', '.$location['postcode'];

        add_product_attribute($location['object_id'], [
        'attributes' => [
            'suburb' => array( $full_address),
            'state' => array($location['region_code']),
                ],

            ]
        );
//        $data = csp_update_location_to_attributes( $location['object_id'], $full_address, 'pa_suburb' );
//        $_product_attributes = array_merge($_product_attributes, $data);
//        $data = csp_update_location_to_attributes( $location['object_id'], $location['region_code'], 'pa_state' );
//        $_product_attributes = array_merge($_product_attributes, $data);
//        update_post_meta($location['object_id'], '_product_attributes', $_product_attributes);
    }


}





function nd_add_google_map_location_to_attributes($product_id) {

    $location = gmw_get_post_location( $product_id );
//    if($location){
//         echo 'qwefqef <pre>';var_dump($location); echo '</pre>';die;
//
//        $full_address = $location->city.' '.$location->region_code.', '.$location->postcode;
//        wp_set_object_terms( $product_id, $full_address, 'pa_suburb',false );
//        wp_set_object_terms( $product_id, $location->region_code, 'pa_state',false );
//    }

    $_product_attributes = get_post_meta($product_id, '_product_attributes', TRUE);
    if($location){

        $full_address = $location['city'].' '.$location['region_code'].', '.$location['postcode'];
        $data = csp_update_location_to_attributes( $product_id, $full_address, 'pa_suburb' );
        $_product_attributes = array_merge($_product_attributes, $data);
        $data = csp_update_location_to_attributes( $product_id, $location['region_code'], 'pa_state' );
        $_product_attributes = array_merge($_product_attributes, $data);
        update_post_meta($location['object_id'], '_product_attributes', $_product_attributes);
    }


}


// add_action( 'wp_insert_post', 'new_book', 1001, 3 );



function nd_ced_map_location(){
    ?>
    <script>
        jQuery(document).ready(function(){
    function initialize() {
  var options = {
    componentRestrictions: {country: 'au'}
  };

  var input = document.getElementById('gmw-lf-address');  //here you have to use youir feild ID 
  var autocomplete = new google.maps.places.Autocomplete(input, options);
}

google.maps.event.addDomListener(window, 'load', initialize);
       })
   </script>
   <?php
}


  
function bbloomer_add_price_prefix( $price, $product ){

    // if ( has_term( 'rebates', 'product_cat' ) ) {
    //     $price = '<small>From </small> ' . $price;
    // }
    

    if($price){
        $price = '<small>From </small> ' . $price;
    }
    
    return $price;
}




// Product manage path
// /Applications/Ampps/www/grant/wp-content/plugins/wc-frontend-manager/controllers/products-manager/wcfm-controller-products-manage.php

// add_filter( 'wcfm_product_content_before_save', 'nd_products_data_filter_before_save', 10,3 );

function nd_products_data_filter_before_save ($post_data, $wcfm_products_manage_form_data)
{
	echo '<pre>';
	print_r($wcfm_products_manage_form_data);

	die;

}


// add_filter( 'gmw_lf_location_args_before_location_updated', 'nd_ad_wp_geo_data_to_attriubutes', 10,3 );

function nd_ad_wp_geo_data_to_attriubutes ($location_args, $location, $wcfm_products_manage_form_data)
{
	echo '<pre>';
	print_r($location_args);
	echo '<pre>';
	print_r($wcfm_products_manage_form_data);

	// die;

}







function nd_add_land_measurement_details(){
    global $product;
    $product_id = $product->get_id();
    $house_size = get_the_terms( $product_id , 'pa_house-size-sqm');
    $lot_size = get_the_terms( $product_id , 'pa_total-lot-area-sqm');
//  $house_name = get_field('house_name', $post_id);
//  echo '<pre>';
//  print_r($house_size);
    $data = '';
    $data .= '<div class="lead1 mb-0 nd-specification-number1 nd_land_measurement_details">';
    $data .= '<ul class="list list-unstyled mt-2 short-desc1 overflow-hidden">';
//     if($house_name){
//         $data .= '<li class="mb-1"><strong>'.$house_name.'</strong></li>';
//     }
    if($house_size){
        $data .= '<li class="mb-1"><strong>House Size: </strong> <span class="m1l-1">'.$house_size[0]->name.' <span class="nd-property-lbl-details bed"></span>m<sup>2</sup></span></li>';
    }
    if($lot_size){
        $data .= '<li class="mb-1"><strong>Lot Size: </strong> <span class="m1l-1">'.$lot_size[0]->name.' <span class="nd-property-lbl-details bath"></span>m<sup>2</sup></span></li>';
    }
    $data .= '</ul>';
    $data .= '</div>';
    echo $data;
}


function nd_add_bed_bath_garage_number(){
    global $product;
    $lot_size = '';
    $product_id = $product->get_id();
    $bed_num = get_the_terms( $product_id , 'pa_bedrooms');
    $bath_num = get_the_terms( $product_id , 'pa_bathrooms');
    $garage_num = get_the_terms( $product_id , 'pa_garage');
    // $lot_size = get_the_terms( $product_id , 'pa_total-block-area-sqm');
    $location = gmw_get_post_location( $product_id );
    $child_product_ids = [];

    if ($product->is_type('grouped')) {

        $child_product_ids = $product->get_children();

    }
    // $federals





    // $address = get_post_meta( $post_id, 'location_address', true );
    // echo '<pre>';var_dump($location) ;die;
    // echo '<p><i class="fas fa-map-marker-alt fa-xs1 mr-2 ml-1 color-primary"></i>'. $location->address. '</p>';
// 

//  $house_name = get_field('house_name', $post_id);
//  $location = get_field('full_address', $post_id);
    // echo '<pre>';
    // print_r($bed_num);
    
    
    
    $data = '';
    if($location){
        $data .= '<p class="my-3"><i class="fas fa-map-marker-alt"></i> <span class="ml-1">'.$location->formatted_address.' <span class="nd-property-lbl-details bed"></span></span></p>';
    }

    
//  if ( is_single() ){
        
//     if($house_name){
//         $data .= '<p class="my-3"><span class="ml-1"><strong>'.$house_name.' </strong></span></p>';
//     }
//      $data .= wc_get_product_category_list( $product->get_id(), ', ', '<p class="nd_design_type lead1 mb-2 text-dark overflow-hidden"> Type: ' . _n( ' ', ' ', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</p>' );
//  }
    
    $data .= '<div class="lead1 mb-0 nd-specification-number nd-beds-numbers-house-and-land">';
    $data .= '<ul class="list list-unstyled mt-2 short-desc1 overflow-hidden">';
    if($bed_num){
        $bed = [];
        foreach($bed_num as $bed_num){
            array_push($bed, $bed_num->name);
        }
        $data .= '<li><i class="fas fa-bed fa-xs1 ml-1 text-color-primary"></i> <span class="ml-1">'.implode(",", $bed).' <span class="nd-property-lbl-details bed"></span></span></li>';
    }
    if($bath_num){

        $bath = [];
        foreach($bath_num as $bath_num){
            array_push($bath, $bath_num->name);
        }

        $data .= '<li><i class="fas fa-shower fa-xs1 ml-1 text-color-primary"></i> <span class="ml-1">'.implode(",", $bath).' <span class="nd-property-lbl-details bath"></span></span></li>';
    }
    if($garage_num){

        $garage = [];
        foreach($garage_num as $garage_num){
            array_push($garage, $garage_num->name);
        }
        $data .= '<li><i class="fas fa-car fa-xs1 ml-1 text-color-primary"></i> <span class="ml-1">'.implode(",", $garage).' <span class="nd-property-lbl-details garage"></span></span></li>';
    }
    if($lot_size){
        $data .= '<li class="mb-1"><i class="text-color-primary fas fa-home1 fa-vector-square1 fa-expand-arrows-alt font-size-lg fa-xs ml-1"></i> <span class="m1l-1">'.$lot_size[0]->name.' <span class="nd-property-lbl-details bath"></span>m<sup>2</sup></span></li>';
    }
        
    $data .= '</ul>';

    if(count($child_product_ids) > 0){

        $data .= '<p><span class="ml-1">'.count($child_product_ids).' <span class="nd-property-lbl-details bed"></span> variations available</span></p>';
    }
    $data .= '</div>';
    
    echo $data;
}




// add_action( 'save_post', 'custom_process_state_save_post', 10,3 );

function custom_process_state_save_post( $post_id, $post, $update ) {

// echo 'here'; var_dump($post);die;

    // exit if not the target post type
    if ( ('product' !== $post->post_type) OR ($post->post_status == 'auto-draft') OR (empty($_POST)) ) {
        return;
    }

    // current post ID
    $post_id = $post->ID;
    // target taxonomy slug
    $taxonomy_slug = 'pa_suburb';

    // var_dump($_POST['wcfm_products_manage_form']);die;

    // $_POST['wpcf-state'] value exists, it means that post is being added using the front-end form
    if(!empty($_POST['wpcf-state'])) {
        // get the term's name from the form's state field
        $term_name = $_POST['wpcf-state'];
        // call the function that processes the taxonomy attachment
        custom_process_new_state_term($post_id, $term_name, $taxonomy_slug);
    }
    // $_POST['wpcf']['state'] value exists, it means that post is being added using the back-end post edit screen
    if(!empty($_POST['wpcf']['state'])) {
        // get the term's name from the state field
        $term_name = $_POST['wpcf']['state'];
        // call the function that processes the taxonomy attachment
        custom_process_new_state_term($post_id, $term_name, $taxonomy_slug);
    }
}
 
// function that processes the taxonomy attachment
function custom_process_new_state_term($post_id, $term_name, $taxonomy_slug) {
    // check if the term already exists
    $term = term_exists( $term_name, $taxonomy_slug );
    // if does exist
    if ( $term !== 0 && $term !== null ) {
        // attach it to the current post
        wp_set_post_terms( $post_id, $term['term_id'], $taxonomy_slug );
    }
    // if doesn't exist
    else
    {   
        // first add it as a new term in the taxonomy
        $inset_new_term = wp_insert_term($term_name, $taxonomy_slug);
        if ( ! is_wp_error( $inset_new_term ) )
        {
            // and then attach this new term to the current post
            $term_id = $inset_new_term['term_id'];
            wp_set_post_terms( $post_id, $term_id, $taxonomy_slug );
        }
    }
}



/* Functions to store product attributes */
//https://stackoverflow.com/questions/63570292/how-can-i-add-new-attribute-to-existing-product-in-woocommerce-with-native-funct


//add_product_attribute(76, [
//        'attributes' => [
//            'Attribute 1' => ['Value 1', 'Value 2'],
//            'Attribute 2' => ['Value 4', 'Value 5'],
//        ],
//
//    ]
//);

function get_attribute_id_from_name($name)
{
    global $wpdb;
    $attribute_id = $wpdb->get_var("SELECT attribute_id
    FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
    WHERE attribute_name LIKE '$name'");
    return $attribute_id;
}


function save_product_attribute_from_name($name, $label = '', $set = true)
{
    if (!function_exists('get_attribute_id_from_name')){
        return;
    }

    global $wpdb;

    $label = $label == '' ? ucfirst($name) : $label;
    $attribute_id = get_attribute_id_from_name($name);
    $taxonomy = wc_attribute_taxonomy_name($name); // The taxonomy slug

    if (empty($attribute_id)) {
        $attribute_id = NULL;
    }
    else {
        $set = false;
    }

    //register existing taxonomy
    if (isset($attribute_id) && !taxonomy_exists($taxonomy)) {
        register_attribute($name);
    }

    $args = array(
        'attribute_id'      => $attribute_id,
        'attribute_name'    => $name,
        'attribute_label'   => $label,
        'attribute_type'    => 'select',
        'attribute_orderby' => 'menu_order',
        'attribute_public'  => 0,
    );


    if (empty($attribute_id)) {
        $wpdb->insert("{$wpdb->prefix}woocommerce_attribute_taxonomies", $args);
        set_transient('wc_attribute_taxonomies', false);
    }

    if ($set) {

        $attributes = wc_get_attribute_taxonomies();
        $args['attribute_id'] = get_attribute_id_from_name($name);
        $attributes[] = (object)$args;
        //print_r($attributes);
        set_transient('wc_attribute_taxonomies', $attributes);
    } else {
        return;
    }
}

function register_attribute($name)
{
    $taxonomy = wc_attribute_taxonomy_name($name); // The taxonomy slug
    $attr_label = ucfirst($name); // attribute label name
    $attr_name = (wc_sanitize_taxonomy_name($name)); // attribute slug

    register_taxonomy(
        $taxonomy,
        'product',
        array(
            'label'        => __($attr_label),
            'rewrite'      => array('slug' => $attr_name),
            'hierarchical' => true,
        )
    );

}

function add_product_attribute($product_id, $data)
{

    $_pf = new WC_Product_Factory();
    $product = $_pf->get_product($product_id);

    $product_attributes = get_post_meta( $product_id, '_product_attributes', true );
    if($product_attributes == ''){
        $product_attributes = array();
    }
    $is_append  = false;
    $append_exclude = array('suburb','state');


    foreach ($data['attributes'] as $key => $terms) {

        if ($product->is_type('grouped')  AND !in_array($key, $append_exclude) ) {
            $is_append  = true;
        }

        $taxonomy = wc_attribute_taxonomy_name($key); // The taxonomy slug
        $attr_label = ucfirst($key); // attribute label name
        $attr_name = (wc_sanitize_taxonomy_name($key)); // attribute slug

        // NEW Attributes: Register and save them
        if (!taxonomy_exists($key))
            save_product_attribute_from_name($attr_name, $attr_label);

        $product_attributes[$taxonomy] = array(
            'name'         => $taxonomy,
            'value'        => '',
            'position'     => '',
            'is_visible'   => 1,
            'is_variation' => 0,
            'is_taxonomy'  => 1
        );

        foreach ($terms as $value) {
            $term_name = ucfirst($value);
            $term_slug = sanitize_title($value);

            // Check if the Term name exist and if not we create it.
            if (!term_exists($value, $taxonomy))
                wp_insert_term($term_name, $taxonomy, array('slug' => $term_slug)); // Create the term
            // Set attribute values
            wp_set_object_terms($product_id, $term_name, $taxonomy, $is_append);

        }

//        $product->set_attributes(array($product_attributes));
    }
    update_post_meta($product_id, '_product_attributes', $product_attributes);


}


