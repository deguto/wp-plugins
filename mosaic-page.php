<?php
/**
 * Template Name: Mosaik-Startseite
 *
 * A custom page template without sidebar.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/mosaic.css" type="text/css" media="screen" />
		
<!--		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>-->
		<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/mosaic.1.0.1.js"></script>
		
		<script type="text/javascript">  
			
			jQuery(function($){
				
				$('.circle').mosaic({
					opacity		:	0.8			//Opacity for overlay (0-1)
				});
				
				$('.fade').mosaic();
				
				$('.bar').mosaic({
					animation	:	'slide'		//fade or slide
				});
				
				$('.bar2').mosaic({
					animation	:	'slide'		//fade or slide
				});
				
				$('.bar3').mosaic({
					animation	:	'slide',	//fade or slide
					anchor_y	:	'top'		//Vertical anchor position
				});
				
				$('.cover').mosaic({
					animation	:	'slide',	//fade or slide
					hover_x		:	'400px'		//Horizontal position on hover
				});
				
				$('.cover2').mosaic({
					animation	:	'slide',	//fade or slide
					anchor_y	:	'top',		//Vertical anchor position
					hover_y		:	'80px'		//Vertical position on hover
				});
				
				$('.cover3').mosaic({
					animation	:	'slide',	//fade or slide
					hover_x		:	'400px',	//Horizontal position on hover
					hover_y		:	'300px'		//Vertical position on hover
				});
		    
		    });
		    
		</script>

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
    
    
  <!-- M o s a i k  S T A R T -->
  <div class="mosaic-wrapper">	

    <div class="wp1">	
      <div class="g11 bar">
        <a href="link" target="_blank" class="mosaic-overlay">
          <div class="details">
            <h1>Willkommen</h1>	
          </div>
        </a>
        <div class="mosaic-backdrop"><img src="<?php bloginfo('template_url'); ?>/images/mosaic/1.jpg" width="332px"/></div>
      </div>	
      </div>
      <div class="wp2">	
        <div class="g21 bar">
          <a href="link" target="_blank" class="mosaic-overlay">
            <div class="details">
              <h1>Natur</h1>				
            </div>
          </a>
          <div class="mosaic-backdrop"><img src="<?php bloginfo('template_url'); ?>/images/mosaic/2.jpg" width="164px"/></div>
        </div>
      </div>

      <div class="wp3">	
        <div class="g21 bar">
          <a href="link" target="_blank" class="mosaic-overlay">
            <div class="details">
              <h1>Erleben</h1>					
            </div>
          </a>
          <div class="mosaic-backdrop"><img src="<?php bloginfo('template_url'); ?>/images/mosaic/3.jpg" width="164px"/></div>
        </div>
      <div class="g21 bar mt">
          <a href="link" target="_blank" class="mosaic-overlay">
            <div class="details">
              <h1>Genuss</h1>					
            </div>
          </a>
          <div class="mosaic-backdrop"><img src="<?php bloginfo('template_url'); ?>/images/mosaic/5.jpg" width="164px"/></div>
        </div>
      </div>
      <div class="wp4">	
      <div class="g41 bar">
          <a href="link" target="_blank" class="mosaic-overlay">
            <div class="details">
              <h1>Wellness</h1>					
            </div>
          </a>
          <div class="mosaic-backdrop"><img src="<?php bloginfo('template_url'); ?>/images/mosaic/4.jpg" width="164px" height="332px"/></div>
        </div>
      </div>
      <div class="wp5">	
      <div class="g21 bar">
          <a href="link" target="_blank" class="mosaic-overlay">
            <div class="details">
              <h1>Wohnen</h1>					
            </div>
          </a>
          <div class="mosaic-backdrop"><img src="<?php bloginfo('template_url'); ?>/images/mosaic/6.jpg" width="164px"/></div>
        </div>
      <div class="g21 mt bar">
          <a href="link" target="_blank" class="mosaic-overlay">
            <div class="details">
              <h1>Business</h1>					
            </div>
          </a>
          <div class="mosaic-backdrop"><img src="<?php bloginfo('template_url'); ?>/images/mosaic/7.jpg" width="164px"/></div>
        </div>	
      </div>	

      <div class="clearfix"></div>
    </div><!-- M o s a i k  E N D E -->
    
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
				'showposts' => 5,
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
				'showposts' => 5,
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

<g:plusone size="small"></g:plusone>
</div><!-- #container -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
