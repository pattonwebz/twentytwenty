<?php
/**
 * The template for displaying single posts and pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since 1.0.0
 */

get_header();
?>

<main id="site-content" role="main">

	<?php

	if ( have_posts() ) {

		while ( have_posts() ) {
			the_post();

			if ( is_page_template( array( 'template-cover.php' ) ) ) {
				get_template_part( 'template-parts/content-cover' );
			} else {
				get_template_part( 'template-parts/content', get_post_type() );
			}
		}
	}

	?>

</main><!-- #site-content -->

<?php get_footer(); ?>
