<?php
include_once(get_stylesheet_directory() .'/porto_feature/grant_meta.php');
include_once( get_stylesheet_directory() .'/inc/grant-functions.php');
include_once( get_stylesheet_directory() .'/inc/global-woocommerce-hooks.php');
include_once( get_stylesheet_directory() .'/inc/archive-product.php');
include_once( get_stylesheet_directory() .'/inc/single-product.php');
include_once( get_stylesheet_directory() .'/inc/single-product-floor-plan.php');
include_once( get_stylesheet_directory() .'/inc/display-homes.php');
require_once('inc/ajaxfilter/woocommerce-filter-hook.php');

if ( ! is_admin() ) {
include_once( get_stylesheet_directory() .'/inc/home-design.php');
include_once( get_stylesheet_directory() .'/inc/popup/state-selection-form.php');
//include_once( get_stylesheet_directory() .'/inc/popup/bootstrap-state-popup.php');
include_once( get_stylesheet_directory() .'/inc/wcfm/wcfm-hooks.php');
	}


function csp_get_current_states(){
    global $wpdb;
    $results = $wpdb->get_results(
        $wpdb->prepare( "SELECT DISTINCT region_code FROM {$wpdb->prefix}gmw_locations"),
        ARRAY_A
    );
    return $results;
}

function nd_year_shortcode() {
  $year = date('Y');
  return $year;
}
add_shortcode('nd_year', 'nd_year_shortcode');

add_action('wcfm_init',function(){
	global $WCFM; 
	remove_action('wcfmmp_store_list_after_store_info', array( $WCFM->wcfm_enquiry, 'wcfmmp_store_list_enquiry_button' ), 35 ); 
});


//add_action('init', 'nd_get_grant_data');
function nd_get_grant_data(){

    if(!is_admin()){

        $grant_ids = get_grant_ids_by_state_and_price('nsw','40000');
        var_dump($grant_ids);
    }
}




function my_admin_bar_render() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
    $wp_admin_bar->remove_menu('easy-updates-manager-admin-bar');
}
add_action( 'wp_before_admin_bar_render', 'my_admin_bar_render' );

add_filter( 'media_library_infinite_scrolling', '__return_true' );

function csp_b5f_increase_upload( $bytes )
{
    return 3554432; // 2 megabytes
}

// add_filter( 'upload_size_limit', 'csp_b5f_increase_upload' );
// add_filter( 'tribe_community_events_max_file_size_allowed', 'csp_b5f_increase_upload' );

add_action( 'wp_enqueue_scripts', 'porto_child_css', 1002 );

// Load CSS

function porto_child_css() {
	// porto child theme styles
	wp_deregister_style( 'styles-child' );
	wp_register_style( 'styles-child', esc_url( get_stylesheet_directory_uri() ) . '/style.css' );
	wp_enqueue_style( 'styles-child' );


	wp_deregister_style( 'wcfm-nd-custom' );
	wp_register_style( 'wcfm-nd-custom', esc_url( get_stylesheet_directory_uri() ) . '/wcfm-custom.css' );
	wp_enqueue_style( 'wcfm-nd-custom' );



	if ( is_rtl() ) {
		wp_deregister_style( 'styles-child-rtl' );
		wp_register_style( 'styles-child-rtl', esc_url( get_stylesheet_directory_uri() ) . '/style_rtl.css' );
		wp_enqueue_style( 'styles-child-rtl' );
	}



		wp_register_script('nd_custom', esc_url( get_stylesheet_directory_uri() ) . '/assets/js/custom.js', array('jquery'), '1.0', true );  
		wp_enqueue_script('nd_custom');
}

function nd_google_tagmanager_script_front() {

if( is_front_page() || is_page('41') )
{
?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-K93GJKJ');</script>
<!-- End Google Tag Manager -->
<!-- Many Chat -->
<script src="//widget.manychat.com/123181625848114.js" async="async"></script>
<!-- Many Chat -->
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '329976558072039');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=329976558072039&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
<?php
}
}
add_action('wp_head', 'nd_google_tagmanager_script_front');

function nd_google_tagmanager_script_footer_front() {
if( is_front_page() || is_page('41') )
{
?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K93GJKJ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php
}
}

add_action('wp_footer', 'nd_google_tagmanager_script_footer_front');

function nd_google_tagmanager_script_solar() {

if( is_page('3091') )
{
?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KJ2BNZR');</script>
<!-- End Google Tag Manager -->
<?php
}
}
add_action('wp_head', 'nd_google_tagmanager_script_solar');


function nd_google_tagmanager_script_footer_solar() {
if( is_page('3091') )
{
?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KJ2BNZR"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php
}
}
add_action('wp_footer', 'nd_google_tagmanager_script_footer_solar');

add_filter( 'wcfm_is_allow_product_toolset_field_group', function($is_allowed, $field_group_index, $field_group ) {
    if( isset($field_group['slug']) && $field_group['slug'] == 'toolset-woocommerce-fields') {
        return false;
    }
    return $is_allowed;
}, 10, 3 );