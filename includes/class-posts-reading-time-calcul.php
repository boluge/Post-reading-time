<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.x.fr
 * @since      1.0.0
 *
 * @package    Posts_Reading_Time
 * @subpackage Posts_Reading_Time/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Posts_Reading_Time
 * @subpackage Posts_Reading_Time/includes
 * @author     Stéphane Deluce <boluge@gmail.com>
 */
class Posts_Reading_Time_Calc {

	private $options;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$default = array(
			'prtime_wpm' => '200',
			'prtime_position' => '1',
			'prtime_page' => array(
				'category',
				'archive'
			),
			'prtime_display' => '1'
		);
		$this->options = get_option('prtime_options', $default);

		function before_the_content( $content ) {
			$before_content = '<p class="read">YOUR CONTENT GOES HERE</p>';
			$before_content .= $content;
			return $before_content;
		}
		add_filter( 'the_content', 'before_the_content' );

		function after_the_content( $content ) {
			$after_content = $content;
			$after_content .= '<div class="read">YOUR CONTENT GOES HERE</div>';
			return $after_content;
		}
		add_filter( 'the_content', 'after_the_content' );

		function before_the_excerpt( $excerpt ) {
			$before_excerpt = '<p class="read">YOUR CONTENT GOES HERE</p>';
			$before_excerpt .= $excerpt;
			return $before_excerpt;
		}
		add_filter( 'the_excerpt', 'before_the_excerpt' );

		function after_the_excerpt( $excerpt ) {
			$after_excerpt = $excerpt;
			$after_excerpt .= '<p class="read">YOUR CONTENT GOES HERE</p>';
			return $after_excerpt;
		}
		add_filter( 'the_excerpt', 'after_the_excerpt' );
	}

}