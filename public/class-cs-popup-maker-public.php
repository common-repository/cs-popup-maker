<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Cs_Popup_Maker
 * @subpackage Cs_Popup_Maker/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Cs_Popup_Maker
 * @subpackage Cs_Popup_Maker/public
 * @author     catchsquare <wearecatchsquare@gmail.com>
 */
class Cs_Popup_Maker_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		$cspopup = get_option( 'cs-popup' );
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cs-popup-maker-public' . $min . '.css', array(), $this->version, 'all' );
		
		$cspopupHtml = get_option( 'cs-popup-html' );
		
		$html_background_color = (isset( $cspopupHtml['html_background_color']) && !empty( $cspopupHtml['html_background_color'] ) ) ? $cspopupHtml['html_background_color'] :'#e1404';
		$html_text_color= (isset( $cspopupHtml['html_text_color']) && !empty( $cspopupHtml['html_text_color'] ) ) ? $cspopupHtml['html_text_color'] : '#000000';
		$custom_css = "
				.cs-wrapper-full .cs-pop-up,
				.cs-wrapper-full .cs-pop-up-left-bottom,
				.cs-wrapper-full .cs-pop-up-center-bottom,
				.cs-wrapper-full .cs-pop-up-full-bottom,
				.cs-wrapper-full .cs-pop-up-right-bottom 
				{ 
                    background: {$html_background_color}  !important; 
                    color: {$html_text_color} !important; 
                } 
                .cs-wrapper-full .cs-button { 
                    color: {$html_text_color} !important; 
                } 
		";		

		wp_add_inline_style( $this->plugin_name, $custom_css );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		$cspopup = get_option( 'cs-popup' );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cs-popup-maker-public' . $min . '.js', array( 'jquery','underscore' ), $this->version, false );
		# Localize the script with new data                
		wp_localize_script( $this->plugin_name, 'cs_obj',  $this->localized_script() );

		wp_localize_script($this->plugin_name, 'cs_html_obj', $this->localized_script_html_popup());


    }

	public function localized_script() {
		$cspopup = get_option( 'cs-popup' );
		
		$cspopup_settings = array();
		$cspopup_settings['cs_popup_status'] = $this->_show_popup();
		$cspopup_settings['cs_popup_enable_session'] = ( isset( $cspopup['cs_popup_enable_session'] ) && 1 == $cspopup['cs_popup_enable_session']) ? 'on':'off';
		
		$image_url = '';
		if( isset($cspopup['cs_popup_image']) ) {
			$image_url = wp_get_attachment_url($cspopup['cs_popup_image'] );			
		}
		$cspopup_settings['image_url'] = $image_url;
		$cspopup_settings['target_url'] = isset($cspopup['cs_popup_target']) ? $cspopup['cs_popup_target'] : '#';
		$cspopup_settings['target_open'] = isset($cspopup['cs_popup_target_openin']) ? $cspopup['cs_popup_target_openin'] : '_blank';
		$cspopup_settings['close_link'] = CS_POPUP_MAKER_URL . '/public/images/close.png';
		
		return $cspopup_settings;
	}

	public function localized_script_html_popup(){
		$cspopup = get_option( 'cs-popup-html' );
		$cspopup_settings = array();
		$cspopup_settings['html_popup_status'] =  ( isset($cspopup['html_popup_status']) && $cspopup['html_popup_status'] == 1 ) ? $cspopup['html_popup_status']: 0;
		$cspopup_settings['Popup'] = isset($cspopup['Popup']) ? $cspopup['Popup'] : '';
		
		$cspopup_settings['html_enable_session'] = (1 == $cspopup['html_enable_session']) ? 'on':'off';

		
		$cspopup_settings['html_add_overlay'] = isset($cspopup['html_add_overlay']) ? $cspopup['html_add_overlay'] : '1';

		$cspopup_settings['html_heading_title'] = isset($cspopup['html_heading_title']) ? $cspopup['html_heading_title'] : '';

		$cspopup_settings['html_subheading_title'] = isset($cspopup['html_subheading_title']) ? $cspopup['html_subheading_title'] : '';

		$cspopup_settings['html_content'] = isset($cspopup['html_content']) ? $cspopup['html_content'] : '';

		$cspopup_settings['heading_target_label'] = isset($cspopup['heading_target_label']) ? $cspopup['heading_target_label'] : '';

		$cspopup_settings['heading_target'] = isset($cspopup['heading_target']) ? $cspopup['heading_target'] : '';

		$cspopup_settings['html_target_openin'] = isset($cspopup['html_target_openin']) ? $cspopup['html_target_openin'] : '';
		return $cspopup_settings;

	}

	private function _show_popup() {
		global $wp_query;

		$post_id = get_queried_object_id();
		$cspopup = get_option('cs-popup');
		if ( isset($cspopup['cs_popup_status']) && $cspopup['cs_popup_status'] == 1 ) {
			$enablePopup = 0;
			
			if ( -1 == $cspopup['cs_popup_page'] ){
				$enablePopup = 1;
			} else if ( 0 == $cspopup['cs_popup_page'] && ( is_home() || is_front_page() ) ) {
				$enablePopup = 1;
			} else if ( $post_id == $cspopup['cs_popup_page'] ) {
				$enablePopup = 1;
			}				
			
			return $enablePopup ?true:false;
		}
		return false;		
	}
	
	private function _disable_popup_in_exclude() {
		global $wp_query;

		$post_id = get_queried_object_id();
		$cspopup = get_option('cs-popup');
		if ( isset($cspopup['cs_popup_status']) && $cspopup['cs_popup_status'] == 1 ) {
			$enablePopup = 0;
			
			if ( $post_id == $cspopup['cs_popup_exclude_page'] ) {
				$enablePopup = 0;
			} else {
				$enablePopup  = 1;
			}				
			
			return $enablePopup ?true:false;
		}
		return false;		
	}


}
