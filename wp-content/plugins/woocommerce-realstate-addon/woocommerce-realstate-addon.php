<?php
/**
 * Plugin Name: WooCommerce Realstate Addon
 * Plugin URI: http://woocommerce.com/products/woocommerce-extension/
 * Description: Your extension's description text.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: http://yourdomain.com/
 * Developer: Your Name
 * Developer URI: http://yourdomain.com/
 * Text Domain: csp_woocommerce_realstate_addon
 * Domain Path: /languages
 *
 * Woo: 12345:342928dfsfhsf8429842374wdf4234sfd
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */



defined( 'ABSPATH' ) || exit;
// This check prevents the execution of your PHP files via direct browser execution. Consequently, they will be only executed from within the WordPress application environment.


//Define required constants
! defined( 'WC_REALSTATE_API' ) && define( 'WC_REALSTATE_API', false );
! defined( 'WC_REALSTATE_DIR' ) && define( 'WC_REALSTATE_DIR', plugin_dir_path( __FILE__ ) );
! defined( 'WC_REALSTATE_INC' ) && define( 'WC_REALSTATE_INC', WC_REALSTATE_DIR . 'includes/' );


if ( ! function_exists( 'csp_wc_realstate_addon_activate' ) ) {

    function csp_wc_realstate_addon_activate() {
        // Your activation logic goes here.

        if ( ! function_exists( 'WC' ) ) {
            add_action( 'admin_notices', 'wc_realstate_install_woocommerce_admin_notice' );
        } else {
            /**
             * Instance main plugin class
             */
            global $wc_realstate;

            // deactivate free version.
            // yith_wcan_deactivate_free_version();

            // load plugin text domain.
            load_plugin_textdomain( 'csp_woocommerce_realstate_addon', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

            // $wc_realstate = yith_wcan_initialize();
        }

    }
    register_activation_hook( __FILE__, 'csp_wc_realstate_addon_activate' );

}


if ( ! function_exists( 'csp_wc_realstate_addon_deactivate' ) ) {

function csp_wc_realstate_addon_deactivate() {
    // Your deactivation logic goes here.
}
register_deactivation_hook( __FILE__, 'csp_wc_realstate_addon_deactivate' );

}


if ( ! function_exists( 'wc_realstate_install_woocommerce_admin_notice' ) ) {
    /**
     * Print an admin notice if woocommerce is deactivated
     *
     * @return void
     *
     * @author Andrea Grillo <andrea.grillo@yithemes.com>
     * @since 1.0
     * @use admin_notices hooks
     */
    function wc_realstate_install_woocommerce_admin_notice() { ?>
        <div class="error">
            <p><?php echo esc_html_x( 'WooCommerce Realstate Addon is enabled but not effective. It requires WooCommerce in order to work.', '[Plugin Name]', 'csp_woocommerce_realstate_addon' ); ?></p>
        </div>
        <?php
    }
}





// Ref: https://wpswings.com/blog/create-woocommerce-plugin/?utm_source=mwb-blog&utm_medium=create-wc-plugin&utm_campaign=wpswings-mwb