<?php
/**
 * base functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package base
 */

include_once(__DIR__ . '/core/functions.inc.php');

if(!is_admin( )) {
    ob_set();
}

// 既存の静的htmlサイトと混在させる時に必要
remove_filter('template_redirect', 'redirect_canonical');

// add_filter('post_link',     'rewrite_permalink');
// add_filter('the_permalink', 'rewrite_permalink');
