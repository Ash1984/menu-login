<?php
/**
 * Elementor Widget for Menu Login.
 *
 * This file contains the complete widget code including all content and style controls,
 * and the render() method which outputs the widget markup along with dynamic inline CSS.
 *
 * @package Menu_Login
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( did_action( 'elementor/loaded' ) ) {

	class Menu_Login_Widget extends \Elementor\Widget_Base {

		public function get_name() {
			return 'menu_login_icon';
		}

		public function get_title() {
			return __( 'Menu Login Icon', 'menu-login' );
		}

		public function get_icon() {
			return 'eicon-lock-user';
		}

		public function get_categories() {
			return [ 'general' ];
		}

		public function get_keywords() {
			return [ 'login', 'icon', 'ajax', 'popup', 'woo', 'woocommerce', 'account', 'user', 'svg' ];
		}

		/**
		 * Constructor: Enqueue editor-only script.
		 */
		public function __construct( $data = [], $args = null ) {
			parent::__construct( $data, $args );
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() && current_user_can( 'edit_posts' ) ) {
				add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_editor_script' ] );
			}
		}

		/**
		 * Enqueue the editor script.
		 */
		public function enqueue_editor_script() {
			wp_enqueue_script(
				'menu-login-editor-custom',
				MENU_LOGIN_PLUGIN_URL . 'assets/js/menu-login-editorv1.js',
				[ 'jquery' ],
				'1.0.0',
				true
			);
		}

		/**
		 * Get Elementor breakpoints.
		 */
		protected function get_elementor_breakpoints() {
			$breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();
			return [
				'tablet' => isset( $breakpoints['tablet'] ) ? $breakpoints['tablet']->get_value() : 1024,
				'mobile' => isset( $breakpoints['mobile'] ) ? $breakpoints['mobile']->get_value() : 767,
			];
		}

		/**
		 * Helper: Print a dimension array as "topunit rightunit bottomunit leftunit".
		 */
		protected function print_dimension( $dim ) {
			if ( ! is_array( $dim ) || ! isset( $dim['top'] ) ) {
				return '0px';
			}
			return sprintf(
				'%s%s %s%s %s%s %s%s',
				$dim['top'],    $dim['unit'],
				$dim['right'],  $dim['unit'],
				$dim['bottom'], $dim['unit'],
				$dim['left'],   $dim['unit']
			);
		}

		/**
		 * Helper: Print a single slider dimension.
		 */
		protected function print_single_dimension( $dim ) {
			if ( ! is_array( $dim ) || ! isset( $dim['size'] ) ) {
				return 'auto';
			}
			return $dim['size'] . $dim['unit'];
		}

		/**
		 * Register widget controls.
		 *
		 * All content and style controls are defined below.
		 */
		protected function register_controls() {

			// -------------------------------------------
			// CONTENT TAB
			// -------------------------------------------
			$this->start_controls_section(
				'section_icon_content',
				[
					'label' => __( 'Content', 'menu-login' ),
					'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);

			$this->add_control(
				'icon',
				[
					'label'   => __( 'Logged-Out Icon', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value'   => 'fas fa-user',
						'library' => 'fa-solid',
					],
				]
			);

			$this->add_control(
				'welcome_message',
				[
					'label'   => __( 'Welcome Message', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'Welcome ', 'menu-login' ),
				]
			);

			$this->add_control(
				'enable_ajax',
				[
					'label'   => __( 'Enable AJAX Login/Register', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'yes' => __( 'AJAX', 'menu-login' ),
						'no'  => __( 'Standard', 'menu-login' ),
					],
					'default' => 'yes',
				]
			);

			$this->add_responsive_control(
				'menu_message_layout',
				[
					'label'   => __( 'Menu message layout', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'off'    => __( 'Off (Hide)', 'menu-login' ),
						'column' => __( 'Column', 'menu-login' ),
						'row'    => __( 'Row', 'menu-login' ),
					],
					'default' => 'row',
				]
			);

			$this->add_control(
				'login_title',
				[
					'label'   => __( 'Login Title', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'Login', 'menu-login' ),
				]
			);

			$this->add_control(
				'pre_show_popup_button',
				[
					'label'           => __( 'Pre-show Popup Demo', 'menu-login' ),
					'type'            => \Elementor\Controls_Manager::RAW_HTML,
					'raw'             => '<button id="pre-show-popup-btn-menu-ajaxlogin-popup" style="padding:6px 12px; background:#0073aa; color:#fff; border:none; border-radius:3px; cursor:pointer;">' . __( 'Pre-show Popup Demo', 'menu-login' ) . '</button>',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);

			$this->end_controls_section();

			// -------------------------------------------
			// STYLE TAB: Login Container
			// -------------------------------------------
			$this->start_controls_section(
				'section_icon_style',
				[
					'label' => __( 'Login Container', 'menu-login' ),
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_responsive_control(
				'menu_login_margin',
				[
					'label'      => __( 'Login Block Margin', 'menu-login' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'default'    => [
						'top'    => '0',
						'right'  => '0',
						'bottom' => '0',
						'left'   => '0',
						'unit'   => 'px',
						'isLinked' => false,
					],
				]
			);

			$this->end_controls_section();

			// -------------------------------------------
			// STYLE TAB: Logged Out Style
			// (Include all controls for normal and hover states)
			// -------------------------------------------
			$this->start_controls_section(
				'section_logged_out_icon_style',
				[
					'label' => __( 'Logged Out Style', 'menu-login' ),
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);

			$this->start_controls_tabs( 'logged_out_icon_tabs' );

			// Normal
			$this->start_controls_tab( 'logged_out_icon_normal', [ 'label' => __( 'Normal', 'menu-login' ) ] );
			$this->add_control(
				'logged_out_icon_color',
				[
					'label'   => __( 'Icon Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#ffffff',
				]
			);
			$this->add_control(
				'logged_out_icon_background_color',
				[
					'label'   => __( 'Background Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#000000',
				]
			);
			$this->add_responsive_control(
				'logged_out_icon_size',
				[
					'label'      => __( 'Icon Size', 'menu-login' ),
					'type'       => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', '%' ],
					'range'      => [
						'px' => [ 'min' => 10, 'max' => 200 ],
						'em' => [ 'min' => 0.5, 'max' => 15 ],
						'%'  => [ 'min' => 5, 'max' => 100 ],
					],
					'default' => [ 'unit' => 'px', 'size' => 40 ],
				]
			);
			$this->add_responsive_control(
				'logged_out_icon_padding',
				[
					'label'      => __( 'Icon Padding', 'menu-login' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'default'    => [
						'top'    => '0',
						'right'  => '0',
						'bottom' => '0',
						'left'   => '0',
						'unit'   => 'px',
						'isLinked' => false,
					],
				]
			);
			$this->add_control(
				'logged_out_icon_border_radius',
				[
					'label'      => __( 'Border Radius', 'menu-login' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'default'    => [
						'top'    => '0',
						'right'  => '0',
						'bottom' => '0',
						'left'   => '0',
						'unit'   => 'px',
						'isLinked' => true,
					],
				]
			);
			$this->add_control(
				'logged_out_icon_border_style',
				[
					'label'   => __( 'Border Style', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'none'   => __( 'None', 'menu-login' ),
						'solid'  => __( 'Solid', 'menu-login' ),
						'dashed' => __( 'Dashed', 'menu-login' ),
						'dotted' => __( 'Dotted', 'menu-login' ),
						'double' => __( 'Double', 'menu-login' ),
					],
					'default' => 'none',
				]
			);
			$this->add_control(
				'logged_out_icon_border_width',
				[
					'label'      => __( 'Border Width', 'menu-login' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'default'    => [
						'top'    => '0',
						'right'  => '0',
						'bottom' => '0',
						'left'   => '0',
						'unit'   => 'px',
						'isLinked' => true,
					],
				]
			);
			$this->add_control(
				'logged_out_icon_border_color',
				[
					'label'   => __( 'Border Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#000000',
				]
			);
			$this->end_controls_tab();

			// Hover
			$this->start_controls_tab( 'logged_out_icon_hover', [ 'label' => __( 'Hover', 'menu-login' ) ] );
			$this->add_control(
				'logged_out_icon_hover_color',
				[
					'label'   => __( 'Icon Hover Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#ffffff',
				]
			);
			$this->add_control(
				'logged_out_icon_hover_background_color',
				[
					'label'   => __( 'Icon Hover Background Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#222222',
				]
			);
			$this->add_control(
				'logged_out_icon_hover_border_radius',
				[
					'label'      => __( 'Border Radius (Hover)', 'menu-login' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'default'    => [
						'top'    => '0',
						'right'  => '0',
						'bottom' => '0',
						'left'   => '0',
						'unit'   => 'px',
						'isLinked' => true,
					],
				]
			);
			$this->add_control(
				'logged_out_icon_hover_border_style',
				[
					'label'   => __( 'Border Style (Hover)', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'none'   => __( 'None', 'menu-login' ),
						'solid'  => __( 'Solid', 'menu-login' ),
						'dashed' => __( 'Dashed', 'menu-login' ),
						'dotted' => __( 'Dotted', 'menu-login' ),
						'double' => __( 'Double', 'menu-login' ),
					],
					'default' => 'none',
				]
			);
			$this->add_control(
				'logged_out_icon_hover_border_width',
				[
					'label'      => __( 'Border Width (Hover)', 'menu-login' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'default'    => [
						'top'    => '0',
						'right'  => '0',
						'bottom' => '0',
						'left'   => '0',
						'unit'   => 'px',
						'isLinked' => true,
					],
				]
			);
			$this->add_control(
				'logged_out_icon_hover_border_color',
				[
					'label'   => __( 'Border Color (Hover)', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#000000',
				]
			);
			$this->end_controls_tab();
			$this->end_controls_tabs();
			$this->end_controls_section();

			// -------------------------------------------
			// STYLE TAB: Logged In Letter Style
			// -------------------------------------------
			$this->start_controls_section(
				'section_logged_in_letter_style',
				[
					'label' => __( 'Logged In Letter', 'menu-login' ),
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
			$this->start_controls_tabs( 'logged_in_letter_tabs' );
			// Normal
			$this->start_controls_tab( 'logged_in_letter_normal', [ 'label' => __( 'Normal', 'menu-login' ) ] );
			$this->add_control(
				'logged_in_letter_color',
				[
					'label'   => __( 'Letter Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#ffffff',
				]
			);
			$this->add_control(
				'logged_in_letter_background_color',
				[
					'label'   => __( 'Background Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#000000',
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => 'logged_in_letter_typography_normal',
					'label'    => __( 'Typography', 'menu-login' ),
					'selector' => '.menu-login-wrapper-{{ID}} .menu-login-icon.loggedin-new',
				]
			);
			$this->add_responsive_control(
				'logged_in_letter_padding',
				[
					'label' => __( 'Letter Padding', 'menu-login' ),
					'type'  => \Elementor\Controls_Manager::DIMENSIONS,
				]
			);
			$this->add_control(
				'logged_in_letter_border_radius',
				[
					'label' => __( 'Border Radius', 'menu-login' ),
					'type'  => \Elementor\Controls_Manager::DIMENSIONS,
				]
			);
			$this->add_control(
				'logged_in_letter_border_style',
				[
					'label'   => __( 'Border Style', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'none'   => __( 'None', 'menu-login' ),
						'solid'  => __( 'Solid', 'menu-login' ),
						'dashed' => __( 'Dashed', 'menu-login' ),
						'dotted' => __( 'Dotted', 'menu-login' ),
						'double' => __( 'Double', 'menu-login' ),
					],
					'default' => 'none',
				]
			);
			$this->add_control(
				'logged_in_letter_border_width',
				[
					'label' => __( 'Border Width', 'menu-login' ),
					'type'  => \Elementor\Controls_Manager::DIMENSIONS,
				]
			);
			$this->add_control(
				'logged_in_letter_border_color',
				[
					'label'   => __( 'Border Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#000000',
				]
			);
			$this->end_controls_tab();
			// Hover
			$this->start_controls_tab( 'logged_in_letter_hover', [ 'label' => __( 'Hover', 'menu-login' ) ] );
			$this->add_control(
				'logged_in_letter_hover_color',
				[
					'label'   => __( 'Letter Hover Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#ffffff',
				]
			);
			$this->add_control(
				'logged_in_letter_hover_background_color',
				[
					'label'   => __( 'Letter Hover Background Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#222222',
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => 'logged_in_letter_typography_hover',
					'label'    => __( 'Typography (Hover)', 'menu-login' ),
					'selector' => '.menu-login-wrapper-{{ID}} .menu-login-icon.loggedin-new + .menu-login-messages .menu-login-welcome:hover',
				]
			);
			$this->add_control(
				'logged_in_letter_hover_border_radius',
				[
					'label' => __( 'Border Radius (Hover)', 'menu-login' ),
					'type'  => \Elementor\Controls_Manager::DIMENSIONS,
				]
			);
			$this->add_control(
				'logged_in_letter_hover_border_style',
				[
					'label'   => __( 'Border Style (Hover)', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'none'   => __( 'None', 'menu-login' ),
						'solid'  => __( 'Solid', 'menu-login' ),
						'dashed' => __( 'Dashed', 'menu-login' ),
						'dotted' => __( 'Dotted', 'menu-login' ),
						'double' => __( 'Double', 'menu-login' ),
					],
					'default' => 'none',
				]
			);
			$this->add_control(
				'logged_in_letter_hover_border_width',
				[
					'label' => __( 'Border Width (Hover)', 'menu-login' ),
					'type'  => \Elementor\Controls_Manager::DIMENSIONS,
				]
			);
			$this->add_control(
				'logged_in_letter_hover_border_color',
				[
					'label'   => __( 'Border Color (Hover)', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#000000',
				]
			);
			$this->end_controls_tab();
			$this->end_controls_tabs();
			$this->end_controls_section();

			// -------------------------------------------
			// STYLE TAB: Logged in Message Text
			// -------------------------------------------
			$this->start_controls_section(
				'section_message_style',
				[
					'label' => __( 'Logged in Message Text', 'menu-login' ),
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
			$this->start_controls_tabs( 'message_text_tabs' );
			// Normal
			$this->start_controls_tab( 'message_text_normal', [ 'label' => __( 'Normal', 'menu-login' ) ] );
			$this->add_control(
				'message_text_color',
				[
					'label'   => __( 'Text Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#ffffff',
					'selectors' => [
						'.menu-login-wrapper-{{ID}} .menu-login-icon.loggedin-new + .menu-login-messages .menu-login-welcome' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => 'message_text_typography_normal',
					'label'    => __( 'Typography', 'menu-login' ),
					'selector' => '.menu-login-wrapper-{{ID}} .menu-login-icon.loggedin-new + .menu-login-messages .menu-login-welcome',
				]
			);
			$this->end_controls_tab();
			// Hover
			$this->start_controls_tab( 'message_text_hover', [ 'label' => __( 'Hover', 'menu-login' ) ] );
			$this->add_control(
				'message_text_hover_color',
				[
					'label'   => __( 'Text Hover Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#ffffff',
					'selectors' => [
						'.menu-login-wrapper-{{ID}} .menu-login-icon.loggedin-new + .menu-login-messages .menu-login-welcome:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => 'message_text_typography_hover',
					'label'    => __( 'Typography (Hover)', 'menu-login' ),
					'selector' => '.menu-login-wrapper-{{ID}} .menu-login-icon.loggedin-new + .menu-login-messages .menu-login-welcome:hover',
				]
			);
			$this->end_controls_tab();
			$this->end_controls_tabs();
			$this->end_controls_section();

			// -------------------------------------------
			// STYLE TAB: Customer Name Style
			// -------------------------------------------
			$this->start_controls_section(
				'section_customer_name_style',
				[
					'label' => __( 'Customer Name', 'menu-login' ),
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
			$this->start_controls_tabs( 'customer_name_tabs' );
			// Normal
			$this->start_controls_tab( 'customer_name_normal', [ 'label' => __( 'Normal', 'menu-login' ) ] );
			$this->add_control(
				'customer_name_color',
				[
					'label'   => __( 'Name Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#ffffff',
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => 'customer_name_typography_normal',
					'label'    => __( 'Typography', 'menu-login' ),
					'selector' => '.menu-login-wrapper-{{ID}} .menu-login-user-name',
				]
			);
			$this->end_controls_tab();
			// Hover
			$this->start_controls_tab( 'customer_name_hover', [ 'label' => __( 'Hover', 'menu-login' ) ] );
			$this->add_control(
				'customer_name_hover_color',
				[
					'label'   => __( 'Name Hover Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#ffffff',
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => 'customer_name_typography_hover',
					'label'    => __( 'Typography (Hover)', 'menu-login' ),
					'selector' => '.menu-login-wrapper-{{ID}} .menu-login-user-name:hover',
				]
			);
			$this->end_controls_tab();
			$this->end_controls_tabs();
			$this->end_controls_section();

			// -------------------------------------------
			// STYLE TAB: Login Popup Style
			// -------------------------------------------
			$this->start_controls_section(
				'section_login_popup_style',
				[
					'label' => __( 'Login Popup Style', 'menu-login' ),
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'login_popup_background_color',
				[
					'label'   => __( 'Popup Background Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#ffffff',
				]
			);
			$this->add_responsive_control(
				'login_popup_border_radius',
				[
					'label'      => __( 'Popup Border Radius', 'menu-login' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'default'    => [
						'top'    => '8',
						'right'  => '8',
						'bottom' => '8',
						'left'   => '8',
						'unit'   => 'px',
						'isLinked' => true,
					],
				]
			);
			$this->add_control(
				'login_popup_border_color',
				[
					'label'   => __( 'Popup Border Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#ccc',
				]
			);
			$this->add_control(
				'login_popup_border_style',
				[
					'label'   => __( 'Popup Border Style', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'none'   => __( 'None', 'menu-login' ),
						'solid'  => __( 'Solid', 'menu-login' ),
						'dashed' => __( 'Dashed', 'menu-login' ),
						'dotted' => __( 'Dotted', 'menu-login' ),
						'double' => __( 'Double', 'menu-login' ),
					],
					'default' => 'solid',
				]
			);
			$this->add_responsive_control(
				'login_popup_border_width',
				[
					'label'      => __( 'Popup Border Width', 'menu-login' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'default'    => [
						'top'    => '1',
						'right'  => '1',
						'bottom' => '1',
						'left'   => '1',
						'unit'   => 'px',
						'isLinked' => true,
					],
				]
			);
			$this->add_responsive_control(
				'login_popup_padding',
				[
					'label'      => __( 'Popup Padding', 'menu-login' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'default'    => [
						'top'    => '20',
						'right'  => '20',
						'bottom' => '20',
						'left'   => '20',
						'unit'   => 'px',
						'isLinked' => true,
					],
				]
			);
			$this->add_responsive_control(
				'login_popup_margin',
				[
					'label'      => __( 'Popup Margin', 'menu-login' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'default'    => [
						'top'    => '0',
						'right'  => '0',
						'bottom' => '0',
						'left'   => '0',
						'unit'   => 'px',
						'isLinked' => true,
					],
				]
			);
			$this->end_controls_section();

			// -------------------------------------------
			// STYLE TAB: Input Field Style
			// -------------------------------------------
			$this->start_controls_section(
				'section_login_input_style',
				[
					'label' => __( 'Input Field Style', 'menu-login' ),
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
			$this->start_controls_tabs( 'input_field_tabs' );
			// Normal
			$this->start_controls_tab( 'input_field_normal', [ 'label' => __( 'Normal', 'menu-login' ) ] );
			$this->add_control(
				'input_background_color',
				[
					'label'   => __( 'Input Background Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#ffffff',
				]
			);
			$this->add_control(
				'input_text_color',
				[
					'label'   => __( 'Input Text Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#333333',
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => 'input_typography_normal',
					'label'    => __( 'Typography', 'menu-login' ),
					'selector' => '#menu-login-ajax-form-{{ID}} input, #menu-register-ajax-form-{{ID}} input',
				]
			);
			$this->add_responsive_control(
				'input_border_radius',
				[
					'label'   => __( 'Input Border Radius', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::DIMENSIONS,
					'default' => [
						'top'    => '4',
						'right'  => '4',
						'bottom' => '4',
						'left'   => '4',
						'unit'   => 'px',
						'isLinked' => true,
					],
				]
			);
			$this->add_control(
				'input_border_color',
				[
					'label'   => __( 'Input Border Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#ccc',
				]
			);
			$this->add_control(
				'input_border_style',
				[
					'label'   => __( 'Input Border Style', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'none'   => __( 'None', 'menu-login' ),
						'solid'  => __( 'Solid', 'menu-login' ),
						'dashed' => __( 'Dashed', 'menu-login' ),
						'dotted' => __( 'Dotted', 'menu-login' ),
						'double' => __( 'Double', 'menu-login' ),
					],
					'default' => 'solid',
				]
			);
			$this->add_responsive_control(
				'input_border_width',
				[
					'label'   => __( 'Input Border Width', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::DIMENSIONS,
					'default' => [
						'top'    => '1',
						'right'  => '1',
						'bottom' => '1',
						'left'   => '1',
						'unit'   => 'px',
						'isLinked' => true,
					],
				]
			);
			$this->add_responsive_control(
				'input_outline_width',
				[
					'label'   => __( 'Input Outline Width', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::DIMENSIONS,
					'default' => [
						'top'    => '0',
						'right'  => '0',
						'bottom' => '0',
						'left'   => '0',
						'unit'   => 'px',
						'isLinked' => true,
					],
				]
			);
			$this->add_control(
				'input_outline_style',
				[
					'label'   => __( 'Input Outline Style', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'none'   => __( 'None', 'menu-login' ),
						'solid'  => __( 'Solid', 'menu-login' ),
						'dashed' => __( 'Dashed', 'menu-login' ),
						'dotted' => __( 'Dotted', 'menu-login' ),
						'double' => __( 'Double', 'menu-login' ),
					],
					'default' => 'none',
				]
			);
			$this->add_control(
				'input_outline_color',
				[
					'label'   => __( 'Input Outline Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#000000',
				]
			);
			$this->end_controls_tab();

			// Active
			$this->start_controls_tab( 'input_field_active', [ 'label' => __( 'Active', 'menu-login' ) ] );
			$this->add_control(
				'input_active_background_color',
				[
					'label'   => __( 'Active Background Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#ffffff',
				]
			);
			$this->add_control(
				'input_active_text_color',
				[
					'label'   => __( 'Active Text Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#333333',
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => 'input_typography_active',
					'label'    => __( 'Typography (Active)', 'menu-login' ),
					'selector' => '#menu-login-ajax-form-{{ID}} input:active, #menu-register-ajax-form-{{ID}} input:active',
				]
			);
			$this->add_responsive_control(
				'input_active_border_radius',
				[
					'label'   => __( 'Active Border Radius', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::DIMENSIONS,
					'default' => [
						'top'    => '4',
						'right'  => '4',
						'bottom' => '4',
						'left'   => '4',
						'unit'   => 'px',
						'isLinked' => true,
					],
				]
			);
			$this->add_control(
				'input_active_border_color',

				[
					'label'   => __( 'Active Border Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#ccc',
				]
			);
			$this->add_responsive_control(
				'input_active_outline_width',
				[
					'label'   => __( 'Active Outline Width', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::DIMENSIONS,
				]
			);
			$this->add_control(
				'input_active_outline_style',
				[
					'label'   => __( 'Active Outline Style', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'none'   => __( 'None', 'menu-login' ),
						'solid'  => __( 'Solid', 'menu-login' ),
						'dashed' => __( 'Dashed', 'menu-login' ),
						'dotted' => __( 'Dotted', 'menu-login' ),
						'double' => __( 'Double', 'menu-login' ),
					],
					'default' => 'none',
				]
			);
			$this->add_control(
				'input_active_outline_color',
				[
					'label'   => __( 'Active Outline Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#000000',
				]
			);
			$this->end_controls_tab();

			// Focused
			$this->start_controls_tab( 'input_field_focused', [ 'label' => __( 'Focused', 'menu-login' ) ] );
			$this->add_control(
				'input_focused_background_color',
				[
					'label'   => __( 'Focused Background Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#f7f7f7',
				]
			);
			$this->add_control(
				'input_focused_text_color',
				[
					'label'   => __( 'Focused Text Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#333333',
				]
			);
			$this->add_control(
				'input_focused_border_color',
				[
					'label'   => __( 'Focused Border Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#ccc',
				]
			);
			$this->add_responsive_control(
				'input_focused_border_radius',
				[
					'label'   => __( 'Focused Border Radius', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::DIMENSIONS,
					'default' => [
						'top'    => '4',
						'right'  => '4',
						'bottom' => '4',
						'left'   => '4',
						'unit'   => 'px',
						'isLinked' => true,
					],
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => 'input_typography_focused',
					'label'    => __( 'Typography (Focused)', 'menu-login' ),
					'selector' => '#menu-login-ajax-form-{{ID}} input:focus, #menu-register-ajax-form-{{ID}} input:focus, .menu-login-wrapper-{{ID}} input:focus',
				]
			);
			$this->add_responsive_control(
				'input_focused_outline_width',
				[
					'label'   => __( 'Focused Outline Width', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::DIMENSIONS,
				]
			);
			$this->add_control(
				'input_focused_outline_style',
				[
					'label'   => __( 'Focused Outline Style', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'none'   => __( 'None', 'menu-login' ),
						'solid'  => __( 'Solid', 'menu-login' ),
						'dashed' => __( 'Dashed', 'menu-login' ),
						'dotted' => __( 'Dotted', 'menu-login' ),
						'double' => __( 'Double', 'menu-login' ),
					],
					'default' => 'none',
				]
			);
			$this->add_control(
				'input_focused_outline_color',
				[
					'label'   => __( 'Focused Outline Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#000000',
				]
			);
			$this->end_controls_tab();
			$this->end_controls_tabs();
			$this->end_controls_section();

			// -------------------------------------------
			// STYLE TAB: Login Loading Style
			// -------------------------------------------
			$this->start_controls_section(
				'section_login_loading_style',
				[
					'label' => __( 'Login Loading Style', 'menu-login' ),
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'login_loading_padding',
				[
					'label'      => __( 'Loading Padding', 'menu-login' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'default'    => [
						'top'    => '20',
						'right'  => '20',
						'bottom' => '20',
						'left'   => '20',
						'unit'   => 'px',
						'isLinked' => true,
					],
				]
			);
			$this->add_control(
				'login_loading_background_color',
				[
					'label'   => __( 'Loading Background Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => 'transparent',
				]
			);
			$this->add_control(
				'login_loading_svg_color',
				[
					'label'   => __( 'Loading SVG Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#333333',
				]
			);
			$this->end_controls_section();

			// -------------------------------------------
			// STYLE TAB: Login Error Message Style
			// -------------------------------------------
			$this->start_controls_section(
				'section_login_error_style',
				[
					'label' => __( 'Login Error Message Style', 'menu-login' ),
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'login_error_text_color',
				[
					'label'   => __( 'Error Text Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#ff0000',
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => 'login_error_typography',
					'label'    => __( 'Typography', 'menu-login' ),
					'selector' => '#menu-login-ajax-popup-{{ID}} #menu-login-error-msg-{{ID}}',
				]
			);
			$this->add_responsive_control(
				'login_error_padding',
				[
					'label'      => __( 'Error Padding', 'menu-login' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'default'    => [
						'top'    => '10',
						'right'  => '10',
						'bottom' => '10',
						'left'   => '10',
						'unit'   => 'px',
						'isLinked' => true,
					],
				]
			);
			$this->end_controls_section();

			// -------------------------------------------
			// STYLE TAB: Login Title Style
			// -------------------------------------------
			$this->start_controls_section(
				'section_login_title_style',
				[
					'label' => __( 'Login Title', 'menu-login' ),
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'login_title_color',
				[
					'label'   => __( 'Title Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#ffffff',
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => 'login_title_typography',
					'label'    => __( 'Typography', 'menu-login' ),
					'selector' => '#menu-login-ajax-popup-{{ID}} .menu-login-title',
				]
			);
			$this->end_controls_section();

			// -------------------------------------------
			// STYLE TAB: Login Field Label Style
			// -------------------------------------------
			$this->start_controls_section(
				'section_login_field_label_style',
				[
					'label' => __( 'Login Field Label', 'menu-login' ),
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'login_field_label_color',
				[
					'label'   => __( 'Label Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#ffffff',
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => 'login_field_label_typography',
					'label'    => __( 'Typography', 'menu-login' ),
					'selector' => '#menu-login-ajax-popup-{{ID}} .menu-login-field-label',
				]
			);
			$this->end_controls_section();

			// -------------------------------------------
			// STYLE TAB: Login Button Style
			// -------------------------------------------
			$this->start_controls_section(
				'section_login_button_style',
				[
					'label' => __( 'Login Button Style', 'menu-login' ),
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
			$this->start_controls_tabs( 'login_button_tabs' );
			// Normal
			$this->start_controls_tab( 'login_button_normal', [ 'label' => __( 'Normal', 'menu-login' ) ] );
			$this->add_control(
				'login_button_text_color',
				[
					'label'   => __( 'Button Text Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#ffffff',
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => 'login_button_typography_normal',
					'label'    => __( 'Typography', 'menu-login' ),
					'selector' => '{{WRAPPER}} .menu-login-button',
				]
			);
			$this->add_responsive_control(
				'login_button_padding',
				[
					'label'      => __( 'Button Padding', 'menu-login' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'default'    => [
						'top'    => '8',
						'right'  => '12',
						'bottom' => '8',
						'left'   => '12',
						'unit'   => 'px',
						'isLinked' => true,
					],
				]
			);
			$this->add_responsive_control(
				'login_button_margin',
				[
					'label'      => __( 'Button Margin', 'menu-login' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'default'    => [
						'top'    => '10',
						'right'  => '0',
						'bottom' => '0',
						'left'   => '0',
						'unit'   => 'px',
						'isLinked' => false,
					],
				]
			);
			$this->add_control(
				'login_button_background_color',
				[
					'label'   => __( 'Button Background Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#0073aa',
				]
			);
			$this->add_control(
				'login_button_border_style',
				[
					'label'   => __( 'Button Border Style', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'none'   => __( 'None', 'menu-login' ),
						'solid'  => __( 'Solid', 'menu-login' ),
						'dashed' => __( 'Dashed', 'menu-login' ),
						'dotted' => __( 'Dotted', 'menu-login' ),
						'double' => __( 'Double', 'menu-login' ),
					],
					'default' => 'none',
				]
			);
			$this->add_responsive_control(
				'login_button_border_color',
				[
					'label'   => __( 'Button Border Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#0073aa',
				]
			);
			$this->add_responsive_control(
				'login_button_border_radius',
				[
					'label'      => __( 'Button Border Radius', 'menu-login' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'default'    => [
						'top'    => '4',
						'right'  => '4',
						'bottom' => '4',
						'left'   => '4',
						'unit'   => 'px',
						'isLinked' => true,
					],
				]
			);
			$this->add_responsive_control(
				'login_button_border_width',
				[
					'label'      => __( 'Button Border Width', 'menu-login' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'default'    => [
						'top'    => '1',
						'right'  => '1',
						'bottom' => '1',
						'left'   => '1',
						'unit'   => 'px',
						'isLinked' => true,
					],
				]
			);
			$this->end_controls_tab();

			// Hover
			$this->start_controls_tab( 'login_button_hover', [ 'label' => __( 'Hover', 'menu-login' ) ] );
			$this->add_control(
				'login_button_hover_text_color',
				[
					'label'   => __( 'Button Text Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#ffffff',
				]
			);
			$this->add_control(
				'login_button_hover_background_color',
				[
					'label'   => __( 'Button Background Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#006799',
				]
			);
			$this->add_responsive_control(
				'login_button_hover_border_color',
				[
					'label'   => __( 'Button Border Color (Hover)', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#006799',
				]
			);
			$this->end_controls_tab();
			$this->end_controls_tabs();
			$this->end_controls_section();

			// -------------------------------------------
			// STYLE TAB: Cancel Button Style
			// -------------------------------------------
			$this->start_controls_section(
				'section_cancel_button_style',
				[
					'label' => __( 'Cancel Button Style', 'menu-login' ),
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
			$this->start_controls_tabs( 'cancel_button_tabs' );
			// Normal
			$this->start_controls_tab( 'cancel_button_normal', [ 'label' => __( 'Normal', 'menu-login' ) ] );
			$this->add_control(
				'cancel_button_background_color',
				[
					'label'   => __( 'Button Background Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#cccccc',
				]
			);
			$this->add_control(
				'cancel_button_border_style',
				[
					'label'   => __( 'Button Border Style', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'none'   => __( 'None', 'menu-login' ),
						'solid'  => __( 'Solid', 'menu-login' ),
						'dashed' => __( 'Dashed', 'menu-login' ),
						'dotted' => __( 'Dotted', 'menu-login' ),
						'double' => __( 'Double', 'menu-login' ),
					],
					'default' => 'none',
				]
			);
			$this->add_responsive_control(
				'cancel_button_border_color',
				[
					'label'   => __( 'Button Border Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#cccccc',
				]
			);
			$this->add_responsive_control(
				'cancel_button_border_radius',
				[
					'label'      => __( 'Button Border Radius', 'menu-login' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'default'    => [
						'top'    => '4',
						'right'  => '4',
						'bottom' => '4',
						'left'   => '4',
						'unit'   => 'px',
						'isLinked' => true,
					],
				]
			);
			$this->add_responsive_control(
				'cancel_button_border_width',
				[
					'label'      => __( 'Button Border Width', 'menu-login' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'default'    => [
						'top'    => '1',
						'right'  => '1',
						'bottom' => '1',
						'left'   => '1',
						'unit'   => 'px',
						'isLinked' => true,
					],
				]
			);
			$this->add_responsive_control(
				'cancel_button_margin',
				[
					'label'      => __( 'Button Margin', 'menu-login' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'default'    => [
						'top'    => '10',
						'right'  => '0',
						'bottom' => '0',
						'left'   => '0',
						'unit'   => 'px',
						'isLinked' => false,
					],
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => 'cancel_button_typography',
					'label'    => __( 'Typography', 'menu-login' ),
					'selector' => '#menu-login-ajax-popup-{{ID}} .menu-login-cancel-button',
				]
			);
			$this->add_control(
				'cancel_button_text_color',
				[
					'label'   => __( 'Text Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#000000',
				]
			);
			$this->end_controls_tab();
			// Hover
			$this->start_controls_tab( 'cancel_button_active', [ 'label' => __( 'Hover', 'menu-login' ) ] );
			$this->add_control(
				'cancel_button_active_background_color',
				[
					'label'   => __( 'Button Background Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#aaaaaa',
				]
			);
			$this->add_control(
				'cancel_button_active_border_color',
				[
					'label'   => __( 'Button Border Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#aaaaaa',
				]
			);
			$this->add_control(
				'cancel_button_active_text_color',
				[
					'label'   => __( 'Text Color (Hover)', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#000000',
				]
			);
			$this->end_controls_tab();
			$this->end_controls_tabs();
			$this->end_controls_section();

			// -------------------------------------------
			// STYLE TAB: Register Link Style
			// -------------------------------------------
			$this->start_controls_section(
				'section_register_link_style',
				[
					'label' => __( 'Register Link', 'menu-login' ),
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
			$this->start_controls_tabs( 'register_link_tabs' );
			// Normal
			$this->start_controls_tab( 'register_link_normal', [ 'label' => __( 'Normal', 'menu-login' ) ] );
			$this->add_control(
				'register_link_text_color',
				[
					'label'   => __( 'Text Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#0073aa',
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => 'register_link_typography',
					'label'    => __( 'Typography', 'menu-login' ),
					'selector' => '{{WRAPPER}} .menu-login-register a',
				]
			);
			$this->end_controls_tab();
			// Hover
			$this->start_controls_tab( 'register_link_hover', [ 'label' => __( 'Hover', 'menu-login' ) ] );
			$this->add_control(
				'register_link_hover_text_color',
				[
					'label'   => __( 'Text Hover Color', 'menu-login' ),
					'type'    => \Elementor\Controls_Manager::COLOR,
					'default' => '#005177',
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => 'register_link_typography_hover',
					'label'    => __( 'Typography (Hover)', 'menu-login' ),
					'selector' => '{{WRAPPER}} .menu-login-register a:hover',
				]
			);
			$this->end_controls_tab();
			$this->end_controls_tabs();
			$this->end_controls_section();
		} // end register_controls()

		/**
		 * Render the widget output.
		 */
		protected function render() {
			$settings      = $this->get_settings_for_display();
			$wrapper_class = 'menu-login-wrapper-' . $this->get_id();
			$force_popup   = \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false;

			if ( is_user_logged_in() ) {
				$icon_link = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : '#';
			} else {
				$icon_link = ( 'yes' === $settings['enable_ajax'] )
					? '#'
					: ( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url() );
			}

			$customer_name = '';
			if ( is_user_logged_in() ) {
				$user_id   = get_current_user_id();
				$user_data = get_userdata( $user_id );
				$username  = isset( $user_data->user_login ) ? $user_data->user_login : '';
				$customer_name = ( mb_strlen( $username ) > 20 ) ? mb_substr( $username, 0, 17 ) . '...' : $username;
			}

			$breaks    = $this->get_elementor_breakpoints();
			$tablet_bp = $breaks['tablet'];
			$mobile_bp = $breaks['mobile'];

			$desktop_msg_layout = $settings['menu_message_layout'] ?? 'row';
			$tablet_msg_layout  = $settings['menu_message_layout_tablet'] ?? $desktop_msg_layout;
			$mobile_msg_layout  = $settings['menu_message_layout_mobile'] ?? $tablet_msg_layout;

			$desktop_message_css = ( $desktop_msg_layout === 'off' )
				? 'display: none;'
				: 'display: flex; flex-direction: ' . ( $desktop_msg_layout === 'row' ? 'row' : 'column' ) . ';';
			$tablet_message_css  = ( $tablet_msg_layout === 'off' )
				? 'display: none;'
				: 'display: flex; flex-direction: ' . ( $tablet_msg_layout === 'row' ? 'row' : 'column' ) . ';';
			$mobile_message_css  = ( $mobile_msg_layout === 'off' )
				? 'display: none;'
				: 'display: flex; flex-direction: ' . ( $mobile_msg_layout === 'row' ? 'row' : 'column' ) . ';';

			// Build dynamic CSS string.
			$dynamic_css = "
			/* Desktop Styles */
			.{$wrapper_class} .menu-login-wrapper {
				margin: " . $this->print_dimension( $settings['menu_login_margin'] ) . ";
			}
			.{$wrapper_class} .menu-login-messages { {$desktop_message_css} gap:4px; }
			.{$wrapper_class} .menu-login-icon.unlogged {
				background-color: " . esc_html( $settings['logged_out_icon_background_color'] ) . ";
				width: " . $this->print_single_dimension( $settings['logged_out_icon_size'] ) . ";
				height: " . $this->print_single_dimension( $settings['logged_out_icon_size'] ) . ";
				padding: " . $this->print_dimension( $settings['logged_out_icon_padding'] ) . ";
				border-radius: " . $this->print_dimension( $settings['logged_out_icon_border_radius'] ) . ";
				border-style: " . esc_html( $settings['logged_out_icon_border_style'] ) . ";
				border-width: " . $this->print_dimension( $settings['logged_out_icon_border_width'] ) . ";
				border-color: " . esc_html( $settings['logged_out_icon_border_color'] ) . ";
			}
			.{$wrapper_class} .menu-login-icon.unlogged .menu-login-svg-icon {
				fill: " . esc_html( $settings['logged_out_icon_color'] ) . " !important;
			}
			.{$wrapper_class} .menu-login-link:hover .menu-login-icon.unlogged {
				background-color: " . esc_html( $settings['logged_out_icon_hover_background_color'] ) . ";
				border-radius: " . $this->print_dimension( $settings['logged_out_icon_hover_border_radius'] ) . ";
				border-style: " . esc_html( $settings['logged_out_icon_hover_border_style'] ) . ";
				border-width: " . $this->print_dimension( $settings['logged_out_icon_hover_border_width'] ) . ";
				border-color: " . esc_html( $settings['logged_out_icon_hover_border_color'] ) . ";
			}
			.{$wrapper_class} .menu-login-link:hover .menu-login-icon.unlogged .menu-login-svg-icon {
				fill: " . esc_html( $settings['logged_out_icon_hover_color'] ) . " !important;
			}
			.{$wrapper_class} .menu-login-icon.loggedin-new {
				color: " . esc_html( $settings['logged_in_letter_color'] ) . " !important;
				background-color: " . esc_html( $settings['logged_in_letter_background_color'] ) . " !important;
				padding: " . $this->print_dimension( $settings['logged_in_letter_padding'] ) . " !important;
				border-radius: " . $this->print_dimension( $settings['logged_in_letter_border_radius'] ) . " !important;
				border-style: " . esc_html( $settings['logged_in_letter_border_style'] ) . " !important;
				border-width: " . $this->print_dimension( $settings['logged_in_letter_border_width'] ) . " !important;
				border-color: " . esc_html( $settings['logged_in_letter_border_color'] ) . " !important;
				display: flex !important; align-items: center !important; justify-content: center !important; text-align: center !important;
			}
			.{$wrapper_class} .menu-login-link:hover .menu-login-icon.loggedin-new {
				color: " . esc_html( $settings['logged_in_letter_hover_color'] ) . " !important;
				background-color: " . esc_html( $settings['logged_in_letter_hover_background_color'] ) . " !important;
				border-radius: " . $this->print_dimension( $settings['logged_in_letter_hover_border_radius'] ) . " !important;
				border-style: " . esc_html( $settings['logged_in_letter_hover_border_style'] ) . " !important;
				border-width: " . $this->print_dimension( $settings['logged_in_letter_hover_border_width'] ) . " !important;
				border-color: " . esc_html( $settings['logged_in_letter_hover_border_color'] ) . " !important;
			}
			.{$wrapper_class} a .menu-login-icon.unlogged ~ .menu-login-messages .menu-login-welcome {
				color: " . esc_html( $settings['welcome_message_text_color'] ) . ";
			}
			.{$wrapper_class} .menu-login-icon.loggedin-new + .menu-login-messages .menu-login-welcome {
				color: " . esc_html( $settings['message_text_color'] ) . ";
			}
			.{$wrapper_class} .menu-login-icon.loggedin-new + .menu-login-messages .menu-login-welcome:hover {
				color: " . esc_html( $settings['message_text_hover_color'] ) . ";
			}
			.{$wrapper_class} .menu-login-user-name {
				color: " . esc_html( $settings['customer_name_color'] ) . ";
			}
			.{$wrapper_class} .menu-login-link:hover .menu-login-user-name {
				color: " . esc_html( $settings['customer_name_hover_color'] ) . ";
			}
			#menu-login-ajax-popup-" . esc_attr( $this->get_id() ) . " {
				background-color: rgba(0,0,0,0.7);
			}
			#menu-login-ajax-content-" . esc_attr( $this->get_id() ) . " {
				background-color: " . esc_html( $settings['login_popup_background_color'] ) . ";
				padding: " . $this->print_dimension( $settings['login_popup_padding'] ) . ";
				border-radius: " . $this->print_dimension( $settings['login_popup_border_radius'] ) . ";
				border: " . $this->print_dimension( $settings['login_popup_border_width'] ) . " " . esc_html( $settings['login_popup_border_style'] ) . " " . esc_html( $settings['login_popup_border_color'] ) . ";
				margin: " . $this->print_dimension( $settings['login_popup_margin'] ) . ";
			}
			#menu-login-ajax-form-" . esc_attr( $this->get_id() ) . " input,
			#menu-register-ajax-form-" . esc_attr( $this->get_id() ) . " input {
				background-color: " . esc_html( $settings['input_background_color'] ) . ";
				color: " . esc_html( $settings['input_text_color'] ) . ";
				border-radius: " . $this->print_dimension( $settings['input_border_radius'] ) . ";
				border-color: " . esc_html( $settings['input_border_color'] ) . ";
				border-style: " . esc_html( $settings['input_border_style'] ) . ";
				border-width: " . $this->print_dimension( $settings['input_border_width'] ) . ";
				outline-width: " . $this->print_dimension( $settings['input_outline_width'] ) . ";
				outline-style: " . esc_html( $settings['input_outline_style'] ) . ";
				outline-color: " . esc_html( $settings['input_outline_color'] ) . ";
			}
			#menu-login-ajax-form-" . esc_attr( $this->get_id() ) . " input:active,
			#menu-register-ajax-form-" . esc_attr( $this->get_id() ) . " input:active {
				background-color: " . esc_html( $settings['input_active_background_color'] ) . ";
				color: " . esc_html( $settings['input_active_text_color'] ) . ";
				border-color: " . esc_html( $settings['input_active_border_color'] ) . ";
				border-radius: " . $this->print_dimension( $settings['input_active_border_radius'] ) . ";
				outline-width: " . $this->print_dimension( $settings['input_active_outline_width'] ) . ";
				outline-style: " . esc_html( $settings['input_active_outline_style'] ) . ";
				outline-color: " . esc_html( $settings['input_active_outline_color'] ) . ";
			}
			#menu-login-ajax-form-" . esc_attr( $this->get_id() ) . " input:focus,
			#menu-register-ajax-form-" . esc_attr( $this->get_id() ) . " input:focus,
			.menu-login-wrapper-" . esc_attr( $this->get_id() ) . " input:focus {
				background-color: " . esc_html( $settings['input_focused_background_color'] ) . " !important;
				color: " . esc_html( $settings['input_focused_text_color'] ) . " !important;
				border-color: " . esc_html( $settings['input_focused_border_color'] ) . " !important;
				border-radius: " . $this->print_dimension( $settings['input_focused_border_radius'] ) . " !important;
				outline-width: " . $this->print_dimension( $settings['input_focused_outline_width'] ) . ";
				outline-style: " . esc_html( $settings['input_focused_outline_style'] ) . ";
				outline-color: " . esc_html( $settings['input_focused_outline_color'] ) . ";
			}
			#menu-login-loading-" . esc_attr( $this->get_id() ) . " div {
				padding: " . $this->print_dimension( $settings['login_loading_padding'] ) . ";
				background-color: " . esc_html( $settings['login_loading_background_color'] ) . ";
				border-top-color: " . esc_html( $settings['login_loading_svg_color'] ) . ";
				border-radius: 50%;
				animation: spin 1s linear infinite;
			}
			#menu-login-error-msg-" . esc_attr( $this->get_id() ) . " {
				color: " . esc_html( $settings['login_error_text_color'] ) . ";
				padding: " . $this->print_dimension( $settings['login_error_padding'] ) . ";
			}
			#menu-login-ajax-popup-" . esc_attr( $this->get_id() ) . " .menu-login-title {
				color: " . esc_html( $settings['login_title_color'] ) . ";
			}
			#menu-login-ajax-popup-" . esc_attr( $this->get_id() ) . " .menu-login-field-label {
				color: " . esc_html( $settings['login_field_label_color'] ) . ";
			}
			#menu-login-ajax-popup-" . esc_attr( $this->get_id() ) . " .menu-login-button {
				color: " . esc_html( $settings['login_button_text_color'] ) . ";
				padding: " . $this->print_dimension( $settings['login_button_padding'] ) . ";
				margin: " . $this->print_dimension( $settings['login_button_margin'] ) . ";
				background-color: " . esc_html( $settings['login_button_background_color'] ) . ";
				border-style: " . esc_html( $settings['login_button_border_style'] ) . ";
				border-color: " . esc_html( $settings['login_button_border_color'] ) . ";
				border-radius: " . $this->print_dimension( $settings['login_button_border_radius'] ) . ";
				border-width: " . $this->print_dimension( $settings['login_button_border_width'] ) . ";
			}
			#menu-login-ajax-popup-" . esc_attr( $this->get_id() ) . " .menu-login-button:hover {
				color: " . esc_html( $settings['login_button_hover_text_color'] ) . ";
				background-color: " . esc_html( $settings['login_button_hover_background_color'] ) . ";
				border-color: " . esc_html( $settings['login_button_hover_border_color'] ) . ";
			}
			#menu-login-ajax-popup-" . esc_attr( $this->get_id() ) . " .menu-login-cancel-button {
				color: " . esc_html( $settings['cancel_button_text_color'] ) . ";
				background-color: " . esc_html( $settings['cancel_button_background_color'] ) . ";
				border-style: " . esc_html( $settings['cancel_button_border_style'] ) . ";
				border-color: " . esc_html( $settings['cancel_button_border_color'] ) . ";
				border-radius: " . $this->print_dimension( $settings['cancel_button_border_radius'] ) . ";
				border-width: " . $this->print_dimension( $settings['cancel_button_border_width'] ) . ";
				margin: " . $this->print_dimension( $settings['cancel_button_margin'] ) . ";
			}
			#menu-login-ajax-popup-" . esc_attr( $this->get_id() ) . " .menu-login-cancel-button:hover {
				color: " . esc_html( $settings['cancel_button_active_text_color'] ) . ";
				background-color: " . esc_html( $settings['cancel_button_active_background_color'] ) . ";
				border-color: " . esc_html( $settings['cancel_button_active_border_color'] ) . ";
			}
			.menu-login-register a {
				color: " . esc_html( $settings['register_link_text_color'] ) . ";
			}
			.menu-login-register a:hover {
				color: " . esc_html( $settings['register_link_hover_text_color'] ) . ";
			}
			/* Tablet Styles */
			@media (max-width: {$tablet_bp}px) {
				.{$wrapper_class} .menu-login-wrapper {
					margin: " . $this->print_dimension( $settings['menu_login_margin_tablet'] ?? $settings['menu_login_margin'] ) . ";
				}
				.{$wrapper_class} .menu-login-messages { {$tablet_message_css} }
				.{$wrapper_class} .menu-login-icon.unlogged {
					width: " . $this->print_single_dimension( $settings['logged_out_icon_size_tablet'] ?? $settings['logged_out_icon_size'] ) . ";
					height: " . $this->print_single_dimension( $settings['logged_out_icon_size_tablet'] ?? $settings['logged_out_icon_size'] ) . ";
					padding: " . $this->print_dimension( $settings['logged_out_icon_padding_tablet'] ?? $settings['logged_out_icon_padding'] ) . ";
					border-radius: " . $this->print_dimension( $settings['logged_out_icon_border_radius_tablet'] ?? $settings['logged_out_icon_border_radius'] ) . ";
					border-width: " . $this->print_dimension( $settings['logged_out_icon_border_width_tablet'] ?? $settings['logged_out_icon_border_width'] ) . ";
				}
				.{$wrapper_class} .menu-login-icon.loggedin-new {
					padding: " . $this->print_dimension( $settings['logged_in_letter_padding_tablet'] ?? $settings['logged_in_letter_padding'] ) . " !important;
					border-radius: " . $this->print_dimension( $settings['logged_in_letter_border_radius_tablet'] ?? $settings['logged_in_letter_border_radius'] ) . " !important;
					border-width: " . $this->print_dimension( $settings['logged_in_letter_border_width_tablet'] ?? $settings['logged_in_letter_border_width'] ) . " !important;
				}
				#menu-login-ajax-content-" . esc_attr( $this->get_id() ) . " {
					padding: " . $this->print_dimension( $settings['login_popup_padding_tablet'] ?? $settings['login_popup_padding'] ) . ";
					border-radius: " . $this->print_dimension( $settings['login_popup_border_radius_tablet'] ?? $settings['login_popup_border_radius'] ) . ";
					border-width: " . $this->print_dimension( $settings['login_popup_border_width_tablet'] ?? $settings['login_popup_border_width'] ) . ";
					margin: " . $this->print_dimension( $settings['login_popup_margin_tablet'] ?? $settings['login_popup_margin'] ) . ";
				}
				#menu-login-ajax-form-" . esc_attr( $this->get_id() ) . " input,
				#menu-register-ajax-form-" . esc_attr( $this->get_id() ) . " input {
					border-radius: " . $this->print_dimension( $settings['input_border_radius_tablet'] ?? $settings['input_border_radius'] ) . ";
					border-width: " . $this->print_dimension( $settings['input_border_width_tablet'] ?? $settings['input_border_width'] ) . ";
					outline-width: " . $this->print_dimension( $settings['input_outline_width_tablet'] ?? $settings['input_outline_width'] ) . ";
				}
				#menu-login-ajax-form-" . esc_attr( $this->get_id() ) . " input:active,
				#menu-register-ajax-form-" . esc_attr( $this->get_id() ) . " input:active {
					border-radius: " . $this->print_dimension( $settings['input_active_border_radius_tablet'] ?? $settings['input_active_border_radius'] ) . ";
					outline-width: " . $this->print_dimension( $settings['input_active_outline_width_tablet'] ?? $settings['input_active_outline_width'] ) . ";
				}
			}
			/* Mobile Styles */
			@media (max-width: {$mobile_bp}px) {
				.{$wrapper_class} .menu-login-wrapper {
					margin: " . $this->print_dimension( $settings['menu_login_margin_mobile'] ?? $settings['menu_login_margin'] ) . ";
				}
				.{$wrapper_class} .menu-login-messages { {$mobile_message_css} }
				.{$wrapper_class} .menu-login-icon.unlogged {
					width: " . $this->print_single_dimension( $settings['logged_out_icon_size_mobile'] ?? $settings['logged_out_icon_size'] ) . ";
					height: " . $this->print_single_dimension( $settings['logged_out_icon_size_mobile'] ?? $settings['logged_out_icon_size'] ) . ";
					padding: " . $this->print_dimension( $settings['logged_out_icon_padding_mobile'] ?? $settings['logged_out_icon_padding'] ) . ";
					border-radius: " . $this->print_dimension( $settings['logged_out_icon_border_radius_mobile'] ?? $settings['logged_out_icon_border_radius'] ) . ";
					border-width: " . $this->print_dimension( $settings['logged_out_icon_border_width_mobile'] ?? $settings['logged_out_icon_border_width'] ) . ";
				}
				.{$wrapper_class} .menu-login-icon.loggedin-new {
					padding: " . $this->print_dimension( $settings['logged_in_letter_padding_mobile'] ?? $settings['logged_in_letter_padding'] ) . " !important;
					border-radius: " . $this->print_dimension( $settings['logged_in_letter_border_radius_mobile'] ?? $settings['logged_in_letter_border_radius'] ) . " !important;
					border-width: " . $this->print_dimension( $settings['logged_in_letter_border_width_mobile'] ?? $settings['logged_in_letter_border_width'] ) . " !important;
				}
				#menu-login-ajax-content-" . esc_attr( $this->get_id() ) . " {
					padding: " . $this->print_dimension( $settings['login_popup_padding_mobile'] ?? $settings['login_popup_padding'] ) . ";
					border-radius: " . $this->print_dimension( $settings['login_popup_border_radius_mobile'] ?? $settings['login_popup_border_radius'] ) . ";
					border-width: " . $this->print_dimension( $settings['login_popup_border_width_mobile'] ?? $settings['login_popup_border_width'] ) . ";
					margin: " . $this->print_dimension( $settings['login_popup_margin_mobile'] ?? $settings['login_popup_margin'] ) . ";
				}
				#menu-login-ajax-form-" . esc_attr( $this->get_id() ) . " input,
				#menu-register-ajax-form-" . esc_attr( $this->get_id() ) . " input {
					border-radius: " . $this->print_dimension( $settings['input_border_radius_mobile'] ?? $settings['input_border_radius'] ) . ";
					border-width: " . $this->print_dimension( $settings['input_border_width_mobile'] ?? $settings['input_border_width'] ) . ";
					outline-width: " . $this->print_dimension( $settings['input_outline_width_mobile'] ?? $settings['input_outline_width'] ) . ";
				}
				#menu-login-ajax-form-" . esc_attr( $this->get_id() ) . " input:active,
				#menu-register-ajax-form-" . esc_attr( $this->get_id() ) . " input:active {
					border-radius: " . $this->print_dimension( $settings['input_active_border_radius_mobile'] ?? $settings['input_active_border_radius'] ) . ";
					outline-width: " . $this->print_dimension( $settings['input_active_outline_width_mobile'] ?? $settings['input_active_outline_width'] ) . ";
				}
			}
			";

			// Enqueue the static style and add inline dynamic CSS.
			wp_enqueue_style( 'menu-login-style' );
			wp_add_inline_style( 'menu-login-style', $dynamic_css );

			?>
			<div class="menu-login-wrapper <?php echo esc_attr( $wrapper_class ); ?>" style="display:inline-flex;align-items:center;gap:8px;">
				<a href="<?php echo esc_url( $icon_link ); ?>" class="menu-login-link"
				   <?php if ( ! is_user_logged_in() && 'yes' === $settings['enable_ajax'] ) : ?>
					   id="menu-login-ajax-trigger-<?php echo esc_attr( $this->get_id() ); ?>"
				   <?php endif; ?>
				>
					<?php
					if ( is_user_logged_in() ) {
						$user_id   = get_current_user_id();
						$user_data = get_userdata( $user_id );
						$avatar_url = get_avatar_url( $user_id, [ 'default' => '404' ] );
						if ( false !== strpos( $avatar_url, '404' ) ) {
							$letter = ( ! empty( $user_data->user_login ) ) ? strtoupper( mb_substr( $user_data->user_login, 0, 1 ) ) : '?';
							echo '<div class="menu-login-icon loggedin-new">' . esc_html( $letter ) . '</div>';
						} else {
							echo '<div class="menu-login-icon loggedin-new">' . get_avatar( $user_id, 96, '404', 'Avatar', [ 'class' => 'menu-login-svg-icon avatar-img' ] ) . '</div>';
						}
						?>
						<div class="menu-login-messages" style="gap:4px;">
							<span class="menu-login-message menu-login-welcome"><?php echo esc_html( $settings['welcome_message'] ); ?></span>
							<span class="menu-login-message menu-login-user-name"><?php echo esc_html( $customer_name ); ?></span>
						</div>
						<?php
					} else {
						?>
						<div class="menu-login-icon unlogged">
							<?php
							if ( ! empty( $settings['icon'] ) ) {
								\Elementor\Icons_Manager::render_icon(
									$settings['icon'],
									[ 'aria-hidden' => 'true', 'class' => 'menu-login-svg-icon' ],
									'i'
								);
							}
							?>
						</div>
						<div class="menu-login-messages" style="gap:4px;">
							<span class="menu-login-message menu-login-welcome"><?php echo esc_html( $settings['welcome_message'] ); ?></span>
							<span class="menu-login-message menu-login-login-text"><?php echo esc_html( 'login' ); ?></span>
						</div>
						<?php
					}
					?>
				</a>
			</div>

			<?php if ( ( ! is_user_logged_in() || $force_popup ) && 'yes' === $settings['enable_ajax'] ) : ?>
				<div id="menu-login-ajax-popup-<?php echo esc_attr( $this->get_id() ); ?>" class="menu-login-ajax-popup-gpt" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background-color:rgba(0,0,0,0.7); justify-content:center; align-items:center; z-index:999999;">
					<div id="menu-login-ajax-content-<?php echo esc_attr( $this->get_id() ); ?>" style="max-width:300px; width:90%; text-align:center;">
						<div id="menu-login-form-container-<?php echo esc_attr( $this->get_id() ); ?>">
							<h3 class="menu-login-title" style="margin-bottom:10px;"><?php echo esc_html( $settings['login_title'] ); ?></h3>
							<form id="menu-login-ajax-form-<?php echo esc_attr( $this->get_id() ); ?>">
								<label class="menu-login-field-label" style="width:100%; text-align:left;"><?php esc_html_e( 'Username', 'menu-login' ); ?></label>
								<input type="text" id="menu-login-username-<?php echo esc_attr( $this->get_id() ); ?>" style="width:100%; margin-bottom:10px;" />
								<label class="menu-login-field-label" style="width:100%; text-align:left;"><?php esc_html_e( 'Password', 'menu-login' ); ?></label>
								<input type="password" id="menu-login-password-<?php echo esc_attr( $this->get_id() ); ?>" style="width:100%; margin-bottom:10px;" />
								<button type="submit" class="menu-login-button" style="cursor:pointer;"><?php esc_html_e( 'Log In', 'menu-login' ); ?></button>
								<button type="button" class="menu-login-cancel-button" id="menu-login-ajax-cancel-<?php echo esc_attr( $this->get_id() ); ?>" style="cursor:pointer; margin-left:10px;"><?php esc_html_e( 'Cancel', 'menu-login' ); ?></button>
							</form>
							<div id="menu-login-error-msg-<?php echo esc_attr( $this->get_id() ); ?>" style="margin-top:10px;"></div>
							<div class="menu-login-register" style="margin-top:15px; text-align:right;">
								<a href="#" class="menu-toggle-link" style="text-decoration:underline;"><?php esc_html_e( 'Register', 'menu-login' ); ?></a>
							</div>
						</div>
						<div id="menu-register-form-container-<?php echo esc_attr( $this->get_id() ); ?>" style="display:none;">
							<h3 class="menu-login-title" style="margin-bottom:10px;"><?php esc_html_e( 'Register', 'menu-login' ); ?></h3>
							<form id="menu-register-ajax-form-<?php echo esc_attr( $this->get_id() ); ?>">
								<label class="menu-login-field-label" style="width:100%; text-align:left;"><?php esc_html_e( 'Email', 'menu-login' ); ?></label>
								<input type="email" id="menu-register-email-<?php echo esc_attr( $this->get_id() ); ?>" style="width:100%; margin-bottom:10px;" />
								<div style="display:flex; justify-content: space-between; margin-bottom:10px;">
									<button type="submit" class="menu-login-button" style="cursor:pointer;"><?php esc_html_e( 'Register', 'menu-login' ); ?></button>
									<button type="button" class="menu-login-cancel-button" id="menu-register-ajax-cancel-<?php echo esc_attr( $this->get_id() ); ?>" style="cursor:pointer;"><?php esc_html_e( 'Cancel', 'menu-login' ); ?></button>
								</div>
							</form>
							<div id="menu-register-error-msg-<?php echo esc_attr( $this->get_id() ); ?>" style="margin-top:10px;"></div>
							<div style="margin-top:10px; text-align:right;">
								<a href="#" class="menu-toggle-link" style="text-decoration:underline;"><?php esc_html_e( 'Return to Login', 'menu-login' ); ?></a>
							</div>
						</div>
						<div id="menu-login-loading-<?php echo esc_attr( $this->get_id() ); ?>" style="display:none; margin:auto;">
							<!-- Corrected spinner markup: no extra quotes -->
							<div style="width:50px; height:50px; margin:auto; border:6px solid #ccc; border-top-color: <?php echo esc_html( $settings['login_loading_svg_color'] ); ?>;"></div>
						</div>
					</div>
				</div>
				<script>
				(function($){
					var widgetID = '<?php echo esc_js( $this->get_id() ); ?>';
					var $trigger = $('#menu-login-ajax-trigger-' + widgetID);
					var $popup   = $('#menu-login-ajax-popup-' + widgetID);
					var $loginForm = $('#menu-login-ajax-form-' + widgetID);
					var $loginFormBox = $('#menu-login-form-container-' + widgetID);
					var $registerForm = $('#menu-register-ajax-form-' + widgetID);
					var $registerBox = $('#menu-register-form-container-' + widgetID);
					var $spinner = $('#menu-login-loading-' + widgetID);
					var $loginCancel = $('#menu-login-ajax-cancel-' + widgetID);
					var $registerCancel = $('#menu-register-ajax-cancel-' + widgetID);
					var $loginErrorMsg = $('#menu-login-error-msg-' + widgetID);
					var $registerErrorMsg = $('#menu-register-error-msg-' + widgetID);
					var $link = $('.menu-login-wrapper-' + widgetID + ' .menu-login-link');
					var $toggleLink = $('.menu-toggle-link');

					$trigger.on('click', function(e){
						e.preventDefault();
						$loginFormBox.show();
						$registerBox.hide();
						$loginErrorMsg.html('');
						$registerErrorMsg.html('');
						$toggleLink.text('Register');
						$popup.css('display','flex');
					});
					$loginCancel.on('click', function(e){
						e.preventDefault();
						$popup.hide();
					});
					$registerCancel.on('click', function(e){
						e.preventDefault();
						$popup.hide();
					});
					$toggleLink.on('click', function(e){
						e.preventDefault();
						if( $registerBox.is(':visible') ) {
							$registerBox.hide();
							$registerErrorMsg.html('');
							$loginFormBox.show();
							$toggleLink.text('Register');
						} else {
							$loginFormBox.hide();
							$loginErrorMsg.html('');
							$registerBox.show();
							$toggleLink.text('Return to Login');
						}
					});
					$popup.on('click', function(e) {
						if (e.target === this) {
							$popup.hide();
						}
					});
					$loginForm.on('submit', function(e){
						e.preventDefault();
						$loginErrorMsg.html('');
						var usernameVal = $('#menu-login-username-' + widgetID).val();
						var passwordVal = $('#menu-login-password-' + widgetID).val();
						$loginFormBox.hide();
						$spinner.show();
						$.ajax({
							url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
							type: 'POST',
							dataType: 'json',
							data: {
								action: 'menu_login_ajax',
								username: usernameVal,
								password: passwordVal,
								security: '<?php echo wp_create_nonce( 'menu_login_ajax_nonce' ); ?>'
							},
							success: function(response){
								if(response.success){
									if(window.location.href.indexOf('/my-account/') !== -1){
										location.reload();
									} else {
										$.ajax({
											url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
											type: 'POST',
											dataType: 'json',
											data: { action: 'menu_login_get_current_user_info' },
											success: function(resp){
												if(resp.success){
													$link.html(resp.data.html);
													$link.attr('href', '/my-account/');
													$link.find('.menu-login-icon').removeClass('unlogged');
													$trigger.off('click');
													$popup.hide();
												} else {
													$loginErrorMsg.html(resp.data.message);
													$spinner.hide();
													$loginFormBox.show();
												}
											},
											error: function(xhr, status, error){
												$loginErrorMsg.html('AJAX Error (update): ' + error);
												$spinner.hide();
												$loginFormBox.show();
											}
										});
									}
								} else {
									$loginErrorMsg.html(response.data.message);
									$spinner.hide();
									$loginFormBox.show();
								}
							},
							error: function(xhr, status, error){
								$loginErrorMsg.html('AJAX Error: ' + error);
								$spinner.hide();
								$loginFormBox.show();
							}
						});
					});
					$registerForm.on('submit', function(e){
						e.preventDefault();
						$registerErrorMsg.html('');
						var emailVal = $('#menu-register-email-' + widgetID).val();
						$registerBox.hide();
						$spinner.show();
						$.ajax({
							url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
							type: 'POST',
							dataType: 'json',
							data: {
								action: 'menu_register_ajax',
								email: emailVal,
								security: '<?php echo wp_create_nonce( 'menu_register_ajax_nonce' ); ?>'
							},
							success: function(response){
								if(response.success){
									if(window.location.href.indexOf('/my-account/') !== -1){
										location.reload();
									} else {
										$.ajax({
											url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
											type: 'POST',
											dataType: 'json',
											data: { action: 'menu_login_get_current_user_info' },
											success: function(resp){
												if(resp.success){
													$link.html(resp.data.html);
													$link.attr('href', '/my-account/');
													$link.find('.menu-login-icon').removeClass('unlogged');
													$trigger.off('click');
													$popup.hide();
												} else {
													$registerErrorMsg.html(resp.data.message);
													$spinner.hide();
													$registerBox.show();
												}
											},
											error: function(xhr, status, error){
												$registerErrorMsg.html('AJAX Error (update): ' + error);
												$spinner.hide();
												$registerBox.show();
											}
										});
									}
								} else {
									$registerErrorMsg.html(response.data.message);
									$spinner.hide();
									$registerBox.show();
								}
							},
							error: function(xhr, status, error){
								$registerErrorMsg.html('AJAX Error: ' + error);
								$spinner.hide();
								$registerBox.show();
							}
						});
					});
				})(jQuery);
				</script>
			<?php endif;
		} // end render()
	}
}
