<?php
/*
 * P2PHelper Stuff
 */
/// posts2posts stuff related to posts2posts plugin
//-------------------------------------------------------------
/**
 * Add connection types
 */
function my_connection_types() {
    p2p_register_connection_type(array(
        'name' => 'address_to_post',
        'from' => 'address',
        'to' => 'post'
    ));
}


//-------------------------------------------------------------
/**
 * Search for connections
 */
/*
function findConnections($headline) {
    // Find connected pages
    $connected = new WP_Query(array(
                'connected_type' => 'address_to_post',
                'connected_items' => get_queried_object(),
                'nopaging' => true,
                    ));

// Display connected pages
    if ($connected->have_posts()) :
        ?>
        <h3><? echo $headline; ?></h3>
        <ul>
            <?php while ($connected->have_posts()) : $connected->the_post(); ?>
                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
        <?php endwhile; ?>
        </ul>

        <?php
// Prevent weirdness
        wp_reset_postdata();

    endif;
}
*/
function hasConnections()
{
	$found = false;
	$relations = get_field('relation');
	if($relations)
		$found = true;

	return $found;
}
function findConnections($headline)
{
  $relations = get_field('relation');
  
   if( $relations ): ?>
<div class="arrangements">
 <h3></b><? echo $headline; ?></h3>
							<ul>
							<?php foreach( $relations as $relation ):
								if($relation->post_status =='publish')
								{
									?>
									<li>
										<a href="<?php echo get_permalink($relation->ID); ?>">
											<?php echo get_the_title($relation->ID); ?>
										</a>
									</li>
									<?php
								}
							endforeach; ?>
 							</ul>
</div>
<?php endif;  
}

function findConnections3($headline)
{
	$relations = get_field('relation');

	if( $relations ): ?>
		<?php foreach( $relations as $relation ): ?>
			<span>
				<strong><? echo $headline; ?></strong>: <a href="<?php echo get_permalink($relation->ID); ?>">
											<?php echo get_the_title($relation->ID); ?>
			</span>
			<? endforeach; ?>

	<?php endif;
}


function findConnections2($headline)
{
	$relations = get_field('relation');

	if( $relations ):
		foreach( $relations as $relation )
		{	if($relation->post_status =='publish')
			{?>
				<article <?php post_class('post-entry'); ?>>
				<h2 class="post-entry-headline title single-title entry-title"><a href="<?php echo get_permalink($relation->ID); ?>"><?php echo get_the_title($relation->ID); ?></a></h2>
				<?php if ( has_post_thumbnail($relation->ID) ) { ?>

				<a href="<?php echo get_permalink($relation->ID); ?>"><?php echo get_the_post_thumbnail($relation->ID,'thumbnail'); ?></a>


			<?php } ?>
				<div class="post-entry-content"><? echo get_excerpt_by_id($relation->ID) ?></div>
				<?php if ( get_theme_mod('restimpo_display_meta_post', restimpo_default_options('restimpo_display_meta_post')) != 'Hide' ) { ?>
				<p class="post-info">
					<a class="read-more" href="<?php echo get_permalink($relation->ID); ?>"><?php _e( 'Zum Arrangement >', 'restimpo' ); ?></a>
				</p>
			<?php } ?>
				</article>
			<?}
		}
	endif;
}

function findConnected($headline)
{
$relations = get_posts(array(
							'post_type' => 'address',
							'meta_query' => array(
								array(
									'key' => 'relation', // name of custom field
									'value' => '"' . get_the_ID() . '"', // matches exaclty "123", not just 123. This prevents a match for "1234"
									'compare' => 'LIKE'
								)
							)
						));
						
						
 
						?>
						<?php if( $relations ): ?>
          <h2><? echo $headline; ?></h2>
							<ul id="event-participants">
							<?php foreach( $relations as $relation ): ?>								
								<li>
									<a href="<?php echo get_permalink( $relation->ID ); ?>">										
										<?php echo get_the_title( $relation->ID ); ?>
									</a>
								</li>
							<?php endforeach; ?>
							</ul>
						<?php endif;
}

function bidirectional_acf_update_value( $value, $post_id, $field  ) {

	// vars
	$field_name = $field['name'];
	$global_name = 'is_updating_' . $field_name;


	// bail early if this filter was triggered from the update_field() function called within the loop below
	// - this prevents an inifinte loop
	if( !empty($GLOBALS[ $global_name ]) ) return $value;


	// set global variable to avoid inifite loop
	// - could also remove_filter() then add_filter() again, but this is simpler
	$GLOBALS[ $global_name ] = 1;


	// loop over selected posts and add this $post_id
	if( is_array($value) ) {

		foreach( $value as $post_id2 ) {

			// load existing related posts
			$value2 = get_field($field_name, $post_id2, false);


			// allow for selected posts to not contain a value
			if( empty($value2) ) {

				$value2 = array();

			}


			// bail early if the current $post_id is already found in selected post's $value2
			if( in_array($post_id, $value2) ) continue;


			// append the current $post_id to the selected post's 'related_posts' value
			$value2[] = $post_id;


			// update the selected post's value
			update_field($field_name, $value2, $post_id2);

		}

	}


	// find posts which have been removed
	$old_value = get_field($field_name, $post_id, false);

	if( is_array($old_value) ) {

		foreach( $old_value as $post_id2 ) {

			// bail early if this value has not been removed
			if( is_array($value) && in_array($post_id2, $value) ) continue;


			// load existing related posts
			$value2 = get_field($field_name, $post_id2, false);


			// bail early if no value
			if( empty($value2) ) continue;


			// find the position of $post_id within $value2 so we can remove it
			$pos = array_search($post_id, $value2);


			// remove
			unset( $value2[ $pos] );


			// update the un-selected post's value
			update_field($field_name, $value2, $post_id2);

		}

	}


	// reset global varibale to allow this filter to function as per normal
	$GLOBALS[ $global_name ] = 0;


	// return
	return $value;

}

add_filter('acf/update_value/name=relation', 'bidirectional_acf_update_value', 10, 3);
?>
