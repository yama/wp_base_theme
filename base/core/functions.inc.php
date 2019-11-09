<?php

function post($key=null, $default=null) {
    global $post;
    if(!$post) {
        return $default;
    }
    if(!$key) {
        return $post;
    }
    if(!isset($post->$key)) {
        return $default;
    }
    return $post->$key;
}

function posts($args=array('post_status'=>'publish')) {
    $wp_query = new WP_Query( $args );
    if ( !$wp_query->have_posts() ) {
        return array();
    }
    $posts = array();
    foreach ($wp_query->posts as $post) {
        $posts[] = $post;
    }
    wp_reset_postdata();
    return $posts;
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
    $full_path = get_template_directory() . '/app/views/'.ltrim($tpl_path,'/');
    if(!is_file($full_path)) {
        exit('template not found!');
    }
    ob_start();
    include($full_path);
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

function remove_info() {
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'feed_links',2);
    remove_action('wp_head', 'feed_links_extra',3);
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'print_emoji_detection_script', 7 );
    remove_action('wp_head', 'wp_resource_hints');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script' );
    remove_action('admin_print_styles',  'print_emoji_styles' );
    add_filter('emoji_svg_url', '__return_false' );
}
