<?php
/**
 * Customizer settings for this theme.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since 1.0.0
 */

if ( ! class_exists( 'TwentyTwenty_Customize' ) ) {
	/**
	 * CUSTOMIZER SETTINGS
	 */
	class TwentyTwenty_Customize {

		/**
		 * Register customizer options.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public static function twentytwenty_register( $wp_customize ) {

			/**
			 * Site Title & Description.
			 * */
			$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
			$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

			$wp_customize->selective_refresh->add_partial(
				'blogname',
				array(
					'selector'        => '.site-title a',
					'render_callback' => 'twentytwenty_customize_partial_blogname',
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'blogdescription',
				array(
					'selector'        => '.site-description',
					'render_callback' => 'twentytwenty_customize_partial_blogdescription',
				)
			);

			/**
			 * Site Identity
			 */

			/* 2X Header Logo ---------------- */
			$wp_customize->add_setting(
				'twentytwenty_retina_logo',
				array(
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'twentytwenty_sanitize_checkbox',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'twentytwenty_retina_logo',
				array(
					'type'        => 'checkbox',
					'section'     => 'title_tagline',
					'priority'    => 10,
					'label'       => __( 'Retina logo', 'twentytwenty' ),
					'description' => __( 'Scales the logo to half its uploaded size, making it sharp on high-res screens.', 'twentytwenty' ),
				)
			);

			/**
			 * Colors.
			*/
			$twentytwenty_accent_color_options = self::twentytwenty_get_color_options();

			// Loop over the color options and add them to the customizer.
			foreach ( $twentytwenty_accent_color_options as $color_option_name => $color_option ) {

				$wp_customize->add_setting(
					$color_option_name,
					array(
						'default'           => $color_option['default'],
						'type'              => 'theme_mod',
						'sanitize_callback' => 'sanitize_hex_color',
					)
				);

				$wp_customize->add_control(
					new WP_Customize_Color_Control(
						$wp_customize,
						$color_option_name,
						array(
							'label'    => $color_option['label'],
							'section'  => 'colors',
							'settings' => $color_option_name,
							'priority' => 10,
						)
					)
				);

			}

			// Update background color with postMessage, so inline CSS output is updated as well.
			$wp_customize->get_setting( 'background_color' )->transport = 'refresh';

			/**
			 * Site Header Options
			 * */

			$wp_customize->add_section(
				'twentytwenty_site_header_options',
				array(
					'title'       => __( 'Site Header', 'twentytwenty' ),
					'priority'    => 40,
					'capability'  => 'edit_theme_options',
					'description' => __( 'Settings for the site header.', 'twentytwenty' ),
				)
			);

			/* Disable Header Search --------- */

			$wp_customize->add_setting(
				'twentytwenty_disable_header_search',
				array(
					'capability'        => 'edit_theme_options',
					'default'           => false,
					'sanitize_callback' => 'twentytwenty_sanitize_checkbox',
				)
			);

			$wp_customize->add_control(
				'twentytwenty_disable_header_search',
				array(
					'type'        => 'checkbox',
					'section'     => 'twentytwenty_site_header_options',
					'priority'    => 10,
					'label'       => __( 'Disable Search Button', 'twentytwenty' ),
					'description' => __( 'Check to disable the search button in the header.', 'twentytwenty' ),
				)
			);

			/**
			 * Template: Cover Template.
			 */
			$wp_customize->add_section(
				'twentytwenty_cover_template_options',
				array(
					'title'       => __( 'Cover Template', 'twentytwenty' ),
					'capability'  => 'edit_theme_options',
					'description' => __( 'Settings for the "Cover Template" page template.', 'twentytwenty' ),
					'priority'    => 42,
				)
			);

			/* Overlay Fixed Background ------ */

			$wp_customize->add_setting(
				'twentytwenty_cover_template_fixed_background',
				array(
					'capability'        => 'edit_theme_options',
					'default'           => true,
					'sanitize_callback' => 'twentytwenty_sanitize_checkbox',
				)
			);

			$wp_customize->add_control(
				'twentytwenty_cover_template_fixed_background',
				array(
					'type'        => 'checkbox',
					'section'     => 'twentytwenty_cover_template_options',
					'label'       => __( 'Fixed Background Image', 'twentytwenty' ),
					'description' => __( 'Creates a parallax effect when the visitor scrolls.', 'twentytwenty' ),
				)
			);

			/* Separator --------------------- */

			$wp_customize->add_setting(
				'twentytwenty_cover_template_separator_1',
				array(
					'sanitize_callback' => 'wp_filter_nohtml_kses',
				)
			);

			$wp_customize->add_control(
				new TwentyTwenty_Separator_Control(
					$wp_customize,
					'twentytwenty_cover_template_separator_1',
					array(
						'section' => 'twentytwenty_cover_template_options',
					)
				)
			);

			/* Overlay Background Color ------ */

			$wp_customize->add_setting(
				'twentytwenty_cover_template_overlay_background_color',
				array(
					'default'           => get_theme_mod( 'twentytwenty_accent_color', '#CD2653' ),
					'type'              => 'theme_mod',
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'twentytwenty_cover_template_overlay_background_color',
					array(
						'label'       => __( 'Image Overlay Background Color', 'twentytwenty' ),
						'description' => __( 'The color used for the featured image overlay. Defaults to the accent color.', 'twentytwenty' ),
						'section'     => 'twentytwenty_cover_template_options',
						'settings'    => 'twentytwenty_cover_template_overlay_background_color',
					)
				)
			);

			/* Overlay Text Color ------------ */

			$wp_customize->add_setting(
				'twentytwenty_cover_template_overlay_text_color',
				array(
					'default'           => '#FFFFFF',
					'type'              => 'theme_mod',
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'twentytwenty_cover_template_overlay_text_color',
					array(
						'label'       => __( 'Image Overlay Text Color', 'twentytwenty' ),
						'description' => __( 'The color used for the text in the featured image overlay.', 'twentytwenty' ),
						'section'     => 'twentytwenty_cover_template_options',
						'settings'    => 'twentytwenty_cover_template_overlay_text_color',
					)
				)
			);

			/* Overlay Blend Mode ------------ */

			$wp_customize->add_setting(
				'twentytwenty_cover_template_overlay_blend_mode',
				array(
					'default'           => 'multiply',
					'sanitize_callback' => 'twentytwenty_sanitize_select',
				)
			);

			$wp_customize->add_control(
				'twentytwenty_cover_template_overlay_blend_mode',
				array(
					'label'       => __( 'Image Overlay Blend Mode', 'twentytwenty' ),
					'description' => __( 'How the overlay color will blend with the image. Some browsers, like Internet Explorer and Edge, only support the "Normal" mode.', 'twentytwenty' ),
					'section'     => 'twentytwenty_cover_template_options',
					'settings'    => 'twentytwenty_cover_template_overlay_blend_mode',
					'type'        => 'select',
					'choices'     => array(
						'normal'      => __( 'Normal', 'twentytwenty' ),
						'multiply'    => __( 'Multiply', 'twentytwenty' ),
						'screen'      => __( 'Screen', 'twentytwenty' ),
						'overlay'     => __( 'Overlay', 'twentytwenty' ),
						'darken'      => __( 'Darken', 'twentytwenty' ),
						'lighten'     => __( 'Lighten', 'twentytwenty' ),
						'color-dodge' => __( 'Color Dodge', 'twentytwenty' ),
						'color-burn'  => __( 'Color Burn', 'twentytwenty' ),
						'hard-light'  => __( 'Hard Light', 'twentytwenty' ),
						'soft-light'  => __( 'Soft Light', 'twentytwenty' ),
						'difference'  => __( 'Difference', 'twentytwenty' ),
						'exclusion'   => __( 'Exclusion', 'twentytwenty' ),
						'hue'         => __( 'Hue', 'twentytwenty' ),
						'saturation'  => __( 'Saturation', 'twentytwenty' ),
						'color'       => __( 'Color', 'twentytwenty' ),
						'luminosity'  => __( 'Luminosity', 'twentytwenty' ),
					),
				)
			);

			/* Overlay Color Opacity --------- */

			$wp_customize->add_setting(
				'twentytwenty_cover_template_overlay_opacity',
				array(
					'default'           => '80',
					'sanitize_callback' => 'twentytwenty_sanitize_select',
				)
			);

			$wp_customize->add_control(
				'twentytwenty_cover_template_overlay_opacity',
				array(
					'label'       => __( 'Image Overlay Opacity', 'twentytwenty' ),
					'description' => __( 'Make sure that the value is high enough that the text is readable.', 'twentytwenty' ),
					'section'     => 'twentytwenty_cover_template_options',
					'settings'    => 'twentytwenty_cover_template_overlay_opacity',
					'type'        => 'select',
					'choices'     => array(
						'0'   => __( '0%', 'twentytwenty' ),
						'10'  => __( '10%', 'twentytwenty' ),
						'20'  => __( '20%', 'twentytwenty' ),
						'30'  => __( '30%', 'twentytwenty' ),
						'40'  => __( '40%', 'twentytwenty' ),
						'50'  => __( '50%', 'twentytwenty' ),
						'60'  => __( '60%', 'twentytwenty' ),
						'70'  => __( '70%', 'twentytwenty' ),
						'80'  => __( '80%', 'twentytwenty' ),
						'90'  => __( '90%', 'twentytwenty' ),
						'100' => __( '100%', 'twentytwenty' ),
					),
				)
			);

			/* Sanitation Functions ---------- */

			/**
			 * Sanitize boolean for checkbox.
			 *
			 * @param bool $checked Wethere or not a blox is checked.
			 */
			function twentytwenty_sanitize_checkbox( $checked ) {
				return ( ( isset( $checked ) && true === $checked ) ? true : false );
			}

			/**
			 * Sanitize select.
			 *
			 * @param string $input The input from the setting.
			 * @param object $setting The selected setting.
			 */
			function twentytwenty_sanitize_select( $input, $setting ) {
				$input   = sanitize_key( $input );
				$choices = $setting->manager->get_control( $setting->id )->choices;
				return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
			}

		}

		/**
		 * Return the sitewide color options included.
		 * Note: These values are shared between the block editor styles and the customizer,
		 * and abstracted to this function.
		 */
		public static function twentytwenty_get_color_options() {
			return apply_filters(
				'twentytwenty_accent_color_options',
				array(
					'twentytwenty_accent_color' => array(
						'default' => '#CD2653',
						'label'   => __( 'Accent Color', 'twentytwenty' ),
						'slug'    => 'accent',
					),
				)
			);
		}

	}

	// Setup the Theme Customizer settings and controls.
	add_action( 'customize_register', array( 'TwentyTwenty_Customize', 'twentytwenty_register' ) );

}

/**
 * PARTIAL REFRESH FUNCTIONS
 * */
if ( ! function_exists( 'twentytwenty_customize_partial_blogname' ) ) {
	/**
	 * Render the site title for the selective refresh partial.
	 */
	function twentytwenty_customize_partial_blogname() {
		bloginfo( 'name' );
	}
}

if ( ! function_exists( 'twentytwenty_customize_partial_blogdescription' ) ) {
	/**
	 * Render the site description for the selective refresh partial.
	 */
	function twentytwenty_customize_partial_blogdescription() {
		bloginfo( 'description' );
	}
}
