<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Cs_Popup_Maker
 * @subpackage Cs_Popup_Maker/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cs_Popup_Maker
 * @subpackage Cs_Popup_Maker/admin
 * @author     catchsquare <wearecatchsquare@gmail.com>
 */
class Cs_Popup_Maker_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $setting_api    The setting api of this plugin.
	 */
	protected $setting_api;


	public $settings_fields;
	public $sections;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $setting_api ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->setting_api = $setting_api;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cs_Popup_Maker_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cs_Popup_Maker_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$min = '';
		if( defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ) {
			$min = '.min';
		}

		wp_enqueue_style($this->plugin_name . '-semantic', plugin_dir_url(__FILE__) . 'css/semantic' . $min . '.css', array(), $this->version, 'all');
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cs-popup-maker-admin' . $min . '.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cs_Popup_Maker_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cs_Popup_Maker_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$min = '';
		if( defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ) {
			$min = '.min';
		}

 		wp_enqueue_media();
		wp_enqueue_script( $this->plugin_name.'-semantic', plugin_dir_url( __FILE__ ) . 'js/semantic' . $min . '.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cs-popup-maker-admin' . $min . '.js', array( 'jquery',$this->plugin_name.'-semantic' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'cs_admin_obj',  $this->__localized_script() );

	}

	public function __localized_script() {	
		
		$cspopup_settings = array();
		
		$cspopup_settings['plugin_popup_css_url'] = CS_POPUP_MAKER_URL.'/admin/css/cs-admin-popup-only.css';
		
		return $cspopup_settings;
	}

	/**
	 * Initialize and registers the settings sections and fileds to WordPress
	 *
	 * This function gets the initiated settings sections and fields. Then
	 * registers them to WordPress and ready for use.
	 */
	public function admin_init() {
		 //set the settings
        $this->setting_api->set_sections( $this->get_settings_sections() );

        $this->setting_api->set_fields( $this->get_settings_fields() );

		$this->setting_api->admin_init();
	}


	/**
	 * Register menu page for the admin area.
	 * 
	 * @since    1.0.0
	 */
	public function menu_page() {
		add_menu_page(
			esc_html__('CS Popup Maker', 'cs-popup-maker'), // page title
			esc_html__('CS Popup Maker', 'cs-popup-maker'), // menu title
			'manage_options', // capability
			'cs-popup-maker', // menu slug
			array($this, 'main_menu_page_cb'), // Callback
			'dashicons-format-image', // icon url
			'26.2987'
		);
	}


	/**
	 * Register menu page for the admin area.
	 *
	 * @since    1.0.0
	 */

	public function main_menu_page_cb() {
		$this->options = get_option('wp_smart_preloader_pro_options');

		require_once CS_POPUP_MAKER_PATH . 'admin' . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . 'cs-popup-maker-admin-display.php';
	}


	public function  get_settings_sections() {

		$this->sections = apply_filters(
			'cs_popup_maker_settings_sections',
			array(				
				array(
					'id'    => 'cs-popup',
					'title' => __( 'Popup Maker', 'cs-popup-maker' ),
					'page_title' => __( 'Popup Maker Settings', 'cs-popup-maker' ),
				),
				array(
					'id'    => 'cs-popup-html',
					'title' => __( 'HTML Templates', 'cs-popup-maker' ),
					'page_title' => __( 'Popup Maker Settings', 'cs-popup-maker' ),
				)
			)
		);

        return $this->sections;
	}
	
	public function  get_settings_fields() {

		$this->settings_fields = apply_filters(
			'cs_popup_maker_settings_settings_fields',
			array(				
				'cs-popup' => array(
					array(
						'name'    => 'cs_popup_status',
						'label'   => __( 'Enable Popup', 'cs-popup-maker' ),						
						'type'    => 'checkbox'
					),
					array(
						'name'    => 'cs_popup_enable_session',
						'label'   => __( 'Enable Popup once over a session', 'cs-popup-maker' ),
						'desc'	  => __( 'If disable it will show in every visit', 'cs-popup-maker' ),
						'type'    => 'checkbox'
					),
					array(
						'name'              => 'cs_popup_page',
						'label'             => __( 'Show Popup in Pages', 'cs-popup-maker' ),						
						'placeholder'       => __( 'Choose Page', 'cs-popup-maker' ),
						'type'              => 'select',
						'default'           => '-1',
						'options' 			=> $this->get_all_pages()
					),
					/* array(
						'name'              => 'cs_popup_exclude_page',
						'label'             => __( 'Exclude Popup a Page', 'cs-popup-maker' ),						
						'placeholder'       => __( 'Choose Page', 'cs-popup-maker' ),
						'type'              => 'select',
						'default'           => '-1',
						'options' 			=> $this->get_all_pages_exclude()
					), */
					array(						
						'name'    	=> 'cs_popup_image',
						'label'   	=> __( 'Select Image', 'cs-popup-maker' ),
						'desc'    	=> __( 'Select Image', 'cs-popup-maker' ),
						'type'    	=> 'file',
						'default' 	=> '',
						'options' 	=> array(
							'button_label' => 'Choose Image'
						)
					),
					array(
						'id'    			=> 'cs_popup_target',
						'name'    			=> 'cs_popup_target',
						'label'             => __( 'Target URL', 'cs-popup-maker' ),
						'desc'              => __( 'Target URL eg. https://example.com', 'cs-popup-maker' ),
						'placeholder'       => __( 'https://example.com', 'cs-popup-maker' ),
						'type'              => 'url',
						'sanitize_callback' => 'sanitize_text_field'
					),
					array(
						'id'    			=> 'cs_popup_target_openin',
						'name'    			=> 'cs_popup_target_openin',
						'label'             => __( 'Select Window', 'cs-popup-maker' ),
						'desc'              => __( 'Select option to open the url', 'cs-popup-maker' ),						
						'type'              => 'select',
						'options'			=> array(
							'_self'		=> esc_html__('Same Window','cs-popup-maker' ),
							'_blank'		=> esc_html__('New Tab','cs-popup-maker')
						),
						'default' => 'self'
					),
				),
				'cs-popup-html' => array(
					array(
						'name'    => 'html_popup_status',
						'label'   => __( 'Enable', 'cs-popup-maker' ),
						'desc'    => __( 'Enable Popup', 'cs-popup-maker' ),
						'type'    => 'checkbox'
					),
					array(
						'name'    => 'html_enable_session',
						'label'   => __( 'Enable Popup once over a session', 'cs-popup-maker' ),
						'desc'	  => __( 'If disable it will show in every visit', 'cs-popup-maker' ),
						'type'    => 'checkbox'
					), 
					array(
						'name'    => 'Popup',
						'label'   => __( 'Popup Position', 'cs-popup-maker' ),						
						'type'    => 'radio',
						'options' => array(
							'1' => '<div id="template-1" style="">Template 1</div>',
							'2' => '<div id="template-2" style="">Template 2</div>',
							'3' => '<div id="template-3" style="">Template 3</div>',
							'4' => '<div id="template-4" style="">Template 4</div>',
							'5' => '<div id="template-5" style="">Template 5</div>',
							'6' => '<div id="template-6" style="">Template 6</div>'
						),
						'default' => '1'
					),
					array(
						'id'    			=> 'html_content_iframe',
						'name'    			=> 'html_content_iframe',
						'label'             => __( 'Popup Preview', 'cs-popup-maker' ),
						'desc'       		=> '<div id="html-template-popup-preview"  style="min-height:600px;"></div>',
						'type'              => 'html',
						'sanitize_callback' => 'sanitize_text_field'
					),
					array(
						'id'    			=> 'html_add_overlay',
						'name'    			=> 'html_add_overlay',
						'label'             => __( 'Show Overlay', 'cs-popup-maker' ),
						'type'              => 'checkbox',
						'default'			=> '1'
					),
					array(
						'id'    			=> 'html_heading_title',
						'name'    			=> 'html_heading_title',
						'label'             => __( 'Title', 'cs-popup-maker' ),						
						'placeholder'       => __( 'Enter Title', 'cs-popup-maker' ),
						'type'              => 'text',
						'sanitize_callback' => 'sanitize_text_field'
					),
					array(
						'id'    			=> 'html_subheading_title',
						'name'    			=> 'html_subheading_title',
						'label'             => __( 'Sub Title', 'cs-popup-maker' ),						
						'placeholder'       => __( 'Enter Sub Title', 'cs-popup-maker' ),
						'type'              => 'text',
						'sanitize_callback' => 'sanitize_text_field'
					),
					array(
						'id'    			=> 'html_content',
						'name'    			=> 'html_content',
						'label'             => __( 'Description', 'cs-popup-maker' ),						
						'placeholder'       => __( 'Enter your Description', 'cs-popup-maker' ),
						'type'              => 'wysiwyg',
						// 'sanitize_callback' => 'sanitize_text_field'
					),
					array(
						'id'    			=> 'heading_target_label',
						'name'    			=> 'heading_target_label',
						'label'             => __( 'Target label', 'cs-popup-maker' ),
						'desc'              => __( 'Target label', 'cs-popup-maker' ),
						'type'              => 'text',
						'sanitize_callback' => 'sanitize_text_field'
					),
					array(
						'id'    			=> 'heading_target',
						'name'    			=> 'heading_target',
						'label'             => __( 'Target URL', 'cs-popup-maker' ),
						'desc'              => __( 'Target URL eg. https://example.com', 'cs-popup-maker' ),
						'placeholder'       => __( 'https://example.com', 'cs-popup-maker' ),
						'type'              => 'url',
						'sanitize_callback' => 'sanitize_text_field'
					),
					array(
						'id'    			=> 'html_target_openin',
						'name'    			=> 'html_target_openin',
						'label'             => __( 'Select Window', 'cs-popup-maker' ),
						'desc'              => __( 'Select option to open the url', 'cs-popup-maker' ),						
						'type'              => 'select',
						'options'			=> array(
							'_self'		=> esc_html__('Same Window','cs-popup-maker' ),
							'_blank'	=> esc_html__('New Tab','cs-popup-maker')
						),
						'default' => '_self'
					),
					array(
						'name'    => 'html_background_color',
						'label'   => __( 'Background Color', 'cs-popup-maker' ),						
						'type'    => 'color',
						'default' => '#e14044'
					),
					array(
						'name'    => 'html_text_color',
						'label'   => __( 'Text Color', 'cs-popup-maker' ),						
						'type'    => 'color',
						'default' => '#000000'
					),
				)

			)
		);

        return $this->settings_fields;
	}

	private function get_all_pages(){
		$pages = get_pages();
		$options = array(
			'-1' => __( 'All', 'cs-popup-maker'),
			'0' => __( 'Home Page', 'cs-popup-maker'),
		);
		foreach( $pages as $pg ) {
			$options[$pg->ID] = $pg->post_title;
		}
		return $options;
	}
	
	private function get_all_pages_exclude(){
		$pages = get_pages();	
		$options = array(
			'' => __( 'Choose page to exlude Popup', 'cs-popup-maker'),
			
		);	
		foreach( $pages as $pg ) {
			$options[$pg->ID] = $pg->post_title;
		}
		return $options;
	}

}
