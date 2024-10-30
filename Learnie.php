<?php
/**
 * Plugin Name:       Learnie
 * Plugin URI:        https://learnie.app
 * Description:       Embeds a Learnie Community into any wordpress space by using a shortcode like this: [Learnie id="MTYxMjIwODI0NjM3MQ=="], where id is the community ID from Learnie (you can find it on your web community config area)
 * Version:           1.0
 * Requires at least: 4.7
 * Requires PHP:      7.0
 * Author:            Learnie
 * Author URI:        https://mylearnie.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       learnie
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define('LEARNIE_PLUGIN_VERSION', '1.0');

function learnie_community_space( $atts ) {
	$defaults = array(
		'src' => 'https://learnie.app/community/',
		'width' => '100%',
		'height' => '600',
		'scrolling' => 'yes',
		'class' => 'Learnie_Community',
		'frameborder' => '0',
		'id' => ''
	);

	foreach ( $defaults as $default => $value ) {
		if ( ! @array_key_exists( $default, $atts ) ) {
			$atts[$default] = $value;
		}
	}

	$html = "\n".'<!-- iframe plugin v.'.LEARNIE_PLUGIN_VERSION.' -->'."\n";
	$html .= '<iframe';
	foreach( $atts as $attr => $value ) {
		if ( strtolower($attr) == 'src' ) {
			$value = esc_url($value);
			$value = $value . (isset( $atts["id"] ) ? $atts["id"] : '');
		}
		if ( strtolower($attr) != 'same_height_as' AND strtolower($attr) != 'onload'
			AND strtolower($attr) != 'onpageshow' AND strtolower($attr) != 'onclick') {
			if ( $value != '' ) {
				$html .= ' ' . esc_attr( $attr ) . '="' . esc_attr( $value ) . '"';
			} else {
				$html .= ' ' . esc_attr( $attr );
			}
		}
	}
	$html .= '></iframe>'."\n";

	if ( isset( $atts["same_height_as"] ) ) {
		$html .= '
			<script>
			document.addEventListener("DOMContentLoaded", function(){
				var target_element, iframe_element;
				iframe_element = document.querySelector("iframe.' . esc_attr( $atts["class"] ) . '");
				target_element = document.querySelector("' . esc_attr( $atts["same_height_as"] ) . '");
				iframe_element.style.height = target_element.offsetHeight + "px";
			});
			</script>
		';
	}

	return $html;
}

add_shortcode( 'Learnie', 'learnie_community_space' );
