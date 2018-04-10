<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
get_header(); ?>

		<div id="container">
			<div id="content" role="main">

				<h1 class="entry-title"><?php
					printf(single_cat_title( '', false )); 
				?></h1>
				<?php
					$category_description = category_description();
					if ( ! empty( $category_description ) )
						echo '<div class="archive-meta">' . $category_description . '</div>';
			?></div><!-- #content --><?
				/* Run the loop for the category page to output the posts.
				 * If you want to overload this in a child theme then include a file
				 * called loop-category.php and that will be used instead.
				 */
				if (isset($_GET['m']) ) {
					 $m = $_GET['m'];
				}else{
					$m = date("Ym");
				}
				dec_the_cnavi($m,$cat);
				?><div id="pageposts">
				<? get_template_part( 'loop', 'events' );?>
				</div>				

			
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
