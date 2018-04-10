<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the wordpress construct of pages
 * and that other 'pages' on your wordpress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>
<?php /* if ( is_front_page() ) { ?>
<a href="http://sommer-urlaub.de/hotel-heinz-kennenlern-special-zum-schnupperpreis/"><div id="banner"> Kennenlern-Special zum Schnupperpreis: 01.-02.08. / 05.-06.08 / 08.-09.08. / 15.-16.08 / 22.-23.08  </div></a>
<? }*/?>
<div id="container">
  <div id="content" role="main">
  <?php the_breadcrumbs(); ?>
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    	<?php
      if ( has_post_thumbnail() && $post->post_parent==0) {
				the_post_thumbnail(array(75,75), array("style" => "float: left; padding: 0; margin-top: -10px;"));
				?>
				<div style="clear: none; height: 65px; width: 360px;">
				<?php
			}
			?>
      <?php if ( is_front_page() ) { ?>
        <h2 class="entry-title" style="clear: none;"><?php the_title(); ?></h2>
      <?php } else { ?>
      	<h1 class="entry-title" style="clear: none;"><?php the_title(); ?></h1>
      <?php } ?>

      <span style="clear: both"></span>
    	<?php
      if ( has_post_thumbnail() && $post->post_parent==0) {
				?>
				</div>
				<?php
			}
			?>
      <div class="entry-content">
        <?php writeContentWithoutImages(); ?>
        <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
        <?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
      </div><!-- .entry-content -->
    </div><!-- #post-## -->

    <?php /*comments_template( '', true );*/ ?>

	<?php endwhile; ?>

  </div><!-- #content -->

  <?php
	global $sitePost;
	$sitePost = $post;

  //if($post->post_parent == 0){
 if(1==0){ //SPECIAL FOR WINTER URLAUB DIFFERENT FROM HOTEL-HEINZ!! keine Unterseiten in ..-Urlaub!!!! (CBW)
  	$children = wp_list_pages("title_li=&child_of=".$post->ID."&sort_column=menu_order&sort_order=asc&echo=0");
  	$post_type = 'page';

		/* Artikel die mit dieser Seite angehakt wurden anzeigen */
		query_posts(
			array(
				'showposts' => 30,
				'post_type' => $post_type,
				'post_parent' => $post->ID,
				'orderby' => 'menu_order',
				'order' => 'asc'
			)
		);
  }else{
  	$children = wp_list_pages("title_li=&child_of=".$post->post_parent."&sort_column=menu_order&sort_order=asc&echo=0");
  	$post_type = 'post';

		/* Artikel die mit dieser Seite angehakt wurden anzeigen */
		query_posts(
			array(
				'showposts' => 30,
				'post_type' => $post_type,
				'seiten' => get_slug(),
				'orderby' => 'date,menu_order',
				'order' => 'desc'
			)
		);		
  }
	//echo get_slug();

  /*if ($post->post_parent == 0 && $children) { ?>
  <strong>Navigation</strong>
  <ul id="page-menu">
  <?php echo $children; ?>
  </ul>
  <?php }*/
  ?>

  <div id="pageposts">

	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

		<?php if($post_type == 'post'): ?>

	    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	    	<a href="<?php the_permalink(); ?>" id="post-<?php the_ID(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
	        <h2 class="entry-title"><?php echo strip_tags(get_the_title()); ?></h2>
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

		<?php elseif($post_type == 'page'): ?>

	    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php
		      if ( has_post_thumbnail() ) {
						the_post_thumbnail(array(75,75), array("style" => "float: left;"));
					}else{
						/* Erstes Bild aus Artikel darstellen */
						writeImagesFromPost("75", "listimage", get_content(), $offset=0);
					}
					?>
	      <div class="entry-content">
		    	<a href="<?php the_permalink(); ?>" id="post-<?php the_ID(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
		        <h2 class="entry-title"><?php echo strip_tags(get_the_title()); ?></h2>
		      </a>
	        <?php the_excerpt(); ?>
	        <div style="clear: both;"></div>
	        <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
	        <?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
	      </div><!-- .entry-content -->
	      <div style="clear: both;"></div>
	    </div><!-- #post-## -->

		<?php endif; ?>

    <?php /*comments_template( '', true );*/ ?>

	<?php endwhile; ?>

  </div><!-- #content -->


</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
