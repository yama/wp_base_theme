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

include_once(__DIR__ . '/core/bootstrap.php');
