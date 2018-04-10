<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 * We filter the output of wp_title() a bit -- see
	 * twentyten_filter_wp_title() in functions.php.
	 */
	wp_title( '|', true, 'right' );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<?php
$class="";
if(date("z") > 304 || date("z") < 74 ){ // is it Christmastime? :)
//	$class="winter";
}
?>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'template_url' ); ?>/css.php<?php echo $class ? '?'.$class : '';?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/js/highslide/highslide.css" type="text/css" media="screen" />
<!--[if IE 6]><link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/js/highslide/highslide-ie6.css" type="text/css" media="screen" /><![endif]-->
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
<script type="text/javaScript" src="<?php bloginfo('template_url'); ?>/js/application.js"></script>
<script type="text/javaScript" src="<?php bloginfo('template_url'); ?>/js/highslide/highslide-with-gallery.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/highslide/highslide.config.js" charset="utf-8"></script>
</head>
<body <?php body_class($class); ?>>
<div id="wrapper" class="hfeed">
	<div id="header">
		<div id="masthead">
			<div id="branding" role="banner">
				<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
				<<?php echo $heading_tag; ?> id="site-title">
					<span>
						<a id="logo" href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
						<?php /*bloginfo( 'name' );*/ ?>
						</a>
					</span>
					<span id="toolbar">
					 	<?php if(function_exists('fontResizer_place')) { fontResizer_place(); } ?>
						<a href="<?php echo home_url( '/' ); ?>sitemap" title="Sitemap" rel="sitemap">Sitemap</a>
						<?php get_search_form(); ?>
					</span>
	   			<a id="katalog-banner" href="http://hotel-heinz.de/zimmer-arrangements-preise/saison-specials/"> </a>						
				</<?php echo $heading_tag; ?>>
				<!--<div id="site-description"><?php bloginfo( 'description' ); ?></div>-->

				<?php
					// Check if this is a post or page, if it has a dynamic featured image, and if it's a big one
					//echo "---->".has_post_format('image');
					if ( ((is_singular() && class_exists('Dynamic_Featured_Image') && has_post_format('image')))) :
						// Houston, we have a new header image!
           global  $dynamic_featured_image;
           $featured_images = $dynamic_featured_image->get_featured_images( $post->ID );
           echo "<img src='".wp_get_attachment_image_src($featured_images[0]['attachment_id'],'single-header')[0]."' alt='Header-Image' />";
						//echo get_the_post_thumbnail( $post->ID, 'post-thumbnail' ); //old
					 elseif (is_home() || is_front_page() || is_single()) : echo '<div class="clear"></div>'.do_shortcode("[metaslider id=15437]");
					 elseif ($post->post_title == 'Startseite') : echo '<div class="clear"></div>'.do_shortcode($post->post_content); //"[metaslider id=10750]"
					 else :?>
						<img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" />
					<?php endif; ?>
			</div><!-- #branding -->

			<div id="access" role="navigation">
			  <?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff */ ?>
				<div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentyten' ); ?>"><?php _e( 'Skip to content', 'twentyten' ); ?></a></div>
				<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
				<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
			</div><!-- #access -->
		</div><!-- #masthead -->
	</div><!-- #header -->

	<div id="main">
