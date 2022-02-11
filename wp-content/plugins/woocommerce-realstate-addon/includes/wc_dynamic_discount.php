<?php

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
{


  // Put your plugin code here
  if ( ! class_exists('WC_Dynamic_Discounts'))
  {

        class WC_Dynamic_Discounts{

			public function __construct(){
				
				add_action('edit_user_profile', array( $this, 'show_discount'), 10, 1);
				add_action( 'edit_user_profile_update', array( $this, 'save_discount'), 10, 1 );
				add_filter( 'woocommerce_product_get_price', array( $this, 'show_dynamic_price' ), 10, 2);
			}

			public function show_discount($user) {
			?>
				<table>
					<tr>
					<th>Discount</th>
						<td>
						<input type="number" id="show_discount" name="show_discount" min="0" max="100" value="<?php echo get_user_meta($user->ID, 'show_user_discount', true)  ?>">
						</td>
					<?php
					if ( ! current_user_can( 'edit_user') ) {
					<td>
					<input type="submit" id="submit" name="submit">
					</td>
					?>
					</tr>
				</table>
				<?php
				}

				public function save_discount($user) {

				if ( ! current_user_can( 'edit_user') ) {
				return false;
				}

				if(isset($_POST['show_discount'])) {
				$show_user_discount = $_POST['show_discount'];
				update_user_meta($user, 'show_discount', $show_user_discount);
				}
				}

				//Add within the class body
				 public function show_dynamic_price($price, $product) {

				      $current_user_id = get_current_user_id();
				      $discount = floatval(get_user_meta($current_user_id,'show_discount', true));

				      if(!empty($discount)) {
				            // Discount is a value between 0 to 100 in percentage
				            $dynamic_price = $price - (($price * $discount)/100);
				            return $dynamic_price;
				      }
				}







				}
 		 }


	}


}

