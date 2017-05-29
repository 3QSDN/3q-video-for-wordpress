<?php
/**
 * @package sdnPlayer
 * @version 1.2
 */
/*
Plugin Name: 3q video player for wordpress
Plugin URI: https://www.3qsdn.com
Description: Embed Videos from 3Q SDN
Author: Julius Thomas
Version: 1.2
*/

$plugin_dir = plugin_dir_path( __FILE__ );

/* Register the scripts and enqueue css files */
function register_sdn(){

    $options = get_option('sdn_options');
    wp_register_style( 'sdn-source', 'https://playout.3qsdn.com/player/css/player.css', null, null, false);
    wp_register_script( 'sdn-source1', 'https://playout.3qsdn.com/player/js/sdnplayer.js', null, null, false);
    wp_register_script( 'sdn3', plugins_url( 'js/sdn-plugin.js' , __FILE__ ));
    wp_enqueue_style( 'sdn-source' );
    wp_enqueue_script( 'sdn-source1' );

}

add_action( 'wp_enqueue_scripts', 'register_sdn' );
function add_sdn_header(){
    wp_enqueue_script( 'sdn3' );
}

/* [sdn] */
function sdn_shortcode($atts, $content=null){

    $_userToken = 0;
    add_sdn_header();

    $options = get_option('sdn_options'); //load the defaults

    extract(shortcode_atts(array(
        'sdnPlayoutId' => $options['data-id'],
        'width' => $options['width'],
        'height' => $options['height'],
        'thumb' => $options['thumb'],
        'usertoken' => $options['usertoken'],
        'autoplay' => $options['autoplay'],
        'vast' => $options['vast'],
        'layout' => $options['responsive'],
    ), $atts));
    if(!empty($atts["usertoken"])) {
        $_userToken = $atts["usertoken"];
    }
    if(empty($atts["autoplay"])) {
        $atts["autoplay"] = 'false';
    }
    if(empty($atts["vast"])) {
        $atts["vast"] = '';
    }

    if(!empty($atts["layout"])) {
        $atts["width"] = '100%25';
        $atts["width"] = '360';
    }

    $id = 'player_'.generateHash();

    if(!empty($atts["sdn_thumb"]) && $atts["sdn_thumb"] == true) {

    $sdnplayer = '
        <div id="'.$id.'" style="width:'.$atts["width"].' height="'.$atts["height"].'"></div>
        <script type="text/javascript" src="//playout.3qsdn.com/'.$atts["data-id"].'?&js=true&container=sdnPlayer&autoplay=false&width='.$atts["width"].'&height='.$atts["height"].'&preload=false"></script>
		';
    } else {

    $sdnplayer = '
        <div id="'.$id.'"></div
        <script type="text/javascript" src="//playout.3qsdn.com/'.$atts["data-id"].'?&js=true&container=sdnPlayer&autoplay=false&width='.$atts["width"].'&height='.$atts["height"].'"></script>
        ';
    }

    return $sdnplayer;

}
add_shortcode('3q', 'sdn_shortcode');
$options = get_option('sdn_options');

function generateHash($length = 16)
{
    $password = "";
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
    $maxlength = strlen($possible);
    if ($length > $maxlength) {
        $length = $maxlength;
    }
    $i = 0;
    while ($i < $length) {
        // pick a random character from the possible ones
        $char = substr($possible, mt_rand(0, $maxlength - 1), 1);
        // have we already used this character in $password?
        if (!strstr($password, $char)) {
            // no, so it's OK to add it onto the end of whatever we've already got...
            $password .= $char;
            // ... and increase the counter by one
            $i++;
        }
    }
    // done!
    return $password;
}
?>
