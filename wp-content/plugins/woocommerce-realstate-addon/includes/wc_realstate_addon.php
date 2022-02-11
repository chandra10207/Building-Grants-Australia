<?php

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

if ( ! class_exists( 'WC_Realstate_Addon' ) ) :
 
    /**
     * My Extension core class
     */
    class WC_Realstate_Addon {npm
        /**
         * The single instance of the class.
         */
        protected static $_instance = null;
 
        /**
         * Constructor.
         */
        protected function __construct() {
            // Instantiation logic will go here.


        }
 
        /**
         * Main Extension Instance.
         * Ensures only one instance of the extension is loaded or can be loaded.
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
 
            return self::$_instance;
        }
 
        /**
         * Cloning is forbidden.
         */
        public function __clone() {
            // Override this PHP function to prevent unwanted copies of your instance.
            //   Implement your own error or use `wc_doing_it_wrong()`
        }
 
        /**
         * Unserializing instances of this class is forbidden.
         */
        public function __wakeup() {
            // Override this PHP function to prevent unwanted copies of your instance.
            //   Implement your own error or use `wc_doing_it_wrong()`
        }
    }
endif;
}


