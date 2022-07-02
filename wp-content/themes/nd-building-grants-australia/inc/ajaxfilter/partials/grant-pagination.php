<?php
/**
 * Grant Pagination 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$page = !empty($_GET['page'])?$_GET['page']:1;
$cur_page = $page;
$page -= 1;
$per_page = $posts_per_page;
$count = $the_query->found_posts;
$total_pages = $the_query->max_num_pages;
$previous_btn = true;
$next_btn = true;

if ( $total_pages <= 1 ) {
	return;
}
$no_of_paginations = ceil($count / $per_page);


if ($cur_page >= 7) {
  $start_loop = $cur_page - 3;
  if ($no_of_paginations > $cur_page + 3)
    $end_loop = $cur_page + 3;
  else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
    $start_loop = $no_of_paginations - 6;
    $end_loop = $no_of_paginations;
  } else {
    $end_loop = $no_of_paginations;
  }
} else {
  $start_loop = 1;
  if ($no_of_paginations > 7)
    $end_loop = 7;
  else
    $end_loop = $no_of_paginations;
}
?>

<ul>
	
	<?php if ($previous_btn && $cur_page > 1) {?>
	<?php $pre = $cur_page - 1;?>
	<li p="<?php echo $pre;?>" class="active"><i class="far fa-angle-left"></i> Previous</li>
	<?php } else if ($previous_btn) { ?>
	<li class="inactive">Previous</li>
	<?php } ?>
	
	<?php for ($i = $start_loop; $i <= $end_loop; $i++) { ?>
		<?php if ($cur_page == $i){ ?>
		<li p="<?php echo $i;?>" class="selected" ><?php echo $i;?></li>
		<?php }else{?>
		<li p="<?php echo $i;?>" class="active"><?php echo $i;?></li>
		<?php } ?>
	<?php } ?>
	
	<?php if ($next_btn && $cur_page < $no_of_paginations) { ?>
	<?php $nex = $cur_page + 1; ?>
	<li p="<?php echo $nex;?>" class="active">Next <i class="far fa-angle-right"></i></li>
	<?php } else if ($next_btn) { ?>
	<li class="inactive">Next</li>
	<?php } ?>
	
</ul>

