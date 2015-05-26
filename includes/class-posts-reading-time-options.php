<?php
/**
 * CMB2 Theme Options
 * @version 0.1.0
 */
class prtime_Admin {

	/**
	 * Option key, and option page slug
	 * @var string
	 */
	private $key = 'prtime_options';
	private $prefix = 'prtime_';

	/**
	 * Options page metabox id
	 * @var string
	 */
	private $metabox_id = 'prtime_option_metabox';

	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = '';

	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Constructor
	 * @since 0.1.0
	 */
	public function __construct() {
		// Set our title
		$this->title = __( 'Reading Time', 'prtime' );
	}

	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_init', array( $this, 'add_options_page_metabox' ) );
	}


	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_options_page() {
		$this->options_page = add_options_page( $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );

		// Include CMB CSS in the head to avoid FOUT
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key, array( 'cmb_styles' => false ) ); ?>
		</div>
		<?php
	}

	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	function add_options_page_metabox() {

		$cmb = new_cmb2_box( array(
			'id'      => $this->metabox_id,
			'hookup'  => false,
			'show_on' => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );

		// Set our CMB2 fields

		$cmb->add_field( array(
			'name' => __( 'Words per minute' ),
			'desc' => __( 'Here you set the reading speed in words per minute. In general rules, the average reading speed of 200 words per minute.' ),
			'id'   => $this->prefix.'wpm',
			'type' => 'text_small',
			'default' => '200',
		) );

		$cmb->add_field( array(
			'name' => __( 'Pages' ),
			'desc' => __( 'Select the position where you want to display the reading time.' ),
			'id'   => $this->prefix.'page',
			'type'    => 'multicheck',
			'default' => array('category', 'archive'),
			'options' => array(
				'front_page' => __('Front page'),
				'home' => __('Home page'),
				'category' => __('Category page'),
				'archive' => __('Archive page'),
				'single' => __('Single page'),
				
			)
		) );

		$cmb->add_field( array(
			'name' => __( 'Reading time Position' ),
			'desc' => __( 'Define the pages where you want the reading time.' ),
			'id'   => $this->prefix.'position',
			'type' => 'select',
			'default' => '1',
			'options' => array(
				'1' => __( 'After Title' ),
				'2' => __( 'After Content' ),
				'3' => __( 'After Excerpt' ),
			),
		) );

		$cmb->add_field( array(
			'name' => __( 'Reading time Position' ),
			'desc' => __( 'Define the pages where you want the reading time.' ),
			'id'   => $this->prefix.'display',
			'type' => 'select',
			'default' => '1',
			'options' => array(
				'1' => __( 'Minutes' ),
				'2' => __( 'Minutes & seconds' )
			),
		) );

	}

	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}

		throw new Exception( 'Invalid property: ' . $field );
	}

}

/**
 * Helper function to get/return the prtime_Admin object
 * @since  0.1.0
 * @return prtime_Admin object
 */
function prtime_admin() {
	static $object = null;
	if ( is_null( $object ) ) {
		$object = new prtime_Admin();
		$object->hooks();
	}

	return $object;
}

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
function prtime_get_option( $key = '' ) {
	return cmb2_get_option( prtime_admin()->key, $key );
}

// Get it started
prtime_admin();