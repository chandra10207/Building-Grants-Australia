<?php
/**
 * Check for available grants on grants post type by state and price
 *
 * @param  string $state, string $price
 * @return array
 */
function get_grant_ids_by_state_and_price($state, $price)
{
    $grant_category_slug = [];
    $grant_category_slug[] = 'grants-available-in-'.$state;
    $price = intval($price);
    $taxonomy = 'grant-type';
    global $wpdb;
    $query = $wpdb->prepare("SELECT p.ID,p.post_title FROM " . $wpdb->posts . " AS p
        INNER JOIN " . $wpdb->term_relationships . " AS tr ON tr.object_id = p.ID
        INNER JOIN " . $wpdb->term_taxonomy . " AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        INNER JOIN " . $wpdb->terms . " AS t ON t.term_id = tt.term_id
        INNER JOIN " . $wpdb->postmeta. " AS pm ON pm.post_id = p.ID
        WHERE p.post_status = 'publish'
        AND  tt.taxonomy = %s          
        AND t.slug IN('%s')
        AND ( pm.meta_key = 'wpcf-max-price' AND pm.meta_value > %d )
        AND p.post_type = 'grant' 
        ORDER BY p.post_date DESC", $taxonomy, implode("','", $grant_category_slug), $price);

    return $wpdb->get_results($query,ARRAY_A );
}

