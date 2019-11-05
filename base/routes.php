<?php

if(!defined('WPINC')) exit('error');

if(route('/')) {
    return get_included_contents('tpl/sample/index.html');
}

if(route('/*') && !is_404()) {
    return get_included_contents('tpl/sample/detail.html');
}

header('HTTP/1.0 404 Not Found');
return get_included_contents('tpl/404.html');
