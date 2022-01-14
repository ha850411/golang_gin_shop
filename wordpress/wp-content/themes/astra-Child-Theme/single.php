<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once __DIR__."/104-custom-page/main-single-page.php";


get_header();?>

<?php if ( astra_page_layout() == 'left-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

	<!-- <div id="primary"  -->
	<?php //astra_primary_class(); ?>
	<!-- > -->

		<?php astra_primary_content_top(); ?>

        <?php // mainSinglePage();?>

		<?php astra_content_loop(); ?>

		<?php astra_primary_content_bottom(); ?>

	<!-- </div> -->
	<!-- #primary -->

<?php if ( astra_page_layout() == 'right-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

<?php get_footer(); ?>
