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
	private static $wpm;
	private static $position;
	private static $page;
	private static $display;

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
				'is_category()',
				'is_archive()'
			),
			'prtime_display' => '1'
		);

		$this->options = get_option('prtime_options', $default);

		self::$wpm = $this->options['prtime_wpm'];
		self::$position = $this->options['prtime_position'];
		self::$page = $this->options['prtime_page'];
		self::$display = $this->options['prtime_display'];

		//var_dump(self::$page);

	}

	public function get_pages_display() {
		$pages = self::$page;
		return $pages;
	}

	public static function display_content( $content, $postid ) {
		
		$content_post = get_post($postid);
		$post_content = $content_post->post_content;

		$nb_words = str_word_count( strip_tags( $post_content ) );
		$wpm = self::$wpm;

		$minutes = floor( $nb_words / $wpm );
		$seconds = floor( $nb_words % $wpm / ($wpm / 60) );

		if( self::$display == 1 ){
			if( $seconds > 30 ){
				$minutes++;
			}
			if($minutes < 1) {
				$time = __('Less than a minute');
			} else {
				$time = $minutes.' min';
			}
			
		} else {
			if ( $seconds < 10){
				$seconds = '0'.$seconds;
			}
			$time = $minutes.':'.$seconds.' sec';
		}

		$time = '<div class="reading_time">'.$time.'</div>';

		if( self::$position == 1 ){

			$display_content = $time;
			$display_content .= $content;

		} else {

			$display_content = $content;
			$display_content .= $time;

		}
		
		return $display_content;
	}
}


// is_front_page()
// is_home()
// is_category()
// is_archive()
// is_single()
// is_page()

// https://wordpress.org/support/topic/getting-is_single-to-work-in-functionsphp

$reading_time = new Posts_Reading_Time_Calc();
$pages = $reading_time->get_pages_display();

function for_the_content( $content ) {
	$postid = get_the_ID();
	return Posts_Reading_Time_Calc::display_content( $content, $postid );
}
add_filter( 'the_content', 'for_the_content' );
add_filter( 'the_excerpt', 'for_the_content' );

