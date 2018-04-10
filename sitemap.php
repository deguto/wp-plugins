<?php
/**
 * Template Name: Sitemap
 *
 * Displays the Sitemap
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
get_header(); ?>

		<div id="container">
			<div id="content" role="main">

 <ul id="page-menu">
  <?php //wp_list_pages('sort_column=menu_order'); 
  
   wp_list_pages('title_li=&sort_column=menu_order');?>
</ul>

			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
