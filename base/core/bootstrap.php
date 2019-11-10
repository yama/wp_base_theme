<?php
if(is_file(get_template_directory() . '/app/extra_functions.php')) {
    include_once(get_template_directory() . '/app/extra_functions.php');
}
include_once(__DIR__ . '/functions.inc.php');
ob_set();
remove_info();
remove_filter('template_redirect', 'redirect_canonical');
