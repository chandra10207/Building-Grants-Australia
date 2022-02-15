<?php
if ( ! is_admin() ) {
include_once( get_stylesheet_directory() .'/inc/house-and-land-package.php');
include_once( get_stylesheet_directory() .'/inc/product-single.php');
include_once( get_stylesheet_directory() .'/inc/single-design.php');
include_once( get_stylesheet_directory() .'/inc/display-homes.php');
}
add_filter( 'media_library_infinite_scrolling', '__return_true' );

function csp_b5f_increase_upload( $bytes )
{
    return 1554432; // 2 megabytes
}

add_filter( 'upload_size_limit', 'csp_b5f_increase_upload' );
add_filter( 'tribe_community_events_max_file_size_allowed', 'csp_b5f_increase_upload' );

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