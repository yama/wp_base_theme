<?php
/**
 * base functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package base
 */

if(is_admin( )) {
    return;
}

include_once(__DIR__ . '/core/functions.inc.php');

ob_set();
remove_info();
remove_filter('template_redirect', 'redirect_canonical');

// add_filter('post_link',     'rewrite_permalink');
// add_filter('the_permalink', 'rewrite_permalink');

// function rewrite_permalink($link) {
// 	return $link;
// }

