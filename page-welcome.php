<?php
/**
 * Template Name: Welcome
 *
 * Displays the Welcome Site
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

<div id="container">
  <div id="content" role="main">
<div class="wrapper">

	<div class="wp1">
		<div class="g11 bar">
			<a href="http://buildinternet.com/project/mightyicons/" target="_blank" class="mosaic-overlay">
				<div class="details">
					<h1>Willkommen</h1>
				</div>
			</a>
			<div class="mosaic-backdrop"><img src="<?php bloginfo('template_url');?>/images/1.jpg"/></div>
		</div>
</div>
<div class="wp2">
		<div class="g21 bar">
			<a href="http://buildinternet.com/project/mightyicons/" target="_blank" class="mosaic-overlay">
				<div class="details">
					<h1>Natur</h1>
				</div>
			</a>
			<div class="mosaic-backdrop"><img src="<?php bloginfo('template_url');?>/images/2.jpg"/></div>
		</div>

</div>

<div class="wp3">
		<div class="g21 bar">
			<a href="http://buildinternet.com/project/mightyicons/" target="_blank" class="mosaic-overlay">
				<div class="details">
					<h1>Erleben</h1>
				</div>
			</a>
			<div class="mosaic-backdrop"><img src="<?php bloginfo('template_url');?>/images/3.jpg"/></div>
		</div>
<div class="g21 bar">
			<a href="http://buildinternet.com/project/mightyicons/" target="_blank" class="mosaic-overlay">
				<div class="details">
					<h1>Genuss</h1>
				</div>
			</a>
			<div class="mosaic-backdrop"><img src="<?php bloginfo('template_url');?>/images/5.jpg"/></div>
		</div>
</div>
<div class="wp4">
<div class="g41 bar">
			<a href="http://buildinternet.com/project/mightyicons/" target="_blank" class="mosaic-overlay">
				<div class="details">
					<h1>Wellness</h1>
				</div>
			</a>
			<div class="mosaic-backdrop"><img src="<?php bloginfo('template_url');?>/images/4.jpg"/></div>
		</div>
</div>
<div class="wp5">
<div class="g21 bar">
			<a href="http://buildinternet.com/project/mightyicons/" target="_blank" class="mosaic-overlay">
				<div class="details">
					<h1>Wohnen</h1>
				</div>
			</a>
			<div class="mosaic-backdrop"><img src="<?php bloginfo('template_url');?>/images/6.jpg"/></div>
		</div>
<div class="g21 bar">
			<a href="http://buildinternet.com/project/mightyicons/" target="_blank" class="mosaic-overlay">
				<div class="details">
					<h1>Business</h1>
				</div>
			</a>
			<div class="mosaic-backdrop"><img src="<?php bloginfo('template_url');?>/images/7.jpg"/></div>
		</div>
</div>

		<div class="clearfix"></div>
	</div>


  </div><!-- #content -->

  <?php
	global $sitePost;
	$sitePost = $post;

  if($post->post_parent == 0){
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
