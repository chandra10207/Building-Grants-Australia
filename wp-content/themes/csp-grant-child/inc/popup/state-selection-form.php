<?php 
// add_rewrite_rule
// add_shortcode('nd_state_selection_form_popup', 'nd_state_selection_form_popup_func');

remove_action( 'woocommerce_no_products_found', 'wc_no_products_found' );
add_action('init', 'csp_start_session', 1000);
add_action('template_redirect', 'csp_add_seession_data_to_url', 999);
add_action( 'wp_footer', 'csp_open_popup_on_change_state_click', 500 );
add_action( 'woocommerce_no_products_found', 'csp_add_new_mesaage_if_no_product', 999);
add_action( 'gform_after_submission', 'csp_set_popup_cokie_after_state_select', 9999, 2 );
// add_action('init', 'csp_add_seession_data_to_url', 1001);

add_filter( 'gform_pre_render_2', 'csp_populate_states' );
add_filter( 'gform_pre_validation_2', 'csp_populate_states' );
add_filter( 'gform_pre_submission_filter_2', 'csp_populate_states' );
add_filter( 'gform_admin_pre_render_2', 'csp_populate_states' );
add_filter( 'porto_page_title', 'csp_filter_page_title_based_on_state_selection', 999);
// add_filter( 'yith_wcan_active_filter_labels', 'csp_reset_default_filters',999,2 );
// add_filter( 'yith_wcan_term_param_uri', 'csp_filter_yith_params', 10, 3 );




 
function csp_add_new_mesaage_if_no_product($wc_no_products_found){
 ?>
 <div class="no-data-found text-center py-5 overflow-hidden">
     <h4 class="py-4 text-center">No data found based on your prefered location</h4>
      <a class="nd-open-state-popup vc_btn3 vc_btn3-shape-default btn btn-modern btn-lg btn-primary" href="#" title="">Change Location</a>
</div>
  <?php
}


function csp_active_state_func(){

    if($_SESSION && isset($_SESSION['csp_user_state_location']) && $_SESSION['csp_user_state_location'] != ''){
        $location = $_SESSION['csp_user_state_location'];
    }
    else{
         $location = "Any";
    }

    ?>
    <div class="text-light ml-auto mr-2">Build in: <span class="text-uppercase"><?php echo $location; ?></span> (<a class="nd-open-state-popup" href="#">change</a>)</div>

    <?php

}



function csp_set_popup_cokie_after_state_select( $entry, $form ) {

    if($form['id'] == 2){
        global $wp;
        if($wp){
        // $current_url = home_url(add_query_arg(array(), $wp->request));
        $current_url = home_url( $wp->request );
        wp_redirect($current_url);
        exit;
        }

    }
}




function csp_open_popup_on_change_state_click() { ?>
    <script type="text/javascript">
jQuery(document).ready(function($){

    jQuery('.nd-open-state-popup').on('click', function (e) {
        e.preventDefault();
        PUM.open(3657);

    });

    jQuery('#gform_2 input[type=radio][name=input_7]').change(function() {
        $( "#gform_2 .gfield_radio" ).after( "<p class='pt-3'>Sending...</p>" );
        // alert("radio1 selected");
        $("#gform_2")[0].submit();
    });

});
    </script>

<?php 

// print_r($_SESSION['csp_user_state_location']);die;

if(
    !is_user_logged_in() AND
    $_SESSION AND 
    ($_SESSION['csp_user_state_location'] == '') AND
    // (is_shop() || is_front_page() || is_product_category() || is_product_tag()) 
    (is_front_page()) 
){
    ?>
   <script type="text/javascript">
jQuery(window).load(function($){
        setTimeout(
                      function() 
                      {
                        PUM.open(3657);
                      }, 10000);
      });

    </script>

<?php   
}

else
{
     ?>
   <!-- <script type="text/javascript">
jQuery(document).ready(function(){
    PUM.close(3657);
    });
    </script> -->

    <?php
}

}

function csp_start_session(){

    if(!session_id()) {
        session_start();
    }
    if(!isset($_SESSION['csp_user_state_location'])){
        $_SESSION['csp_user_state_location'] = '';
    }
    // $_SESSION['csp_user_state_location'] = '';
    if(isset($_REQUEST['gform_submit']) == "2"):
         // var_dump($_REQUEST['input_7']);
        $submitted_state = $_REQUEST['input_7'];
        $_SESSION['csp_user_state_location'] = $submitted_state;
    endif;
    add_shortcode('csp_active_state', 'csp_active_state_func');
}





function csp_filter_page_title_based_on_state_selection($title){

      if ($_GET AND isset($_GET['state']) ) {
        $title =  $title.' in '.strtoupper($_GET['state']);
    }

    elseif(($_SESSION AND isset($_SESSION['csp_user_state_location']) AND $_SESSION['csp_user_state_location'] != '' && $_SESSION['csp_user_state_location'] != 'any') && (is_shop() || is_product_category() || is_product_tag())){

       $title =  $title.' in '.strtoupper($_SESSION['csp_user_state_location']);
    }
    elseif( function_exists('wcfm_is_store_page') AND wcfm_is_store_page()){
        $title =  $title;
    }
    else{
         $title =  $title;
    }

    return $title; 
}


function csp_add_seession_data_to_url(){

    if(is_product_category() || is_product_tag()){

    global $wp;
    // $current_url = home_url(add_query_arg(array(), $wp->request));
    $current_url = home_url( $wp->request );
        // echo '<pre>'; var_dump($_SESSION);die;
    if($_SESSION && 
        isset($_SESSION['csp_user_state_location']) && 
        ($_SESSION['csp_user_state_location'] != 'any') && 
        ($_SESSION['csp_user_state_location'] != '') && 
        !$_GET['state'])
    {
        /*wp_safe_redirect( add_query_arg( 
            array(
                'yith_wcan' => '1',
                'filter_state' => $_SESSION['csp_user_state_location'],
                'query_type_state' => 'and'
             ), $current_url ) );*/

        wp_safe_redirect( add_query_arg( 
            array(
                'state' => $_SESSION['csp_user_state_location'],
             ), $current_url ) );

        exit;
    }

    // if ($_GET AND isset($_GET['filter_state']) ) {
    //     $_SESSION['csp_user_state_location'] = $_GET['filter_state'];
    // }

    }

}

function csp_populate_states( $form ) {
 
    foreach ( $form['fields'] as &$field ) {
 
        // if ( $field->type != 'select' || strpos( $field->cssClass, 'populate-posts' ) === false ) {
        //     continue;
        // }


    // echo '<pre>'; var_dump($field);die;

 
        // you can add additional parameters here to alter the posts that are retrieved
        // more info: http://codex.wordpress.org/Template_Tags/get_posts

        if ( $field->type == 'radio' &&  $field->label == 'State') {
 
        $choices = array();
//        $current_state = get_terms("pa_state");

            $current_state = csp_get_current_states();
//        echo '<pre>';
//        var_dump($sta$current_statetes);

 
        foreach ( $current_state as $state ) {
//            $choices[] = array( 'text' => $state->name, 'value' => $state->slug );
            $choices[] = array( 'text' => $state["region_code"], 'value' => strtolower($state["region_code"]) );
        }

//         $choices[] = array( 'text' => 'x', 'value' => 'any');
 
        // update 'Select a Post' to whatever you'd like the instructive option to be
        $field->placeholder = 'Select a current_state';
        $field->choices = $choices;
 
    }
}
 
    return $form;
}