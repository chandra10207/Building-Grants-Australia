<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$term_name =  get_queried_object()->name?get_queried_object()->name:"results"; 
$count = $the_query->found_posts;
$per_page = $posts_per_page;
$current = !empty($_GET['page'])?$_GET['page']:1;
?>
<p class="woocommerce-result-count">
	<?php
	// phpcs:disable WordPress.Security
	if ( 1 === intval( $count ) ) {
		_e( 'Showing the single '.rtrim($term_name,'s'), 'woocommerce' );
	} elseif ( $count <= $per_page || -1 === $per_page ) {
		/* translators: %d: total results */
		printf( _n( 'Showing all %d '.rtrim($term_name,'s'), 'Showing all %d '.$term_name, $count, 'woocommerce' ), $count );
	} else {
		$first = ( $per_page * $current ) - $per_page + 1;
		$last  = min( $count, $per_page * $current );
		/* translators: 1: first result 2: last result 3: total results */
		printf( _nx( 'Showing %1$d&ndash;%2$d of %3$d '.rtrim($term_name,'s'), 'Showing %1$d&ndash;%2$d of %3$d '.$term_name, $count, 'with first and last '.rtrim($term_name,'s'), 'woocommerce' ), $first, $last, $count );
	}
	// phpcs:enable WordPress.Security
	?>
	<?php if(isset($_GET) && count($_GET) > 1){?> <span class="gt-reset-filters">Reset Filters</span><?php } ?>
</p>
