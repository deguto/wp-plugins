<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>
	</div><!-- #main -->

	<div id="footer" role="contentinfo">
		<div id="colophon">
<?php
if(isset($_GET['footerbar'])){
	$css = 'style="position: fixed; z-index: 1000; bottom: 0; margin-bottom: 0;"';
}else{
	$css = '';
}
?>
			<div id="footernavi" role="navigation" <?php echo $css ?>>
			  <?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff */ ?>
				<div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentyten' ); ?>"><?php _e( 'Skip to content', 'twentyten' ); ?></a></div>
				<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
				<?php //wp_nav_menu( array( 'menu' => 'Menü-Footer', 'container_class' => 'menu-header', 'theme_location' => 'primary', 'before' => '<span class="img">&nbsp;</span>' ) ); //cbw(23.07.2015) erstmal entfernt ?>
			</div><!-- #access -->


<?php
	/* A sidebar in the footer? Yep. You can can customize
	 * your footer with four columns of widgets.
	 */
	get_sidebar( 'footer' );
?>
			<?php /*<div id="site-info">
				<a href="<?php echo home_url( '/' ) ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<?php _e('Startseite'); ?>
				</a>
			</div><!-- #site-info -->
			*/ ?>
			<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
			<<?php echo $heading_tag; ?> id="site-title">
				<span>
					<a id="logo" href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<?php //bloginfo( 'name' ); ?>
					</a>
				</span>
			</<?php echo $heading_tag; ?>>

			<h3>In zentraler und verkehrsg&uuml;nstiger Lage genau zwischen K&ouml;ln und Frankfurt.<br/>
				56203 H&ouml;hr-Grenzhausen &middot; Bergstra&szlig;e 77 &middot; Telefon: 02624/9430-0 &middot; Fax: 02624/9430-800 &middot; E-Mail: <script type='text/javascript'>var v2="CVFU62XXERHUAW48DQX";var v7=unescape("*8%20%3AvZ7%2C%20%3Ee%3D%24%3EZBj5%3D");var v5=v2.length;var v1="";for(var v4=0;v4<v5;v4++){v1+=String.fromCharCode(v2.charCodeAt(v4)^v7.charCodeAt(v4));}document.write('<a href="javascript:void(0)" onclick="window.location=\'mail\u0074o\u003a'+v1+'?subject='+'\'">'+'info@hotel-heinz.de</a>');</script></h3>

		</div><!-- #colophon -->
	</div><!-- #footer -->

	<div id="logo_sidebar">
		&nbsp;
	</div>
</div><!-- #wrapper -->

<div id="footer_toolbar">
	<a href="<?php echo home_url( '/' ); ?>kontakt/">Wir beraten Sie gerne:<br/>02624/9430-0</span>
</div>

<?php
	wp_footer();
?>
<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
  {lang: 'de'}
</script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-43282843-3', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>
