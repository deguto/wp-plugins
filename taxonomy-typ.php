<?php
/**
 * The template for displaying Tag Archive pages.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<div id="container">
			<div id="content" role="main">
         <?php //the_breadcrumbs(); ?>
				<h1 class="entry-title" style="clear: none;"><?php
					printf( '<span>' . single_tag_title( '', false ) . '</span>' );
				?></h1>
        <?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); ?>
        <?php echo $term->description; ?>
        </div><!-- #content -->
         <div id="pageposts">
<?php
/* Run the loop for the tag archive to output the posts
 * If you want to overload this in a child theme then include a file
 * called loop-tag.php and that will be used instead.
 */
 get_template_part( 'loop', 'typ' );
?>
            </div>
			
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
