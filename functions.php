<?php
/**
 * Twenty Twenty functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since 1.0.0
 */

/**
 * Table of Contents:
 * Theme Support
 * Required Files
 * Register Styles
 * Register Scripts
 * Register Menus
 * Custom Logo
 * WP Body Open
 * Register Sidebars
 * Enqueue Block editor assets
 * Classic Editor Style
 * Block editor settings
 */

if ( ! function_exists( 'twentytwenty_theme_support' ) ) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function twentytwenty_theme_support() {

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Custom background color.
		add_theme_support(
			'custom-background',
			array(
				'default-color' => 'F5EFE0',
			)
		);

		// Set content-width.
		global $content_width;
		if ( ! isset( $content_width ) ) {
			$content_width = 580;
		}

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// Set post thumbnail size.
		set_post_thumbnail_size( 1200, 9999 );

		// Add custom image sizes.
		add_image_size( 'twentytwenty_fullscreen', 1980, 9999 );

		// Custom logo.
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 240,
				'width'       => 320,
				'flex-height' => true,
				'flex-width'  => true,
				'header-text' => array( 'site-title', 'site-description' ),
			)
		);

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Twenty Nineteen, use a find and replace
		 * to change 'twentynineteen' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'twentytwenty', get_template_directory() . '/languages' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

	}

	add_action( 'after_setup_theme', 'twentytwenty_theme_support' );

}

/**
 * REQUIRED FILES
 * Include required files.
 */
require get_template_directory() . '/inc/template-tags.php';

// Handle SVG icons.
require get_template_directory() . '/classes/class-twentytwenty-svg-icons.php';
require get_template_directory() . '/inc/svg-icons.php';

// Handle Customizer settings.
require get_template_directory() . '/classes/class-twentytwenty-customize.php';

// Require Separator Control class.
require get_template_directory() . '/classes/class-twentytwenty-separator-control.php';

// Custom comment walker.
require get_template_directory() . '/classes/class-twentytwenty-walker-comment.php';

// Custom CSS.
require get_template_directory() . '/inc/custom-css.php';

if ( ! function_exists( 'twentytwenty_register_styles' ) ) {
	/**
	 * Register and Enqueue Styles.
	 */
	function twentytwenty_register_styles() {

		$theme_version    = wp_get_theme()->get( 'Version' );
		$css_dependencies = array();

		// By default, only load the Font Awesome fonts if the social menu is in use.
		$load_font_awesome = apply_filters( 'twentytwenty_load_font_awesome', has_nav_menu( 'social-menu' ) );

		if ( $load_font_awesome ) {
			wp_register_style( 'font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.css', false, '5.10.2', 'all' );
			$css_dependencies[] = 'font-awesome';
		}

		wp_enqueue_style( 'twentytwenty-style', get_template_directory_uri() . '/style.css', $css_dependencies, $theme_version );

		// Add output of Customizer settings as inline style.
		wp_add_inline_style( 'twentytwenty-style', twentytwenty_get_customizer_css( 'front-end' ) );

	}

	add_action( 'wp_enqueue_scripts', 'twentytwenty_register_styles' );

}

if ( ! function_exists( 'twentytwenty_register_scripts' ) ) {
	/**
	 * Register and Enqueue Scripts.
	 */
	function twentytwenty_register_scripts() {

		$theme_version = wp_get_theme()->get( 'Version' );

		if ( ( ! is_admin() ) && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		$js_dependencies = array( 'jquery' );

		wp_enqueue_script( 'twentytwenty-construct', get_template_directory_uri() . '/assets/js/construct.js', $js_dependencies, $theme_version, false );

	}

	add_action( 'wp_enqueue_scripts', 'twentytwenty_register_scripts' );

}

if ( ! function_exists( 'twentytwenty_menus' ) ) {
	/**
	 * Register navigation menus uses wp_nav_menu in three places.
	 */
	function twentytwenty_menus() {

		$locations = array(
			'footer-menu'    => __( 'Footer Menu', 'twentytwenty' ),
			'main-menu'      => __( 'Main Menu', 'twentytwenty' ),
			'shortcuts-menu' => __( 'Shortcuts Menu', 'twentytwenty' ),
			'social-menu'    => __( 'Social Menu', 'twentytwenty' ),
		);

		register_nav_menus( $locations );
	}

	add_action( 'init', 'twentytwenty_menus' );

}

if ( ! function_exists( 'twentytwenty_the_custom_logo' ) ) {

	/**
	 * Add and Output Custom Logo.
	 *
	 * @param string $logo_theme_mod HTML for the custom logo.
	 */
	function twentytwenty_the_custom_logo( $logo_theme_mod = 'custom_logo' ) {
		echo esc_html( twentytwenty_get_custom_logo( $logo_theme_mod ) );
	}
}

if ( ! function_exists( 'twentytwenty_get_custom_logo' ) ) {

	/**

	 * Get the information about the logo.
	 *
	 * @param string $logo_theme_mod The name of the theme mod.
	 */
	function twentytwenty_get_custom_logo( $logo_theme_mod = 'custom_logo' ) {

		$logo_id = get_theme_mod( $logo_theme_mod );

		if ( ! $logo_id ) {
			return;
		}

		$logo = wp_get_attachment_image_src( $logo_id, 'full' );

		if ( ! $logo ) {
			return;
		}

		// For clarity.
		$logo_url    = esc_url( $logo[0] );
		$logo_width  = esc_attr( $logo[1] );
		$logo_height = esc_attr( $logo[2] );

		// If the retina logo setting is active, reduce the width/height by half.
		if ( get_theme_mod( 'twentytwenty_retina_logo', false ) ) {
			$logo_width  = floor( $logo_width / 2 );
			$logo_height = floor( $logo_height / 2 );
		}

		// CSS friendly class.
		$logo_theme_mod_class = str_replace( '_', '-', $logo_theme_mod );

		// Record our output.
		ob_start();

		?>

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link <?php echo esc_attr( $logo_theme_mod_class ); ?>">
			<img src="<?php echo esc_url( $logo_url ); ?>" width="<?php echo esc_attr( $logo_width ); ?>" height="<?php echo esc_attr( $logo_height ); ?>" alt="<?php bloginfo( 'name' ); ?>" />
		</a>

		<?php

		// Return our output.
		return ob_get_clean();

	}
}

if ( ! function_exists( 'wp_body_open' ) ) {

	/**
	 * Shim for wp_body_open, ensuring backwards compatibility with versions of WordPress older than 5.2.
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

if ( ! function_exists( 'twentytwenty_skip_link' ) ) {

	/**
	 * Include a skip to content link at the top of the page so that users can bypass the menu.
	 */
	function twentytwenty_skip_link() {
		echo '<a class="skip-link faux-button screen-reader-text" href="#site-content">' . esc_html__( 'Skip to the content', 'twentytwenty' ) . '</a>';
	}

	add_action( 'wp_body_open', 'twentytwenty_skip_link', 5 );

}

if ( ! function_exists( 'twentytwenty_sidebar_registration' ) ) {

	/**
	 * Register widget areas.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 */
	function twentytwenty_sidebar_registration() {

		// Arguments used in all register_sidebar() calls.
		$shared_args = array(
			'before_title'  => '<h2 class="widget-title subheading heading-size-3">',
			'after_title'   => '</h2>',
			'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
			'after_widget'  => '</div></div>',
		);

		// Footer #1.
		register_sidebar(
			array_merge(
				$shared_args,
				array(
					'name'        => __( 'Footer #1', 'twentytwenty' ),
					'id'          => 'footer-one',
					'description' => __( 'Widgets in this area will be displayed in the first column in the footer.', 'twentytwenty' ),
				)
			)
		);

		// Footer #2.
		register_sidebar(
			array_merge(
				$shared_args,
				array(
					'name'        => __( 'Footer #2', 'twentytwenty' ),
					'id'          => 'footer-two',
					'description' => __( 'Widgets in this area will be displayed in the second column in the footer.', 'twentytwenty' ),
				)
			)
		);

	}

	add_action( 'widgets_init', 'twentytwenty_sidebar_registration' );

}

if ( ! function_exists( 'twentytwenty_block_editor_styles' ) ) {
	/**
	 * Enqueue supplemental block editor styles.
	 */
	function twentytwenty_block_editor_styles() {

		$css_dependencies = array();

		// Enqueue the editor styles.
		wp_enqueue_style( 'twentytwenty-block-editor-styles', get_theme_file_uri( '/twentytwenty-editor-style-block-editor.css' ), $css_dependencies, wp_get_theme()->get( 'Version' ), 'all' );

		// Add inline style from the Customizer.
		wp_add_inline_style( 'twentytwenty-block-editor-styles', twentytwenty_get_customizer_css( 'block-editor' ) );

	}

	add_action( 'enqueue_block_editor_assets', 'twentytwenty_block_editor_styles', 1, 1 );

}

if ( ! function_exists( 'twentytwenty_classic_editor_styles' ) ) {

	/**
	 * Enqueue classic editor styles.
	 */
	function twentytwenty_classic_editor_styles() {

		$classic_editor_styles = array(
			'twentytwenty-editor-style-classic-editor.css',
		);

		add_editor_style( $classic_editor_styles );

	}

	add_action( 'init', 'twentytwenty_classic_editor_styles' );

}

if ( ! function_exists( 'twentytwenty_add_classic_editor_customizer_styles' ) ) {

	/**
	 * Output Customizer Settings in the Classic Editor.
	 * Adds styles to the head of the TinyMCE iframe. Kudos to @Otto42 for the original solution.
	 *
	 * @param array $mce_init TinyMCE styles.
	 */
	function twentytwenty_add_classic_editor_customizer_styles( $mce_init ) {

		$styles = twentytwenty_get_customizer_css( 'classic-editor' );

		if ( ! isset( $mce_init['content_style'] ) ) {
			$mce_init['content_style'] = $styles . ' ';
		} else {
			$mce_init['content_style'] .= ' ' . $styles . ' ';
		}

		return $mce_init;

	}

	add_filter( 'tiny_mce_before_init', 'twentytwenty_add_classic_editor_customizer_styles' );

}

if ( ! function_exists( 'twentytwenty_block_editor_settings' ) ) {

	/**
	 * Block Editor Settings.
	 * Add custom colors and font sizes to the block editor.
	 */
	function twentytwenty_block_editor_settings() {

		// Block Editor Palette.
		$editor_color_palette = array();

		// Get the color options.
		$twentytwenty_accent_color_options = TwentyTwenty_Customize::twentytwenty_get_color_options();

		// Loop over them and construct an array for the editor-color-palette.
		if ( $twentytwenty_accent_color_options ) {
			foreach ( $twentytwenty_accent_color_options as $color_option_name => $color_option ) {
				$editor_color_palette[] = array(
					'name'  => $color_option['label'],
					'slug'  => $color_option['slug'],
					'color' => get_theme_mod( $color_option_name, $color_option['default'] ),
				);
			}
		}

		// Add the background option.
		$background_color = get_theme_mod( 'background_color' );
		if ( ! $background_color ) {
			$background_color_arr = get_theme_support( 'custom-background' );
			$background_color     = $background_color_arr[0]['default-color'];
		}
		$editor_color_palette[] = array(
			'name'  => __( 'Background Color', 'twentytwenty' ),
			'slug'  => 'background',
			'color' => '#' . $background_color,
		);

		// If we have accent colors, add them to the block editor palette.
		if ( $editor_color_palette ) {
			add_theme_support( 'editor-color-palette', $editor_color_palette );
		}

		// Gutenberg Font Sizes.
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name'      => _x( 'Small', 'Name of the small font size in Gutenberg', 'twentytwenty' ),
					'shortName' => _x( 'S', 'Short name of the small font size in the Gutenberg editor.', 'twentytwenty' ),
					'size'      => 16,
					'slug'      => 'small',
				),
				array(
					'name'      => _x( 'Regular', 'Name of the regular font size in Gutenberg', 'twentytwenty' ),
					'shortName' => _x( 'M', 'Short name of the regular font size in the Gutenberg editor.', 'twentytwenty' ),
					'size'      => 18,
					'slug'      => 'regular',
				),
				array(
					'name'      => _x( 'Large', 'Name of the large font size in Gutenberg', 'twentytwenty' ),
					'shortName' => _x( 'L', 'Short name of the large font size in the Gutenberg editor.', 'twentytwenty' ),
					'size'      => 24,
					'slug'      => 'large',
				),
				array(
					'name'      => _x( 'Larger', 'Name of the larger font size in Gutenberg', 'twentytwenty' ),
					'shortName' => _x( 'XL', 'Short name of the larger font size in the Gutenberg editor.', 'twentytwenty' ),
					'size'      => 32,
					'slug'      => 'larger',
				),
			)
		);

	}

	add_action( 'after_setup_theme', 'twentytwenty_block_editor_settings' );

}
