<?php

function post($key=null, $default=null) {
    global $post;
    if(!$post) {
        return $default;
    }
    if(!$key) {
        return $post;
    }
    if(isset($post->$key)) {
        return $post->$key;
    }
    return $default;
}

function posts($args=array('post_status'=>'publish')) {
    $wp_query = new WP_Query( $args );
    if ( !$wp_query->have_posts() ) {
        return array();
    }
    return $wp_query->posts;
}

function route($route) {
    if($route === '/') {
        return (url()==='/' || strpos(url(),'/index.')===0);
    }
    if(substr($route,-1)==='*') {
        return (strpos(url(), substr($route,0,-1))===0);
    }
    return (url()===$route);
}

function datef($format, $datetime=null, $default='') {
    if(!$datetime) {
        return $default;
    }
    if($datetime === null) {
        $datetime = time();
    }
    if(!preg_match('/^[0-9]+$/', $datetime)) {
        $datetime = strtotime($datetime);
    }
    if(strpos($format,'%')!==false) {
        return strftime($format, $datetime);
    }
    return date($format, $datetime);
}

function get_included_contents($tpl_path) {
    ob_start();
    include(__DIR__ . '/' . $tpl_path);
    return ob_get_clean();
}

function ob_set() {
    ob_start();
    add_action(
        'shutdown'
        , function() {
            echo ob_get_clean();
        }
        , -1
    );
}

function url() {
    return $_SERVER['REQUEST_URI'];
}
