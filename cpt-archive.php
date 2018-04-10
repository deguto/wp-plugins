<?php
/**
 * Template Name: CPT-Archive
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

<div id="container">
  <?php the_breadcrumbs(); ?>
  <div id="content" role="main">
		<h1 class="page-title"><?php the_title(); ?></h1>

<?php
	/* Queue the first post, that way we know
	 * what date we're dealing with (if that is the case).
	 *
	 * We reset this later so we can run the loop
	 * properly with a call to rewind_posts().
	 */
	if ( have_posts() )
		the_post();

	/* Artikel die mit dieser Seite angehakt wurden anzeigen */
	$post_types = array('Events','rechte_box','arrangements');

	$slug = get_slug();

	if($slug == 'insider-tips' ||
		$slug == 'event-highlight' ||
		$slug == 'rechte_box'){
		$post_types = 'rechte_box';
	}elseif($slug == 'events'){
		$post_types = 'events';
	}

	if(isset($_GET['sitetag']) && $_GET['sitetag']){
		$sitetag = htmlentities($_GET['sitetag']);
	}else{
		$sitetag = '';
	}

	query_posts(
		array(
			'showposts' => 30,
			'post_type' => $post_types,
			'seiten' => $sitetag,
			'post_status' => array( 'pending', 'draft', 'future', 'publish' )
		)
	);
	?>

	<?php if ( have_posts() ): while ( have_posts() ) : the_post(); ?>

    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    	<a href="<?php the_permalink(); ?>" id="post-<?php the_ID(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
        <h2 class="entry-title"><?php the_title(); ?></h2>
      </a>

      <div class="entry-content">
        <?php the_excerpt(); ?>
        <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
        <?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
      </div><!-- .entry-content -->
    </div><!-- #post-## -->

    <?php /*comments_template( '', true );*/ ?>

	<?php endwhile; else: ?>
		<div id="post-0" class="post error404 not-found">
			<h1 class="entry-title"><?php _e( 'Not Found', 'twentyten' ); ?></h1>
			<div class="entry-content">
				<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyten' ); ?></p>
				<?php get_search_form(); ?>
			</div><!-- .entry-content -->
		</div><!-- #post-0 -->
	<?php endif; ?>

	</div><!-- #content -->
</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
