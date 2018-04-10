<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<div id="container">
			<?php the_breadcrumbs(); ?>
			<div id="content" role="main">

<?php if ( have_posts() ) : ?>
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'twentyten' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				<?php
				/* Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called loop-search.php and that will be used instead.
				 */
//				 get_template_part( 'loop', 'search' );
				?>
			  <div id="pageposts" class="search">

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	    	<a href="<?php the_permalink(); ?>" id="post-<?php the_ID(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
	        <h2 class="entry-title"><?php the_title(); ?></h2>
	      </a>

	      <div class="entry-content">
	      	<?php
		      if ( has_post_thumbnail() ) {
						the_post_thumbnail(array(75,75), array("style" => "float: left;"));
					}else{
						/* Erstes Bild aus Artikel darstellen */
						writeImagesFromPost("75", "listimage", get_content(), $offset=0);
					}
					?>
	        <?php the_excerpt("20"); ?>
	        <div style="clear: both;"></div>
	        <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
	        <?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
	      </div><!-- .entry-content -->
	    </div><!-- #post-## -->

<?php endwhile; ?>
			</div><!-- #pageposts -->
<?php else : ?>
				<div id="post-0" class="post no-results not-found">
					<h2 class="entry-title"><?php _e( 'Nothing Found', 'twentyten' ); ?></h2>
					<div class="entry-content">
						<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'twentyten' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</div><!-- #post-0 -->
<?php endif; ?>

			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
