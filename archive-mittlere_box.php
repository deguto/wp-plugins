<?php
/**
 * The template for displaying Archive Aktuelles pages.
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

get_header();
//add_new_rules();
function add_new_rules(){

	global $wp_rewrite;

	$rewrite_rules = $wp_rewrite->generate_rewrite_rules('aktuelles/');
	$rewrite_rules['aktuelles/?$'] = 'index.php?paged=1';

	foreach($rewrite_rules as $regex => $redirect)
	{
		if(strpos($redirect, 'attachment=') === false)
			{
				$redirect .= '&post_type=rechte_box';
			}
		if(0 < preg_match_all('@\$([0-9])@', $redirect, $matches))
			{
				for($i = 0; $i < count($matches[0]); $i++)
				{
					$redirect = str_replace($matches[0][$i], '$matches['.$matches[1][$i].']', $redirect);
				}
			}
		$wp_rewrite->add_rule($regex, $redirect, 'top');
	}
echo "done";
$wp_rewrite->flush_rules();
}?>

		<div id="container">
			<div id="content" role="main">

<?php
	/* Queue the first post, that way we know
	 * what date we're dealing with (if that is the case).
	 *
	 * We reset this later so we can run the loop
	 * properly with a call to rewind_posts().
	 */
	if ( have_posts() )
		the_post();
?>

			<h1 class="page-title">
<?php if ( is_day() ) : ?>
				<?php printf( __( 'Daily Archives: <span>%s</span>', 'twentyten' ), get_the_date() ); ?>
<?php elseif ( is_month() ) : ?>
				<?php printf( __( 'Monthly Archives: <span>%s</span>', 'twentyten' ), get_the_date('F Y') ); ?>
<?php elseif ( is_year() ) : ?>
				<?php printf( __( 'Yearly Archives: <span>%s</span>', 'twentyten' ), get_the_date('Y') ); ?>
<?php else : ?>
				<?php _e( 'Archive', 'twentyten' ); ?>
<?php endif; ?>
			</h1>
</div><!-- #content -->
<div id="pageposts">
<?php
	/* Since we called the_post() above, we need to
	 * rewind the loop back to the beginning that way
	 * we can run the loop properly, in full.
	 */
	rewind_posts();

	/* Run the loop for the archives page to output the posts.
	 * If you want to overload this in a child theme then include a file
	 * called loop-archives.php and that will be used instead.
	 */
	 get_template_part( 'loop', 'archive-mittlere_box' );
?>

			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
